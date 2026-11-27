<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGalleryRequest;
use App\Models\GaleriFoto;
use App\Services\MediaStorageService;
use App\Services\SectionContentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function __construct(
        private readonly SectionContentService $sectionService,
        private readonly MediaStorageService $mediaService,
    ) {}

    public function index(): View
    {
        $section = $this->sectionService->getSection('section_5_gallery');

        return view('admin.galeri.index', [
            'header' => $section['data'],
            'defaults' => $section['defaults'],
            'photos' => GaleriFoto::ordered()->get(),
        ]);
    }

    public function store(StoreGalleryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $nextOrder = (int) (GaleriFoto::query()->max('urutan') ?? 0) + 1;

        GaleriFoto::query()->create([
            'user_id' => Auth::id(),
            'judul' => $validated['judul'],
            'kategori' => $validated['kategori'],
            'foto_url' => MediaStorageService::normalizeAssetUrl($validated['foto_url'], '/aset/section5_galerifoto.webp'),
            'keterangan' => $validated['keterangan'] ?? null,
            'urutan' => $validated['urutan'] ?? $nextOrder,
        ]);

        return redirect()->route('admin.galeri.index')
            ->with('status', 'Foto baru berhasil ditambahkan ke galeri!');
    }

    public function update(StoreGalleryRequest $request, GaleriFoto $galeri): RedirectResponse
    {
        $validated = $request->validated();
        $oldPhoto = $galeri->foto_url;
        $newPhoto = MediaStorageService::normalizeAssetUrl($validated['foto_url'], $oldPhoto ?: '/aset/section5_galerifoto.webp');

        $galeri->update([
            'judul' => $validated['judul'],
            'kategori' => $validated['kategori'],
            'foto_url' => $newPhoto,
            'keterangan' => array_key_exists('keterangan', $validated) ? $validated['keterangan'] : $galeri->keterangan,
            'urutan' => $validated['urutan'] ?? $galeri->urutan,
        ]);

        if ($oldPhoto && $oldPhoto !== $newPhoto) {
            $this->mediaService->deleteIfUnused($oldPhoto);
        }

        return redirect()->route('admin.galeri.index')
            ->with('status', "Foto \"{$galeri->judul}\" berhasil diperbarui!");
    }

    public function destroy(GaleriFoto $galeri): RedirectResponse
    {
        $oldPhoto = $galeri->foto_url;
        $title = $galeri->judul;
        $galeri->delete();

        if ($oldPhoto) {
            $this->mediaService->deleteIfUnused($oldPhoto);
        }

        return redirect()->route('admin.galeri.index')
            ->with('status', "Foto \"{$title}\" berhasil dihapus dari galeri.");
    }
}
