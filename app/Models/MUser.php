<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class MUser extends Authenticatable implements JWTSubject
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'id_m_roles',
        'obj_type',
        'created_by',
        'updated_by',
        'deleted_by',
        'flag_active',
        'img_path'
    ];
    protected $hidden = [
        'password',
        "obj_type",
        "created_at",
        "updated_at",
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by",
    ];

    public function role(): HasOne
    {
        return $this->hasOne(MRole::class, 'id', 'id_m_roles');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
