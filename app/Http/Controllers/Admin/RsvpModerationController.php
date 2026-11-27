<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminRsvpRequest;
use App\Models\DoaUcapan;
use App\Services\DashboardStatsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RsvpModerationController extends Controller
{
    public function __construct(
        private readonly DashboardStatsService $statsService,
    ) {}

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $statusFilter = trim((string) $request->query('status', 'all'));

        $wishes = DoaUcapan::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('nama_pengirim', 'like', "%{$search}%")
                        ->orWhere('pesan_ucapan', 'like', "%{$search}%");
                });
            })
            ->when($statusFilter !== '' && $statusFilter !== 'all', fn ($query) => $query->where('status_kehadiran', $statusFilter))
            ->latest('created_at')
            ->paginate(15);

        return view('admin.rsvp.index', [
            'wishes' => $wishes,
            'stats' => $this->statsService->getRsvpStats(true),
            'search' => $search,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function store(StoreAdminRsvpRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DoaUcapan::query()->create([
            'nama_pengirim' => $validated['nama_pengirim'],
            'status_kehadiran' => $validated['status_kehadiran'],
            'pesan_ucapan' => $validated['pesan_ucapan'],
            'is_tampil' => true,
            'created_at' => now(),
        ]);

        return redirect()->route('admin.rsvp.index')
            ->with('status', 'Doa dan ucapan baru berhasil ditambahkan!');
    }

    public function update(StoreAdminRsvpRequest $request, DoaUcapan $rsvp): RedirectResponse
    {
        $validated = $request->validated();

        $rsvp->update([
            'nama_pengirim' => $validated['nama_pengirim'],
            'status_kehadiran' => $validated['status_kehadiran'],
            'pesan_ucapan' => $validated['pesan_ucapan'],
            'is_tampil' => (bool) ($validated['is_tampil'] ?? $rsvp->is_tampil),
        ]);

        return redirect()->route('admin.rsvp.index')
            ->with('status', 'Ucapan berhasil diperbarui!');
    }

    public function toggleVisibility(DoaUcapan $rsvp): RedirectResponse
    {
        $rsvp->update(['is_tampil' => ! $rsvp->is_tampil]);

        $state = $rsvp->is_tampil ? 'ditampilkan di halaman publik' : 'disembunyikan dari halaman publik';

        return redirect()->route('admin.rsvp.index')
            ->with('status', "Ucapan dari \"{$rsvp->nama_pengirim}\" kini {$state}.");
    }

    public function destroy(DoaUcapan $rsvp): RedirectResponse
    {
        $name = $rsvp->nama_pengirim;
        $rsvp->delete();

        return redirect()->route('admin.rsvp.index')
            ->with('status', "Ucapan dari \"{$name}\" berhasil dihapus.");
    }

    public function export(): StreamedResponse
    {
        $filename = 'doa-ucapan-fakhrel-olivia-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM for Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['No', 'Nama Pengirim', 'Status Kehadiran', 'Pesan Ucapan', 'Tampil Publik', 'Waktu']);

            DoaUcapan::query()->latest('created_at')->chunk(200, function ($rows) use ($handle) {
                static $index = 0;
                foreach ($rows as $row) {
                    $index++;
                    fputcsv($handle, [
                        $index,
                        $row->nama_pengirim,
                        $row->status_kehadiran,
                        $row->pesan_ucapan,
                        $row->is_tampil ? 'Ya' : 'Disembunyikan',
                        $row->created_at?->timezone(config('app.timezone'))->format('Y-m-d H:i:s') ?? '-',
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
