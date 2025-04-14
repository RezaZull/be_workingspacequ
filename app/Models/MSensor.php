<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class MSensor extends Model
{
    use SoftDeletes;
    protected $fillable = [
        "name",
        "id_m_unit",
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

    public function unit(): HasOne
    {
        return $this->hasOne(MUnit::class, 'id', 'id_m_unit');
    }

}
