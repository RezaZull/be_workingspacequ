<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class TBooking extends Model
{
    use SoftDeletes;
    protected $fillable = [
        "id_m_user",
        "payment_status",
        "date_book",
        "grandtotal",
        "order_id",
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
    public function user(): HasOne
    {
        return $this->hasOne(MUser::class, 'id', 'id_m_user');
    }
}
