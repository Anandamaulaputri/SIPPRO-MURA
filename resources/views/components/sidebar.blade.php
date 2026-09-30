@props([
    'active' => 'dashboard',
])

@php
    $user = auth()->user();
@endphp

<!-- Mobile Backdrop -->
<div id="mobile-sidebar-backdrop" 
     class="fixed inset-0 z-40 bg-black/80 backdrop-blur-sm hidden md:hidden transition-opacity duration-300"
     onclick="toggleMobileSidebar()"></div>

<!-- Sidebar Container -->
<aside id="app-sidebar" 
       class="fixed md:sticky top-0 left-0 z-50 h-screen w-64 bg-[#111111] border-r border-[#2A2A2A] flex flex-col justify-between shrink-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
    
    <div>
        <!-- Brand Header in Sidebar -->
        <div class="h-20 px-6 border-b border-[#2A2A2A] flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                <div class="w-11 h-11 rounded-lg bg-[#151515] border border-[#D4AF37]/50 flex items-center justify-center text-[#D4AF37] font-black text-base tracking-wider shadow-sm group-hover:border-[#D4AF37] transition-all shrink-0">
                    SP
                </div>
                <div>
                    <div class="flex items-baseline space-x-1.5">
                        <span class="font-black text-lg tracking-tight text-white group-hover:text-[#E6C65C] transition-colors">
                            SIPPRO <span class="text-[#D4AF37]">MURA</span>
                        </span>
                    </div>
                    <p class="text-[10px] text-[#A3A3A3] font-medium leading-none mt-0.5">Kab. Murung Raya</p>
                </div>
            </a>

            <!-- Mobile Close Button -->
            <button type="button" 
                    onclick="toggleMobileSidebar()" 
                    class="md:hidden text-[#A3A3A3] hover:text-white p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="p-4 space-y-1 text-xs font-semibold">
            <a href="{{ route('dashboard') }}" 
               class="flex items-center space-x-3 px-3.5 py-3 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/40 font-bold' : 'text-[#A3A3A3] hover:text-white hover:bg-[#1A1A1A]' }}">
                <span class="text-base">📊</span>
                <span>Dasbor Utama</span>
            </a>

            <a href="{{ route('proposals.index') }}" 
               class="flex items-center space-x-3 px-3.5 py-3 rounded-xl transition-all {{ request()->routeIs('proposals.index') || request()->routeIs('proposals.show') ? 'bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/40 font-bold' : 'text-[#A3A3A3] hover:text-white hover:bg-[#1A1A1A]' }}">
                <span class="text-base">📑</span>
                <span>Daftar Proposal</span>
            </a>

            @if($user && $user->isPengusul())
                <a href="{{ route('proposals.create') }}" 
                   class="flex items-center space-x-3 px-3.5 py-3 rounded-xl transition-all {{ request()->routeIs('proposals.create') ? 'bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/40 font-bold' : 'text-[#A3A3A3] hover:text-white hover:bg-[#1A1A1A]' }}">
                    <span class="text-base">✍️</span>
                    <span>Ajukan Usulan Baru</span>
                </a>

                <a href="{{ route('profile.show') }}" 
                   class="flex items-center space-x-3 px-3.5 py-3 rounded-xl transition-all {{ request()->routeIs('profile.*') ? 'bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/40 font-bold' : 'text-[#A3A3A3] hover:text-white hover:bg-[#1A1A1A]' }}">
                    <span class="text-base">🏢</span>
                    <span>Profil & Rekening</span>
                </a>
            @endif

            <a href="{{ route('home') }}" 
               class="flex items-center space-x-3 px-3.5 py-3 rounded-xl text-[#A3A3A3] hover:text-white hover:bg-[#1A1A1A] transition-all">
                <span class="text-base">🌐</span>
                <span>Portal Beranda Publik</span>
            </a>
        </nav>
    </div>

    <!-- User Profile & Footer in Sidebar -->
    @if($user)
        <div class="p-4 border-t border-[#2A2A2A] bg-[#0E0E0E]">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 truncate">
                    <div class="w-8 h-8 rounded-full bg-[#1F1F1F] border border-[#2A2A2A] text-[#D4AF37] font-bold flex items-center justify-center text-xs shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="truncate">
                        <p class="text-xs font-bold text-white truncate">{{ $user->name }}</p>
                        <p class="text-[10px] text-[#A3A3A3] capitalize">{{ $user->role === 'wabup' ? 'Wakil Bupati' : ($user->role === 'admin' ? 'Staf Admin' : 'Pengusul') }}</p>
                    </div>
                </div>

                <button type="button" onclick="openLogoutModal()" class="shrink-0 p-1.5 text-[#737373] hover:text-rose-400 hover:bg-rose-950/30 rounded-lg transition-colors" title="Keluar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </div>
        </div>
    @endif
</aside>

<script>
    function toggleMobileSidebar() {
        const sidebar = document.getElementById('app-sidebar');
        const backdrop = document.getElementById('mobile-sidebar-backdrop');
        
        if (sidebar && backdrop) {
            const isHidden = sidebar.classList.contains('-translate-x-full');
            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    }
</script>
