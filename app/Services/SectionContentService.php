<?php

namespace App\Services;

use App\Models\Acara;
use App\Models\Pengantin;
use App\Models\SectionKonten;
use Illuminate\Support\Facades\DB;

class SectionContentService
{
    public const IMAGE_KEYS = [
        'bg_desktop', 'bg_mobile', 'photo_couple', 'groom_photo', 'bride_photo',
        'photo_hero', 'header_photo', 'akad_photo', 'resepsi_photo', 'venue_photo',
        'hero_photo', 'banner_photo',
    ];

    public const REQUIRED_NON_EMPTY_KEYS = [
        'groom_nickname',
        'bride_nickname',
        'wedding_date',
        'maps_embed',
        'ewallet_name',
        'ewallet_number',
        'bank_name',
        'bank_number',
    ];

    public const SLUG_TO_KEY = [
        'hero' => 'section_1_hero',
        'pengantin' => 'section_2_about',
        'story' => 'section_3_story',
        'acara' => 'section_4_event',
        'galeri' => 'section_5_gallery',
        'penutup' => 'section_6_rsvp',
    ];

    public function __construct(
        private readonly MediaStorageService $mediaService,
    ) {}

    public static function defaults(?string $key = null): ?array
    {
        $defaults = [
            'section_1_hero' => [
                'section_number' => 1,
                'slug' => 'hero',
                'nama_section' => 'Section 1: Hero & Pembuka',
                'description' => 'Tagline acara, nama pasangan, tanggal pernikahan, salam pembuka, dan background hero.',
                'route' => 'admin.konten.edit',
                'route_params' => ['section' => 'hero'],
                'thumbnail' => '/aset/background_herosection(desktop).webp',
                'data' => [
                    'tagline' => 'THE WEDDING OF',
                    'couple_title' => 'Fakhrel and Olivia',
                    'groom_nickname' => 'Fakhrel',
                    'bride_nickname' => 'Olivia',
                    'wedding_date' => '25 DESEMBER 2026',
                    'salam_text' => 'Tanpa mengurangi rasa hormat, kami mengundang Anda untuk hadir dalam hari bahagia kami.',
                    'side_text' => "SAME\nPEOPLE\nBRIGHTER\nDAYS",
                    'script_text' => "A new\nchapter\ntogether",
                    'city' => 'JAKARTA, INDONESIA',
                    'bg_desktop' => '/aset/background_herosection(desktop).webp',
                    'bg_mobile' => '/aset/background_herosection.webp',
                ],
            ],
            'section_2_about' => [
                'section_number' => 2,
                'slug' => 'pengantin',
                'nama_section' => 'Section 2: Mempelai (About)',
                'description' => 'Profil mempelai pria & wanita, nama orang tua, akun media sosial, foto, dan quote pengantar.',
                'route' => 'admin.konten.edit',
                'route_params' => ['section' => 'pengantin'],
                'thumbnail' => '/aset/section2_fotopengantin.webp',
                'data' => [
                    'headline' => "Sebuah\nAwal dari\nSelamanya",
                    'intro_1' => 'Dengan penuh rasa syukur dan bahagia, kami mengundang Bapak/Ibu/Saudara/i untuk menjadi bagian dari hari istimewa dalam perjalanan hidup kami.',
                    'intro_2' => 'Pernikahan ini bukan hanya tentang dua insan yang saling mencintai, tetapi juga tentang dua keluarga yang dipersatukan dalam doa, restu, dan kebahagiaan bersama.',
                    'photo_couple' => '/aset/section2_fotopengantin.webp',
                    'groom_nickname' => 'Fakhrel',
                    'groom_fullname' => 'Pratama',
                    'groom_parents' => 'Putra pertama dari Bapak Surya Pratama dan Ibu Ratna Dewi',
                    'groom_instagram' => 'https://instagram.com',
                    'groom_photo' => '/aset/section2_fotopengantin(2).webp',
                    'bride_nickname' => 'Olivia',
                    'bride_fullname' => 'Putri',
                    'bride_parents' => 'Putri kedua dari Bapak Hendra Wijaya dan Ibu Sari Lestari',
                    'bride_instagram' => 'https://instagram.com',
                    'bride_photo' => '/aset/section2_fotopengantin(3).webp',
                    'bottom_quote' => 'Dua hati, satu tujuan, melangkah bersama menuju selamanya.',
                ],
            ],
            'section_3_story' => [
                'section_number' => 3,
                'slug' => 'story',
                'nama_section' => 'Section 3: Our Story (Kisah Cinta)',
                'description' => 'Judul kisah cinta, paragraf perjalanan kasih, foto hero, quote penutup, dan milestone timeline.',
                'route' => 'admin.story.index',
                'route_params' => [],
                'thumbnail' => '/aset/section3_fotopengantin.webp',
                'data' => [
                    'headline' => "Kisah\nCinta Kami",
                    'intro' => 'Setiap perjalanan memiliki cerita, dan ini adalah cerita tentang dua hati yang dipertemukan, tumbuh bersama, dan memilih untuk melangkah ke masa depan yang sama.',
                    'photo_hero' => '/aset/section3_fotopengantin.webp',
                    'bottom_quote' => 'Bukan tentang siapa yang sempurna, tapi tentang dua orang yang memilih untuk selalu berjuang bersama.',
                ],
            ],
            'section_4_event' => [
                'section_number' => 4,
                'slug' => 'acara',
                'nama_section' => 'Section 4: Waktu & Lokasi Acara',
                'description' => 'Detail waktu Akad Nikah, Resepsi Pernikahan, nama venue, alamat, dan Google Maps interaktif.',
                'route' => 'admin.konten.edit',
                'route_params' => ['section' => 'acara'],
                'thumbnail' => '/aset/section4_fotoakadnikah.webp',
                'data' => [
                    'headline' => "Waktu\nPernikahan",
                    'intro' => 'Dengan penuh rasa syukur, kami mengundang Bapak/Ibu/Saudara/i untuk hadir dalam rangkaian acara pernikahan kami.',
                    'header_photo' => '/aset/section4_header_cincin.webp',
                    'akad_name' => 'Akad Nikah',
                    'akad_date' => 'Jumat, 25 Desember 2026',
                    'akad_time' => '08.00 - 10.00 WIB',
                    'akad_place' => 'The Glass House, Jakarta',
                    'akad_address' => 'Jl. Bunga Melati No. 12, Jakarta Selatan',
                    'akad_photo' => '/aset/section4_fotoakadnikah.webp',
                    'resepsi_name' => 'Resepsi Pernikahan',
                    'resepsi_date' => 'Jumat, 25 Desember 2026',
                    'resepsi_time' => '11.00 - 14.00 WIB',
                    'resepsi_place' => 'The Glass House, Jakarta',
                    'resepsi_address' => 'Jl. Bunga Melati No. 12, Jakarta Selatan',
                    'resepsi_photo' => '/aset/section4_fotoresepsipernikahan.webp',
                    'venue_headline' => 'Temui Kami Di Sini',
                    'venue_intro' => 'Kami sangat berharap kehadiran Bapak/Ibu/Saudara/i untuk menjadi bagian dari hari istimewa ini.',
                    'venue_name' => 'The Backyard Cafe',
                    'venue_address' => 'Jl. Melawai No. 123, Jakarta Selatan',
                    'venue_photo' => '/aset/section4_bagianbawah.webp',
                    'maps_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15865.1!2d106.79!3d-6.24!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2z!5e0!3m2!1sen!2sid!4v1',
                    'maps_link' => 'https://maps.google.com',
                    'routes_link' => 'https://maps.google.com',
                    'features_parking' => 'Tersedia area parkir',
                    'features_transit' => 'Dapat diakses dengan transportasi umum',
                    'bottom_quote' => 'Tempat di mana cerita baru kami dimulai bersama orang-orang tercinta.',
                ],
            ],
            'section_5_gallery' => [
                'section_number' => 5,
                'slug' => 'galeri',
                'nama_section' => 'Section 5: Galeri Foto',
                'description' => 'Headline galeri, caption editorial, foto utama, quote penutup, dan koleksi foto bento grid.',
                'route' => 'admin.galeri.index',
                'route_params' => [],
                'thumbnail' => '/aset/section5_fotopengantin.webp',
                'data' => [
                    'headline' => "Momen\nBerharga Bersama",
                    'intro' => 'Setiap foto menyimpan cerita, tentang tawa, perjalanan, dan cinta yang tumbuh.',
                    'hero_photo' => '/aset/section5_fotopengantin.webp',
                    'hero_script' => "Same\nPeople\nBrighter\nDays",
                    'bottom_quote' => 'Cinta bukan tentang mencari seseorang yang sempurna, tetapi tentang melihat seseorang yang tidak sempurna dengan cara yang sempurna.',
                ],
            ],
            'section_6_rsvp' => [
                'section_number' => 6,
                'slug' => 'penutup',
                'nama_section' => 'Section 6: RSVP, Ucapan & Penutup',
                'description' => 'Form doa & ucapan, kotak tanda kasih DANA & transfer bank, kalimat penutup, dan footer.',
                'route' => 'admin.konten.edit',
                'route_params' => ['section' => 'penutup'],
                'thumbnail' => '/aset/section6(01).webp',
                'data' => [
                    'headline' => "Doa dan\nUcapan",
                    'intro' => 'Terima kasih atas kehadiran, doa dan ucapan yang tulus untuk kami. Semoga segala kebaikan kembali kepada Anda.',
                    'banner_photo' => '/aset/section6(01).webp',
                    'form_title' => 'Kirim Ucapan',
                    'form_intro' => 'Tuliskan doa, harapan, dan ucapan terbaik untuk kami.',
                    'confirm_box_title' => "Tanda\nKasih",
                    'confirm_box_intro' => 'Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Namun apabila memberi adalah ungkapan tanda kasih, Anda dapat mengirimkannya secara cashless melalui DANA atau transfer rekening berikut.',
                    'ewallet_name' => 'DANA',
                    'ewallet_number' => '08566601948',
                    'ewallet_holder' => 'Fakhrel & Olivia',
                    'bank_name' => 'SeaBank',
                    'bank_number' => '901905120917',
                    'bank_holder' => 'Fakhrel & Olivia',
                    'thanks_script' => "Terima\nKasih",
                    'closing_quote' => "Doa Anda adalah hadiah\nterindah bagi kami.",
                    'closing_subtext' => 'Terima kasih telah menjadi bagian dari hari istimewa kami.',
                    'footer_couple' => 'FAKHREL & OLIVIA',
                    'footer_date' => '25 DESEMBER 2026',
                ],
            ],
        ];

        if ($key !== null) {
            return $defaults[$key] ?? null;
        }

        return $defaults;
    }

