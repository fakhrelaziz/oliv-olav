<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UploadMediaRequest;
use App\Services\MediaStorageService;
use Illuminate\Http\JsonResponse;

class MediaUploadController extends Controller
{
    public function __construct(
        private readonly MediaStorageService $mediaService,
    ) {}

    public function store(UploadMediaRequest $request): JsonResponse
    {
        $result = $this->mediaService->storeImage(
            $request->file('image'),
            (string) $request->input('section', 'img')
        );

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil diunggah.',
            'url' => $result['url'],
            'filename' => $result['filename'],
            'path' => $result['path'],
        ], 201);
    }
}
