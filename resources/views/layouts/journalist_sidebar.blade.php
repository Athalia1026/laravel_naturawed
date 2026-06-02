<aside class="w-64 bg-[#fafafa] border-r border-gray-100 flex flex-col sticky top-0 h-screen shrink-0">

    <!-- Area Logo / Judul -->
    <div class="p-8 pb-4">
        <h1 class="text-xl font-serif font-semibold text-[#2d3e2d]">
            {{ Auth::user()->name }}
        </h1>
        <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mt-1">Journalist Portal</p>
    </div>

    <!-- Area Menu Navigasi (flex-1 akan mendorong elemen di bawahnya ke paling dasar) -->
    <nav class="flex-1 px-4 space-y-1 mt-4 overflow-y-auto hide-scrollbar">
        <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 rounded-xl transition-colors text-sm font-medium">
            <i data-lucide="arrow-left" class="w-[18px] h-[18px]"></i>
            Back to Home
        </a>

        <a href="{{ route('journalist.article.create') }}" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 rounded-xl transition-colors text-sm font-medium">
            <i data-lucide="pen-tool" class="w-[18px] h-[18px]"></i>
            Write Article
            </a>

        <a href="{{ route('journalist.dashboard') }}" class="flex items-center gap-3 px-4 py-3 bg-[#f0f2f0] text-[#2d3e2d] font-semibold rounded-xl transition-colors text-sm relative">
            <i data-lucide="layout-grid" class="w-[18px] h-[18px]"></i>
            View Inspiration
            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#2d3e2d] rounded-l-full"></div>
        </a>
    </nav>

    <!-- Area Profil di Pojok Kiri Bawah -->
    <div class="p-5 border-t border-gray-200 mt-auto bg-white">
        <div class="flex items-center gap-3">
            <!-- Lingkaran Inisial / Foto Profil -->
            <div class="w-10 h-10 rounded-full overflow-hidden bg-[#2d3e2d] flex items-center justify-center shrink-0 shadow-inner">
                <span class="text-white text-xs font-bold">{{ substr(Auth::user()->name, 0, 2) }}</span>
            </div>
            <!-- Nama & Role -->
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-[#2d3e2d] truncate">{{ Auth::user()->name }}</p>
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Journalist</p>
            </div>
            <!-- Tombol Edit Oval -->
            <a href="{{ route('journalist.profile.edit') }}" class="px-4 py-1.5 bg-[#f0f2f0] hover:bg-[#e2e8e2] text-[#2d3e2d] text-[10px] font-bold uppercase tracking-wider rounded-full transition-colors shrink-0">
                Edit
            </a>
        </div>
    </div>
</aside>