<aside class="w-64 bg-white border-r border-gray-100 flex flex-col sticky top-0 h-screen shrink-0">

    <div class="p-8 pb-4">
        <h1 class="text-xl font-serif font-semibold text-[#2d3e2d]">NaturaWed</h1>
        <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mt-1">Vendor Portal</p>
    </div>

    <nav class="flex-1 px-4 space-y-1 mt-4 overflow-y-auto hide-scrollbar">

        <a href="{{ route('vendor.dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors text-sm font-medium relative 
                  {{ Route::is('vendor.dashboard') ? 'bg-[#f0f2f0] text-[#2d3e2d] font-semibold' : 'text-gray-500 hover:bg-gray-50' }}">
            <i data-lucide="layout-dashboard" class="w-[18px] h-[18px]"></i>
            Dashboard
            @if(Route::is('vendor.dashboard'))
                <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#2d3e2d] rounded-l-full"></div>
            @endif
        </a>

        <a href="{{ route('vendor.portfolio') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors text-sm font-medium relative 
                  {{ Route::is('vendor.portfolio') ? 'bg-[#f0f2f0] text-[#2d3e2d] font-semibold' : 'text-gray-500 hover:bg-gray-50' }}">
            <i data-lucide="briefcase" class="w-[18px] h-[18px]"></i>
            Studio Profile
            @if(Route::is('vendor.portfolio'))
                <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#2d3e2d] rounded-l-full"></div>
            @endif
        </a>

        <a href="{{ route('vendor.packages.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors text-sm font-medium relative 
          {{ Route::is('vendor.packages.*') ? 'bg-[#f0f2f0] text-[#2d3e2d] font-semibold' : 'text-gray-500 hover:bg-gray-50' }}">
            <i data-lucide="package" class="w-[18px] h-[18px]"></i>
            Packages
            @if(Route::is('vendor.packages.*'))
                <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#2d3e2d] rounded-l-full"></div>
            @endif
        </a>

        <a href="{{ route('vendor.analytics') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors text-sm font-medium relative 
            {{ Route::is('vendor.analytics') ? 'bg-[#f0f2f0] text-[#2d3e2d] font-semibold' : 'text-gray-500 hover:bg-gray-50' }}">
                <i data-lucide="bar-chart-3" class="w-[18px] h-[18px]"></i>
                Analytics & Reports
                
                {{-- Indikator garis vertikal di sebelah kanan jika menu sedang aktif --}}
                @if(Route::is('vendor.analytics'))
                    <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#2d3e2d] rounded-l-full"></div>
                @endif
        </a>

        {{-- Menu Messages dengan Notifikasi Unread --}}
        @php
            // Hitung total pesan yang belum dibaca khusus untuk user ini
            $unreadChatCount = DB::table('messages')
                ->join('conversations', 'messages.conversation_id', '=', 'conversations.id')
                ->where(function($q) {
                    $q->where('conversations.user_one', Auth::id())
                      ->orWhere('conversations.user_two', Auth::id());
                })
                ->where('messages.sender_id', '!=', Auth::id())
                ->whereNull('messages.read_at')
                ->count();
        @endphp

        <a href="{{ route('chat.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors text-sm font-medium relative 
            {{ Route::is('chat.*') ? 'bg-[#f0f2f0] text-[#2d3e2d] font-semibold' : 'text-gray-500 hover:bg-gray-50' }}">
            
            <div class="relative flex items-center justify-center">
                <i data-lucide="message-square" class="w-[18px] h-[18px]"></i>
                
                {{-- Indikator Red Dot Animasi Ping --}}
                @if($unreadChatCount > 0)
                    <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 border border-white"></span>
                    </span>
                @endif
            </div>

            <span class="flex-1">Messages</span>

            {{-- Angka jumlah pesan (Opsional, tampil jika ada) --}}
            @if($unreadChatCount > 0)
                <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0">
                    {{ $unreadChatCount }}
                </span>
            @endif

            @if(Route::is('chat.*'))
                <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#2d3e2d] rounded-l-full"></div>
            @endif
        </a>



        <div class="pt-6 mt-6 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}"
                onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl transition-colors text-sm font-medium text-left">
                    <i data-lucide="log-out" class="w-[18px] h-[18px]"></i>
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="p-4 mt-auto">
        <div class="bg-[#f0f2f0] p-3 rounded-2xl flex items-center gap-3">
            <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-300 shadow-inner">
                <img 
                    src="{{ Auth::user()->vendorProfile && Auth::user()->vendorProfile->profile_image ? asset(Auth::user()->vendorProfile->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=2d4a22&color=fff' }}" 
                    alt="Profile" 
                    class="w-full h-full object-cover"
                />
            </div>
            <div class="min-w-0">
                <h4 class="text-xs font-bold truncate text-[#2d3e2d]">
                    {{ Auth::user()->name ?? 'Vendor Studio' }}
                </h4>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-0.5">Active</p>
            </div>
        </div>
    </div>
</aside>