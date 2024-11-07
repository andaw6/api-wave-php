<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Transaction extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = ['id','amount', 'senderId', 'receiverId', 'feeAmount', 'currency', 'transactionType', 'status'];
    protected $casts = [ 
        'id' => 'string',  
        'receiverId' => 'string', // Changé en string au lieu de string
        'senderId' => 'string',   // Changé en string au lieu de string
    ]; 

    const TYPE_DEPOSIT = 'DEPOSIT';
    const TYPE_WITHDRAW = 'WITHDRAW';
    const TYPE_PURCHASE = 'PURCHASE';
    const TYPE_TRANSFER = 'TRANSFER';

    const STATUS_PENDING = 'PENDING';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_FAILED = 'FAILED';
    const STATUS_CANCELLED = 'CANCELLED';

    public function sender() 
    { 
        return $this->belongsTo(User::class, 'senderId', 'id');
    } 
 
    public function receiver() 
    { 
        return $this->belongsTo(User::class, 'receiverId', 'id');
    } 
 
    public function creditPurchase() 
    { 
        return $this->hasOne(CreditPurchaseTransaction::class, 'transactionId', 'id');
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
