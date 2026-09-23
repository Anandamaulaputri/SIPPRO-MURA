<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SIPPRO MURA' }} - Sistem Informasi Pelayanan Proposal Murung Raya</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;0,800;0,900;1,600&family=Cinzel:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN for instant rendering -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', '"Times New Roman"', 'Times', 'serif'],
                        times: ['"Times New Roman"', 'Times', 'serif'],
                    },
                    colors: {
                        theme: {
                            base: '#0B0B0B',
                            dark: '#151515',
                            card: '#151515',
                            cardHover: '#1B1B1B',
                            surface: '#1A1A1A',
                            border: '#2A2A2A',
                            borderLight: '#383838',
                            gold: '#D4AF37',
                            goldLight: '#E6C65C',
                            goldDim: '#997C21',
                            muted: '#A3A3A3',
                            mutedLight: '#D4D4D4',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0B0B0B;
            color: #FFFFFF;
        }

        /* Subtle Gold Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0B0B0B;
        }
        ::-webkit-scrollbar-thumb {
            background: #2A2A2A;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #D4AF37;
        }

        /* Reusable Theme Utilities */
        .gold-gradient-text {
            background: linear-gradient(135deg, #FFFFFF 0%, #E6C65C 50%, #D4AF37 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gold-border-glow {
            border-color: rgba(212, 175, 55, 0.4);
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.08);
        }

        .gold-btn {
            background-color: #D4AF37;
            color: #0B0B0B;
            font-weight: 700;
            transition: all 0.2s ease;
        }
        .gold-btn:hover {
            background-color: #E6C65C;
            box-shadow: 0 4px 14px rgba(212, 175, 55, 0.25);
            transform: translateY(-1px);
        }

        .dark-btn {
            background-color: #151515;
            color: #D4AF37;
            border: 1px solid rgba(212, 175, 55, 0.4);
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .dark-btn:hover {
            background-color: #1F1F1F;
            border-color: #D4AF37;
            color: #E6C65C;
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="bg-[#0B0B0B] text-white flex flex-col min-h-screen selection:bg-[#D4AF37] selection:text-[#0B0B0B] overflow-x-hidden">

    @auth
        <x-sidebar />
    @endauth

    <!-- Top Minimalist Government Header -->
    <div class="bg-[#080808] text-[#737373] text-[11px] py-1.5 px-4 border-b border-[#2A2A2A]">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-2">
            <div>
                <span>Pemerintah Kabupaten Murung Raya &bull; Bagian Pelayanan Administrasi Pimpinan</span>
            </div>
            <div class="text-[#A3A3A3] font-mono text-[10px]">
                Tahun Anggaran {{ date('Y') }}
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="bg-[#151515] border-b border-[#2A2A2A] sticky top-0 z-40 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo & Unified Brand Title -->
                <div class="flex items-center space-x-3">
                    @auth
                        <button type="button" 
                                onclick="toggleMobileSidebar()" 
                                class="md:hidden p-2 rounded-lg text-[#A3A3A3] hover:text-white hover:bg-[#1F1F1F] border border-[#2A2A2A] transition-colors" 
                                title="Buka Menu Navigasi">
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
                            <p class="text-[10px] sm:text-xs text-[#A3A3A3] font-medium tracking-tight block leading-tight mt-0.5">
                                Sistem Informasi Pelayanan Proposal Murung Raya
                            </p>
                        </div>
                    </a>
                </div>

                @auth
                    <!-- Desktop Navigation Links for Authenticated Users Only -->
                    <nav class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('home') }}" 
                           class="px-3.5 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('home') ? 'text-white bg-[#1F1F1F] border border-[#2A2A2A]' : 'text-[#A3A3A3] hover:text-white hover:bg-[#1A1A1A]' }}">
                            Beranda
                        </a>

                        <a href="{{ route('dashboard') }}" 
                           class="px-3.5 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('dashboard') ? 'text-[#D4AF37] bg-[#D4AF37]/10 border border-[#D4AF37]/30' : 'text-[#A3A3A3] hover:text-white hover:bg-[#1A1A1A]' }}">
                            Dasbor
                        </a>

                        <a href="{{ route('proposals.index') }}" 
                           class="px-3.5 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('proposals.*') && !request()->routeIs('proposals.create') ? 'text-[#D4AF37] bg-[#D4AF37]/10 border border-[#D4AF37]/30' : 'text-[#A3A3A3] hover:text-white hover:bg-[#1A1A1A]' }}">
                            Daftar Proposal
                        </a>

                        @if(auth()->user()->isPengusul())
                            <a href="{{ route('proposals.create') }}" 
                               class="px-3.5 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('proposals.create') ? 'text-[#D4AF37] bg-[#D4AF37]/10 border border-[#D4AF37]/30' : 'text-[#A3A3A3] hover:text-white hover:bg-[#1A1A1A]' }}">
                                + Ajukan Baru
                            </a>
                        @endif
                    </nav>
                @endauth

                @auth
                    <!-- Right Actions for Logged In Users -->
                    <div class="flex items-center space-x-2 sm:space-x-3">
                        <!-- User Role Badge -->
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

                        <!-- Logout Form -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-[#737373] hover:text-rose-400 hover:bg-rose-950/30 rounded-lg transition-colors" title="Keluar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Global Toast Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5 w-full">
        @if(session('success'))
            <div class="p-4 mb-4 rounded-2xl bg-[#151515] border border-emerald-500/40 text-emerald-300 flex items-start space-x-3 shadow-lg shadow-black/50">
                <div class="text-emerald-400 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-xs font-semibold leading-relaxed">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-4 rounded-2xl bg-[#151515] border border-rose-500/40 text-rose-300 flex items-start space-x-3 shadow-lg shadow-black/50">
                <div class="text-rose-400 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-xs font-semibold leading-relaxed">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="p-4 mb-4 rounded-2xl bg-[#151515] border border-[#D4AF37]/50 text-[#E6C65C] flex items-start space-x-3 shadow-lg shadow-black/50">
                <div class="text-[#D4AF37] mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-xs font-semibold leading-relaxed">
                    {{ session('info') }}
                </div>
            </div>
        @endif
    </div>

    <!-- Main Dynamic Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#0B0B0B] text-[#A3A3A3] mt-16 border-t border-[#2A2A2A]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-11 h-11 rounded-lg bg-[#151515] border border-[#D4AF37]/50 flex items-center justify-center text-[#D4AF37] font-black text-base tracking-wider shadow-sm shrink-0">
                            SP
                        </div>
                        <div>
                            <span class="font-black text-lg text-white tracking-tight">SIPPRO <span class="text-[#D4AF37]">MURA</span></span>
                            <p class="text-xs text-[#A3A3A3] mt-0.5">Sistem Informasi Pelayanan Proposal Murung Raya</p>
                        </div>
                    </div>
                    <p class="text-xs text-[#A3A3A3] max-w-md leading-relaxed">
                        Sistem pelayanan administrasi permohonan dan penelaahan proposal secara transparan, akuntabel, dan terstruktur di lingkungan Pemerintah Kabupaten Murung Raya.
                    </p>
                </div>

                <div>
                    <h4 class="text-white font-bold text-xs uppercase tracking-wider mb-3">Tautan Cepat</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('login') }}" class="hover:text-[#D4AF37] transition-colors">Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-[#D4AF37] transition-colors">Daftar Akun</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold text-xs uppercase tracking-wider mb-3">Kontak Pelayanan</h4>
                    <p class="text-xs leading-relaxed mb-2 text-[#A3A3A3]">
                        Bagian Pelayanan Administrasi Pimpinan<br>
                        Sekretariat Daerah Kab. Murung Raya<br>
                        Puruk Cahu, Kalimantan Tengah
                    </p>
                    <p class="text-xs text-[#D4AF37]">Email: adpim@murungrayakab.go.id</p>
                </div>
            </div>

            <div class="pt-8 border-t border-[#2A2A2A] text-[11px] text-[#A3A3A3] flex flex-wrap justify-between items-center gap-2">
                <p>&copy; {{ date('Y') }} Pemerintah Kabupaten Murung Raya. Hak Cipta Dilindungi.</p>
                <p class="text-[#D4AF37]/80">Black Luxury & Gold Executive Theme</p>
            </div>
        </div>
    </footer>
</body>
</html>
