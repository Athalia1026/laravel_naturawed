@php
    // 1. Cek Autentikasi & Role ala Laravel (Menggantikan $_SESSION)
    $isAuthenticated = auth()->check();
    $isCustomer = $isAuthenticated && auth()->user()->role === 'customer';

    // 2. CSS Classes
    $activeClass = 'relative font-bold text-[#2d4a22] after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-full after:bg-[#2d4a22] pb-1';
    $inactiveClass = 'font-semibold text-zinc-400 hover:text-[#2d4a22] transition-colors';
@endphp

@include('layouts.includes.authmodal')

<header class="sticky top-0 z-50 w-full bg-white/80 backdrop-blur-md border-b border-zinc-100 h-20">
    <div class="mx-auto flex h-full max-w-7xl items-center justify-between px-6">
        
        <div class="flex items-center space-x-12">
            <div class="flex items-center space-x-4">
                {{-- 3. Logika Tombol Back (Menggantikan $_GET['action']) --}}
                @if(request()->routeIs('package.detail'))
                    <a href="javascript:history.back()" class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-50 text-gray-600 transition-colors hover:bg-gray-100">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                    </a>
                @endif
                
                {{-- 4. Pemanggilan Route (Menggantikan index.php?action=home) --}}
                <a href="{{ route('home') }}" class="font-serif text-2xl font-bold tracking-tight text-[#2d4a22] transition-opacity hover:opacity-70">
                    NaturaWed
                </a>
            </div>
            
            <nav class="hidden items-center space-x-8 md:flex">
                <a href="{{ route('home') }}" class="text-[11px] uppercase tracking-[0.15em] {{ request()->routeIs('home') ? $activeClass : $inactiveClass }}">Home</a>
                <a href="{{ route('vendors.index') }}" class="text-[11px] uppercase tracking-[0.15em] {{ request()->routeIs('vendors.*') || request()->routeIs('package.detail') ? $activeClass : $inactiveClass }}">Vendors</a>
                <a href="{{ route('inspiration') }}" class="text-[11px] uppercase tracking-[0.15em] {{ request()->routeIs('inspiration') ? $activeClass : $inactiveClass }}">Inspiration</a>
                <a href="#" class="text-[11px] uppercase tracking-[0.15em] {{ $inactiveClass }}">About</a>
            </nav>
        </div>

        <div class="flex items-center space-x-6 text-zinc-400">
            
            {{-- 5. Logika Proteksi Modal ala Blade --}}
            <a href="{{ $isAuthenticated ? route('notifications') : 'javascript:openAuthModal()' }}" class="transition-colors hover:text-[#2d4a22]">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                </svg>
            </a>

            <a href="{{ $isAuthenticated ? route('bookmarks') : 'javascript:openAuthModal()' }}" class="transition-colors hover:text-[#2d4a22]">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/>
                </svg>
            </a>

            <a href="{{ $isAuthenticated ? route('chat') : 'javascript:openAuthModal()' }}" class="transition-colors hover:text-[#2d4a22] {{ request()->routeIs('chat') ? 'text-[#2d4a22]' : '' }}">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
            </a>

            <a href="{{ $isAuthenticated ? route('history') : 'javascript:openAuthModal()' }}" class="transition-colors hover:text-[#2d4a22] {{ request()->routeIs('history') ? 'text-[#2d4a22]' : '' }}">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M12 6v6l4 2" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="12" cy="12" r="9"/>
                </svg>
            </a>

            <div class="mx-2 h-8 w-px bg-zinc-100"></div>

            @if($isAuthenticated)
                {{-- Form Logout Laravel (Wajib pakai POST demi keamanan) --}}
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 text-zinc-400 transition-colors hover:text-red-600">
                        <span class="text-[10px] font-bold uppercase tracking-widest">Logout</span>
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                    </button>
                </form>
            @else
                <a href="javascript:openAuthModal()" class="flex items-center gap-2 text-zinc-400 transition-colors hover:text-[#2d4a22]">
                    <span class="text-[10px] font-bold uppercase tracking-widest">Login</span>
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                        <path d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>
            @endif
        </div>
    </div>
</header>