    public function resolveSectionKey(string $slugOrKey): ?string
    {
        if (isset(self::SLUG_TO_KEY[$slugOrKey])) {
            return self::SLUG_TO_KEY[$slugOrKey];
        }

        return self::defaults($slugOrKey) ? $slugOrKey : null;
    }

    public function getAll(): array
    {
        $rows = SectionKonten::all()->keyBy('section_key');
        $result = [];

        foreach (self::defaults() as $key => $def) {
            $saved = $rows->get($key)?->konten_json;
            $result[$key] = $this->mergeAndNormalize($key, $def['data'], is_array($saved) ? $saved : []);
        }

        return $result;
    }

    public function getSection(string $key): ?array
    {
        $def = self::defaults($key);
        if (! $def) {
            return null;
        }

        $row = SectionKonten::query()->where('section_key', $key)->first();
        $saved = $row?->konten_json;

        return [
            'key' => $key,
            'slug' => $def['slug'],
            'number' => $def['section_number'],
            'name' => $def['nama_section'],
            'description' => $def['description'],
            'updated_at' => $row?->updated_at,
            'data' => $this->mergeAndNormalize($key, $def['data'], is_array($saved) ? $saved : []),
            'defaults' => $def['data'],
        ];
    }

    public function getSummaryList(): array
    {
        $rows = SectionKonten::all()->keyBy('section_key');
        $list = [];

        foreach (self::defaults() as $key => $def) {
            $row = $rows->get($key);
            $merged = $this->mergeAndNormalize($key, $def['data'], is_array($row?->konten_json) ? $row->konten_json : []);

            $thumb = $def['thumbnail'];
            foreach (['bg_desktop', 'photo_couple', 'photo_hero', 'header_photo', 'hero_photo', 'banner_photo'] as $tk) {
                if (! empty($merged[$tk])) {
                    $thumb = $merged[$tk];
                    break;
                }
            }

            $list[] = [
                'key' => $key,
                'slug' => $def['slug'],
                'number' => $def['section_number'],
                'name' => $def['nama_section'],
                'description' => $def['description'],
                'route' => $def['route'],
                'route_params' => $def['route_params'],
                'thumbnail' => MediaStorageService::normalizeAssetUrl($thumb, $def['thumbnail']),
                'updated_at' => $row?->updated_at,
                'item_count' => count($merged),
                'is_customized' => $row !== null,
            ];
        }

        return $list;
    }

