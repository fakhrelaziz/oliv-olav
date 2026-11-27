<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoaUcapan extends Model
{
    protected $table = 'doa_ucapan';

    public $timestamps = false;

    protected $fillable = [
        'tamu_id',
        'nama_pengirim',
        'status_kehadiran',
        'jumlah_kehadiran',
        'pesan_ucapan',
        'is_tampil',
        'ip_address',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_kehadiran' => 'integer',
            'is_tampil' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function tamu(): BelongsTo
    {
        return $this->belongsTo(Tamu::class, 'tamu_id');
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_tampil', true);
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->created_at
            ? $this->created_at->translatedFormat('d M Y')
            : 'Baru saja';
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(mb_substr(trim((string) $this->nama_pengirim), 0, 2)) ?: 'TU';
    }
}
