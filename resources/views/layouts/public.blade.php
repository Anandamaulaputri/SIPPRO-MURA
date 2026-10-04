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

        .gold-gradient-text {
            background: linear-gradient(135deg, #FFFFFF 0%, #E6C65C 50%, #D4AF37 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-[#0B0B0B] text-white selection:bg-[#D4AF37] selection:text-[#0B0B0B] overflow-x-hidden min-h-screen flex flex-col">

    <!-- Top Minimalist Government Header -->
    <div class="bg-[#080808] text-[#737373] text-[11px] py-1.5 px-4 sm:px-6 lg:px-8 border-b border-[#2A2A2A]">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-2">
            <div>
                <span>Pemerintah Kabupaten Murung Raya &bull; Bagian Pelayanan Administrasi Pimpinan</span>
            </div>
            <div class="text-[#D4AF37]/90 font-mono text-[10px]">
                Tahun Anggaran {{ date('Y') }}
            </div>
        </div>
    </div>

    <!-- Public Navigation Bar -->
    <header class="bg-[#151515] border-b border-[#2A2A2A] sticky top-0 z-40 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo & Unified Brand Title -->
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

                <!-- Header Actions (Lacak Usulan & Auth) -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <a href="{{ route('tracking.search') }}" 
                       class="px-3 py-2 rounded-xl text-xs font-bold text-[#D4AF37] hover:text-[#E6C65C] bg-[#1A1A1A]/60 hover:bg-[#1A1A1A] border border-[#D4AF37]/40 hover:border-[#D4AF37] transition-all flex items-center space-x-1.5 shadow-sm">
                        <span>🔍</span>
                        <span>Lacak Usulan</span>
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" 
                           class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#C5A028] hover:from-[#E6C65C] hover:to-[#D4AF37] text-[#0B0B0B] font-black text-xs shadow-md shadow-[#D4AF37]/20 transition-all hover:scale-105">
                            Buka Dasbor
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="px-3 py-2 text-xs font-bold text-[#A3A3A3] hover:text-white transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" 
                           class="hidden sm:inline-flex px-4 py-2 rounded-xl bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] font-black text-xs shadow-md shadow-[#D4AF37]/20 transition-all hover:scale-105">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Global Toast Alerts for Public Pages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="p-4 mb-4 rounded-2xl bg-[#151515] border border-emerald-500/50 text-emerald-300 text-xs font-semibold shadow-lg">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 mb-4 rounded-2xl bg-[#151515] border border-rose-500/50 text-rose-300 text-xs font-semibold shadow-lg">
                {{ session('error') }}
            </div>
        @endif
        @if(session('info'))
            <div class="p-4 mb-4 rounded-2xl bg-[#151515] border border-[#D4AF37]/50 text-[#E6C65C] text-xs font-semibold shadow-lg">
                {{ session('info') }}
            </div>
        @endif
    </div>

    <!-- Main Public Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Public Footer -->
    <footer class="bg-[#0B0B0B] text-[#A3A3A3] border-t border-[#2A2A2A]">
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
                    <h4 class="text-white font-bold text-xs uppercase tracking-wider mb-3">Tautan Publik</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('home') }}" class="hover:text-[#D4AF37] transition-colors">Beranda Publik</a></li>
                        <li><a href="{{ route('tracking.search') }}" class="hover:text-[#D4AF37] text-[#D4AF37]/90 transition-colors">Lacak Status Proposal</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-[#D4AF37] transition-colors">Masuk ke Sistem</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-[#D4AF37] transition-colors">Daftar Akun Baru</a></li>
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

    @stack('scripts')
</body>
</html>
