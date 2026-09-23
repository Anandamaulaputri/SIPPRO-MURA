@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between text-xs">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-xs font-medium text-[#737373] bg-[#151515] border border-[#2A2A2A] rounded-xl cursor-default">
                    &laquo; Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-[#D4AF37] bg-[#151515] border border-[#D4AF37]/40 rounded-xl hover:bg-[#1F1F1F]">
                    &laquo; Sebelumnya
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-xs font-bold text-[#D4AF37] bg-[#151515] border border-[#D4AF37]/40 rounded-xl hover:bg-[#1F1F1F]">
                    Berikutnya &raquo;
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-xs font-medium text-[#737373] bg-[#151515] border border-[#2A2A2A] rounded-xl cursor-default">
                    Berikutnya &raquo;
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-xs text-[#A3A3A3]">
                    Menampilkan
                    <span class="font-bold text-white">{{ $paginator->firstItem() }}</span>
                    sampai
                    <span class="font-bold text-white">{{ $paginator->lastItem() }}</span>
                    dari
                    <span class="font-bold text-[#D4AF37]">{{ $paginator->total() }}</span>
                    usulan
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-xl shadow-sm space-x-1">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="@lang('pagination.previous')">
                            <span class="relative inline-flex items-center px-3 py-2 text-xs font-medium text-[#737373] bg-[#151515] border border-[#2A2A2A] cursor-default rounded-xl">
                                &lsaquo;
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3 py-2 text-xs font-bold text-[#A3A3A3] hover:text-white bg-[#151515] border border-[#2A2A2A] hover:border-[#D4AF37]/40 rounded-xl transition-colors">
                            &lsaquo;
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-3 py-2 text-xs font-medium text-[#737373] bg-[#151515] border border-[#2A2A2A] cursor-default rounded-xl">
                                    {{ $element }}
                                </span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-3.5 py-2 text-xs font-extrabold text-[#0B0B0B] bg-[#D4AF37] border border-[#D4AF37] rounded-xl shadow-md shadow-[#D4AF37]/20">
                                            {{ $page }}
                                        </span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-3.5 py-2 text-xs font-bold text-[#A3A3A3] hover:text-white bg-[#151515] border border-[#2A2A2A] hover:border-[#D4AF37]/40 rounded-xl transition-colors">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3 py-2 text-xs font-bold text-[#A3A3A3] hover:text-white bg-[#151515] border border-[#2A2A2A] hover:border-[#D4AF37]/40 rounded-xl transition-colors">
                            &rsaquo;
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="@lang('pagination.next')">
                            <span class="relative inline-flex items-center px-3 py-2 text-xs font-medium text-[#737373] bg-[#151515] border border-[#2A2A2A] cursor-default rounded-xl">
                                &rsaquo;
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
