<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectionKonten extends Model
{
    protected $table = 'section_konten';

    public $timestamps = false;

    protected $fillable = [
        'section_key',
        'nama_section',
        'konten_json',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'konten_json' => 'array',
            'updated_at' => 'datetime',
        ];
    }
}