    public function saveSection(string $key, array $incomingData): array
    {
        $def = self::defaults($key);
        if (! $def) {
            throw new \InvalidArgumentException("Section key [{$key}] tidak dikenali.");
        }

        return DB::transaction(function () use ($key, $def, $incomingData) {
            $existing = SectionKonten::query()->where('section_key', $key)->first();
            $prev = is_array($existing?->konten_json) ? $existing->konten_json : [];

            $allowedKeys = array_keys($def['data']);
            $filteredIncoming = array_intersect_key($incomingData, array_flip($allowedKeys));
            $merged = array_merge($def['data'], $prev, $filteredIncoming);

            if ($key === 'section_4_event' && array_key_exists('maps_embed', $merged)) {
                $merged['maps_embed'] = self::extractMapsEmbedUrl(
                    (string) ($merged['maps_embed'] ?? ''),
                    (string) ($def['data']['maps_embed'] ?? '')
                );
            }

            foreach ($merged as $fieldKey => $value) {
                if (in_array($fieldKey, self::IMAGE_KEYS, true)) {
                    $val = trim((string) ($value ?? ''));
                    $merged[$fieldKey] = $val === ''
                        ? ($def['data'][$fieldKey] ?? '')
                        : MediaStorageService::normalizeAssetUrl($val, $def['data'][$fieldKey] ?? '');
                    continue;
                }

                $strVal = is_null($value) ? '' : (string) $value;
                if (trim($strVal) === '' && in_array($fieldKey, self::REQUIRED_NON_EMPTY_KEYS, true)) {
                    $merged[$fieldKey] = $def['data'][$fieldKey] ?? '';
                } else {
                    $merged[$fieldKey] = $strVal;
                }
            }

            SectionKonten::query()->updateOrCreate(
                ['section_key' => $key],
                [
                    'nama_section' => $def['nama_section'],
                    'konten_json' => $merged,
                    'updated_at' => now(),
                ]
            );

            foreach (self::IMAGE_KEYS as $imgKey) {
                $oldUrl = $prev[$imgKey] ?? '';
                $newUrl = $merged[$imgKey] ?? '';
                if ($oldUrl !== '' && $oldUrl !== $newUrl) {
                    $this->mediaService->deleteIfUnused($oldUrl);
                }
            }

            $this->syncRelatedTables($key, $merged);

            return $merged;
        });
    }

