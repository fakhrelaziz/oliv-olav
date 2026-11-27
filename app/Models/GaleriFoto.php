<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GaleriFoto extends Model
{
    protected $table = 'galeri_foto';

    protected $fillable = [
        'user_id',
        'judul',
        'kategori',
        'foto_url',
        'keterangan',
        'urutan',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('urutan')->orderByDesc('id');
    }

    public function isPhotoboothStrip(): bool
    {
        $url = strtolower((string) $this->foto_url);
        $title = strtolower((string) $this->judul);

        return str_contains($url, 'photobooth_strip')
            || str_contains($url, 'img_735')
            || str_contains($title, 'photobooth')
            || str_contains($title, 'picapica');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->kategori) {
            'acara' => 'Detail & Cincin',
            'keseharian' => 'Keseharian',
            'lainnya' => 'Lainnya',
            default => 'Prewedding',
        };
    }
}
