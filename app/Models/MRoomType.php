<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MRoomType extends Model
{

    use SoftDeletes;
    protected $fillable = [
        'name',
        'max_capacity',
        'max_price',
        'low_price',
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
}