    public function resetSection(string $key, ?string $field = null): array
    {
        $def = self::defaults($key);
        if (! $def) {
            throw new \InvalidArgumentException("Section key [{$key}] tidak dikenali.");
        }

        return DB::transaction(function () use ($key, $def, $field) {
            $existing = SectionKonten::query()->where('section_key', $key)->first();
            $oldData = is_array($existing?->konten_json) ? $existing->konten_json : [];

            if ($field !== null && $field !== '') {
                if (! array_key_exists($field, $def['data'])) {
                    throw new \InvalidArgumentException("Field [{$field}] tidak valid untuk section [{$key}].");
                }

                $oldVal = $oldData[$field] ?? '';
                $merged = array_merge($def['data'], $oldData);
                $merged[$field] = $def['data'][$field];

                SectionKonten::query()->updateOrCreate(
                    ['section_key' => $key],
                    [
                        'nama_section' => $def['nama_section'],
                        'konten_json' => $merged,
                        'updated_at' => now(),
                    ]
                );

                if (is_string($oldVal) && $oldVal !== '' && $oldVal !== $def['data'][$field]) {
                    $this->mediaService->deleteIfUnused($oldVal);
                }

                $this->syncRelatedTables($key, $merged);

                return $merged;
            }

            SectionKonten::query()->where('section_key', $key)->delete();

            foreach ($oldData as $val) {
                if (is_string($val)) {
                    $this->mediaService->deleteIfUnused($val);
                }
            }

            $this->syncRelatedTables($key, $def['data']);

            return $def['data'];
        });
    }

