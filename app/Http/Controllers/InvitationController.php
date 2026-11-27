<?php

namespace App\Http\Controllers;

use App\Models\CeritaCinta;
use App\Models\DoaUcapan;
use App\Models\GaleriFoto;
use App\Models\Tamu;
use App\Services\SectionContentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvitationController extends Controller
{
    public function __construct(
        private readonly SectionContentService $sectionService,
    ) {}

    public function show(Request $request): View
    {
        $guestQuery = $request->query('to') ?? $request->query('tamu');

        return $this->renderInvitation(null, is_string($guestQuery) ? $guestQuery : null);
    }

    public function showBySlug(string $slug): View
    {
        return $this->renderInvitation($slug, null);
    }

    private function renderInvitation(?string $guestSlug, ?string $guestQuery): View
    {
        $guestName = 'Tamu Undangan';
        $isPersonalizedGuest = false;

        if ($guestSlug !== null && $guestSlug !== '') {
            $tamu = Tamu::query()->where('slug', $guestSlug)->first();
            if ($tamu) {
                $guestName = $tamu->nama;
                $isPersonalizedGuest = true;
            } else {
                $cleaned = trim(strip_tags(str_replace('-', ' ', $guestSlug)));
                if ($cleaned !== '') {
                    $guestName = mb_substr(ucwords($cleaned), 0, 100);
                    $isPersonalizedGuest = true;
                }
            }
        } elseif ($guestQuery !== null) {
            $cleaned = trim(strip_tags($guestQuery));
            if ($cleaned !== '') {
                $guestName = mb_substr($cleaned, 0, 100);
                $isPersonalizedGuest = true;
            }
        }

        $sections = $this->sectionService->getAll();
        $stories = CeritaCinta::ordered()->get();
        $allPhotos = GaleriFoto::ordered()->get();

        $stripPhotos = $allPhotos->filter(fn (GaleriFoto $photo) => $photo->isPhotoboothStrip())->values();
        $bentoPhotos = $allPhotos->reject(fn (GaleriFoto $photo) => $photo->isPhotoboothStrip())->values();

        $wishes = DoaUcapan::visible()->latest('created_at')->limit(30)->get();

        $hero = $sections['section_1_hero'];
        $groom = $hero['groom_nickname'] ?? 'Fakhrel';
        $bride = $hero['bride_nickname'] ?? 'Olivia';

        return view('invitation.show', [
            'pageTitle' => "{$groom} & {$bride} — Wedding Invitation",
            'metaDescription' => "Undangan Pernikahan {$groom} & {$bride} — {$hero['wedding_date']}",
            'sections' => $sections,
            'stories' => $stories,
            'bentoPhotos' => $bentoPhotos,
            'stripPhotos' => $stripPhotos,
            'wishes' => $wishes,
            'guestName' => $guestName,
            'isPersonalizedGuest' => $isPersonalizedGuest,
        ]);
    }
}
