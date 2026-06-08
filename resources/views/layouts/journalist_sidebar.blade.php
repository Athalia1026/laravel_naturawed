<aside class="w-64 bg-[#fafafa] border-r border-gray-100 flex flex-col sticky top-0 h-screen shrink-0">
    <!-- Area Nama & Portal -->
    <div class="p-8 pb-4">
        <h1 class="text-xl font-serif font-semibold text-[#2d3e2d]">
            {{ Auth::user()->name }}
        </h1>
        <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mt-1">Journalist Portal</p>
    </div>

    <!-- Area Menu Navigasi -->
    <nav class="flex-1 px-4 space-y-2 mt-4 overflow-y-auto hide-scrollbar">
        
        <!-- Tombol Logout -->
        <form method="POST" action="{{ route('logout') }}" class="mb-0">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-3 w-full text-left text-gray-500 hover:bg-red-50 hover:text-red-500 rounded-xl transition-all duration-200 active:scale-95 text-sm group">
                <i data-lucide="log-out" class="w-[18px] h-[18px] group-hover:-translate-x-1 transition-transform"></i>
                <span class="font-medium">Logout</span>
            </button>
        </form>

        <!-- Menu Write Article -->
        <a href="{{ route('journalist.article.create') }}" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 active:scale-95 text-sm group {{ request()->routeIs('journalist.article.create') ? 'bg-[#f0f2f0] text-[#2d3e2d]' : 'text-gray-500 hover:bg-[#e8eee8] hover:text-[#2d4a22]' }}">
            <div class="flex items-center gap-3">
                <i data-lucide="pen-tool" class="w-[18px] h-[18px] group-hover:scale-110 transition-transform"></i>
                <span class="font-medium">Write Article</span>
            </div>
            <!-- Indikator Aktif (Garis Hijau di Kanan) -->
            @if(request()->routeIs('journalist.article.create'))
                <div class="w-1 h-5 bg-[#2d4a22] rounded-full"></div>
            @endif
        </a>

        <!-- Menu View Inspiration (Dashboard) -->
        <a href="{{ route('journalist.dashboard') }}" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 active:scale-95 text-sm group {{ request()->routeIs('journalist.dashboard') ? 'bg-[#f0f2f0] text-[#2d3e2d]' : 'text-gray-500 hover:bg-[#e8eee8] hover:text-[#2d4a22]' }}">
            <div class="flex items-center gap-3">
                <i data-lucide="layout-grid" class="w-[18px] h-[18px] group-hover:scale-110 transition-transform"></i>
                <span class="font-medium">View Inspiration</span>
            </div>
            <!-- Indikator Aktif (Garis Hijau di Kanan) -->
            @if(request()->routeIs('journalist.dashboard'))
                <div class="w-1 h-5 bg-[#2d4a22] rounded-full"></div>
            @endif
        </a>

    </nav>

    <!-- Area Profil Bawah -->
    <div class="p-4 border-t border-gray-100">
        <div class="flex items-center gap-3 bg-white p-3 rounded-2xl shadow-sm border border-gray-50 transition-colors hover:border-[#2d4a22]/30">
            <!-- Avatar Inisial Bawah -->
            <div class="w-10 h-10 rounded-full bg-[#2d3e2d] text-white flex items-center justify-center font-bold text-sm shrink-0 overflow-hidden">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            
            <!-- Info Bawah -->
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-[#2d3e2d] truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest truncate mt-0.5">Journalist</p>
            </div>

            <!-- Tombol View Profile (Sekalian di-upgrade!) -->
            <a href="{{ route('journalist.profile.show') }}" class="px-4 py-1.5 bg-[#f0f2f0] hover:bg-[#2d4a22] hover:text-white text-[#2d3e2d] text-[10px] font-bold uppercase tracking-wider rounded-full transition-all active:scale-95 shrink-0">
                VIEW
            </a>
        </div>
    </div>
</aside>