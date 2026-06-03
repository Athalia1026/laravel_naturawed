<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journalist Profile - NaturaWed</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="flex min-h-screen bg-white font-sans text-[#1a1a1a]">

    <!-- Sidebar -->
    @include('layouts.journalist_sidebar')

    <!-- Main Container: Background putih polos, TANPA padding agar header mepet pol -->
    <main class="flex-1 overflow-y-auto bg-white">
            
        <!-- Area Header Cover: Lebar 100% menabrak ujung kiri dan kanan -->
        <div class="h-64 w-full bg-[#f0f2f0] relative">
            @if(!empty($profile->header_image))
                <!-- Jika sudah ada foto, tampilkan -->
                <img src="{{ asset($profile->header_image) }}" alt="Header" class="w-full h-full object-cover">
            @else
                <!-- Jika belum, tampilkan gradien abu-abu -->
                <div class="w-full h-full bg-gradient-to-r from-[#e8ebe8] to-[#f0f2f0]"></div>
            @endif
        </div>
        
        <!-- Area Identitas: Diberi padding p-12 agar teks sejajar dengan halaman lain, tapi kotak/border dihilangkan -->
        <div class="px-12 pb-12 relative max-w-6xl mx-auto w-full">
            
            <!-- Foto Profil Melayang -->
            <div class="absolute -top-20 left-12 w-40 h-40 rounded-full border-[6px] border-white overflow-hidden bg-white shadow-sm flex items-center justify-center z-10">
                <img src="{{ !empty($profile->profile_image) ? asset($profile->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode($profile->full_name ?? Auth::user()->name).'&background=2d3e2d&color=fff' }}" alt="Profile" class="w-full h-full object-cover">
            </div>

            <!-- Baris Nama & Tombol Edit -->
            <div class="flex justify-between items-start pt-24">
                <div>
                    <h3 class="text-4xl font-serif text-[#2d3e2d]">{{ $profile->full_name ?? Auth::user()->name }}</h3>
                    <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase mt-2">NaturaWed Editorial Journalist</p>
                </div>
                
                <!-- Tombol Edit Profile -->
                <a href="{{ route('journalist.profile.edit') }}" class="flex items-center gap-2 px-6 py-2.5 border border-gray-200 rounded-full text-sm font-bold text-gray-700 hover:border-[#2d3e2d] hover:text-[#2d3e2d] hover:bg-gray-50 transition-all shrink-0 shadow-sm">
                    <i data-lucide="edit-2" class="w-4 h-4"></i> Edit Profile
                </a>
            </div>

            <!-- Area Biografi -->
            <div class="mt-8">
                <p class="text-gray-600 leading-relaxed text-sm text-justify whitespace-pre-line">
                    {{ $profile->bio ?? 'No biography added yet. Click edit profile to tell readers about your journalistic experience.' }}
                </p>
            </div>

            <!-- Area Statistik -->
            <div class="flex gap-6 mt-12 pt-10 border-t border-gray-100">
                <div class="p-6 rounded-2xl flex-1 flex items-center gap-6 bg-[#f8f9fa]">
                    <div class="w-14 h-14 rounded-full bg-[#e1f5e1] flex items-center justify-center text-[#2d4a22]">
                        <i data-lucide="file-text" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Published Articles</p>
                        <p class="text-3xl font-serif text-[#2d3e2d] mt-1">{{ $totalArticles }}</p>
                    </div>
                </div>

                <div class="p-6 rounded-2xl flex-1 flex items-center gap-6 bg-[#f8f9fa]">
                    <div class="w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                        <i data-lucide="eye" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Total Readers</p>
                        <p class="text-3xl font-serif text-[#2d3e2d] mt-1">{{ $totalViews }}</p>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script> lucide.createIcons(); </script>
</body>
</html>