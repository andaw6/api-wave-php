<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class PersonalInfo extends Model
{
    use HasFactory;
    
    protected $fillable = ['id','userId', 'documentType', 'idCardFrontPhoto', 'idCardBackPhoto', 'verificationStatus', 'verifiedAt', 'verificationMethod', 'rejectionReason'];
    protected $casts = [
        'id' => 'string', 
        'userId' => 'string', 
    ];
    const STATUS_PENDING = 'PENDING';
    const STATUS_VERIFIED = 'VERIFIED';
    const STATUS_REJECTED = 'REJECTED';

    public function user()
    {
        return $this->belongsTo(User::class);
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
