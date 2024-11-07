<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Bill extends Model
{
    use HasFactory;
    
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = ['id', 'userId', 'companyId', 'amount', 'currency', 'dueDate', 'status'];
    protected $casts = [
        'id' => 'string',
        'userId' => 'string',
        'companyId' => 'string',
        'amount' => 'decimal:2',
        'dueDate' => 'datetime'
    ];

    const STATUS_PENDING = 'PENDING';
    const STATUS_PAID = 'PAID';
    const STATUS_OVERDUE = 'OVERDUE';

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'companyId', 'id');
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
