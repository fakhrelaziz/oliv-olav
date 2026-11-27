@props(['wish'])

<div class="bg-warmwhite/75 p-5 rounded-xl shadow-sm border border-moss/10">
    <p class="font-serif font-semibold text-sm mb-0.5 text-moss">{{ $wish->nama_pengirim }}</p>
    <p class="text-[11px] text-moss/60 mb-2.5">{{ $wish->formatted_date }}</p>
    <p class="text-xs sm:text-sm text-moss/85 leading-relaxed">{{ $wish->pesan_ucapan }}</p>
</div>
