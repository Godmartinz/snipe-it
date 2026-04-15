<?php

namespace Tests\Feature\Search;

use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Category;
use App\Models\CustomField;
use App\Models\License;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Statuslabel;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * Test the Searchable trait across multiple search modes:
 * - Free-text search (search=term)
 * - Structured filter search (filter={"field":"value"})
 *
 * Tests verify that:
 * 1. Attributes are searchable via both modes
 * 2. Relations are searchable via both modes
 * 3. Relation aliases (e.g., status_label → status) work correctly
 * 4. Multi-word searches work as expected
 */
class SearchableTraitTest extends TestCase
{
    /**
     * Test Asset free-text search on attributes
     */
    public function test_asset_free_text_search_on_attributes()
    {
        Asset::factory()->create(['name' => 'MacBook Pro 15"', 'asset_tag' => 'ASSET-001']);
        Asset::factory()->create(['name' => 'Dell XPS 13', 'asset_tag' => 'ASSET-002']);
        Asset::factory()->create(['name' => 'HP Pavilion', 'asset_tag' => 'ASSET-003']);

        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', ['search' => 'MacBook']))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());

        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', ['search' => 'ASSET']))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 3)->etc());
    }

    /**
     * Test Asset free-text search on relations
     */
    public function test_asset_free_text_search_on_relations()
    {
        // Create fresh test data that won't conflict with system data
        $supplier = Supplier::factory()->create(['name' => 'TestVendor-'.now()->timestamp]);
        $location = Location::factory()->create(['name' => 'TestBuilding-'.now()->timestamp]);

        Asset::factory()->create([
            'name' => 'Asset 1',
            'supplier_id' => $supplier->id,
            'location_id' => $location->id,
        ]);

        Asset::factory()->create(['name' => 'Asset 2']);

        // Search by supplier name
        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', ['search' => 'TestVendor']))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());

        // Search by location name
        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', ['search' => 'TestBuilding']))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());
    }

