<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class MMenuGroup extends Model
{
    use SoftDeletes;

    protected $fillable = ['id_m_roles', 'name', 'obj_type', 'created_by', 'updated_by', 'deleted_by', 'flag_active'];

    protected $hidden = [
        "obj_type",
        "created_at",
        "updated_at",
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by",
    ];

    public function menuGroupDetail(): HasMany
    {
        return $this->hasMany(MMenuGroupDetail::class, 'id_m_menu_groups', 'id');
    }
    public function role(): HasOne
    {
        return $this->hasOne(MRole::class, 'id', 'id_m_roles');
    }
}
