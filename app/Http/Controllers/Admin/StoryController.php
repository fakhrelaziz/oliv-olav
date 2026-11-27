<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStoryRequest;
use App\Models\CeritaCinta;
use App\Services\MediaStorageService;
use App\Services\SectionContentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StoryController extends Controller
{
    public function __construct(
        private readonly SectionContentService $sectionService,
        private readonly MediaStorageService $mediaService,
    ) {}

    public function index(): View
    {
        $section = $this->sectionService->getSection('section_3_story');

        return view('admin.story.index', [
            'header' => $section['data'],
            'defaults' => $section['defaults'],
            'stories' => CeritaCinta::ordered()->get(),
        ]);
    }

    public function store(StoreStoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $nextOrder = (int) (CeritaCinta::query()->max('urutan') ?? 0) + 1;

        CeritaCinta::query()->create([
            'user_id' => Auth::id(),
            'judul' => $validated['judul'],
            'tanggal_momen' => $validated['tanggal_momen'],
            'deskripsi' => $validated['deskripsi'],
            'foto_url' => MediaStorageService::normalizeAssetUrl(
                $validated['foto_url'] ?? '',
                '/aset/section3_fotopengantin(3).webp'
            ),
            'urutan' => $validated['urutan'] ?? $nextOrder,
        ]);

        return redirect()->route('admin.story.index')
            ->with('status', 'Momen cerita baru berhasil disimpan ke database!');
    }

    public function update(StoreStoryRequest $request, CeritaCinta $story): RedirectResponse
    {
        $validated = $request->validated();
        $oldPhoto = $story->foto_url;
        $newPhoto = MediaStorageService::normalizeAssetUrl(
            $validated['foto_url'] ?? $oldPhoto,
            '/aset/section3_fotopengantin(3).webp'
        );

        $story->update([
            'judul' => $validated['judul'],
            'tanggal_momen' => $validated['tanggal_momen'],
            'deskripsi' => $validated['deskripsi'],
            'foto_url' => $newPhoto,
            'urutan' => $validated['urutan'] ?? $story->urutan,
        ]);

        if ($oldPhoto && $oldPhoto !== $newPhoto) {
            $this->mediaService->deleteIfUnused($oldPhoto);
        }

        return redirect()->route('admin.story.index')
            ->with('status', 'Momen cerita berhasil diperbarui!');
    }

    public function destroy(CeritaCinta $story): RedirectResponse
    {
        $oldPhoto = $story->foto_url;
        $story->delete();

        if ($oldPhoto) {
            $this->mediaService->deleteIfUnused($oldPhoto);
        }

        return redirect()->route('admin.story.index')
            ->with('status', 'Momen cerita berhasil dihapus.');
    }
}
