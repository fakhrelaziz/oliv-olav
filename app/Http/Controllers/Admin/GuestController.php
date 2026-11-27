<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGuestRequest;
use App\Models\Tamu;
use App\Services\DashboardStatsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GuestController extends Controller
{
    public function __construct(
        private readonly DashboardStatsService $statsService,
    ) {}

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $statusFilter = trim((string) $request->query('status', 'all'));

        $guests = Tamu::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('nama', 'like', "%{$search}%")
                        ->orWhere('alamat', 'like', "%{$search}%")
                        ->orWhere('no_telepon', 'like', "%{$search}%");
                });
            })
            ->when($statusFilter !== '' && $statusFilter !== 'all', fn ($query) => $query->where('status', $statusFilter))
            ->latest('id')
            ->paginate(15);

        return view('admin.tamu.index', [
            'guests' => $guests,
            'stats' => $this->statsService->getGuestStats(),
            'search' => $search,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function store(StoreGuestRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $guest = Tamu::query()->create([
            'user_id' => Auth::id(),
            'nama' => $validated['nama'],
            'slug' => Tamu::generateUniqueSlug($validated['nama']),
            'no_telepon' => $validated['no_telepon'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'jumlah_undangan' => $validated['jumlah_undangan'],
            'status' => $validated['status'],
            'jumlah_hadir_aktual' => $validated['status'] === 'Hadir' ? $validated['jumlah_undangan'] : 0,
        ]);

        return redirect()->route('admin.tamu.index')
            ->with('status', "Tamu \"{$guest->nama}\" berhasil ditambahkan!");
    }

    public function update(StoreGuestRequest $request, Tamu $tamu): RedirectResponse
    {
        $validated = $request->validated();
        $slug = $tamu->nama !== $validated['nama']
            ? Tamu::generateUniqueSlug($validated['nama'], $tamu->id)
            : $tamu->slug;

        $tamu->update([
            'nama' => $validated['nama'],
            'slug' => $slug,
            'no_telepon' => $validated['no_telepon'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'jumlah_undangan' => $validated['jumlah_undangan'],
            'status' => $validated['status'],
            'jumlah_hadir_aktual' => $validated['status'] === 'Hadir' ? $validated['jumlah_undangan'] : 0,
        ]);

        return redirect()->route('admin.tamu.index')
            ->with('status', "Data tamu \"{$tamu->nama}\" berhasil diperbarui!");
    }

    public function destroy(Tamu $tamu): RedirectResponse
    {
        $name = $tamu->nama;
        $tamu->delete();

        return redirect()->route('admin.tamu.index')
            ->with('status', "Data tamu \"{$name}\" berhasil dihapus.");
    }
}
