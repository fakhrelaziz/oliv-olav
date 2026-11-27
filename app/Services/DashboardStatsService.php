<?php

namespace App\Services;

use App\Models\CeritaCinta;
use App\Models\DoaUcapan;
use App\Models\GaleriFoto;
use App\Models\KunjunganLog;
use App\Models\SectionKonten;
use App\Models\Tamu;

class DashboardStatsService
{
    public function getVisitStats(): array
    {
        $total = KunjunganLog::query()->count();
        $today = KunjunganLog::query()->whereDate('visited_at', today())->count();
        $week = KunjunganLog::query()->where('visited_at', '>=', now()->subDays(7))->count();
        $prevWeek = KunjunganLog::query()
            ->where('visited_at', '>=', now()->subDays(14))
            ->where('visited_at', '<', now()->subDays(7))
            ->count();

        if ($prevWeek > 0) {
            $percent = (int) round((($week - $prevWeek) / $prevWeek) * 100);
            $growthLabel = ($percent >= 0 ? '▲ +' : '▼ ') . "{$percent}% dari minggu lalu";
        } elseif ($today > 0) {
            $growthLabel = "▲ +{$today} pengunjung hari ini";
        } else {
            $growthLabel = '0 kunjungan hari ini';
        }

        return [
            'total' => $total,
            'total_display' => number_format($total, 0, ',', '.'),
            'today' => $today,
            'week' => $week,
            'growth' => $growthLabel,
        ];
    }

    public function getGuestStats(): array
    {
        $totalTamu = Tamu::query()->count();
        $hadir = Tamu::query()->where('status', 'Hadir')->count();
        $tidakHadir = Tamu::query()->where('status', 'Tidak Hadir')->count();
        $belum = Tamu::query()->where('status', 'Belum Konfirmasi')->count();
        $ragu = Tamu::query()->where('status', 'Masih Ragu')->count();
        $totalPax = (int) Tamu::query()->sum('jumlah_undangan');
        $hadirPax = (int) Tamu::query()->where('status', 'Hadir')->sum('jumlah_hadir_aktual');

        return [
            'total_tamu' => $totalTamu,
            'hadir' => $hadir,
            'tidak_hadir' => $tidakHadir,
            'belum_konfirmasi' => $belum,
            'masih_ragu' => $ragu,
            'pct_hadir' => $totalTamu > 0 ? round(($hadir / $totalTamu) * 100, 1) : 0,
            'pct_tidak_hadir' => $totalTamu > 0 ? round(($tidakHadir / $totalTamu) * 100, 1) : 0,
            'pct_belum_konfirmasi' => $totalTamu > 0 ? round(($belum / $totalTamu) * 100, 1) : 0,
            'total_pax' => $totalPax,
            'hadir_pax' => $hadirPax,
        ];
    }

    public function getRsvpStats(bool $includeHidden = false): array
    {
        $base = DoaUcapan::query()->when(! $includeHidden, fn ($q) => $q->visible());
        $total = (clone $base)->count();
        $hadir = (clone $base)->where('status_kehadiran', 'Hadir')->count();
        $tidakHadir = (clone $base)->where('status_kehadiran', 'Tidak Hadir')->count();
        $ragu = (clone $base)->where('status_kehadiran', 'Masih Ragu')->count();

        return [
            'total_ucapan' => $total,
            'hadir' => $hadir,
            'tidak_hadir' => $tidakHadir,
            'masih_ragu' => $ragu,
            'pct_hadir' => $total > 0 ? round(($hadir / $total) * 100, 1) : 0,
            'pct_tidak_hadir' => $total > 0 ? round(($tidakHadir / $total) * 100, 1) : 0,
            'pct_masih_ragu' => $total > 0 ? round(($ragu / $total) * 100, 1) : 0,
        ];
    }

    public function getCompletionSteps(): array
    {
        $sectionKeys = SectionKonten::query()->pluck('section_key')->flip();

        $steps = [
            ['label' => 'Cover', 'done' => $sectionKeys->has('section_1_hero'), 'route' => route('admin.konten.edit', 'hero')],
            ['label' => 'Mempelai', 'done' => $sectionKeys->has('section_2_about'), 'route' => route('admin.konten.edit', 'pengantin')],
            ['label' => 'Acara', 'done' => $sectionKeys->has('section_4_event'), 'route' => route('admin.konten.edit', 'acara')],
            ['label' => 'Story', 'done' => CeritaCinta::query()->exists(), 'route' => route('admin.story.index')],
            ['label' => 'Galeri', 'done' => GaleriFoto::query()->exists(), 'route' => route('admin.galeri.index')],
            ['label' => 'RSVP', 'done' => DoaUcapan::query()->exists(), 'route' => route('admin.rsvp.index')],
        ];

        $completed = collect($steps)->where('done', true)->count();

        return [
            'steps' => $steps,
            'completed' => $completed,
            'total' => count($steps),
            'percent' => round(($completed / count($steps)) * 100, 1),
        ];
    }
}
