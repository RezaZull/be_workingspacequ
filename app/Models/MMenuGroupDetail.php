<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class MMenuGroupDetail extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id_m_menu_groups',
        'id_m_menus',
        'flag_create',
        'flag_read',
        'flag_update',
        'flag_delete',
        'flag_export',
        'flag_import',
        'obj_type',
        'created_by',
        'updated_by',
        'deleted_by',
        'flag_active'
    ];

    protected $hidden = [
        "obj_type",
        "created_at",
        "updated_at",
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by",
    ];
    public function menu(): HasOne
    {
        return $this->hasOne(MMenu::class, 'id', 'id_m_menus');
    }
}
