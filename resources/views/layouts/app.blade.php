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
<body class="bg-[#0B0B0B] text-white selection:bg-[#D4AF37] selection:text-[#0B0B0B] overflow-x-hidden">

    <div class="min-h-screen {{ auth()->check() ? 'flex' : 'flex flex-col' }}">
        @auth
            <x-sidebar />
        @endauth

        <div class="flex-1 flex flex-col min-w-0">
            @auth
                <!-- Minimal Top Information Bar for Authenticated Dashboard -->
                <header class="bg-[#080808] text-[#737373] text-[11px] py-2.5 px-4 sm:px-6 lg:px-8 border-b border-[#2A2A2A] sticky top-0 z-30 shadow-xs">
                    <div class="flex flex-wrap justify-between items-center gap-2">
                        <div class="flex items-center space-x-3">
                            <!-- Mobile Hamburger Button to open sidebar drawer (< md) -->
                            <button type="button" 
                                    onclick="toggleMobileSidebar()" 
                                    class="md:hidden p-1.5 rounded-lg text-[#A3A3A3] hover:text-white hover:bg-[#1F1F1F] border border-[#2A2A2A] transition-colors" 
                                    title="Buka Menu Navigasi">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <span class="font-medium text-[#A3A3A3]">
                                Pemerintah Kabupaten Murung Raya <span class="text-[#444444]">&bull;</span> Bagian Pelayanan Administrasi Pimpinan
                            </span>
                        </div>
                        <div class="text-[#D4AF37]/90 font-mono text-[10px]">
                            Tahun Anggaran {{ date('Y') }}
                        </div>
                    </div>
                </header>
            @else
                <!-- Top Minimalist Government Header for Guests -->
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

                <!-- Public Navigation Bar (Landing Page, Login, Register, Lacak) -->
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
                        </div>
                    </div>
                </header>
            @endauth

    <!-- Global Toast Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5 w-full">
        @if(session('success'))
            <div id="flash-success-toast" 
                 class="p-4 sm:p-5 mb-4 rounded-2xl bg-[#151515] border border-emerald-500/50 text-emerald-300 text-sm shadow-xl shadow-black/50 flex items-center justify-between transition-all duration-500 ease-in-out transform">
                <div class="flex items-center space-x-3.5">
                    <div class="w-8 h-8 rounded-xl bg-emerald-950/70 border border-emerald-700/50 text-emerald-400 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-emerald-200">{{ session('success') }}</p>
                        <p class="text-xs text-emerald-400/80 mt-0.5">Seluruh perubahan profil telah tersimpan aman di sistem.</p>
                    </div>
                </div>
                <button type="button" 
                        onclick="dismissSuccessToast()" 
                        class="ml-4 p-1.5 text-emerald-400/70 hover:text-emerald-200 hover:bg-emerald-900/30 rounded-lg transition-colors" 
                        title="Tutup Notifikasi">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <script>
                function dismissSuccessToast() {
                    const toast = document.getElementById('flash-success-toast');
                    if (toast) {
                        toast.classList.add('opacity-0', '-translate-y-3');
                        setTimeout(function() {
                            toast.remove();
                        }, 500);
                    }
                }

                // Otomatis fade-out setelah 3 detik (3000ms)
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(dismissSuccessToast, 3000);
                });
            </script>
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
    </div>
</div>

    @auth
        <!-- Dialog Konfirmasi Logout (Sesuai Standar UX SIPPRO MURA) -->
        <div id="logout-confirm-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="logout-modal-title">
            <!-- Backdrop with blur -->
            <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" onclick="closeLogoutModal()"></div>

            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-[#151515] border border-[#2A2A2A] text-left shadow-2xl transition-all w-full max-w-sm my-8">
                    <!-- Header -->
                    <div class="flex items-center justify-between border-b border-[#2A2A2A] p-5 bg-[#181818]">
                        <h3 class="text-sm sm:text-base font-extrabold text-white flex items-center space-x-2" id="logout-modal-title">
                            <span class="text-[#D4AF37]">🚪</span>
                            <span>Keluar dari SIPPRO MURA?</span>
                        </h3>
                        <button type="button" 
                                onclick="closeLogoutModal()"
                                class="rounded-lg p-1.5 text-[#A3A3A3] hover:text-white hover:bg-[#252525] transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-5 sm:p-6 text-xs sm:text-sm text-[#D4D4D4] leading-relaxed">
                        <p class="text-white font-medium">
                            Apakah Anda yakin ingin keluar dari akun?
                        </p>
                        <p class="text-xs text-[#A3A3A3] mt-1.5 leading-relaxed">
                            Sesi Anda akan diakhiri dan Anda perlu masuk kembali untuk mengakses sistem.
                        </p>
                    </div>

                    <!-- Footer -->
                    <div class="border-t border-[#2A2A2A] p-4 bg-[#111111] flex justify-end space-x-2.5">
                        <button type="button" 
                                onclick="closeLogoutModal()"
                                class="px-4 py-2 rounded-xl text-xs font-semibold text-[#A3A3A3] hover:text-white hover:bg-[#1F1F1F] border border-[#2A2A2A] transition-colors">
                            Batal
                        </button>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-md shadow-rose-900/30 transition-all">
                                Ya, Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function openLogoutModal() {
                const modal = document.getElementById('logout-confirm-modal');
                if (modal) {
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                }
            }
            function closeLogoutModal() {
                const modal = document.getElementById('logout-confirm-modal');
                if (modal) {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            }
        </script>
    @endauth

    @stack('scripts')
</body>
</html>
