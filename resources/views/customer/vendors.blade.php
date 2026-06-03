@extends('layouts.customer')

@section('content')
<style>
    .font-serif { font-family: 'Playfair Display', Georgia, serif; }
    .bg-brand-cream { background-color: #fdfcf7; }
</style>

<div class="bg-white min-h-screen">
    {{-- SECTION HEADER --}}
    <section class="px-6 pt-12 pb-8 max-w-7xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-[#2d4a22] mb-4">Our Curated Packages</h1>
        <p class="text-gray-600 max-w-2xl text-lg">Temukan mitra terbaik untuk hari spesial Anda, mulai dari fotografer hingga dekorator yang berkelanjutan.</p>
    </section>

    {{-- STICKY FILTER BAR --}}
    <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-gray-100 mb-8">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">

                <div class="relative w-full md:w-96">
                    <input type="text" id="katalogSearch" placeholder="Search package or vendor name..."
                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#2d4a22] focus:outline-none transition-all">
                    <span class="absolute left-3 top-3.5 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                </div>

                <div class="flex gap-3 overflow-x-auto w-full md:w-auto pb-2 md:pb-0">
                    <select id="filterKategori"
                        class="px-4 py-2 bg-gray-50 border border-gray-200 rounded-full text-sm font-medium text-gray-700 focus:outline-none cursor-pointer">
                        <option value="all">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ strtolower($cat->name) }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>

                    <select id="filterHarga"
                        class="px-4 py-2 bg-gray-50 border border-gray-200 rounded-full text-sm font-medium text-gray-700 focus:outline-none cursor-pointer">
                        <option value="default">Price Range</option>
                        <option value="low-high">Low - High</option>
                        <option value="high-low">High - Low</option>
                    </select>
                </div>

            </div>
        </div>
    </div>

    {{-- MAIN GRID KATALOG --}}
    <main class="max-w-7xl mx-auto px-6 pb-20">

        @if($allPackages->isNotEmpty())
            <div id="katalogGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">

                @foreach($allPackages as $pkg)
                    <a href="{{ route('packages.show', ['id' => $pkg->id]) }}" class="katalog-item group cursor-pointer block no-underline bg-white rounded-[2rem] overflow-hidden border border-gray-100 hover:shadow-xl transition-all duration-300 p-2"
                        data-name="{{ strtolower($pkg->package_name) }}"
                        data-vendor="{{ strtolower($pkg->business_name ?? '') }}"
                        data-category="{{ strtolower($pkg->category_name ?? 'uncategorized') }}"
                        data-price="{{ (float) $pkg->price }}">

                        {{-- CONTAINER MEDIA ATAS (Diubah ke aspect-[4/3] agar tinggi gambar proporsional) --}}
                        <div class="relative aspect-[4/3] rounded-[1.7rem] overflow-hidden bg-gray-50 mb-4 w-full block">
                            <img src="{{ $pkg->main_image ?: 'https://picsum.photos/600/400' }}" alt="{{ $pkg->package_name }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                referrerpolicy="no-referrer">

                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 bg-white/95 backdrop-blur-md text-[9px] font-bold uppercase tracking-widest text-[#2d4a22] rounded-full shadow-sm">
                                    {{ $pkg->category_name ?? 'Uncategorized' }}
                                </span>
                            </div>
                        </div>

                        {{-- CONTAINER TEKS BAWAH (Berada mutlak di bawah gambar secara linear) --}}
                        <div class="p-4 flex flex-col justify-between">
                            <div class="mb-3">
                                <h3 class="text-2xl font-serif font-bold text-gray-900 mb-1.5 group-hover:text-amber-700 transition-colors line-clamp-1 leading-tight">
                                    {{ $pkg->package_name }}
                                </h3>
                                <div class="flex items-center text-gray-500 text-sm">
                                <span>🌿 By
                                    <span onclick="event.preventDefault(); event.stopPropagation(); window.location.href='{{ route('vendor.show', ['id' => $pkg->vendor_id]) }}';" 
                                        class="text-[#2d4a22] font-semibold hover:underline hover:text-amber-700 transition-colors cursor-pointer">
                                        {{ $pkg->business_name ?? 'NaturaWed Vendor' }}
                                    </span>
                                </span>
                            </div>
                            </div>
                            
                            <div class="pt-3 border-t border-gray-50 flex items-center justify-between">
                                <div>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Starting Investment</p>
                                    <p class="text-lg font-bold text-[#2d4a22]">
                                        IDR {{ number_format((float) $pkg->price, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="bg-[#2d4a22]/10 text-[#2d4a22] group-hover:bg-[#2d4a22] group-hover:text-white px-4 py-2 rounded-xl text-xs font-bold transition-colors">
                                    Details
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach

            </div>
        @else
            <div class="text-center py-20 bg-gray-50 rounded-[2.5rem] border-2 border-dashed border-gray-200">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M20 12H4M12 4v16" stroke-width="2" stroke-linecap="round"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-serif font-bold text-gray-900 mb-2">Belum Ada Paket</h3>
                <p class="text-gray-500">Para vendor sedang mempersiapkan paket-paket terbaik mereka.</p>
            </div>
        @endif

        {{-- PAGINATION BUTTONS --}}
        @if($allPackages->isNotEmpty())
            <div class="mt-16 flex justify-center">
                <nav class="flex items-center space-x-2">
                    <a href="#" class="p-2 text-gray-400 hover:text-[#2d4a22]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M15 19l-7-7 7-7" stroke-width="2"></path>
                        </svg>
                    </a>
                    <a href="#" class="px-4 py-2 rounded-xl bg-[#2d4a22] text-white font-bold shadow-md">1</a>
                    <a href="#" class="p-2 text-gray-400 hover:text-[#2d4a22]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M9 5l7 7-7 7" stroke-width="2"></path>
                        </svg>
                    </a>
                </nav>
            </div>
        @endif

    </main>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const inputSearch = document.getElementById('katalogSearch');
        const selectKategori = document.getElementById('filterKategori');
        const selectHarga = document.getElementById('filterHarga');
        const gridContainer = document.getElementById('katalogGrid');

        function dapatkanItem() {
            return document.querySelectorAll('.katalog-item');
        }

        function saringKatalog() {
            const kataKunci = inputSearch.value.toLowerCase().trim();
            const kategoriDipilih = selectKategori.value;
            const items = dapatkanItem();

            items.forEach(function (item) {
                const namaPaket = item.getAttribute('data-name');
                const namaVendor = item.getAttribute('data-vendor');
                const kategoriPaket = item.getAttribute('data-category');

                const cocokTeks = namaPaket.includes(kataKunci) || namaVendor.includes(kataKunci);
                const cocokKategori = (kategoriDipilih === 'all') || (kategoriPaket === kategoriDipilih);

                if (cocokTeks && cocokKategori) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        }

        if (inputSearch) inputSearch.addEventListener('input', saringKatalog);
        if (selectKategori) selectKategori.addEventListener('change', saringKatalog);

        if (selectHarga && gridContainer) {
            selectHarga.addEventListener('change', function () {
                const tipeSort = this.value;
                if (tipeSort === 'default') return;

                const itemsArray = Array.from(dapatkanItem());

                itemsArray.sort(function (a, b) {
                    const hargaA = parseFloat(a.getAttribute('data-price'));
                    const hargaB = parseFloat(b.getAttribute('data-price'));
                    return tipeSort === 'low-high' ? hargaA - hargaB : hargaB - hargaA;
                });

                itemsArray.forEach(item => gridContainer.appendChild(item));
            });
        }
    });
</script>
@endsection