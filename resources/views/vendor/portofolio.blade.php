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
            font-family: 'Playfair Display', serif;
        }

        .font-sans {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body>
    <div class="min-h-screen flex bg-[#f8f9fa] font-sans text-[#1a1a1a]">

        @include('layouts.vendor_sidebar')

        <main class="flex-1 overflow-y-auto">
            <div class="relative h-80 w-full bg-gray-200">
                <img src="https://images.unsplash.com/photo-1511895426328-dc8714191300?q=80&w=2000&auto=format&fit=crop"
                    alt="Crew Photo" class="w-full h-full object-cover" referrerpolicy="no-referrer" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>

                <button
                    class="absolute top-6 right-12 px-4 py-2 bg-white/20 backdrop-blur-md text-white rounded-xl text-xs font-bold tracking-widest uppercase hover:bg-white hover:text-[#2d3e2d] transition-all flex items-center gap-2">
                    <i data-lucide="camera" class="w-4 h-4"></i> Change Cover
                </button>
            </div>

            <div class="max-w-6xl mx-auto px-12 -mt-16 relative z-10">
                <div
                    class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-50 mb-12 flex flex-col md:flex-row gap-8 items-start">

                    <div class="relative -mt-20 group cursor-pointer">
                        <div class="w-32 h-32 rounded-full border-4 border-white overflow-hidden bg-gray-100 shadow-lg">
                            <img src="https://picsum.photos/seed/vendorlogo/200/200" alt="Vendor Logo"
                                class="w-full h-full object-cover" referrerpolicy="no-referrer" />
                        </div>
                        <div
                            class="absolute inset-0 rounded-full bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <i data-lucide="edit-2" class="text-white w-6 h-6"></i>
                        </div>
                    </div>

                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="text-4xl font-serif text-[#2d3e2d] mb-2">{{ Auth::user()->name }}</h2>
                                <div class="flex items-center gap-4 text-sm font-medium text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                                        {{ $vendorProfile->address ?? 'Surabaya, Indonesia' }}
                                    </span>
                                    <span class="flex items-center gap-1 text-yellow-500">
                                        <i data-lucide="star" class="w-4 h-4 fill-current"></i> 4.9/5 (120 Reviews)
                                    </span>
                                </div>
                            </div>

                            <a href="{{ route('profile.edit') }}"
                                class="px-6 py-2 border border-gray-200 text-gray-600 rounded-xl text-xs font-bold tracking-widest uppercase hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <i data-lucide="edit-3" class="w-4 h-4"></i> Edit Profile
                            </a>
                        </div>

                        <div class="mt-6">
                            <h4 class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-3">About Us</h4>
                            <p class="text-gray-600 leading-relaxed">
                                {{ $vendorProfile->description ?? 'We are a passionate team of wedding decorators and organizers dedicated to turning your dream day into reality.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-12">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-3xl font-serif text-[#2d3e2d]">Our Packages</h3>
                        <a href="#"
                            class="text-[10px] font-bold tracking-widest text-[#2d3e2d] uppercase hover:opacity-70 transition-opacity flex items-center gap-1">
                            <i data-lucide="plus" class="w-4 h-4"></i> Create New
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @forelse ($myPackages as $pkg)
                            <div
                                class="bg-white rounded-[2rem] overflow-hidden border border-gray-100 shadow-sm hover:-translate-y-1 transition-transform group cursor-pointer">
                                <div class="aspect-[4/3] overflow-hidden relative">
                                    <img src="{{ $pkg->main_image ?? 'https://via.placeholder.com/600' }}"
                                        alt="Package Image"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                        referrerpolicy="no-referrer">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
                                        <button
                                            class="w-full py-3 bg-white/20 backdrop-blur-md text-white rounded-xl text-xs font-bold tracking-widest uppercase hover:bg-white hover:text-[#2d3e2d] transition-colors">
                                            <span class="flex items-center justify-center gap-2">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i> Edit Package
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-2">
                                        {{ $pkg->category_name ?? 'Uncategorized' }}
                                    </p>
                                    <h4 class="text-xl font-serif text-[#2d3e2d] mb-4 truncate">
                                        {{ $pkg->package_name }}
                                    </h4>
                                    <div class="flex justify-between items-center border-t border-gray-50 pt-4">
                                        <p class="text-sm font-bold text-[#2d3e2d]">
                                            Rp {{ number_format($pkg->price, 0, ',', '.') }}
                                        </p>
                                        <button class="text-gray-400 hover:text-red-500 transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-gray-100">
                                <p class="text-gray-500 mb-4">You haven't created any packages yet.</p>
                                <a href="#"
                                    class="inline-block px-6 py-2 bg-[#2d3e2d] text-white rounded-full text-sm font-semibold hover:opacity-90">Create
                                    Your First Package</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            lucide.createIcons();
        });
    </script>
</body>

</html>