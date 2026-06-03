<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Portfolio - NaturaWed</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap"
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

        {{-- AREA KONTEN UTAMA (Layout disetarakan penuh dengan vendor_detail) --}}
        <main class="flex-1 overflow-y-auto min-h-screen bg-brand-cream pb-24">

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

                {{-- VENDOR INFO CARD (Desain Identik Premium Berwarna Putih Melayang) --}}
                <div
                    class="bg-white rounded-[3.5rem] shadow-2xl border border-gray-100 -mt-24 relative z-10 mb-12 block w-full">
                    <div class="px-8 md:px-12 pt-8 md:pt-10 pb-8 md:pb-12">
                        <div class="flex flex-col lg:flex-row gap-12 items-start">

                            {{-- LOGO STUDIO VENDOR (Mendukung Live Preview Edit) --}}
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
                                                <span>{{ $ratingStats && $ratingStats->average_rating ? number_format($ratingStats->average_rating, 0) : '0' }}/5</span>
                                                <span
                                                    class="text-gray-400 font-normal">({{ $ratingStats && $ratingStats->total_reviews ? $ratingStats->total_reviews : 0 }}
                                                    Reviews)</span>
                                            </div>
                                            {{-- Alamat Fisik --}}
                                            <div
                                                class="flex items-center gap-1.5 text-gray-500 bg-gray-50 px-3 py-1 rounded-full text-xs">
                                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                                                <span>{{ $vendorProfile->address ?? 'Address not set' }}</span>
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
                                    <h4 class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-2">About
                                        Our Studio</h4>
                                    <p class="text-gray-600 leading-relaxed text-lg max-w-5xl">
                                        {{ $vendorProfile->bio ?? 'We are a passionate team of wedding decorators and organizers dedicated to turning your dream day into reality. Update your bio in the Edit Profile section.' }}
                                    </p>
                                </div>

                                {{-- SOSIAL MEDIA TAUTAN DINAMIS --}}
                                <div
                                    class="flex flex-wrap justify-center lg:justify-start gap-8 pt-4 border-t border-gray-100 text-xs font-bold tracking-widest text-[#2d4a22]/70">
                                    @if($vendorProfile && $vendorProfile->website)
                                        <a href="{{ $vendorProfile->website }}" target="_blank" rel="noopener noreferrer"
                                            class="flex items-center gap-2.5 hover:text-[#2d4a22] transition-colors uppercase no-underline">
                                            <i data-lucide="globe" class="w-4 h-4"></i> WEBSITE
                                        </a>
                                    @endif
                                    @if($vendorProfile && $vendorProfile->instagram)
                                        <a href="https://instagram.com/{{ ltrim($vendorProfile->instagram, '@') }}"
                                            target="_blank" rel="noopener noreferrer"
                                            class="flex items-center gap-2.5 hover:text-[#2d4a22] transition-colors uppercase no-underline">
                                            <i data-lucide="instagram" class="w-4 h-4"></i> INSTAGRAM
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

                <div class="py-16 border-b border-zinc-200/60 w-full block">
                    <div class="flex items-center justify-between mb-10">
                        <div>
                            <span class="text-xs font-bold tracking-[0.2em] text-[#c5a059] uppercase block mb-1">THE
                                ARTISANS OF THE ATELIER</span>
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
                                    <i data-lucide="users" class="w-3.5 h-3.5 text-white"></i> {{ Auth::user()->name }}
                                    Dream Team
                                </div>
                                <h3 class="text-2xl md:text-3xl font-serif font-medium leading-tight text-white">
                                    {{ $vendorProfile->team_description ?? 'Together, crafting magical, premium wedding masterpieces in seamless synergy.' }}
                                </h3>
                            </div>
                            <span
                                class="text-white/60 text-xs font-mono tracking-widest uppercase md:text-right flex-shrink-0">
                                Studio Corporate Portrait
                            </span>
                        </div>
                    </div>
                </div>

                <div class="py-16 w-full block">
                    <div class="flex items-center justify-between mb-12">
                        <div>
                            <span class="text-xs font-bold tracking-[0.2em] text-[#c5a059] uppercase block mb-1">CURATED
                                SEALS OF HAPPINESS</span>
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

                                        {{-- Hover Action Overlay: Mengaktifkan rute Tombol Edit Package Anda --}}
                                        <div
                                            class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-6 z-10">
                                            <a href="{{ route('vendor.packages.edit', $pkg->id) }}"
                                                class="px-6 py-3 bg-white text-[#2d3e2d] rounded-xl text-xs font-bold tracking-widest uppercase hover:bg-amber-700 hover:text-white transition-colors text-center shadow-md decoration-none flex items-center gap-2">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i> Edit Details
                                            </a>
                                        </div>
                                    </div>

                                    {{-- Keterangan Informasi & Tombol Hapus Data Paket --}}
                                    <div class="p-8 flex-1 flex flex-col justify-between bg-white rounded-b-[2rem]">
                                        <div>
                                            <h3
                                                class="text-2xl font-bold text-gray-900 mb-2 leading-tight group-hover:text-amber-700 transition-colors truncate">
                                                {{ $pkg->package_name }}</h3>
                                            <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed mb-4"> Bantuan
                                                manajemen framework premium berkelanjutan terkurasi murni zero-waste setup.
                                            </p>
                                        </div>
                                        <div class="mt-6 pt-5 border-t border-gray-50 flex items-center justify-between">
                                            <div>
                                                <p
                                                    class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-1">
                                                    Price Investment</p>
                                                <div class="text-xl font-bold text-[#2d4a22]">Rp
                                                    {{ number_format($pkg->price, 0, ',', '.') }}</div>
                                            </div>

                                            {{-- Form Hapus: Tetap Mengarah Mutlak pada Fungsi Penghancuran Data Anda --}}
                                            <form action="{{ route('vendor.packages.delete', $pkg->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this package?');"
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
                                <div
                                    class="col-span-full text-center py-20 bg-white rounded-[2.5rem] border border-dashed border-zinc-200 w-full block">
                                    <p class="text-gray-400 italic mb-4">You haven't published any event package menus yet.
                                    </p>
                                    <a href="{{ route('vendor.packages.create') }}"
                                        class="inline-block px-6 py-3 bg-[#2d3e2d] text-white rounded-full text-xs font-bold tracking-widest uppercase hover:opacity-90 decoration-none shadow-md">
                                        Create Your First Package
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