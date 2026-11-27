<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Tamu extends Model
{
    protected $table = 'tamu';

    protected $fillable = [
        'user_id',
        'slug',
        'nama',
        'no_telepon',
        'alamat',
        'jumlah_undangan',
        'status',
        'jumlah_hadir_aktual',
        'is_terkirim',
        'waktu_kirim',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_undangan' => 'integer',
            'jumlah_hadir_aktual' => 'integer',
            'is_terkirim' => 'boolean',
            'waktu_kirim' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function doaUcapan(): HasMany
    {
        return $this->hasMany(DoaUcapan::class, 'tamu_id');
    }

    public static function generateUniqueSlug(string $nama, ?int $ignoreId = null): string
    {
        $base = Str::slug($nama) ?: 'tamu-' . random_int(100, 999);
        $slug = $base;
        $counter = 1;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . (++$counter);
        }

        return $slug;
    }

    public function getInvitationUrlAttribute(): string
    {
        return url('/?to=' . rawurlencode($this->nama));
    }
}