    public static function extractMapsEmbedUrl(string $raw, string $fallback = ''): string
    {
        $trimmed = trim($raw);
        if ($trimmed === '') {
            return $fallback;
        }

        if (preg_match('/<iframe[^>]+src=["\']([^"\']+)["\']/i', $trimmed, $matches)) {
            $trimmed = trim(html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }

        if (preg_match('#^https?://#i', $trimmed)) {
            return $trimmed;
        }

        return $fallback;
    }

    private function mergeAndNormalize(string $key, array $defaults, array $saved): array
    {
        $merged = array_merge($defaults, $saved);

        foreach (self::IMAGE_KEYS as $imgKey) {
            if (array_key_exists($imgKey, $merged)) {
                $merged[$imgKey] = MediaStorageService::normalizeAssetUrl(
                    $merged[$imgKey],
                    $defaults[$imgKey] ?? ''
                );
            }
        }

        return $merged;
    }

    private function syncRelatedTables(string $key, array $merged): void
    {
        if ($key === 'section_2_about') {
            if (! empty($merged['groom_nickname'])) {
                Pengantin::query()->where('peran', 'groom')->update([
                    'nama_panggilan' => $merged['groom_nickname'],
                    'nama_lengkap' => trim(($merged['groom_nickname'] ?? '') . ' ' . ($merged['groom_fullname'] ?? '')),
                    'nama_ayah' => $merged['groom_parents'] ?? '',
                    'urutan_anak' => '',
                    'foto_url' => $merged['groom_photo'] ?? '',
                    'instagram' => $merged['groom_instagram'] ?? '',
                ]);
            }
            if (! empty($merged['bride_nickname'])) {
                Pengantin::query()->where('peran', 'bride')->update([
                    'nama_panggilan' => $merged['bride_nickname'],
                    'nama_lengkap' => trim(($merged['bride_nickname'] ?? '') . ' ' . ($merged['bride_fullname'] ?? '')),
                    'nama_ayah' => $merged['bride_parents'] ?? '',
                    'urutan_anak' => '',
                    'foto_url' => $merged['bride_photo'] ?? '',
                    'instagram' => $merged['bride_instagram'] ?? '',
                ]);
            }
        }

        if ($key === 'section_4_event') {
            if (! empty($merged['akad_name'])) {
                Acara::query()->where('nama_acara', 'like', '%Akad%')->update([
                    'tempat' => $merged['akad_place'] ?? '',
                    'alamat' => $merged['akad_address'] ?? '',
                ]);
            }
            if (! empty($merged['resepsi_name'])) {
                Acara::query()->where('nama_acara', 'like', '%Resepsi%')->update([
                    'tempat' => $merged['resepsi_place'] ?? '',
                    'alamat' => $merged['resepsi_address'] ?? '',
                    'google_maps_url' => $merged['maps_link'] ?? '',
                ]);
            }
        }
    }
}
