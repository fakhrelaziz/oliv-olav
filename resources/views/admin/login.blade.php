@extends('layouts.auth')

@section('title', 'Login Admin — REL | LIV Dashboard')

@section('content')
<div class="relative z-10 w-full max-w-[440px] bg-[#FCFCF9] border border-borderSoft/80 rounded-3xl p-8 sm:p-10 shadow-[0_12px_40px_rgba(44,52,36,0.06)]">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center space-x-3 mb-4">
            <span class="font-display text-2xl tracking-[0.3em] font-semibold text-moss">REL</span>
            <span class="text-borderSoft text-xl">|</span>
            <span class="font-display text-2xl tracking-[0.3em] font-semibold text-moss">LIV</span>
        </div>
        <h1 class="font-display text-3xl font-semibold text-inkAdmin tracking-tight">Selamat Datang Kembali</h1>
        <p class="text-xs text-inkMuted mt-2 font-normal leading-relaxed">
            Kelola undangan pernikahan Fakhrel &amp; Olivia dengan mudah.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-5 px-4 py-3 rounded-xl bg-badgeRed border border-textRed/20 text-textRed text-xs font-medium">
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('status'))
        <div class="mb-5 px-4 py-3 rounded-xl bg-badgeGreen border border-moss/20 text-textGreen text-xs font-medium">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('login.attempt') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-xs font-medium text-inkAdmin mb-1.5">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                required
                autofocus
                autocomplete="email"
                value="{{ old('email') }}"
                placeholder="admin@relliv.com"
                class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-white border border-borderSoft focus:outline-none focus:border-moss focus:ring-2 focus:ring-moss/10 transition text-inkAdmin placeholder:text-gray-400"
            >
        </div>

        <div>
            <label for="password" class="block text-xs font-medium text-inkAdmin mb-1.5">Password</label>
            <div class="relative">
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full pl-3.5 pr-10 py-2.5 rounded-xl text-sm bg-white border border-borderSoft focus:outline-none focus:border-moss focus:ring-2 focus:ring-moss/10 transition text-inkAdmin placeholder:text-gray-400"
                >
                <button
                    type="button"
                    data-toggle-password="password"
                    aria-pressed="false"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-inkAdmin transition inline-flex items-center justify-center"
                    aria-label="Tampilkan atau sembunyikan password"
                >
                    <span data-eye-show class="inline-flex"><i data-lucide="eye" class="w-4 h-4"></i></span>
                    <span data-eye-hide class="hidden"><i data-lucide="eye-off" class="w-4 h-4"></i></span>
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between text-xs pt-1">
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" name="remember" value="1" @checked(old('remember')) class="w-3.5 h-3.5 rounded text-moss focus:ring-moss border-borderSoft accent-moss">
                <span class="text-inkMuted">Ingat saya</span>
            </label>
            <a href="{{ route('invitation.show') }}" class="text-inkMuted hover:text-moss underline decoration-borderSoft underline-offset-4 transition">
                Lihat Undangan
            </a>
        </div>

        <button
            type="submit"
            class="w-full py-3 px-4 rounded-xl bg-moss hover:bg-adminMossLight text-adminBg font-medium text-sm transition-all duration-200 shadow-sm hover:shadow active:scale-[0.99] flex items-center justify-center space-x-2 mt-2"
        >
            <span>Masuk ke Dashboard</span>
        </button>
    </form>
</div>
@endsection
