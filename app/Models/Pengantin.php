<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengantin extends Model
{
    protected $table = 'pengantin';

    protected $fillable = [
        'user_id',
        'peran',
        'nama_panggilan',
        'nama_lengkap',
        'nama_ayah',
        'nama_ibu',
        'urutan_anak',
        'foto_url',
        'instagram',
        'bio_singkat',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
