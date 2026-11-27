<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pengantin(): HasMany
    {
        return $this->hasMany(Pengantin::class);
    }

    public function acara(): HasMany
    {
        return $this->hasMany(Acara::class);
    }

    public function galeriFoto(): HasMany
    {
        return $this->hasMany(GaleriFoto::class);
    }

    public function ceritaCinta(): HasMany
    {
        return $this->hasMany(CeritaCinta::class);
    }

    public function tamu(): HasMany
    {
        return $this->hasMany(Tamu::class);
    }
}
