<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ScheduledTransfer extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'receiver_phone_number',
        'amount',
        'frequency',
        'next_execution',
        'fee_amount',
        'currency',
        'status'
    ];

    // Si vous voulez personnaliser la colonne d'ID
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
}
