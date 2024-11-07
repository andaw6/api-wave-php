<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = ['id', 'name', 'email', 'password', 'phoneNumber', 'isActive', 'role'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'password', 'remember_token'];
    protected $casts = [
        'id' => 'string',
        'password' => 'hashed',
        'isActive' => 'boolean'
    ];
    
    const ROLE_ADMIN = 'ADMIN';
    const ROLE_AGENT = 'AGENT';
    const ROLE_VENDOR = 'VENDOR';
    const ROLE_CLIENT = 'CLIENT';

    // public function transactions()
    // {
    //     return $this->hasMany(Transaction::class, 'senderId')
    //                 ->withCast('id', 'string')
    //                 ->withCast('senderId', 'string');
    // }
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'senderId', 'id');
    }

    public function receivedTransactions()
    {
        return $this->hasMany(Transaction::class, 'receiverId', 'id');
    }

    public function bills()
    {
        return $this->hasMany(Bill::class, 'userId', 'id');
    }

    public function account()
    {
        return $this->hasOne(Account::class, 'userId', 'id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'userId', 'id');
    }

    public function personalInfo()
    {
        return $this->hasOne(PersonalInfo::class, 'userId', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        // Générer un UUID pour chaque nouvel utilisateur
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Retourne un tableau de claims personnalisés pour le JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'user_id' => $this->id,
            'phone_number' => $this->phoneNumber,
            'role' => $this->role,
        ];
    }
}
