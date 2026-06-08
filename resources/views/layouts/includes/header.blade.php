@php
    $isAuthenticated = auth()->check();
    $isCustomer = $isAuthenticated && auth()->user()->role === 'customer';

    $activeClass = 'relative font-bold text-[#2d4a22] after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-full after:bg-[#2d4a22] pb-1';
    $inactiveClass = 'font-semibold text-zinc-400 hover:text-[#2d4a22] transition-colors';
@endphp

{{-- Hanya include modal login jika pengguna saat ini adalah tamu/belum login --}}
@guest
    @include('layouts.includes.authmodal')
@endguest

<header class="sticky top-0 z-50 w-full bg-white/80 backdrop-blur-md border-b border-zinc-100 h-20">
    <div class="mx-auto flex h-full max-w-7xl items-center justify-between px-6">
        
        <div class="flex items-center space-x-12">
            <div class="flex items-center space-x-4">
                @if(request()->routeIs('package.detail'))
                    <a href="javascript:history.back()" class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-50 text-gray-600 transition-colors hover:bg-gray-100">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                    </a>
                @endif
                
                <a href="{{ route('home') }}" class="font-serif text-2xl font-bold tracking-tight text-[#2d4a22] transition-opacity hover:opacity-70">
                    NaturaWed
                </a>
            </div>
            
            <nav class="hidden items-center space-x-8 md:flex">
                <a href="{{ route('home') }}" class="text-[11px] uppercase tracking-[0.15em] {{ request()->routeIs('home') ? $activeClass : $inactiveClass }}">Home</a>
                <a href="{{ route('customer.vendors') }}" class="text-[11px] uppercase tracking-[0.15em] {{ request()->routeIs('customer.vendors*') || request()->routeIs('package.detail') ? $activeClass : $inactiveClass }}">Vendors</a>
                <a href="{{ route('inspiration') }}" class="text-[11px] uppercase tracking-[0.15em] {{ request()->routeIs('inspiration') ? $activeClass : $inactiveClass }}">Inspiration</a>
                <a href="{{ route('customer.about') }}" class="text-[11px] uppercase tracking-[0.15em] {{ request()->routeIs('customer.about') ? $activeClass : $inactiveClass }}">About</a>
            </nav>
        </div>

        <div class="flex items-center space-x-6 text-zinc-400">
            
           {{-- Dropdown Notifikasi --}}
            @auth
                @php
                    $unreadNotifications = collect();
                    if(Auth::user()->role === 'customer') {
                        $customerProfile = \Illuminate\Support\Facades\DB::table('customer_profiles')
                            ->where('user_id', Auth::id())
                            ->first();
                            
                        if($customerProfile) {
                            $unreadNotifications = \Illuminate\Support\Facades\DB::table('bookings as b')
                                ->join('packages as p', 'b.package_id', '=', 'p.id')
                                ->where('b.customer_id', $customerProfile->id)
                                ->where(function($query) {
                                    // 1. JIKA approved: Tampilkan jika belum dibayar (unpaid)
                                    $query->where(function($q) {
                                        $q->where('b.booking_status', 'approved')
                                          ->where('b.payment_status', 'unpaid');
                                    })
                                    // 2. JIKA rejected: HANYA tampilkan jika ditolak dalam 1 hari terakhir
                                    ->orWhere(function($q) {
                                        $q->where('b.booking_status', 'rejected')
                                          ->where('b.updated_at', '>=', now()->subDays(1)); 
                                    });
                                })
                                ->select('b.id', 'p.package_name', 'b.booking_status', 'b.updated_at') 
                                ->orderBy('b.updated_at', 'desc')
                                ->take(5)
                                ->get();
                        }
                    }
                @endphp
                <div class="relative flex items-center" x-data="{ showNotif: false }">
                    <button @click="showNotif = !showNotif" @click.away="showNotif = false" class="hover:text-[#2d4a22] transition-colors relative bg-transparent border-none p-0 outline-none cursor-pointer">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                        </svg>
                        @if(count($unreadNotifications) > 0)
                            <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border-2 border-white"></span>
                            </span>
                        @endif
                    </button>

                    <div x-show="showNotif" x-cloak 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 translate-y-2" 
                         x-transition:enter-end="opacity-100 translate-y-0" 
                         class="absolute right-0 top-full mt-4 w-80 bg-white rounded-2xl shadow-2xl border border-zinc-100 overflow-hidden z-50">
                        <div class="p-4 border-b border-zinc-50 bg-zinc-50/80 backdrop-blur-sm">
                            <h3 class="text-[10px] font-bold tracking-widest uppercase text-gray-400">Recent Updates</h3>
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            @forelse($unreadNotifications as $notif)
                                <a href="{{ route('customer.bookings.history') }}" class="block p-5 border-b border-zinc-50 hover:bg-zinc-50 transition-colors decoration-none">
                                    <div class="flex items-start gap-3">
                                        @if($notif->booking_status === 'approved')
                                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center shrink-0 mt-0.5">
                                                <i data-lucide="check-circle" class="w-4 h-4 text-[#2d4a22]"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-[#2d4a22] font-bold mb-1">Booking Approved! 🎉</p>
                                                <p class="text-sm text-gray-600 leading-snug">The vendor accepted your booking for <span class="font-semibold text-gray-900">{{ $notif->package_name }}</span>.</p>
                                            </div>
                                        @elseif($notif->booking_status === 'rejected')
                                            <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center shrink-0 mt-0.5 border border-red-100">
                                                <i data-lucide="x-circle" class="w-4 h-4 text-red-500"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-red-500 font-bold mb-1">Booking Declined</p>
                                                <p class="text-sm text-gray-600 leading-snug">Vendor cannot accept your booking for <span class="font-semibold text-gray-900">{{ $notif->package_name }}</span>.</p>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            @empty
                                <div class="p-10 text-center flex flex-col items-center justify-center">
                                    <p class="text-sm text-gray-400 italic">You're all caught up!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @else
                <button type="button" @click="showAuthModal = true" class="hover:text-[#2d4a22] transition-colors bg-transparent border-none p-0 outline-none cursor-pointer flex items-center">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                    </svg>
                </button>
            @endauth

            {{-- TOMBOL BOOKMARK DI-DISABLE SEMENTARA AGAR TIDAK MEMBUAT ROUTE ERROR --}}
            {{-- 
            <a href="#" class="transition-colors hover:text-[#2d4a22]">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/>
                </svg>
            </a> 
            --}}

            {{-- Link Chat --}}
            <a href="{{ $isAuthenticated ? route('chat.index') : 'javascript:showAuthModal = true' }}" class="transition-colors hover:text-[#2d4a22] {{ request()->routeIs('chat.*') ? 'text-[#2d4a22]' : '' }}">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
            </a>

            {{-- Link History Booking --}}
            <a href="{{ $isAuthenticated ? route('customer.bookings.history') : 'javascript:showAuthModal = true' }}" class="transition-colors hover:text-[#2d4a22] {{ request()->routeIs('customer.bookings.history') ? 'text-[#2d4a22]' : '' }}">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M12 6v6l4 2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9"/>
                </svg>
            </a>

            <div class="mx-2 h-8 w-px bg-zinc-100"></div>

            @if($isAuthenticated)
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 text-zinc-400 transition-colors hover:text-red-600 outline-none border-none bg-transparent cursor-pointer">
                        <span class="text-[10px] font-bold uppercase tracking-widest">Logout</span>
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                    </button>
                </form>
            @else
                <button type="button" @click="showAuthModal = true" class="flex items-center gap-2 text-zinc-400 hover:text-[#2d4a22] transition-colors outline-none border-none bg-transparent cursor-pointer">
                    <span class="text-[10px] font-bold uppercase tracking-widest">Login</span>
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                        <path d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            @endif
        </div>
    </div>
</header>