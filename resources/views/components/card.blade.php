@props([
    'title' => null,
    'subtitle' => null,
    'headerAction' => null,
    'glow' => false,
])

<div {{ $attributes->merge(['class' => 'bg-[#151515] rounded-2xl border ' . ($glow ? 'border-[#D4AF37]/40 shadow-lg shadow-[#D4AF37]/5' : 'border-[#2A2A2A]') . ' shadow-md overflow-hidden transition-all']) }}>
    @if($title || isset($header))
        <div class="p-5 border-b border-[#2A2A2A] bg-[#181818] flex items-center justify-between">
            <div>
                @if($title)
                    <h3 class="text-sm sm:text-base font-extrabold text-white tracking-tight">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="text-xs text-[#A3A3A3] mt-0.5">{{ $subtitle }}</p>
                @endif
                @if(isset($header))
                    {{ $header }}
                @endif
            </div>
            @if(isset($headerAction))
                <div class="shrink-0">
                    {{ $headerAction }}
                </div>
            @endif
        </div>
    @endif

    <div class="p-5 sm:p-6">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="p-4 border-t border-[#2A2A2A] bg-[#111111]">
            {{ $footer }}
        </div>
    @endif
</div>
