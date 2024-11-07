<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class CreditPurchaseTransaction extends Model
{
    use HasFactory;
    
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = ['id', 'transactionId', 'receiverName', 'receiverPhoneNumber', 'receiverEmail'];
    protected $casts = [
        'id' => 'string',
        'transactionId' => 'string'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transactionId', 'id');
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
