<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class MFeedback extends Model
{
    use SoftDeletes;
    protected $fillable = [
        "id_t_booking",
        "id_m_room",
        "rating",
        "feedback",
        "flag_positif_feedback",
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
}
