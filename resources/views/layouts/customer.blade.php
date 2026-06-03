<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $pageTitle ?? 'NaturaWed' }}</title>

    <link class="hide-scrollbar" rel="stylesheet" href="/assets/css/output.css" />
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine.js WAJIB ada sebelum dipakai --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-white font-sans text-gray-900 overflow-x-hidden" x-data="{ showAuthModal: false }">

    <header class="sticky top-0 z-50 w-full bg-white/80 backdrop-blur-md border-b border-zinc-100 h-20">
        <div class="max-w-7xl mx-auto px-6 h-full flex items-center justify-between">

            <div class="flex items-center space-x-12">
                <a href="{{ route('home') }}" class="text-2xl font-serif font-bold text-[#2d4a22] tracking-tight hover:opacity-70 transition-opacity">
                    NaturaWed
                </a>

                <nav class="hidden md:flex items-center space-x-8">
                    {{-- Home --}}
                    <a href="{{ route('home') }}"
                        class="text-[11px] tracking-[0.15em] uppercase transition-colors
                        {{ Route::is('home')
                            ? 'relative font-bold text-[#2d4a22] after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-full after:bg-[#2d4a22] pb-1'
                            : 'font-semibold text-zinc-400 hover:text-[#2d4a22]' }}">
                        Home
                    </a>

                    {{-- Vendors --}}
                    <a href="{{ route('customer.vendors') }}"
                        class="text-[11px] tracking-[0.15em] uppercase transition-colors
                        {{ Route::is('customer.vendors') || Route::is('customer.vendors.*')
                            ? 'relative font-bold text-[#2d4a22] after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-full after:bg-[#2d4a22] pb-1'
                            : 'font-semibold text-zinc-400 hover:text-[#2d4a22]' }}">
                        Vendors
                    </a>

                    {{-- Inspiration --}}
                    <a href="{{ route('customer.inspiration') }}"
                        class="text-[11px] tracking-[0.15em] uppercase transition-colors
                        {{ Route::is('inspiration') || Route::is('inspiration.*')
                            ? 'relative font-bold text-[#2d4a22] after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-full after:bg-[#2d4a22] pb-1'
                            : 'font-semibold text-zinc-400 hover:text-[#2d4a22]' }}">
                        Inspiration
                    </a>

                    {{-- About --}}
                    <a href="{{ route('customer.about') }}"
                        class="text-[11px] tracking-[0.15em] uppercase transition-colors
                        {{ Route::is('customer.about') || Route::is('customer.about.*')
                            ? 'relative font-bold text-[#2d4a22] after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-full after:bg-[#2d4a22] pb-1'
                            : 'font-semibold text-zinc-400 hover:text-[#2d4a22]' }}">
                        About
                    </a>
                </nav>
            </div>

            <div class="flex items-center space-x-6 text-zinc-400">

                {{-- Notification --}}
                @auth
                    <a href="#" class="hover:text-[#2d4a22] transition-colors">
                @else
                    <button type="button" @click="showAuthModal = true" class="hover:text-[#2d4a22] transition-colors bg-transparent border-none p-0 outline-none cursor-pointer">
                @endauth
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                        </svg>
                @auth
                    </a>
                @else
                    </button>
                @endauth

                {{-- Bookmark --}}
                @auth
                    <a href="#" class="hover:text-[#2d4a22] transition-colors">
                @else
                    <button type="button" @click="showAuthModal = true" class="hover:text-[#2d4a22] transition-colors bg-transparent border-none p-0 outline-none cursor-pointer">
                @endauth
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/>
                        </svg>
                @auth
                    </a>
                @else
                    </button>
                @endauth

                {{-- Chat --}}
                @auth
                    <a href="#" class="hover:text-[#2d4a22] transition-colors">
                @else
                    <button type="button" @click="showAuthModal = true" class="hover:text-[#2d4a22] transition-colors bg-transparent border-none p-0 outline-none cursor-pointer">
                @endauth
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                @auth
                    </a>
                @else
                    </button>
                @endauth

                {{-- Booking History --}}
                @auth
                    <a href="{{ route('customer.bookings.history') }}" class="hover:text-[#2d4a22] transition-colors">
                @else
                    <button type="button" @click="showAuthModal = true" class="hover:text-[#2d4a22] transition-colors bg-transparent border-none p-0 outline-none cursor-pointer">
                @endauth
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M12 6v6l4 2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9"/>
                        </svg>
                @auth
                    </a>
                @else
                    </button>
                @endauth

                <div class="h-8 w-px bg-zinc-100 mx-2"></div>

                {{-- Logout / Login --}}
                @auth
                    @if(Auth::user()->role === 'customer')
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 text-zinc-400 hover:text-red-600 transition-colors uppercase text-[10px] font-bold tracking-widest outline-none border-none bg-transparent cursor-pointer">
                                <span>Logout</span>
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                </svg>
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="flex items-center gap-2 text-zinc-400 hover:text-[#2d4a22] transition-colors">
                        <span class="text-[10px] font-bold tracking-widest uppercase">Login</span>
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                            <path d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </a>
                @endauth

            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="bg-[#2d4a22] py-16 text-center text-white">
        <div class="text-4xl font-bold tracking-tight">NaturaWed</div>
        <p class="mt-6 text-lg opacity-60">&copy; {{ date('Y') }} NaturaWed. All rights reserved.</p>
        <div class="mt-8 flex justify-center space-x-10 text-sm font-bold uppercase tracking-widest opacity-80">
            <a href="#" class="hover:opacity-100 transition-opacity">Privacy Policy</a>
            <a href="#" class="hover:opacity-100 transition-opacity">Terms of Service</a>
            <a href="#" class="hover:opacity-100 transition-opacity">Contact Us</a>
        </div>
    </footer>

    <script src="/assets/js/global.js"></script>

    {{-- Auth Modal --}}
    <div x-show="showAuthModal"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center"
         style="display: none;">

        <div @click="showAuthModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300"></div>

        <div class="relative w-full max-w-md overflow-hidden rounded-[40px] bg-white p-12 shadow-2xl transform transition-all duration-300 mx-4 z-10">

            <button @click="showAuthModal = false" class="absolute right-8 top-8 p-2 text-zinc-400 hover:text-[#2d4a22] transition-colors cursor-pointer outline-none border-none bg-transparent">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div class="text-center">
                <div class="mx-auto mb-8 flex h-20 w-20 items-center justify-center rounded-full bg-[#2d4a22]/10 text-[#2d4a22]">
                    <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <h2 class="mb-4 text-3xl font-serif font-bold text-[#2d4a22]">Join NaturaWed</h2>
                <p class="mb-10 text-zinc-500 leading-relaxed text-sm">
                    Sign in to access your bookings, chat with vendors, and save your favorite inspirations.
                </p>

                <div class="space-y-4">
                    <a href="{{ route('login') }}"
                       class="flex w-full items-center justify-center gap-3 rounded-2xl bg-[#2d4a22] py-5 text-lg font-bold text-white shadow-xl transition-all hover:bg-[#1e3317] active:scale-95 no-underline">
                        <span>Sign In</span>
                    </a>
                    <a href="{{ route('register') }}"
                       class="flex w-full items-center justify-center gap-3 rounded-2xl border-2 border-[#2d4a22] py-5 text-lg font-bold text-[#2d4a22] transition-all hover:bg-[#2d4a22]/5 active:scale-95 no-underline">
                        <span>Create Account</span>
                    </a>
                </div>

                <p class="mt-8 text-[10px] text-zinc-400 uppercase tracking-widest font-bold">
                    Eco-friendly weddings start here
                </p>
            </div>
        </div>
    </div>

</body>
</html>