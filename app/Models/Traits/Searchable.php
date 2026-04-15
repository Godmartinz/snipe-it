<?php

namespace App\Models\Traits;

use App\Models\Asset;
use App\Models\CustomField;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * This trait allows for cleaner searching of models,
 * moving from complex queries to an easier declarative syntax.
 *
 * This handles all the out of the box advanced search stuff (using the "advanced search" bootstrap table plugin),
 * allowing you to just define which attributes and relations should be searched, and then it does the rest.
 *
 * You can override these trait methods (for example, advancedSearch) if you need different behavior, but this really
 * should cover most of the use cases, and allows you to easily add searching to your models without having to
 * write complex queries.
 *
 * To use this:
 *
 * 1. Make sure the model has $searchableAttributes and $searchableRelations set
 * 2. Make sure you import the App\Models\Traits\Searchable trait and use Searchable in the model
 * 3. Make sure you check the request for the request input filter or search and then invoke the TextSearch scope, like:
 *
 * if ($request->filled('filter') || $request->filled('search')) {
 *       $whateverModel->TextSearch($request->input('filter') ? $request->input('filter') : $request->input('search'));
 * }
 * 4. Set the "data-advanced-search="true" in the
 *
 *
 * @author Till Deeke <kontakt@tilldeeke.de>
 */
trait Searchable
{
    /**
     * Per-class cache for the custom field filter map, keyed by db_column / lowercase name.
     * Populated lazily; cleared via flushCustomFieldFilterMap().
     *
     * @var array<string, string>|null
     */
    private static ?array $customFieldFilterMapCache = null;

    /**
     * Performs a search on the model, using the provided search terms
     *
     * @param  Builder  $query  The query to start the search on
     * @param  string  $search
     * @return Builder A query with added "where" clauses
     */
    public function scopeTextSearch($query, $search)
    {
        $preparedSearch = $this->prepareSearchInput((string) $search);
        $terms = $preparedSearch['terms'];
        $filters = $preparedSearch['filters'];

        if (! empty($filters)) {
            return $this->applySearchFilters($query, $filters);
        }

        /**
         * Search the attributes of this model
         */
        $query = $this->searchAttributes($query, $terms);

        /**
         * Search through the custom fields of the model
         */
        $query = $this->searchCustomFields($query, $terms);

        /**
         * Search through the relations of the model
         */
        $query = $this->searchRelations($query, $terms);

        /**
         * Search for additional attributes defined by the model
         */
        $query = $this->advancedTextSearch($query, $terms);

        return $query;
    }

    /**
     * Parse free-text terms and structured filters for TextSearch.
     *
     * Supported filter inputs:
     * - {"field":"value"}
     * - filter:{"field":"value"}
     */
    private function prepareSearchInput(string $search): array
    {
        $search = trim($search);

        $parsedFilters = $this->parseStructuredFilterPayload($search);

        if ($parsedFilters !== null) {
            return [
                'terms' => [],
                'filters' => $parsedFilters,
            ];
        }

        return [
            'terms' => $this->prepeareSearchTerms($search),
            'filters' => [],
        ];
    }

    /**
     * Normalize a structured filter payload into scalar string filters.
     */
    private function parseStructuredFilterPayload(string $search): ?array
    {
        if ($search === '') {
            return null;
        }

        $payload = $search;

        if (str_starts_with($search, 'filter:')) {
            $payload = substr($search, 7);
        } elseif (! (str_starts_with($search, '{') && str_ends_with($search, '}'))) {
            return null;
        }

        $decoded = json_decode($payload, true);

        if (! is_array($decoded)) {
            return null;
        }

        $filters = [];

        foreach ($decoded as $key => $value) {
            if (! is_string($key)) {
                continue;
            }

            if (! is_scalar($value) && $value !== null) {
                continue;
            }

            $normalizedValue = trim((string) ($value ?? ''));

            if ($normalizedValue === '') {
                continue;
            }

            $filters[$key] = $normalizedValue;
        }

        return $filters;
    }