    /**
     * Test Asset structured filter search on attributes
     */
    public function test_asset_structured_filter_on_attributes()
    {
        Asset::factory()->create(['name' => 'MacBook Pro 15"', 'serial' => 'SN123456']);
        Asset::factory()->create(['name' => 'Dell XPS 13', 'serial' => 'SN789012']);

        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', [
                'filter' => json_encode(['name' => 'MacBook']),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());

        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', [
                'filter' => json_encode(['serial' => 'SN789']),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());
    }

    /**
     * Test Asset structured filter search on relations
     */
    public function test_asset_structured_filter_on_relations()
    {
        $supplier = Supplier::factory()->create(['name' => 'TechVendor Inc']);
        $location = Location::factory()->create(['name' => 'Building A']);
        $manufacturer = Manufacturer::factory()->apple()->create();
        $model = AssetModel::factory()->create(['manufacturer_id' => $manufacturer->id]);
        $category = Category::factory()->assetLaptopCategory()->create();

        Asset::factory()->create([
            'name' => 'Asset 1',
            'model_id' => $model->id,
            'supplier_id' => $supplier->id,
            'location_id' => $location->id,
        ]);

        Asset::factory()->create(['name' => 'Asset 2']);

        // Filter by supplier name
        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', [
                'filter' => json_encode(['supplier' => 'TechVendor']),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());

        // Filter by location name
        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', [
                'filter' => json_encode(['location' => 'Building']),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());

        // Filter by manufacturer name (nested relation via model)
        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', [
                'filter' => json_encode(['manufacturer' => 'Apple']),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());
    }

    /**
     * Test Asset structured filter using relation alias (status_label → status)
     */
    public function test_asset_structured_filter_using_relation_alias()
    {
        // Create a unique status to avoid conflicts with system data
        $status = Statuslabel::factory()->create(['name' => 'TestStatus-'.now()->timestamp]);

        Asset::factory()->create(['status_id' => $status->id]);
        Asset::factory()->create();

        // Filter using the API key 'status_label' should map to 'status' relation
        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', [
                'filter' => json_encode(['status_label' => 'TestStatus']),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());
    }

    /**
     * Test License free-text search on attributes
     */
    public function test_license_free_text_search_on_attributes()
    {
        License::factory()->create(['name' => 'Microsoft Office 365', 'serial' => 'OFFICE-123']);
        License::factory()->create(['name' => 'Adobe Creative Cloud', 'serial' => 'ADOBE-456']);

        $this->actingAsForApi(User::factory()->viewLicenses()->create())
            ->getJson(route('api.licenses.index', ['search' => 'Microsoft']))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());

        $this->actingAsForApi(User::factory()->viewLicenses()->create())
            ->getJson(route('api.licenses.index', ['search' => 'OFFICE']))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());
    }

    /**
     * Test License free-text search on relations
     */
    public function test_license_free_text_search_on_relations()
    {
        $manufacturer = Manufacturer::factory()->microsoft()->create();
        $supplier = Supplier::factory()->create(['name' => 'CloudVendor Inc']);

        License::factory()->create([
            'name' => 'License 1',
            'manufacturer_id' => $manufacturer->id,
            'supplier_id' => $supplier->id,
        ]);

        License::factory()->create(['name' => 'License 2']);

        // Search by manufacturer name
        $this->actingAsForApi(User::factory()->viewLicenses()->create())
            ->getJson(route('api.licenses.index', ['search' => 'Microsoft']))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());

        // Search by supplier name
        $this->actingAsForApi(User::factory()->viewLicenses()->create())
            ->getJson(route('api.licenses.index', ['search' => 'CloudVendor']))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());
    }

    /**
     * Test License structured filter search on attributes
     */
    public function test_license_structured_filter_on_attributes()
    {
        License::factory()->create(['name' => 'Microsoft Office', 'serial' => 'SN-OFFICE-001']);
        License::factory()->create(['name' => 'Adobe Suite', 'serial' => 'SN-ADOBE-002']);

        $this->actingAsForApi(User::factory()->viewLicenses()->create())
            ->getJson(route('api.licenses.index', [
                'filter' => json_encode(['name' => 'Office']),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());

        $this->actingAsForApi(User::factory()->viewLicenses()->create())
            ->getJson(route('api.licenses.index', [
                'filter' => json_encode(['serial' => 'ADOBE']),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());
    }

    /**
     * Test License structured filter search on relations
     */
    public function test_license_structured_filter_on_relations()
    {
        $manufacturer = Manufacturer::factory()->adobe()->create();
        $supplier = Supplier::factory()->create(['name' => 'TechSupply Inc']);

        License::factory()->create([
            'name' => 'License 1',
            'manufacturer_id' => $manufacturer->id,
            'supplier_id' => $supplier->id,
        ]);

        License::factory()->create(['name' => 'License 2']);

        // Filter by manufacturer
        $this->actingAsForApi(User::factory()->viewLicenses()->create())
            ->getJson(route('api.licenses.index', [
                'filter' => json_encode(['manufacturer' => 'Adobe']),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());

        // Filter by supplier
        $this->actingAsForApi(User::factory()->viewLicenses()->create())
            ->getJson(route('api.licenses.index', [
                'filter' => json_encode(['supplier' => 'TechSupply']),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());
    }

    /**
     * Test User free-text search on attributes
     *
     * @group skip-flaky
     */
    public function test_user_free_text_search_on_attributes()
    {
        // Note: User search includes the acting user in results, making this test flaky
        // Use the username search instead which is more deterministic
        $timestamp = now()->timestamp;
        $uniqueName = 'XYZ'.$timestamp;
        User::factory()->create(['first_name' => 'TestJohn'.$uniqueName, 'last_name' => 'Smith'.$uniqueName, 'username' => 'jsmith'.$uniqueName]);
        User::factory()->create(['first_name' => 'TestJane'.$uniqueName, 'last_name' => 'Doe'.$uniqueName, 'username' => 'jdoe'.$uniqueName]);

        $this->actingAsForApi(User::factory()->superuser()->create())
            ->getJson(route('api.users.index', ['search' => 'jsmith'.$uniqueName]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());
    }

    /**
     * Test User multi-word search (first_name + last_name concat)
     */
    public function test_user_multi_word_free_text_search()
    {
        $timestamp = now()->timestamp;
        $uniqueName = 'ABC'.$timestamp;
        User::factory()->create(['first_name' => 'TestJohn'.$uniqueName, 'last_name' => 'Smith'.$uniqueName, 'username' => 'jsmith'.$uniqueName]);
        User::factory()->create(['first_name' => 'TestJane'.$uniqueName, 'last_name' => 'Doe'.$uniqueName, 'username' => 'jdoe'.$uniqueName]);

        // Search for full name should match when both first and last are concatenated
        $this->actingAsForApi(User::factory()->superuser()->create())
            ->getJson(route('api.users.index', ['search' => 'TestJohn'.$uniqueName.' Smith']))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());
    }

    /**
     * Test User structured filter on attributes
     */
    public function test_user_structured_filter_on_attributes()
    {
        $timestamp = now()->timestamp;
        $uniqueName = 'DEF'.$timestamp;
        User::factory()->create(['first_name' => 'TestJohn'.$uniqueName, 'last_name' => 'Smith'.$uniqueName, 'email' => 'john'.$uniqueName.'@example.com']);
        User::factory()->create(['first_name' => 'TestJane'.$uniqueName, 'last_name' => 'Doe'.$uniqueName, 'email' => 'jane'.$uniqueName.'@example.com']);

        $this->actingAsForApi(User::factory()->superuser()->create())
            ->getJson(route('api.users.index', [
                'filter' => json_encode(['first_name' => 'TestJohn'.$uniqueName]),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());

        $this->actingAsForApi(User::factory()->superuser()->create())
            ->getJson(route('api.users.index', [
                'filter' => json_encode(['email' => 'jane'.$uniqueName]),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());
    }

    /**
     * Test Category free-text search on attributes
     */
    public function test_category_free_text_search_on_attributes()
    {
        Category::factory()->assetLaptopCategory()->create();
        Category::factory()->assetDesktopCategory()->create();

        $this->actingAsForApi(User::factory()->superuser()->create())
            ->getJson(route('api.categories.index', ['search' => 'Laptop']))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());

        $this->actingAsForApi(User::factory()->superuser()->create())
            ->getJson(route('api.categories.index', ['search' => 'Desktop']))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());
    }

    /**
     * Test Category structured filter on attributes
     */
    public function test_category_structured_filter_on_attributes()
    {
        Category::factory()->assetLaptopCategory()->create(['notes' => 'For portable computing']);
        Category::factory()->assetDesktopCategory()->create(['notes' => 'For stationary computing']);

        $this->actingAsForApi(User::factory()->superuser()->create())
            ->getJson(route('api.categories.index', [
                'filter' => json_encode(['name' => 'Laptop']),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());

        $this->actingAsForApi(User::factory()->superuser()->create())
            ->getJson(route('api.categories.index', [
                'filter' => json_encode(['notes' => 'portable']),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());
    }

    /**
     * Test Manufacturer free-text search on attributes
     */
    public function test_manufacturer_free_text_search_on_attributes()
    {
        Manufacturer::factory()->apple()->create();
        Manufacturer::factory()->microsoft()->create();
        Manufacturer::factory()->dell()->create();

        $this->actingAsForApi(User::factory()->superuser()->create())
            ->getJson(route('api.manufacturers.index', ['search' => 'Apple']))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());

        $this->actingAsForApi(User::factory()->superuser()->create())
            ->getJson(route('api.manufacturers.index', ['search' => 'Microsoft']))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());
    }

    /**
     * Test Manufacturer structured filter on attributes
     */
    public function test_manufacturer_structured_filter_on_attributes()
    {
        Manufacturer::factory()->apple()->create();
        Manufacturer::factory()->microsoft()->create();

        $this->actingAsForApi(User::factory()->superuser()->create())
            ->getJson(route('api.manufacturers.index', [
                'filter' => json_encode(['name' => 'Apple']),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());
    }

    /**
     * Test Location free-text search on attributes
     */
    public function test_location_free_text_search_on_attributes()
    {
        Location::factory()->create(['name' => 'Building A', 'city' => 'New York']);
        Location::factory()->create(['name' => 'Building B', 'city' => 'Los Angeles']);

        $this->actingAsForApi(User::factory()->superuser()->create())
            ->getJson(route('api.locations.index', ['search' => 'Building']))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 2)->etc());

        $this->actingAsForApi(User::factory()->superuser()->create())
            ->getJson(route('api.locations.index', ['search' => 'New York']))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());
    }

    /**
     * Test Location structured filter on attributes
     */
    public function test_location_structured_filter_on_attributes()
    {
        Location::factory()->create(['name' => 'Building A', 'city' => 'New York']);
        Location::factory()->create(['name' => 'Building B', 'city' => 'Los Angeles']);

        $this->actingAsForApi(User::factory()->superuser()->create())
            ->getJson(route('api.locations.index', [
                'filter' => json_encode(['city' => 'New York']),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());

        $this->actingAsForApi(User::factory()->superuser()->create())
            ->getJson(route('api.locations.index', [
                'filter' => json_encode(['name' => 'Building']),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 2)->etc());
    }

    /**
     * Test partial word matching works in both search modes
     */
    public function test_partial_word_matching()
    {
        Asset::factory()->create(['name' => 'MacBook Pro 15"']);

        // Free-text search
        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', ['search' => 'Book']))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());

        // Filter search
        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', [
                'filter' => json_encode(['name' => 'Pro']),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());
    }

    /**
     * Test search is case-insensitive
     */
    public function test_search_is_case_insensitive()
    {
        Asset::factory()->create(['name' => 'MacBook Pro 15"']);

        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', ['search' => 'macbook']))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());

        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', [
                'filter' => json_encode(['name' => 'MACBOOK']),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());
    }

    /**
     * Test empty search/filter returns no special errors
     */
    public function test_empty_search_returns_all_results()
    {
        Asset::factory()->count(3)->create();

        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', ['search' => '']))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 3)->etc());
    }

    /**
     * Test no results when search matches nothing
     */
    public function test_search_no_results()
    {
        Asset::factory()->create(['name' => 'Asset 1']);

        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', ['search' => 'NonExistentTerm']))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 0)->etc());
    }

    /**
     * Test asset free-text search across multiple custom fields.
     */
    public function test_asset_free_text_search_matches_across_multiple_custom_fields()
    {
        $macFieldOne = CustomField::factory()->macAddress()->create([
            'name' => 'MAC Address One '.now()->timestamp,
        ]);
        $macFieldTwo = CustomField::factory()->macAddress()->create([
            'name' => 'MAC Address Two '.(now()->timestamp + 1),
        ]);

        $dbColumnOne = $macFieldOne->db_column_name();
        $dbColumnTwo = $macFieldTwo->db_column_name();

        $firstMatchingAsset = Asset::factory()->create([
            $dbColumnOne => 'AA:BB:CC:11:22:33',
            $dbColumnTwo => null,
        ]);
        $secondMatchingAsset = Asset::factory()->create([
            $dbColumnOne => null,
            $dbColumnTwo => 'AA:BB:CC:44:55:66',
        ]);
        Asset::factory()->create([
            $dbColumnOne => '10:20:30:40:50:60',
            $dbColumnTwo => '66:55:44:33:22:11',
        ]);

        $response = $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', ['search' => 'AA:BB:CC']))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 2)->etc());

        $returnedIds = collect($response->json('rows'))
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->sort()
            ->values()
            ->all();

        $expectedIds = collect([$firstMatchingAsset->id, $secondMatchingAsset->id])
            ->sort()
            ->values()
            ->all();

        $this->assertSame($expectedIds, $returnedIds);
    }

    /**
     * Test filtering on a custom field using the raw db_column slug.
     */
    public function test_custom_field_filter_by_db_column_slug()
    {
        $field = CustomField::factory()->cpu()->create();
        $dbColumn = $field->db_column_name();

        Asset::factory()->create([$dbColumn => '3.2GHz i9']);
        Asset::factory()->create([$dbColumn => '2.4GHz i5']);
        Asset::factory()->create([$dbColumn => null]);

        // Flush cache so the newly created field is picked up.
        Asset::flushCustomFieldFilterMap();

        // Filter using the raw db_column key.
        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', [
                'filter' => json_encode([$dbColumn => '3.2GHz']),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());
    }

    /**
     * Test filtering by a human-readable custom field name is ignored.
     */
    public function test_custom_field_filter_by_human_readable_name_is_ignored()
    {
        $field = CustomField::factory()->cpu()->create();
        $dbColumn = $field->db_column_name();

        Asset::factory()->create([$dbColumn => '3.2GHz i9']);
        Asset::factory()->create([$dbColumn => '2.4GHz i5']);

        Asset::flushCustomFieldFilterMap();

        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', [
                'filter' => json_encode(['CPU' => 'i9']),
            ]))
            ->assertOk()
            // Human-readable custom field keys are intentionally ignored.
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 2)->etc());
    }

    /**
     * Test custom field name collisions do not override relation filters.
     */
    public function test_custom_field_name_collision_does_not_override_relation_filter()
    {
        $status = Statuslabel::factory()->create(['name' => 'CollisionStatus-'.now()->timestamp]);
        $otherStatus = Statuslabel::factory()->create(['name' => 'DifferentStatus-'.now()->timestamp]);

        $field = CustomField::factory()->create([
            'name' => 'status',
            'field_encrypted' => 0,
        ]);
        $dbColumn = $field->db_column_name();

        Asset::factory()->create([
            'status_id' => $status->id,
            $dbColumn => 'custom-status-value',
        ]);
        Asset::factory()->create([
            'status_id' => $otherStatus->id,
            $dbColumn => 'CollisionStatus',
        ]);

        Asset::flushCustomFieldFilterMap();

        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', [
                'filter' => json_encode(['status' => 'CollisionStatus']),
            ]))
            ->assertOk()
            // This must filter the status relation, not the custom field with same name.
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());
    }

    /**
     * Test filtering on a custom field using the raw db_column slug.
     */
    public function test_custom_field_gets_skipped_if_encrypted()
    {
        $field = CustomField::factory()->testEncrypted()->create();
        $dbColumn = $field->db_column_name();

        Asset::factory()->create([$dbColumn => '3.2GHz i9']);
        Asset::factory()->create([$dbColumn => '2.4GHz i5']);

        Asset::flushCustomFieldFilterMap();

        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', [
                'filter' => json_encode([$dbColumn => 'i9']),
            ]))
            ->assertOk()
            // Encrypted fields are not searchable, so this filter key is ignored.
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 2)->etc());
    }

    /**
     * Test that custom field filter returns no results when value doesn't match.
     */
    public function test_custom_field_filter_returns_empty_when_no_match()
    {
        $field = CustomField::factory()->cpu()->create();
        $dbColumn = $field->db_column_name();

        Asset::factory()->create([$dbColumn => '3.2GHz i9']);

        Asset::flushCustomFieldFilterMap();

        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', [
                'filter' => json_encode([$dbColumn => 'NonExistentCPU']),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 0)->etc());
    }

    /**
     * Test custom field partial match via filter.
     */
    public function test_custom_field_filter_partial_match()
    {
        $field = CustomField::factory()->cpu()->create();
        $dbColumn = $field->db_column_name();

        Asset::factory()->create([$dbColumn => '3.2GHz Intel Core i9']);
        Asset::factory()->create([$dbColumn => '2.4GHz AMD Ryzen 7']);
        Asset::factory()->create([$dbColumn => null]);

        Asset::flushCustomFieldFilterMap();

        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.index', [
                'filter' => json_encode([$dbColumn => 'Intel']),
            ]))
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('rows', 1)->etc());
    }
}
