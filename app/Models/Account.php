<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Account extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'userId', 'balance', 'currency', 'qrCode', 'isActive', 'plafond'];
    protected $casts = [
        'id' => 'string',
        'userId' => 'string',
        'balance' => 'decimal:2',
        'plafond' => 'decimal:2',
        'isActive' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
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
}
