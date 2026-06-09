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

        @include('layouts.includes.authmodal')
    
    @stack('scripts')
</body >
</html>