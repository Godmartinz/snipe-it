<?php

namespace App\Http\Transformers;

use App\Helpers\Helper;
use App\Models\MaintenanceType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;

class MaintenanceTypesTransformer
{
    public function transformMaintenanceTypes(Collection $types, int $total): array
    {
        $array = [];
        foreach ($types as $type) {
            $array[] = self::transformMaintenanceType($type);
        }

        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }

    public function transformMaintenanceType(MaintenanceType $type): array
    {
        return [
            'id' => (int) $type->id,
            'name' => e($type->name),
            'created_at' => Helper::getFormattedDateObject($type->created_at, 'datetime'),
            'updated_at' => Helper::getFormattedDateObject($type->updated_at, 'datetime'),
            'deleted_at' => Helper::getFormattedDateObject($type->deleted_at, 'datetime'),
            'available_actions' => [
                'update' => Gate::allows('update', $type),
                'delete' => $type->isDeletable(),
                'restore' => Gate::allows('delete', $type),
            ],
        ];
    }
}
