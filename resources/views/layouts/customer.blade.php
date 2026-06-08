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

   
    @include('layouts.includes.header')
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

    @stack('scripts')
</body >
</html>