    /**
     * Prepares the search term, splitting and cleaning it up
     *
     * @TODO: see if there's a way to tweak the advanced search plugin to split the terms on the frontend, so we don't have to do it here. This is pretty hacky and fragile, since it relies on the user inputting " OR " between search terms, which is not very user-friendly, but we could potentially hack the advanced search extension itself to add an operator. (That extension's UI is pretty terrible, but it's what we have)
     *
     * @param  string  $search  The search term
     * @return array An array of search terms
     */
    private function prepeareSearchTerms($search)
    {
        return explode(' AND ', $search);
    }

    /**
     * Apply structured filters to searchable attributes and relations.
     *
     * @param  array<string, string>  $filters
     */
    private function applySearchFilters(Builder $query, array $filters): Builder
    {
        $searchableAttributes = $this->getSearchableAttributes();
        $searchableCounts = $this->getSearchableCounts();
        $searchableRelations = $this->getSearchableRelations();
        $table = $this->getTable();

        foreach ($filters as $filterKey => $filterValue) {
            if (in_array($filterKey, $searchableAttributes, true)) {
                $query->where($table.'.'.$filterKey, 'LIKE', '%'.$filterValue.'%');

                continue;
            }

            if (in_array($filterKey, $searchableCounts, true)) {
                $query = $this->applyCountAliasFilter($query, $filterKey, $filterValue);

                continue;
            }

            // Check if this is a custom field (only for Assets - for *now*).
            // Only db_column keys (e.g. "_snipeit_cpu_4") are accepted to avoid
            // collisions with standard attributes or relation filter keys.
            if ($this instanceof Asset) {
                $dbColumn = $this->resolveCustomFieldDbColumn($filterKey);

                if ($dbColumn !== null) {
                    $query->where($table.'.'.$dbColumn, 'LIKE', '%'.$filterValue.'%');

                    continue;
                }
            }

            $resolvedRelationKey = $this->resolveSearchableRelationKey($filterKey, $searchableRelations);

            if ($resolvedRelationKey === null) {
                continue;
            }

            if ($this->isAssignedToRelationKey($resolvedRelationKey)) {
                $query = $this->applyAssignedToRelationFilter($query, $resolvedRelationKey, $filterValue);

                continue;
            }

            $relationColumns = (array) $searchableRelations[$resolvedRelationKey];

            $query->whereHas($resolvedRelationKey, function (Builder $relationQuery) use ($resolvedRelationKey, $relationColumns, $filterValue) {
                $relationTable = $this->getRelationTable($resolvedRelationKey);
                $firstConditionAdded = false;

                foreach ($relationColumns as $relationColumn) {
                    if (! $firstConditionAdded) {
                        $relationQuery->where($relationTable.'.'.$relationColumn, 'LIKE', '%'.$filterValue.'%');
                        $firstConditionAdded = true;

                        continue;
                    }

                    $relationQuery->orWhere($relationTable.'.'.$relationColumn, 'LIKE', '%'.$filterValue.'%');
                }

                if (($resolvedRelationKey === 'adminuser') || ($resolvedRelationKey === 'user')) {
                    $relationQuery->orWhereRaw(
                        $this->buildMultipleColumnSearch(
                            [
                                'users.first_name',
                                'users.last_name',
                                'users.display_name',
                            ]
                        ),
                        ["%{$filterValue}%"]
                    );
                }
            });
        }

        return $query;
    }

