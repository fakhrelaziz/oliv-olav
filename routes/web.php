<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\GuestController;
use App\Http\Controllers\Admin\MediaUploadController;
use App\Http\Controllers\Admin\RsvpModerationController;
use App\Http\Controllers\Admin\SectionContentController;
use App\Http\Controllers\Admin\StoryController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\PublicRsvpController;
use App\Http\Controllers\VisitTrackerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// =========================================================================
// PUBLIC WEDDING INVITATION ROUTES
// =========================================================================
Route::get('/', [InvitationController::class, 'show'])->name('invitation.show');
Route::get('/u/{slug}', [InvitationController::class, 'showBySlug'])
    ->where('slug', '[A-Za-z0-9\-_]+')
    ->name('invitation.guest');

Route::get('/rsvp/wishes', [PublicRsvpController::class, 'index'])->name('rsvp.wishes');
Route::post('/rsvp', [PublicRsvpController::class, 'store'])
    ->middleware('throttle:20,1')
    ->name('rsvp.store');

Route::post('/track-visit', [VisitTrackerController::class, 'store'])
    ->middleware('throttle:30,1')
    ->name('visit.track');

// Fallback route to serve uploaded files from storage/app/public when OS symlink isn't present
Route::get('/storage/{path}', function (string $path) {
    $cleanPath = ltrim(str_replace('\\', '/', $path), '/');
    if (str_contains($cleanPath, '..') || ! Storage::disk('public')->exists($cleanPath)) {
        abort(404);
    }

    return Storage::disk('public')->response($cleanPath, null, [
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('path', '.*')->name('storage.fallback');

// =========================================================================
// ADMIN AUTHENTICATION ROUTES
// =========================================================================
Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login'])
    ->middleware('throttle:10,1')
    ->name('login.attempt');
Route::post('/logout', [AdminAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// =========================================================================
// ADMIN DASHBOARD CMS ROUTES (PROTECTED BY SESSION AUTH)
// =========================================================================
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // CMS 6 Sections
    Route::get('/konten', [SectionContentController::class, 'index'])->name('konten.index');
    Route::get('/konten/{section}', [SectionContentController::class, 'edit'])->name('konten.edit');
    Route::put('/konten/{section}', [SectionContentController::class, 'update'])->name('konten.update');
    Route::post('/konten/{section}/reset', [SectionContentController::class, 'reset'])->name('konten.reset');

    // Asynchronous Image Upload
    Route::post('/media/upload', [MediaUploadController::class, 'store'])->name('media.upload');

    // Section 3: Our Story Timeline CRUD
    Route::get('/story', [StoryController::class, 'index'])->name('story.index');
    Route::post('/story', [StoryController::class, 'store'])->name('story.store');
    Route::put('/story/{story}', [StoryController::class, 'update'])->name('story.update');
    Route::delete('/story/{story}', [StoryController::class, 'destroy'])->name('story.destroy');

    // Section 5: Galeri Foto CRUD
    Route::get('/galeri', [GalleryController::class, 'index'])->name('galeri.index');
    Route::post('/galeri', [GalleryController::class, 'store'])->name('galeri.store');
    Route::put('/galeri/{galeri}', [GalleryController::class, 'update'])->name('galeri.update');
    Route::delete('/galeri/{galeri}', [GalleryController::class, 'destroy'])->name('galeri.destroy');

    // Data Tamu Undangan CRUD
    Route::get('/tamu', [GuestController::class, 'index'])->name('tamu.index');
    Route::post('/tamu', [GuestController::class, 'store'])->name('tamu.store');
    Route::put('/tamu/{tamu}', [GuestController::class, 'update'])->name('tamu.update');
    Route::delete('/tamu/{tamu}', [GuestController::class, 'destroy'])->name('tamu.destroy');

    // Moderasi Doa & Ucapan RSVP
    Route::get('/rsvp', [RsvpModerationController::class, 'index'])->name('rsvp.index');
    Route::post('/rsvp', [RsvpModerationController::class, 'store'])->name('rsvp.store');
    Route::get('/rsvp/export', [RsvpModerationController::class, 'export'])->name('rsvp.export');
    Route::put('/rsvp/{rsvp}', [RsvpModerationController::class, 'update'])->name('rsvp.update');
    Route::patch('/rsvp/{rsvp}/toggle-visibility', [RsvpModerationController::class, 'toggleVisibility'])->name('rsvp.toggle');
    Route::delete('/rsvp/{rsvp}', [RsvpModerationController::class, 'destroy'])->name('rsvp.destroy');
});
