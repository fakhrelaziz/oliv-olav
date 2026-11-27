<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Acara extends Model
{
    protected $table = 'acara';

    protected $fillable = [
        'user_id',
        'nama_acara',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'zona_waktu',
        'tempat',
        'alamat',
        'google_maps_url',
        'catatan',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'urutan' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
