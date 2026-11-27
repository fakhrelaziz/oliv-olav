<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoaUcapan;
use App\Services\DashboardStatsService;
use App\Services\SectionContentService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardStatsService $statsService,
        private readonly SectionContentService $sectionService,
    ) {}

    public function index(): View
    {
        $heroSection = $this->sectionService->getSection('section_1_hero');
        $eventSection = $this->sectionService->getSection('section_4_event');

        $eventData = $eventSection['data'] ?? [];

        return view('admin.dashboard', [
            'completion' => $this->statsService->getCompletionSteps(),
            'visitStats' => $this->statsService->getVisitStats(),
            'guestStats' => $this->statsService->getGuestStats(),
            'rsvpStats' => $this->statsService->getRsvpStats(true),
            'recentWishes' => DoaUcapan::query()->latest('created_at')->limit(5)->get(),
            'heroPreview' => $heroSection['data']['bg_desktop'] ?? '/aset/background_herosection(desktop).webp',
            'eventSummary' => [
                'venue_name' => $eventData['venue_name'] ?? $eventData['akad_place'] ?? 'The Backyard Cafe',
                'date' => $eventData['akad_date'] ?? '25 Desember 2026',
            ],
        ]);
    }
}
