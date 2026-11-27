<?php

namespace App\Http\Controllers;

use App\Http\Requests\Public\StorePublicRsvpRequest;
use App\Models\DoaUcapan;
use App\Models\Tamu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class PublicRsvpController extends Controller
{
    public function index(): JsonResponse
    {
        $wishes = DoaUcapan::visible()
            ->latest('created_at')
            ->limit(30)
            ->get()
            ->map(fn (DoaUcapan $w) => [
                'id' => $w->id,
                'nama_pengirim' => $w->nama_pengirim,
                'status_kehadiran' => $w->status_kehadiran,
                'pesan_ucapan' => $w->pesan_ucapan,
                'formatted_date' => $w->formatted_date,
                'initials' => $w->initials,
            ]);

        return response()->json([
            'success' => true,
            'data' => $wishes,
        ]);
    }

    public function store(StorePublicRsvpRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        $matchedTamu = Tamu::query()
            ->whereRaw('LOWER(nama) = ?', [mb_strtolower($validated['nama_pengirim'])])
            ->first();

        $wish = DoaUcapan::query()->create([
            'tamu_id' => $matchedTamu?->id,
            'nama_pengirim' => $validated['nama_pengirim'],
            'status_kehadiran' => $validated['status_kehadiran'],
            'pesan_ucapan' => $validated['pesan_ucapan'],
            'is_tampil' => true,
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        // Sinkronisasi otomatis status tamu apabila nama terdaftar di daftar tamu
        if ($matchedTamu) {
            $matchedTamu->update([
                'status' => $validated['status_kehadiran'],
                'jumlah_hadir_aktual' => $validated['status_kehadiran'] === 'Hadir'
                    ? max(1, (int) $matchedTamu->jumlah_undangan)
                    : 0,
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Terima kasih! Ucapan dan konfirmasi kehadiran Anda telah tersimpan.',
                'data' => [
                    'id' => $wish->id,
                    'nama_pengirim' => $wish->nama_pengirim,
                    'status_kehadiran' => $wish->status_kehadiran,
                    'pesan_ucapan' => $wish->pesan_ucapan,
                    'formatted_date' => $wish->formatted_date,
                    'initials' => $wish->initials,
                ],
            ], 201);
        }

        return redirect(route('invitation.show') . '#rsvp')
            ->with('status', 'Terima kasih! Ucapan dan konfirmasi kehadiran Anda telah tersimpan.');
    }
}
