<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Company extends Model
{
    use HasFactory;
    
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = ['id', 'name', 'type', 'icon'];
    protected $casts = [
        'id' => 'string'
    ];

    public function bills()
    {
        return $this->hasMany(Bill::class, 'companyId', 'id');
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