    /**
     * Resolve alias keys to configured searchable relation keys.
     *
     * Resolution order:
     *  1. Direct match in $searchableRelations (relation name used as-is by the API)
     *  2. $searchableRelationAliases (API/transformer key → Eloquent relation name)
     *  3. Built-in assigned_to ↔ assignedTo camel/snake alias
     */
    private function resolveSearchableRelationKey(string $filterKey, array $searchableRelations): ?string
    {
        // 1. Direct match — the filter key is already the relation name.
        if (array_key_exists($filterKey, $searchableRelations)) {
            return $filterKey;
        }

        // 2. Model-defined aliases — e.g. 'status_label' => 'status'.
        $aliases = $this->getSearchableRelationAliases();

        if (array_key_exists($filterKey, $aliases)) {
            $aliasedRelation = $aliases[$filterKey];

            if (array_key_exists($aliasedRelation, $searchableRelations)) {
                return $aliasedRelation;
            }
        }

        // 3. Built-in camel/snake alias for the polymorphic assignee relation.
        if ($filterKey === 'assigned_to' && array_key_exists('assignedTo', $searchableRelations)) {
            return 'assignedTo';
        }

        if ($filterKey === 'assignedTo' && array_key_exists('assigned_to', $searchableRelations)) {
            return 'assigned_to';
        }

        return null;
    }

    /**
     * Determine whether a relation key represents polymorphic assignee lookups.
     */
    private function isAssignedToRelationKey(string $relationKey): bool
    {
        return in_array($relationKey, ['assigned_to', 'assignedTo'], true);
    }

    /**
     * Apply filters for assignees with type-specific searchable columns.
     */
    private function applyAssignedToRelationFilter(Builder $query, string $relationKey, string $filterValue): Builder
    {
        $relationName = $this->resolveAssignedToRelationName();

        if ($relationName === null) {
            return $query;
        }

        return $query->whereHasMorph(
            $relationName,
            [User::class, Asset::class, Location::class],
            function (Builder $assigneeQuery, string $assigneeType) use ($filterValue) {
                $columns = $this->getAssigneeColumnsByType($assigneeType);

                if (empty($columns)) {
                    return;
                }

                $table = (new $assigneeType)->getTable();
                $firstConditionAdded = false;

                foreach ($columns as $column) {
                    if (! $firstConditionAdded) {
                        $assigneeQuery->where($table.'.'.$column, 'LIKE', '%'.$filterValue.'%');
                        $firstConditionAdded = true;

                        continue;
                    }

                    $assigneeQuery->orWhere($table.'.'.$column, 'LIKE', '%'.$filterValue.'%');
                }

                if ($assigneeType === User::class) {
                    $assigneeQuery->orWhereRaw(
                        $this->buildMultipleColumnSearch(['users.first_name', 'users.last_name']),
                        ["%{$filterValue}%"]
                    );
                }
            }
        );
    }

    /**
     * Get the searchable columns for a given assignee morph type.
     *
     * Users have no "name" column, only first_name/last_name/username/display_name.
     * Assets use asset_tag as the primary identifier (name is nullable).
     * Locations use name.
     */
    private function getAssigneeColumnsByType(string $assigneeType): array
    {
        return match ($assigneeType) {
            User::class => ['first_name', 'last_name', 'username', 'display_name'],
            Asset::class => ['asset_tag', 'name'],
            Location::class => ['name'],
            default => [],
        };
    }

    /**
     * Resolve the actual relation method name for the assignedTo polymorphic relation.
     *
     * Models may define it as "assignedTo" (camelCase) or "assigned_to" (snake_case).
     * We prefer "assignedTo" when both exist.
     */
    private function resolveAssignedToRelationName(): ?string
    {
        if (method_exists($this, 'assignedTo')) {
            return 'assignedTo';
        }

        if (method_exists($this, 'assigned_to')) {
            return 'assigned_to';
        }

        return null;
    }

    /**
     * Apply filtering on computed count aliases (for example withCount aliases).
     */
    private function applyCountAliasFilter(Builder $query, string $countAlias, string $filterValue): Builder
    {
        if (is_numeric($filterValue)) {
            return $query->having($countAlias, '=', (int) $filterValue);
        }

        return $query->having($countAlias, 'LIKE', '%'.$filterValue.'%');
    }

