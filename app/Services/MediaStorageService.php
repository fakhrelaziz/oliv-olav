<?php

namespace App\Services;

use App\Models\CeritaCinta;
use App\Models\GaleriFoto;
use App\Models\Pengantin;
use App\Models\SectionKonten;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaStorageService
{
    public function storeImage(UploadedFile $file, string $sectionPrefix = 'img'): array
    {
        $prefix = preg_replace('/[^a-zA-Z0-9_-]/', '', $sectionPrefix) ?: 'img';
        $ext = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension() ?: 'webp');
        if ($ext === 'jpeg') {
            $ext = 'jpg';
        }

        $filename = $prefix . '_' . time() . '_' . Str::lower(Str::random(6)) . '.' . $ext;
        $storedPath = $file->storeAs('uploads', $filename, 'public');

        return [
            'filename' => $filename,
            'path' => $storedPath,
            'url' => '/storage/' . ltrim($storedPath, '/'),
        ];
    }

    public function deleteIfUnused(?string $oldUrl): bool
    {
        $raw = trim((string) $oldUrl);
        if ($raw === '' || str_starts_with($raw, 'data:') || preg_match('#^https?://#i', $raw)) {
            return false;
        }

        $normalized = ltrim(str_replace('\\', '/', $raw), '/');
        while (str_starts_with($normalized, '../')) {
            $normalized = substr($normalized, 3);
        }

        if (str_starts_with($normalized, 'storage/uploads/')) {
            $relativeStoragePath = substr($normalized, strlen('storage/'));
        } elseif (str_starts_with($normalized, 'uploads/')) {
            $relativeStoragePath = $normalized;
        } else {
            return false;
        }

        $filename = basename($relativeStoragePath);
        if ($filename === '' || $filename === '.' || $filename === '..') {
            return false;
        }

        $pattern = '%' . $filename . '%';
        $stillReferenced =
            SectionKonten::query()->where('konten_json', 'like', $pattern)->exists() ||
            GaleriFoto::query()->where('foto_url', 'like', $pattern)->exists() ||
            CeritaCinta::query()->where('foto_url', 'like', $pattern)->exists() ||
            Pengantin::query()->where('foto_url', 'like', $pattern)->exists();

        if ($stillReferenced) {
            return false;
        }

        if (Storage::disk('public')->exists($relativeStoragePath)) {
            return Storage::disk('public')->delete($relativeStoragePath);
        }

        return false;
    }

    public static function normalizeAssetUrl(?string $url, string $fallback = '/aset/background_herosection.webp'): string
    {
        $val = trim((string) $url);
        if ($val === '') {
            return $fallback;
        }

        $val = str_ireplace(
            [
                'asetV2/dekorasi_akad_hd.webp',
                'asetV2/tata_meja_taman_hd.webp',
                'asetV2/background_herosection(mobile).webp',
                'asetV2/',
            ],
            [
                'aset/section4_fotoakadnikah.webp',
                'aset/section4_fotoresepsipernikahan.webp',
                'aset/background_herosection.webp',
                'aset/',
            ],
            $val
        );

        if (preg_match('#^(data:image/|https?://|/)#i', $val)) {
            return $val;
        }

        while (str_starts_with($val, '../')) {
            $val = substr($val, 3);
        }
        $val = ltrim($val, './');

        if (str_starts_with($val, 'uploads/')) {
            return '/storage/' . $val;
        }

        return '/' . $val;
    }
}
