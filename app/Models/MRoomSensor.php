<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class MRoomSensor extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'id_m_room',
        'id_m_sensor',
        'value',
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
    public function room(): HasOne
    {
        return $this->hasOne(MRoom::class, 'id', 'id_m_room');
    }
    public function sensor(): HasOne
    {
        return $this->hasOne(MSensor::class, 'id', 'id_m_sensor');
    }
}