    /**
     * Searches the models attributes for the search terms
     *
     * @param  $query  Builder
     * @param  $terms  array
     * @return Builder
     */
    private function searchAttributes(Builder $query, array $terms)
    {
        $table = $this->getTable();

        $firstConditionAdded = false;

        foreach ($this->getSearchableAttributes() as $column) {
            foreach ($terms as $term) {
                /**
                 * Making sure to only search in date columns if the search term consists of characters that can make up a MySQL timestamp!
                 *
                 * @see https://github.com/grokability/snipe-it/issues/4590
                 */
                if (! preg_match('/^[0-9 :-]++$/', $term) && in_array($column, $this->getDates())) {
                    continue;
                }

                /**
                 * We need to form the query properly, starting with a "where",
                 * otherwise the generated select is wrong.
                 *
                 * @todo This does the job, but is inelegant and fragile
                 */
                if (! $firstConditionAdded) {
                    $query = $query->where($table.'.'.$column, 'LIKE', '%'.$term.'%');

                    $firstConditionAdded = true;

                    continue;
                }

                $query = $query->orWhere($table.'.'.$column, 'LIKE', '%'.$term.'%');
            }
        }

        return $query;
    }

    /**
     * Searches the models custom fields for the search terms
     *
     * @param  $query  Builder
     * @param  $terms  array
     * @return Builder
     */
    private function searchCustomFields(Builder $query, array $terms)
    {

        /**
         * If we are searching on something other that an asset, skip custom fields.
         */
        if (! $this instanceof Asset) {
            return $query;
        }

        // Only pull unencrypted fields, since encrypted fields cannot be searched on
        $customFields = CustomField::query()
            ->whereNotNull('db_column')
            ->where('field_encrypted', 0)
            ->get(['db_column']);

        if ($customFields->isEmpty()) {
            return $query;
        }

        // Group custom-fields so all custom fields behave consistently as OR conditions.
        return $query->orWhere(function (Builder $customFieldQuery) use ($customFields, $terms): void {
            $firstConditionAdded = false;

            foreach ($customFields as $field) {
                foreach ($terms as $term) {
                    if (! $firstConditionAdded) {
                        $customFieldQuery->where($this->getTable().'.'.$field->db_column_name(), 'LIKE', '%'.$term.'%');
                        $firstConditionAdded = true;

                        continue;
                    }

                    $customFieldQuery->orWhere($this->getTable().'.'.$field->db_column_name(), 'LIKE', '%'.$term.'%');
                }
            }
        });
    }

    /**
     * Searches the models relations for the search terms
     *
     * @param  $query  Builder
     * @param  $terms  array
     */
    private function searchRelations(Builder $query, array $terms): Builder
    {
        foreach ($this->getSearchableRelations() as $relation => $columns) {

            // Polymorphic assignee relations need special per-type column handling
            // because users, assets, and locations each have different identifier columns.
            if ($this->isAssignedToRelationKey($relation)) {
                $query = $this->searchAssignedToRelation($query, $terms);

                continue;
            }

            $isUserRelation = in_array($relation, ['adminuser', 'user'], true);

            // Pre-build the concat SQL outside the closure so $this->buildMultipleColumnSearch()
            // doesn't need to be called inside a nested closure context.
            $concatSql = $isUserRelation
                ? $this->buildMultipleColumnSearch(['users.first_name', 'users.last_name'])
                : null;

            $query = $query->orWhereHas(
                $relation, function (Builder $relationQuery) use ($relation, $columns, $terms, $isUserRelation, $concatSql) {

                    // $table must be resolved inside the closure for self-referential relations
                    // (e.g. User->manager, User->adminuser). getRelationTable relies on the
                    // alias counter that orWhereHas increments before this callback runs.
                    $table = $this->getRelationTable($relation);

                    /**
                     * We need to form the query properly, starting with a "where",
                     * otherwise the generated nested select is wrong.
                     *
                     * @todo This does the job, but is inelegant and fragile
                     */
                    $firstConditionAdded = false;

                    foreach ($columns as $column) {
                        foreach ($terms as $term) {
                            if (! $firstConditionAdded) {
                                $relationQuery->where($table.'.'.$column, 'LIKE', '%'.$term.'%');
                                $firstConditionAdded = true;

                                continue;
                            }

                            $relationQuery->orWhere($table.'.'.$column, 'LIKE', '%'.$term.'%');
                        }
                    }

                    // Also search first+last name concatenated for user relations so that
                    // "John Smith" matches even when the terms are split across columns.
                    if ($isUserRelation && $concatSql !== null) {
                        foreach ($terms as $term) {
                            $relationQuery->orWhereRaw($concatSql, ["%{$term}%"]);
                        }
                    }
                }
            );
        }

        return $query;
    }

