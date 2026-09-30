@props([
    'title' => null,
])

<header class="bg-[#151515] border-b border-[#2A2A2A] sticky top-0 z-40 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 sm:h-20">
            <!-- Left: Mobile Sidebar Trigger + Logo / Title -->
            <div class="flex items-center space-x-3">
                @auth
                    <!-- Mobile Hamburger Button -->
                    <button type="button" 
                            onclick="toggleMobileSidebar()" 
                            class="md:hidden p-2 rounded-xl text-[#A3A3A3] hover:text-white hover:bg-[#1F1F1F] border border-[#2A2A2A] transition-colors"
                            title="Menu Navigasi">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                @endauth

                <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                    <div class="w-11 h-11 rounded-lg bg-[#151515] border border-[#D4AF37]/50 flex items-center justify-center text-[#D4AF37] font-black text-base tracking-wider shadow-sm group-hover:border-[#D4AF37] transition-all shrink-0">
                        SP
                    </div>
                    <div>
                        <div class="flex items-baseline space-x-1.5">
                            <span class="font-black text-xl sm:text-2xl tracking-tight text-white group-hover:text-[#E6C65C] transition-colors">
                                SIPPRO <span class="text-[#D4AF37]">MURA</span>
                            </span>
                        </div>
                        <p class="text-[10px] sm:text-xs text-[#A3A3A3] font-medium leading-none mt-0.5 hidden sm:block">Sistem Informasi Pelayanan Proposal Murung Raya</p>
                    </div>
                </a>
            </div>

            <!-- Center / Desktop Menu (If not using sidebar) -->
            <nav class="hidden lg:flex items-center space-x-1">
                <a href="{{ route('home') }}" 
                   class="px-3 py-2 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('home') ? 'text-[#D4AF37] bg-[#D4AF37]/10 border border-[#D4AF37]/30' : 'text-[#A3A3A3] hover:text-white hover:bg-[#1F1F1F]' }}">
                    Beranda Publik
                </a>

                @auth
                    <a href="{{ route('dashboard') }}" 
                       class="px-3 py-2 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('dashboard') ? 'text-[#D4AF37] bg-[#D4AF37]/10 border border-[#D4AF37]/30' : 'text-[#A3A3A3] hover:text-white hover:bg-[#1F1F1F]' }}">
                        Dasbor
                    </a>

                    <a href="{{ route('proposals.index') }}" 
                       class="px-3 py-2 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('proposals.*') && !request()->routeIs('proposals.create') ? 'text-[#D4AF37] bg-[#D4AF37]/10 border border-[#D4AF37]/30' : 'text-[#A3A3A3] hover:text-white hover:bg-[#1F1F1F]' }}">
                        Daftar Proposal
                    </a>

                    @if(auth()->user()->isPengusul())
                        <a href="{{ route('proposals.create') }}" 
                           class="px-3 py-2 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('proposals.create') ? 'text-[#D4AF37] bg-[#D4AF37]/10 border border-[#D4AF37]/30' : 'text-[#A3A3A3] hover:text-white hover:bg-[#1F1F1F]' }}">
                            + Ajukan Baru
                        </a>

                        <a href="{{ route('profile.show') }}" 
                           class="px-3 py-2 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('profile.*') ? 'text-[#D4AF37] bg-[#D4AF37]/10 border border-[#D4AF37]/30' : 'text-[#A3A3A3] hover:text-white hover:bg-[#1F1F1F]' }}">
                            Profil Lembaga
                        </a>
                    @endif
                @endauth
            </nav>

            @auth
                <!-- Right Actions / Profile for Authenticated Users -->
                <div class="flex items-center space-x-3">
                    <div class="hidden sm:flex flex-col items-end mr-1 text-right">
                        <span class="text-xs font-bold text-white">{{ auth()->user()->name }}</span>
                        <div class="flex items-center space-x-1.5 mt-0.5">
                            @if(auth()->user()->role === 'wabup')
                                <span class="bg-[#D4AF37]/15 text-[#E6C65C] border border-[#D4AF37]/40 text-[9px] font-bold px-2 py-0.5 rounded-full">
                                    👑 Wakil Bupati
                                </span>
                            @elseif(auth()->user()->role === 'admin')
                                <span class="bg-blue-950/70 text-blue-300 border border-blue-700/50 text-[9px] font-bold px-2 py-0.5 rounded-full">
                                    🛡️ Staf Admin
                                </span>
                            @else
                                <span class="bg-emerald-950/70 text-emerald-300 border border-emerald-700/50 text-[9px] font-bold px-2 py-0.5 rounded-full">
                                    📝 Pengusul
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Logout Trigger Button -->
                    <button type="button" onclick="openLogoutModal()" class="p-2 text-[#A3A3A3] hover:text-rose-400 hover:bg-rose-950/30 border border-transparent hover:border-rose-900/50 rounded-xl transition-all" title="Keluar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </div>
            @endauth
        </div>
    </div>
</header>
