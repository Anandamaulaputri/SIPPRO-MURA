@props([
    'variant' => 'primary', // 'primary', 'secondary', 'danger', 'ghost'
    'type' => 'button',
    'size' => 'md', // 'sm', 'md', 'lg'
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-bold rounded-xl transition-all duration-200 cursor-pointer focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed';
    
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-xs',
        'lg' => 'px-7 py-3.5 text-sm sm:text-base',
        default => 'px-5 py-2.5 text-xs sm:text-sm',
    };

    $variantClasses = match($variant) {
        'secondary' => 'bg-[#151515] hover:bg-[#1F1F1F] text-[#D4AF37] hover:text-[#E6C65C] border border-[#D4AF37]/50 shadow-sm',
        'danger' => 'bg-rose-950/40 hover:bg-rose-900/60 text-rose-400 border border-rose-800/50 shadow-sm',
        'ghost' => 'bg-transparent hover:bg-[#1A1A1A] text-[#A3A3A3] hover:text-white',
        default => 'bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] shadow-md shadow-[#D4AF37]/20 hover:scale-[1.02]',
    };
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "$baseClasses $sizeClasses $variantClasses"]) }}>
    {{ $slot }}
</button>
