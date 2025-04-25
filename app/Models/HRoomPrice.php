<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HRoomPrice extends Model
{
    use SoftDeletes;
    protected $fillable = [
        "id_m_room",
        "price",
        'obj_type',
        'created_by',
        'updated_by',
        'deleted_by',
        'flag_active'
    ];
    protected $hidden = [
        "obj_type",
        "updated_at",
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by",
    ];
}
