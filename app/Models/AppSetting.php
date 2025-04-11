<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppSetting extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'code',
        'name',
        'value'
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