    /**
     * Search across the polymorphic assignee relation (assignedTo / assigned_to).
     *
     * Uses whereHasMorph so that each possible assignee type is constrained to the
     * columns that actually exist on that type:
     *   - User     → first_name, last_name, username, display_name
     *   - Asset    → asset_tag, name
     *   - Location → name
     */
    private function searchAssignedToRelation(Builder $query, array $terms): Builder
    {
        $relationName = $this->resolveAssignedToRelationName();

        if ($relationName === null) {
            return $query;
        }

        return $query->orWhereHasMorph(
            $relationName,
            [User::class, Asset::class, Location::class],
            function (Builder $morphQuery, string $morphType) use ($terms) {
                $columns = $this->getAssigneeColumnsByType($morphType);

                if (empty($columns)) {
                    return;
                }

                $table = (new $morphType)->getTable();
                $firstConditionAdded = false;

                foreach ($columns as $column) {
                    foreach ($terms as $term) {
                        if (! $firstConditionAdded) {
                            $morphQuery->where($table.'.'.$column, 'LIKE', '%'.$term.'%');
                            $firstConditionAdded = true;

                            continue;
                        }

                        $morphQuery->orWhere($table.'.'.$column, 'LIKE', '%'.$term.'%');
                    }
                }

                // Also search first+last concatenated for users.
                if ($morphType === User::class) {
                    foreach ($terms as $term) {
                        $morphQuery->orWhereRaw(
                            $this->buildMultipleColumnSearch(['users.first_name', 'users.last_name']),
                            ["%{$term}%"]
                        );
                    }
                }
            }
        );
    }

    /**
     * Run additional, advanced searches that can't be done using the attributes or relations.
     *
     * This is a noop in this trait, but can be overridden in the implementing model, to allow more advanced searches
     *
     * @param  $query  Builder
     * @param  $terms  array
     * @return Builder
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function advancedTextSearch(Builder $query, array $terms)
    {
        return $query;
    }

    /**
     * Get the searchable attributes, if defined. Otherwise it returns an empty array
     *
     * @return array The attributes to search in
     */
    private function getSearchableAttributes()
    {
        return $this->searchableAttributes ?? [];
    }

    /**
     * Get the searchable relations, if defined. Otherwise it returns an empty array
     *
     * @return array The relations to search in
     */
    private function getSearchableRelations()
    {
        return $this->searchableRelations ?? [];
    }

    /**
     * Get searchable computed count aliases, if defined.
     */
    private function getSearchableCounts(): array
    {
        return $this->searchableCounts ?? [];
    }

    /**
     * Get the relation aliases defined on the model.
     *
     * Maps the field names that the API / transformers expose to the actual
     * Eloquent relation names used in $searchableRelations.  For example:
     *
     *   protected $searchableRelationAliases = [
     *       'status_label' => 'status',
     *   ];
     *
     * Override this method in a model if you need dynamic alias resolution.
     *
     * @return array<string, string> [ api_key => relation_name ]
     */
    protected function getSearchableRelationAliases(): array
    {
        return $this->searchableRelationAliases ?? [];
    }

