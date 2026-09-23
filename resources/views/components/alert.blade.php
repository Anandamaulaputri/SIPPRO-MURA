@props([
    'type' => 'info', // 'success', 'error', 'warning', 'info'
    'dismissible' => false,
])

@php
    $typeConfig = match($type) {
        'success' => [
            'border' => 'border-emerald-500/40',
            'text' => 'text-emerald-300',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
            'iconColor' => 'text-emerald-400',
        ],
        'error' => [
            'border' => 'border-rose-500/40',
            'text' => 'text-rose-300',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
            'iconColor' => 'text-rose-400',
        ],
        'warning' => [
            'border' => 'border-amber-500/40',
            'text' => 'text-amber-300',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
            'iconColor' => 'text-amber-400',
        ],
        default => [
            'border' => 'border-[#D4AF37]/50',
            'text' => 'text-[#E6C65C]',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
            'iconColor' => 'text-[#D4AF37]',
        ],
    };
@endphp

<div {{ $attributes->merge(['class' => 'p-4 rounded-2xl bg-[#151515] border ' . $typeConfig['border'] . ' ' . $typeConfig['text'] . ' flex items-start space-x-3 shadow-md']) }}>
    <div class="{{ $typeConfig['iconColor'] }} mt-0.5 shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $typeConfig['icon'] !!}
        </svg>
    </div>
    <div class="text-xs sm:text-sm font-medium leading-relaxed flex-grow">
        {{ $slot }}
    </div>
    @if($dismissible)
        <button type="button" onclick="this.parentElement.remove()" class="text-[#737373] hover:text-white transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    @endif
</div>
