<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KontenAcara extends Model
{
    protected $table = 'konten_acara';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi',
        'kategori',
        'thumbnail_url',
        'link_halaman',
        'urutan',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
