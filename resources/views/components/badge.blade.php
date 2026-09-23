@props([
    'status' => 'default', // 'draft', 'diajukan', 'perlu_perbaikan', 'disetujui', 'ditolak', 'gold', 'wabup', 'admin', 'pengusul'
    'size' => 'md', // 'sm', 'md'
])

@php
    $sizeClass = $size === 'sm' ? 'px-2 py-0.5 text-[10px]' : 'px-2.5 py-1 text-[11px]';

    $statusClass = match($status) {
        'disetujui' => 'bg-emerald-950/60 text-emerald-400 border border-emerald-500/40',
        'perlu_perbaikan' => 'bg-amber-950/60 text-amber-400 border border-amber-500/40',
        'ditolak' => 'bg-rose-950/60 text-rose-400 border border-rose-500/40',
        'diajukan' => 'bg-[#D4AF37]/15 text-[#E6C65C] border border-[#D4AF37]/40',
        'draft' => 'bg-[#1A1A1A] text-[#A3A3A3] border border-[#2A2A2A]',
        'wabup' => 'bg-[#D4AF37]/20 text-[#E6C65C] border border-[#D4AF37]/40 font-bold',
        'admin' => 'bg-blue-950/70 text-blue-300 border border-blue-700/50 font-bold',
        'pengusul' => 'bg-emerald-950/70 text-emerald-300 border border-emerald-700/50 font-bold',
        'gold' => 'bg-[#D4AF37]/15 text-[#E6C65C] border border-[#D4AF37]/40',
        default => 'bg-[#1A1A1A] text-[#A3A3A3] border border-[#2A2A2A]',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center font-bold uppercase tracking-wider rounded-full $sizeClass $statusClass"]) }}>
    {{ $slot }}
</span>