    /**
     * Get the table name of a relation.
     *
     * This method loops over a relation name,
     * getting the table name of the last relation in the series.
     * So "category" would get the table name for the Category model,
     * "model.manufacturer" would get the tablename for the Manufacturer model.
     *
     * @param  string  $relation
     * @return string The table name
     */
    private function getRelationTable($relation)
    {
        $related = $this;

        foreach (explode('.', $relation) as $relationName) {
            $related = $related->{$relationName}()->getRelated();
        }

        /**
         * Are we referencing the model that called?
         * Then get the internal join-tablename, since laravel
         * has trouble selecting the correct one in this type of
         * parent-child self-join.
         *
         * @todo Does this work with deeply nested resources? Like "category.assets.model.category" or something like that?
         */
        if ($this instanceof $related) {

            /**
             * Since laravel increases the counter on the hash on retrieval, we have to count it down again.
             *
             * This causes side effects! Every time we access this method, laravel increases the counter!
             *
             * Format: laravel_reserved_XXX
             */
            $relationCountHash = $this->{$relationName}()->getRelationCountHash();

            $parts = collect(explode('_', $relationCountHash));

            $counter = $parts->pop();

            $parts->push($counter - 1);

            return implode('_', $parts->toArray());
        }

        return $related->getTable();
    }

    /**
     * Builds a search string for either MySQL or sqlite by separating the provided columns with a space.
     *
     * @param  array  $columns  Columns to include in search string.
     */
    private function buildMultipleColumnSearch(array $columns): string
    {
        $mappedColumns = collect($columns)->map(fn ($column) => DB::getTablePrefix().$column)->toArray();

        $driver = config('database.connections.'.config('database.default').'.driver');

        if ($driver === 'sqlite') {
            return implode("||' '||", $mappedColumns).' LIKE ?';
        }

        // Default to MySQL's concatenation method
        return 'CONCAT('.implode('," ",', $mappedColumns).') LIKE ?';
    }

    /**
     * Search a string across multiple columns separated with a space.
     *
     * @param  Builder  $query
     * @param  array  $columns  - Columns to include in search string.
     * @return Builder
     */
    public function scopeOrWhereMultipleColumns($query, array $columns, $term)
    {
        return $query->orWhereRaw($this->buildMultipleColumnSearch($columns), ["%{$term}%"]);
    }

    /**
     * Resolve a filter key to the actual database column name for a custom field.
     *
     * Accepts only raw db_column slugs (e.g. "_snipeit_cpu_4") as filter keys.
     *
     * Returns null when the key cannot be matched to any known custom field.
     *
     * Only applicable to the Asset model.
     */
    private function resolveCustomFieldDbColumn(string $filterKey): ?string
    {
        if (! $this instanceof Asset) {
            return null;
        }

        $map = $this->buildCustomFieldFilterMap();

        // Exact match on db_column (e.g. "_snipeit_cpu_4") only.
        return $map[$filterKey] ?? null;
    }

    /**
     * Build a lookup map for custom field filter resolution.
     *
     * The returned array contains db_column entries only:
     *   - db_column (exact) → db_column, e.g. "_snipeit_cpu_4" => "_snipeit_cpu_4"
     *
     * Results are cached statically for the duration of the request.
     * Call flushCustomFieldFilterMap() to reset the cache (useful in tests).
     *
     * @return array<string, string>
     */
    private function buildCustomFieldFilterMap(): array
    {
        if (isset(static::$customFieldFilterMapCache)) {
            return static::$customFieldFilterMapCache;
        }

        $map = [];

        try {
            CustomField::query()
                ->whereNotNull('db_column')
                ->where('field_encrypted', 0)
                ->get(['db_column'])
                ->each(function (CustomField $field) use (&$map): void {
                    $dbColumn = $field->db_column;

                    // Exact db_column key (e.g. "_snipeit_cpu_4")
                    $map[$dbColumn] = $dbColumn;
                });
        } catch (\Exception $e) {
            // Guard against missing table or schema issues during migrations / tests
        }

        static::$customFieldFilterMapCache = $map;

        return $map;
    }

    /**
     * Flush the custom field filter map cache.
     *
     * Useful in tests or after custom fields are added/modified.
     */
    public static function flushCustomFieldFilterMap(): void
    {
        static::$customFieldFilterMapCache = null;
    }
}
