<?php

namespace App\Models;

use App\Presenters\Presentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Gate;
use Watson\Validating\ValidatingTrait;

class MaintenanceType extends SnipeModel
{
    use HasFactory;
    use Presentable;
    use SoftDeletes;
    use ValidatingTrait;

    protected $table = 'maintenance_types';

    protected $rules = [
        'name' => 'required|max:100|unique:maintenance_types,name,NULL,id,deleted_at,NULL',
    ];

    protected $injectUniqueIdentifier = true;

    protected $fillable = ['name'];

    public function isDeletable(): bool
    {
        return Gate::allows('delete', $this)
            && ($this->deleted_at == '');
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'maintenance_type_id');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name;
    }
}
