<aside class="w-64 bg-[#fafafa] border-r border-gray-100 flex flex-col sticky top-0 h-screen shrink-0">
    
    <div class="p-8 pb-4">
        <h1 class="text-xl font-serif font-semibold text-[#2d3e2d]">
            {{ Auth::user()->name ?? 'NaturaWed' }}
        </h1>
        <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mt-1">Journalist Portal</p>
    </div>

    <nav class="flex-1 px-4 space-y-1 mt-4 overflow-y-auto hide-scrollbar">
        
        <a href="{{ route('home') ?? url('/') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors text-sm font-medium relative text-gray-500 hover:bg-gray-50">
            <i data-lucide="arrow-left" class="w-[18px] h-[18px]"></i>
            Back to Home
        </a>

        <a href="{{ route('journalist.article.create') ?? '#' }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors text-sm font-medium relative {{ request()->routeIs('journalist.article.create') ? 'bg-[#f0f2f0] text-[#2d3e2d] font-semibold' : 'text-gray-500 hover:bg-gray-50' }}">
            <i data-lucide="pen-tool" class="w-[18px] h-[18px]"></i>
            Write Article
            @if(request()->routeIs('journalist.article.create'))
                <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#2d3e2d] rounded-l-full"></div>
            @endif
        </a>

        <a href="{{ route('journalist.dashboard') ?? '#' }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors text-sm font-medium relative {{ request()->routeIs('journalist.dashboard') ? 'bg-[#f0f2f0] text-[#2d3e2d] font-semibold' : 'text-gray-500 hover:bg-gray-50' }}">
            <i data-lucide="layout-grid" class="w-[18px] h-[18px]"></i>
            View Inspiration
            @if(request()->routeIs('journalist.dashboard'))
                <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#2d3e2d] rounded-l-full"></div>
            @endif
        </a>
        
        <div class="pt-6 mt-6 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" onclick="return confirm('Keluar dari portal?')" class="flex items-center gap-3 px-4 py-3 w-full text-red-500 hover:bg-red-50 rounded-xl transition-colors text-sm font-medium text-left">
                    <i data-lucide="log-out" class="w-[18px] h-[18px]"></i>
                    Logout
                </button>
            </form>
        </div>
    </nav>
</aside>