<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Packages - NaturaWed</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        .font-sans {
            font-family: 'Inter', sans-serif;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="flex min-h-screen bg-white font-sans text-[#1a1a1a]">

    @include('layouts.vendor_sidebar')

    <main class="flex-1 p-12 overflow-y-auto bg-[#f8f9fa]">

        <header class="flex justify-between items-start mb-8">
            <div>
                <h2 class="text-4xl font-serif text-[#2d3e2d] mb-2">Wedding Packages</h2>
                <p class="text-gray-500 text-sm">Manage your signature collections and service offerings.</p>
            </div>

            <a href="{{ route('vendor.packages.create') }}"
                class="flex items-center gap-2 px-6 py-3.5 bg-[#2a3f24] text-white rounded-xl text-xs font-bold tracking-widest uppercase hover:opacity-90 transition-all shadow-lg hover:shadow-xl active:scale-95">
                <i data-lucide="plus" class="w-4 h-4"></i> Add New Package
            </a>
        </header>

        <div class="flex items-center gap-4 mb-10">
            <div class="relative flex-1">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                <input type="text" id="packageSearch" placeholder="Search packages by name or category..."
                    class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#2d3e2d] focus:ring-1 focus:ring-[#2d3e2d] transition-all shadow-sm">
            </div>
            <button
                class="flex items-center gap-2 px-6 py-3.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm active:scale-95">
                <i data-lucide="filter" class="w-4 h-4 text-gray-500"></i> Filters
            </button>
        </div>

        @if ($myPackages->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                @foreach ($myPackages as $pkg)
                    <div class="package-card group bg-white rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col relative z-10 hover:z-20"
                        data-name="{{ strtolower($pkg->package_name) }}"
                        data-category="{{ strtolower($pkg->category_name ?? 'uncategorized') }}">

                        <div class="relative h-64 overflow-hidden rounded-t-[2rem] bg-gray-100 shrink-0">
                            <img src="{{ $pkg->main_image ?: 'https://picsum.photos/600/400' }}" alt="{{ $pkg->package_name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        </div>
                        <div class="p-6 flex items-start justify-between flex-1">
                            <div class="pr-4">
                                <h3 class="text-xl font-serif text-gray-900 mb-1.5 leading-tight line-clamp-2">
                                    {{ $pkg->package_name }}</h3>
                                <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">
                                    {{ $pkg->category_name ?? 'Uncategorized' }} • IDR
                                    {{ number_format((float) $pkg->price, 0, ',', '.') }}</p>
                            </div>

                            <div class="relative" onclick="this.querySelector('.dropdown-menu').classList.toggle('hidden')">
                                <button type="button"
                                    class="text-gray-400 hover:text-[#2d3e2d] hover:bg-gray-50 p-2 rounded-full transition-colors shrink-0 focus:outline-none cursor-pointer">
                                    <i data-lucide="more-vertical" class="w-5 h-5 pointer-events-none"></i>
                                </button>

                                <div
                                    class="dropdown-menu hidden absolute right-0 mt-2 w-36 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-2">
                                    <a href="{{ route('vendor.packages.edit', ['id' => $pkg->id]) }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50 hover:text-[#2d4a22] transition-colors">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i> Edit
                                    </a>
                                    <a href="{{ route('vendor.packages.delete', ['id' => $pkg->id]) }}"
                                        onclick="return confirm('Yakin ingin menghapus paket ini secara permanen?')"
                                        class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-red-600 hover:bg-red-50 transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div
                class="w-full bg-white rounded-[2.5rem] border border-gray-100 border-dashed p-16 flex flex-col items-center justify-center text-center shadow-sm">
                <div class="w-20 h-20 bg-[#f0f2f0] rounded-full flex items-center justify-center text-[#2d3e2d] mb-6">
                    <i data-lucide="package-open" class="w-10 h-10"></i>
                </div>
                <h3 class="text-2xl font-serif text-gray-900 mb-2">No Packages Yet</h3>
                <p class="text-gray-500 text-sm max-w-md mb-8">You haven't created any signature collections. Add your first
                    package to start showcasing your services to couples.</p>
                <a href="{{ route('vendor.packages.create') }}"
                    class="px-8 py-4 bg-[#2a3f24] text-white rounded-xl text-xs font-bold tracking-widest uppercase hover:opacity-90 transition-all shadow-lg active:scale-95">
                    Create First Package
                </a>
            </div>
        @endif

    </main>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Lucide Icons
        lucide.createIcons();
        
        // Ambil elemen input pencarian dan semua kartu paket
        const searchInput = document.getElementById('packageSearch');
        const packageCards = document.querySelectorAll('.package-card');

        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                // Konversi input teks ke huruf kecil agar pencarian tidak sensitif huruf kapital
                const searchTerm = e.target.value.toLowerCase().trim();

                packageCards.forEach(function(card) {
                    // Ambil data nama dan kategori dari atribut elemen kartu
                    const packageName = card.getAttribute('data-name');
                    const packageCategory = card.getAttribute('data-category');

                    // Cek apakah kata kunci pencarian ada di dalam nama atau kategori paket
                    if (packageName.includes(searchTerm) || packageCategory.includes(searchTerm)) {
                        // Jika cocok, tampilkan kartu (hapus kelas hidden kustom Tailwind)
                        card.classList.remove('hidden');
                    } else {
                        // Jika tidak cocok, sembunyikan kartu secara instan
                        card.classList.add('hidden');
                    }
                });
            });
        }
    });
</script>
</body>

</html>