<?php

namespace App\Http\Controllers;

use App\Models\KunjunganLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VisitTrackerController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $page = mb_substr(trim((string) $request->input('page', 'invitation')), 0, 100) ?: 'invitation';

        KunjunganLog::query()->create([
            'halaman' => $page,
            'ip_address' => $request->ip(),
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 255),
            'visited_at' => now(),
        ]);

        return response()->json(['success' => true], 201);
    }
}
