<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Portfolio - NaturaWed</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght=0,400;0,600;0,700;1,400&family=Inter:wght=400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        .font-serif {
            font-family: 'Playfair Display', Georgia, serif;
        }

        .font-sans {
            font-family: 'Inter', sans-serif;
        }

        .bg-brand-cream {
            background-color: #fdfcf7;
        }
    </style>
</head>

<body>
    <div class="min-h-screen flex bg-[#f8f9fa] font-sans text-[#1a1a1a]">

        {{-- SIDEBAR UTAMA VENDOR --}}
        @include('layouts.vendor_sidebar')

        {{-- AREA KONTEN UTAMA --}}
        <main class="flex-1 overflow-y-auto min-h-screen bg-brand-cream pb-24">

            {{-- 1. HEADER HERO COVER --}}
            <div class="relative h-[480px] w-full overflow-hidden block bg-[#2d3e2d]">
                @if($vendorProfile && $vendorProfile->cover_image)
                    {{-- Menampilkan gambar kover kustom dari database --}}
                    <img id="cover-image" src="{{ asset($vendorProfile->cover_image) }}" alt="Vendor Cover Background"
                        class="w-full h-full object-cover" referrerpolicy="no-referrer" />
                @else
                    {{-- Fallback image jika vendor belum mengunggah kover kustom --}}
                    <img id="cover-image"
                        src="https://images.unsplash.com/photo-1519167758481-83f550bb49b3?q=80&w=2074&auto=format&fit=crop"
                        alt="Vendor Default Cover" class="w-full h-full object-cover brightness-95"
                        referrerpolicy="no-referrer" />
                @endif

                {{-- Efek gradien bayangan premium --}}
                <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-black/20"></div>
            </div>

            <div class="max-w-7xl mx-auto px-6 relative w-full block">

                {{-- 2. VENDOR INFO CARD --}}
                <div
                    class="bg-white rounded-[3.5rem] shadow-2xl border border-gray-100 -mt-24 relative z-10 mb-12 block w-full">
                    <div class="px-8 md:px-12 pt-8 md:pt-10 pb-8 md:pb-12">
                        <div class="flex flex-col lg:flex-row gap-12 items-start">

                            {{-- LOGO STUDIO VENDOR --}}
                            <div
                                class="relative w-44 h-44 rounded-[3rem] overflow-hidden border-4 border-white shadow-2xl -mt-28 bg-brand-cream flex-shrink-0 mx-auto lg:mx-0 z-20 group cursor-pointer">
                                <img src="{{ $vendorProfile && $vendorProfile->profile_image ? asset($vendorProfile->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=2d4a22&color=fff' }}"
                                    alt="Vendor Logo" class="w-full h-full object-cover" referrerpolicy="no-referrer" />
                                <a href="{{ route('profile.edit') }}"
                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center decoration-none">
                                    <i data-lucide="edit-2" class="text-white w-6 h-6"></i>
                                </a>
                            </div>

                            {{-- DETAIL DATA IDENTITAS UTAMA BISNIS --}}
                            <div class="flex-1 space-y-5 text-center lg:text-left w-full">
                                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                                    <div>
                                        <h1
                                            class="text-4xl md:text-5xl font-serif font-bold text-[#2d4a22] tracking-tight">
                                            {{ Auth::user()->name }}
                                        </h1>
                                        <div
                                            class="flex flex-wrap items-center justify-center lg:justify-start gap-4 mt-3">
                                            {{-- Nilai Rating & Ulasan Akumulatif Riil --}}
                                            <div
                                                class="flex items-center gap-1.5 text-[#c5a059] bg-yellow-50 px-3 py-1 rounded-full text-sm font-semibold">
                                                <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                                <span>{{ $ratingStats && $ratingStats->average_rating ? number_format($ratingStats->average_rating, 1) : '0.0' }}/5</span>
                                                <span
                                                    class="text-gray-400 font-normal">({{ $ratingStats && $ratingStats->total_reviews ? $ratingStats->total_reviews : 0 }}
                                                    Reviews)</span>
                                            </div>
                                            {{-- Alamat Fisik dengan Proteksi Null --}}
                                            <div
                                                class="flex items-center gap-1.5 text-gray-500 bg-gray-50 px-3 py-1 rounded-full text-xs">
                                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                                                <span>{{ $vendorProfile->address ?? 'Alamat studio belum diatur' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- AKSEN HUBUNGI / TOMBOL EDIT UTAMA --}}
                                    <div class="flex justify-center shrink-0">
                                        <a href="{{ route('profile.edit') }}"
                                            class="flex items-center gap-2.5 px-8 py-5 bg-[#2d4a22] text-white rounded-2xl font-bold shadow-xl hover:bg-[#1e3317] transition-all transform hover:-translate-y-0.5 active:scale-95 cursor-pointer border-none outline-none text-base decoration-none">
                                            <i data-lucide="edit-3" class="w-5 h-5"></i>
                                            Edit Studio Profile
                                        </a>
                                    </div>
                                </div>

                                {{-- Deskripsi Bio Narasi Usaha --}}
                                <div class="pt-2">
                                    <h4 class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-2">About Our Studio</h4>
                                    <p class="text-gray-600 leading-relaxed text-lg max-w-5xl">
                                        {{ $vendorProfile->bio ?? 'Selamat datang! Kami adalah penyedia layanan pernikahan ramah lingkungan yang berdedikasi tinggi. Silakan lengkapi profil studio Anda di halaman pengaturan profil untuk memperbarui informasi bio ini.' }}
                                    </p>
                                </div>

                                {{-- SOSIAL MEDIA TAUTAN DINAMIS --}}
                                <div
                                    class="flex flex-wrap justify-center lg:justify-start gap-8 pt-4 border-t border-gray-100 text-xs font-bold tracking-widest text-[#2d4a22]/70">
                                    @if($vendorProfile && $vendorProfile->website)
                                        <a href="{{ $vendorProfile->website }}" target="_blank" rel="noopener noreferrer"
                                            class="flex items-center gap-2.5 hover:text-[#2d4a22] transition-colors uppercase no-underline">
                                            <i data-lucide="globe" class="w-4 h-4"></i>{{ $vendorProfile->website }}
                                        </a>
                                    @endif
                                    @if($vendorProfile && $vendorProfile->instagram)
                                        <a href="https://instagram.com/{{ ltrim($vendorProfile->instagram, '@') }}"
                                            target="_blank" rel="noopener noreferrer"
                                            class="flex items-center gap-2.5 hover:text-[#2d4a22] transition-colors uppercase no-underline">
                                            <svg class="w-4 h-4 fill-current text-[#2d4a22]/70 group-hover:text-[#2d4a22]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                </i> {{ ltrim($vendorProfile->instagram, '@') }}
                                        </a>
                                    @endif
                                    <a href="mailto:{{ Auth::user()->email }}"
                                        class="flex items-center gap-2.5 hover:text-[#2d4a22] transition-colors uppercase no-underline">
                                        <i data-lucide="mail" class="w-4 h-4"></i> {{ Auth::user()->email }}
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- 3. TEAM CREATIVE SECTION --}}
                <div class="py-16 border-b border-zinc-200/60 w-full block">
                    <div class="flex items-center justify-between mb-10">
                        <div>
                            <span class="text-xs font-bold tracking-[0.2em] text-[#c5a059] uppercase block mb-1">THE ARTISANS OF THE ATELIER</span>
                            <h2 class="text-4xl font-serif font-bold text-[#2d4a22]">Our Creative Team</h2>
                        </div>
                        <a href="{{ route('profile.edit') }}"
                            class="px-5 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-xs font-bold tracking-widest uppercase hover:bg-gray-50 transition-colors flex items-center gap-2 decoration-none">
                            <i data-lucide="settings" class="w-4 h-4"></i> Edit Team
                        </a>
                    </div>

                    {{-- Bingkai Spanduk Foto Kolase Tim Besar --}}
                    <div
                        class="relative rounded-[3.5rem] overflow-hidden shadow-2xl h-[420px] bg-gray-100 group w-full block border border-gray-100">
                        <img src="{{ $vendorProfile && $vendorProfile->team_image ? asset($vendorProfile->team_image) : 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=2070&auto=format&fit=crop' }}"
                            alt="Vendor Team Group Portrait"
                            class="w-full h-full object-cover brightness-[0.85] transition-transform duration-1000 group-hover:scale-[1.01]"
                            referrerpolicy="no-referrer" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
                        <div
                            class="absolute bottom-10 left-10 right-10 flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                            <div class="max-w-2xl text-white">
                                <div
                                    class="flex items-center gap-2 mb-3 bg-[#2d4a22] text-xs font-bold tracking-widest uppercase py-1.5 px-4 rounded-full w-fit">
                                    <i data-lucide="users" class="w-3.5 h-3.5 text-white"></i> {{ Auth::user()->name }} Dream Team
                                </div>
                                <h3 class="text-2xl md:text-3xl font-serif font-medium leading-tight text-white">
                                    {{ $vendorProfile->team_description ?? 'Tim solid yang siap merancang mahakarya pernikahan ramah lingkungan impian Anda secara profesional.' }}
                                </h3>
                            </div>
                            <span
                                class="text-white/60 text-xs font-mono tracking-widest uppercase md:text-right flex-shrink-0">
                                Studio Corporate Portrait
                            </span>
                        </div>
                    </div>
                </div>

                {{-- 4. MENU PACKAGES SECTION --}}
                <div class="py-16 w-full block">
                    <div class="flex items-center justify-between mb-12">
                        <div>
                            <span class="text-xs font-bold tracking-[0.2em] text-[#c5a059] uppercase block mb-1">CURATED SEALS OF HAPPINESS</span>
                            <h2 class="text-4xl font-serif font-bold text-[#2d4a22]">Our Menu Packages</h2>
                        </div>
                        <a href="{{ route('vendor.packages.create') }}"
                            class="px-6 py-3.5 bg-[#2d4a22] text-white rounded-xl text-xs font-bold tracking-widest uppercase hover:bg-[#1e3317] transition-all shadow-md flex items-center gap-2 decoration-none">
                            <i data-lucide="plus" class="w-4 h-4"></i> Create New Package
                        </a>
                    </div>

                    {{-- Loop Kartu Grid Menu Paket Pernikahan --}}
                    <div class="w-full block">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                            @forelse ($myPackages as $pkg)
                                <div
                                    class="group relative overflow-hidden rounded-[2.5rem] bg-white shadow-xl border border-gray-50 flex flex-col h-full hover:shadow-2xl transition-all p-2">

                                    {{-- Gambar Thumbnail Paket & Overlay Edit Konten --}}
                                    <div class="h-60 relative rounded-[2rem] overflow-hidden bg-gray-50 w-full block">
                                        <img src="{{ $pkg->main_image ? asset($pkg->main_image) : 'https://via.placeholder.com/600' }}"
                                            alt="Package Portrait"
                                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                            referrerpolicy="no-referrer" />
                                        <div
                                            class="absolute top-4 right-4 bg-[#2d4a22] text-white px-3.5 py-1.5 rounded-full text-[10px] font-bold tracking-wider uppercase shadow-sm">
                                            {{ $pkg->category_name ?? 'Uncategorized' }}
                                        </div>

                                        {{-- Hover Action Overlay --}}
                                        <div
                                            class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-6 z-10">
                                            <a href="{{ route('vendor.packages.edit', $pkg->id) }}"
                                                class="px-6 py-3 bg-white text-[#2d3e2d] rounded-xl text-xs font-bold tracking-widest uppercase hover:bg-emerald-700 hover:text-white transition-colors text-center shadow-md decoration-none flex items-center gap-2">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i> Edit Details
                                            </a>
                                        </div>
                                    </div>

                                    {{-- Keterangan Informasi & Tombol Hapus Data Paket --}}
                                    <div class="p-8 flex-1 flex flex-col justify-between bg-white rounded-b-[2rem]">
                                        <div>
                                            <h3
                                                class="text-2xl font-bold text-gray-900 mb-2 leading-tight group-hover:text-emerald-700 transition-colors truncate">
                                                {{ $pkg->package_name }}
                                            </h3>
                                            <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed mb-4">
                                                {{ Str::limit(strip_tags($pkg->description ?? 'Manajemen kelola dekorasi pernikahan eco-friendly berkelanjutan zero-waste setup.'), 90) }}
                                            </p>
                                        </div>
                                        <div class="mt-6 pt-5 border-t border-gray-50 flex items-center justify-between">
                                            <div>
                                                <p
                                                    class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-1">
                                                    Price Investment</p>
                                                <div class="text-xl font-bold text-[#2d4a22]">Rp {{ number_format($pkg->price, 0, ',', '.') }}</div>
                                            </div>

                                            {{-- Form Hapus --}}
                                            <form action="{{ route('vendor.packages.delete', $pkg->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket pernikahan ini?');"
                                                class="m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors cursor-pointer border-none outline-none">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            @empty
                                {{-- 🌟 PREMIUM EMPTY STATE INTERFACE (Jika belum ada paket) --}}
                                <div
                                    class="col-span-full flex flex-col items-center justify-center py-20 px-6 bg-white rounded-[2.5rem] border border-dashed border-zinc-200 w-full block">
                                    <div class="p-5 bg-emerald-50 rounded-full text-[#2d4a22] mb-5">
                                        <i data-lucide="package-open" class="w-12 h-12"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-1">Katalog Paket Masih Kosong</h3>
                                    <p class="text-sm text-gray-400 italic text-center max-w-sm mb-6">
                                        Studio Anda belum merilis paket layanan pernikahan apapun saat ini.
                                    </p>
                                    <a href="{{ route('vendor.packages.create') }}"
                                        class="inline-flex items-center gap-2 px-8 py-3.5 bg-[#2d4a22] text-white rounded-full text-xs font-bold tracking-widest uppercase hover:bg-[#1e3317] transition-all decoration-none shadow-md">
                                        <i data-lucide="plus" class="w-4 h-4"></i> Buat Paket Pertama Anda
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Penutup Aliran Grid --}}
                <div class="clear-both w-full h-1 block"></div>

            </div>
        </main>
    </div>

    {{-- MANIPULASI LOGIKA JAVASCRIPT KONTROL COVER THEME --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            lucide.createIcons();
        });
    </script>

</body>

</html>