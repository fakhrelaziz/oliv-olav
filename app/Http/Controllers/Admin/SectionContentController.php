<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSectionContentRequest;
use App\Services\SectionContentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SectionContentController extends Controller
{
    private const VIEW_MAP = [
        'hero' => 'admin.konten.hero',
        'pengantin' => 'admin.konten.pengantin',
        'acara' => 'admin.konten.acara',
        'penutup' => 'admin.konten.penutup',
    ];

    public function __construct(
        private readonly SectionContentService $sectionService,
    ) {}

    public function index(): View
    {
        return view('admin.konten.index', [
            'sections' => $this->sectionService->getSummaryList(),
        ]);
    }

    public function edit(string $section): View|RedirectResponse
    {
        if ($section === 'story') {
            return redirect()->route('admin.story.index');
        }
        if ($section === 'galeri') {
            return redirect()->route('admin.galeri.index');
        }

        if (! isset(self::VIEW_MAP[$section])) {
            abort(404);
        }

        $key = $this->sectionService->resolveSectionKey($section);
        $sectionPayload = $key ? $this->sectionService->getSection($key) : null;

        if (! $sectionPayload) {
            abort(404);
        }

        return view(self::VIEW_MAP[$section], [
            'sectionSlug' => $section,
            'section' => $sectionPayload,
            'data' => $sectionPayload['data'],
            'defaults' => $sectionPayload['defaults'],
        ]);
    }

    public function update(UpdateSectionContentRequest $request, string $section): RedirectResponse
    {
        $key = $this->sectionService->resolveSectionKey($section);
        if (! $key) {
            abort(404);
        }

        $validated = $request->validated();

        if ($key === 'section_1_hero') {
            $groom = trim((string) ($validated['groom_nickname'] ?? '')) ?: 'Fakhrel';
            $bride = trim((string) ($validated['bride_nickname'] ?? '')) ?: 'Olivia';
            $validated['couple_title'] = "{$groom} and {$bride}";
        }

        $this->sectionService->saveSection($key, $validated);

        return back()->with('status', 'Perubahan konten berhasil disimpan ke database!');
    }

    public function reset(Request $request, string $section): JsonResponse|RedirectResponse
    {
        $key = $this->sectionService->resolveSectionKey($section);
        if (! $key) {
            abort(404);
        }

        $field = $request->input('field');
        $field = is_string($field) && $field !== '' ? $field : null;

        try {
            $data = $this->sectionService->resetSection($key, $field);
        } catch (\InvalidArgumentException $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return back()->with('error', $e->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil dikembalikan ke default.',
                'data' => $data,
            ]);
        }

        return back()->with('status', 'Konten section berhasil dikembalikan ke pengaturan default.');
    }
}
