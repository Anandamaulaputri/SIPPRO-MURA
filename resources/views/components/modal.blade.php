@props([
    'id' => 'modal',
    'title' => '',
    'maxWidth' => 'md', // 'sm', 'md', 'lg', 'xl', '2xl'
])

@php
    $maxWidthClass = match($maxWidth) {
        'sm' => 'max-w-sm',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        default => 'max-w-md',
    };
@endphp

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="{{ $id }}-title" role="dialog" aria-modal="true">
    <!-- Backdrop with blur -->
    <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" onclick="document.getElementById('{{ $id }}').classList.add('hidden')"></div>

    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-[#151515] border border-[#2A2A2A] text-left shadow-2xl transition-all w-full {{ $maxWidthClass }} my-8">
            <!-- Header -->
            @if($title || isset($header))
                <div class="flex items-center justify-between border-b border-[#2A2A2A] p-5 bg-[#181818]">
                    <h3 class="text-sm sm:text-base font-extrabold text-white" id="{{ $id }}-title">
                        {{ $title }}
                        @if(isset($header)) {{ $header }} @endif
                    </h3>
                    <button type="button" 
                            onclick="document.getElementById('{{ $id }}').classList.add('hidden')"
                            class="rounded-lg p-1.5 text-[#A3A3A3] hover:text-white hover:bg-[#252525] transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Body -->
            <div class="p-5 sm:p-6 text-xs sm:text-sm text-[#D4D4D4] leading-relaxed">
                {{ $slot }}
            </div>

            <!-- Footer -->
            @if(isset($footer))
                <div class="border-t border-[#2A2A2A] p-4 bg-[#111111] flex justify-end space-x-2.5">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
