@props(['status'])

@php
    $badgeClass = match ($status) {
        'Hadir' => 'bg-badgeGreen text-textGreen',
        'Tidak Hadir' => 'bg-badgeRed text-textRed',
        default => 'bg-badgeAmber text-textAmber',
    };
@endphp

<span {{ $attributes->merge(['class' => "px-2.5 py-1 rounded-full text-[11px] font-medium whitespace-nowrap {$badgeClass}"]) }}>
    {{ $status }}
</span>
