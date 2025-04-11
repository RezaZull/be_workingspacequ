<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MRole extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'obj_type', 'created_by', 'updated_by', 'deleted_by', 'flag_active'];

    protected $hidden = [
        "obj_type",
        "created_at",
        "updated_at",
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by",
    ];

    public function menuGroup(): HasMany
    {
        return $this->hasMany(MMenuGroup::class, 'id_m_roles', 'id');
    }
}
