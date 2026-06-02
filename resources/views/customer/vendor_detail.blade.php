@extends('layouts.customer')

@section('content')
<style>
    .font-serif { font-family: 'Playfair Display', Georgia, serif; }
    .bg-brand-cream { background-color: #fdfcf7; }
</style>

<main class="min-h-screen bg-brand-cream font-sans text-gray-900 block relative w-full pb-24">

    {{-- HERO BANNER — STATIS: Menggunakan gambar Unsplash sesuai permintaan --}}
    <div class="relative h-[480px] w-full overflow-hidden block">
        <img
            src="https://images.unsplash.com/photo-1519167758481-83f550bb49b3?q=80&w=2074&auto=format&fit=crop"
            alt="Vendor Cover Background"
            class="w-full h-full object-cover"
            referrerpolicy="no-referrer"
        />
        <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-black/20"></div>

        <button onclick="window.history.back()" class="absolute top-6 left-6 p-3 bg-white/20 backdrop-blur-md text-white rounded-full hover:bg-white/40 transition-all border border-white/30 cursor-pointer outline-none flex items-center justify-center z-20">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        </button>

        <div class="absolute top-6 right-6 flex gap-3 z-20">
            <button class="p-3 bg-white/20 backdrop-blur-md text-white rounded-full hover:bg-white/40 transition-all border border-white/30 cursor-pointer flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
            </button>
            <button class="p-3 bg-white/20 backdrop-blur-md text-white rounded-full hover:bg-white/40 transition-all border border-white/30 cursor-pointer flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </button>
        </div>
    </div>

    {{-- MAIN CONTENT WRAPPER --}}
    <div class="max-w-7xl mx-auto px-6 relative w-full block">

        {{-- VENDOR INFO CARD --}}
        <div class="bg-white rounded-[3.5rem] shadow-2xl border border-gray-100 -mt-24 relative z-10 mb-12 block w-full">
            <div class="px-8 md:px-12 pt-8 md:pt-10 pb-8 md:pb-12">
                <div class="flex flex-col lg:flex-row gap-12 items-start">

                    {{-- VENDOR LOGO — DINAMIS: Menampilkan dari database dengan fallback gambar statis --}}
                    <div class="w-44 h-44 rounded-[3rem] overflow-hidden border-4 border-white shadow-2xl -mt-28 bg-brand-cream flex-shrink-0 mx-auto lg:mx-0 relative z-20">
                        <img src="{{ $vendorProfile && $vendorProfile->profile_image ? asset($vendorProfile->profile_image) : 'https://picsum.photos/seed/vendor/400/400' }}" alt="Vendor Logo" class="w-full h-full object-cover" referrerpolicy="no-referrer" />
                    </div>

                    {{-- VENDOR INFO --}}
                    <div class="flex-1 space-y-5 text-center lg:text-left w-full">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                            <div>
                                <h1 class="text-4xl md:text-5xl font-serif font-bold text-[#2d4a22] tracking-tight">
                                    {{ $vendorProfile->studio_name ?? ($vendorUser->name ?? 'Ocean Wed n Co.') }}
                                </h1>
                                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 mt-3">
                                    <div class="flex items-center gap-1.5 text-[#c5a059] bg-yellow-50 px-3 py-1 rounded-full text-sm font-semibold">
                                        <svg class="w-4 h-4 fill-[#c5a059] text-[#c5a059]" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <span>4.9</span>
                                        <span class="text-gray-400 font-normal">(128 Reviews)</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-gray-500 bg-gray-50 px-3 py-1 rounded-full text-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"></path></svg>
                                        <span>{{ $vendorProfile->physical_address ?? ($vendorProfile->address ?? 'Bali, Indonesia') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-center shrink-0">
                                <button type="button" class="flex items-center gap-2.5 px-8 py-5 bg-[#2d4a22] text-white rounded-2xl font-bold shadow-xl hover:bg-[#1e3317] transition-all transform hover:-translate-y-0.5 active:scale-95 cursor-pointer border-none outline-none text-base">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                    Message Vendor
                                </button>
                            </div>
                        </div>

                        <p class="text-gray-600 leading-relaxed text-lg max-w-5xl pt-2">
                            {{ $vendorProfile->bio ?? 'Specializing in ultra-premium eco-friendly and sustainable wedding celebrations. We believe that your special day should be as gorgeous as the nature that surrounds us. With over 10 years of experience curating immaculate destination wedding experiences across tropical paradises.' }}
                        </p>

                        <div class="flex flex-wrap justify-center lg:justify-start gap-8 pt-4 border-t border-gray-100 text-xs font-bold tracking-widest text-[#2d4a22]/70">
                            <a href="#" class="flex items-center gap-2.5 hover:text-[#2d4a22] transition-colors uppercase no-underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9-9c1.657 0 3 4.03 3 9s-1.343 9-3 9m0-18c-1.657 0-3 4.03-3 9s1.343 9 3 9m-9-9h18" stroke-width="2"></path></svg> WEBSITE
                            </a>
                            <a href="#" class="flex items-center gap-2.5 hover:text-[#2d4a22] transition-colors uppercase no-underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 4v16M17 4v16M3 8h18M3 16h18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg> INSTAGRAM
                            </a>
                            <a href="#" class="flex items-center gap-2.5 hover:text-[#2d4a22] transition-colors uppercase no-underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                {{ $vendorUser->email ?? 'EMAIL' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TEAM SECTION — DINAMIS: Menampilkan Foto Bersama Tim dari Database --}}
        <div class="py-16 border-b border-zinc-200/60 w-full block">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-bold tracking-[0.2em] text-[#c5a059] uppercase block mb-3">THE ARTISANS OF THE ATELIER</span>
                <h2 class="text-4xl font-serif font-bold text-[#2d4a22] mb-4">Meet Our Creative Team</h2>
                <p class="text-gray-500 text-base leading-relaxed">
                    We are a passionate collective of eco-consultants, structural florals designers, and master wedding coordinators dedicated to drafting sustainable visual wonders.
                </p>
            </div>

            <div class="relative rounded-[3.5rem] overflow-hidden shadow-2xl h-[420px] bg-gray-100 group w-full block">
                <img
                    src="{{ $vendorProfile && $vendorProfile->team_image ? asset($vendorProfile->team_image) : 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=2070&auto=format&fit=crop' }}"
                    alt="Vendor Team Group Portrait"
                    class="w-full h-full object-cover brightness-[0.85] transition-transform duration-1000 group-hover:scale-[1.02]"
                    referrerpolicy="no-referrer"
                />
                <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
                <div class="absolute bottom-10 left-10 right-10 flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                    <div class="max-w-2xl text-white">
                        <div class="flex items-center gap-2 mb-3 bg-[#2d4a22] text-xs font-bold tracking-widest uppercase py-1.5 px-4 rounded-full w-fit">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zm14 10v-2a4 4 0 00-3-3.87m-4-12a4 4 0 010 7.75" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            {{ $vendorProfile->studio_name ?? ($vendorUser->name ?? 'NaturaWed Vendor') }} Dream Team
                        </div>
                        <h3 class="text-2xl md:text-3xl font-serif font-medium leading-tight text-white">
                            {{ $vendorProfile->team_description ?? 'Together, crafting magical, premium wedding masterpieces in seamless synergy.' }}
                        </h3>
                    </div>
                    <span class="text-white/60 text-xs font-mono tracking-widest uppercase md:text-right flex-shrink-0">
                        Studio Portrait, 2026
                    </span>
                </div>
            </div>
        </div>

        {{-- PACKAGES SECTION — DINAMIS: Menampilkan Paket Pernikahan --}}
        <div class="py-16 w-full block">
            <span class="text-xs font-bold tracking-[0.2em] text-[#c5a059] uppercase block mb-3">CURATED SEALS OF HAPPINESS</span>
            <h2 class="text-4xl font-serif font-bold text-[#2d4a22] mb-12">Our Packages</h2>

            @if($myPackages->isNotEmpty())
                <div class="w-full block">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                        @foreach($myPackages as $pkg)
                            <a href="{{ route('packages.show', ['id' => $pkg->id]) }}" class="group block cursor-pointer overflow-hidden rounded-[2.5rem] bg-white shadow-xl border border-gray-50 hover:shadow-2xl transition-all p-2 no-underline">
                                <div class="flex flex-col h-full w-full">
                                    <div class="h-60 relative rounded-[2rem] overflow-hidden bg-gray-50 w-full block">
                                        <img src="{{ $pkg->main_image ? asset($pkg->main_image) : 'https://picsum.photos/seed/pkg/600/400' }}" alt="Package Portrait" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" referrerpolicy="no-referrer" />
                                        <div class="absolute top-4 right-4 bg-[#2d4a22] text-white px-3.5 py-1.5 rounded-full text-[10px] font-bold tracking-wider uppercase shadow-sm">
                                            {{ $pkg->category_name ?? 'Sustainable' }}
                                        </div>
                                    </div>
                                    <div class="p-8 flex-1 flex flex-col justify-between bg-white rounded-b-[2rem]">
                                        <div>
                                            <h3 class="text-2xl font-bold text-gray-900 mb-2 leading-tight group-hover:text-amber-700 transition-colors">{{ $pkg->package_name }}</h3>
                                            <p class="text-sm text-gray-500 line-clamp-3 leading-relaxed mb-4">
                                                {{ $pkg->description ?? 'Timeless elegant celebration framework curated with bespoke luxury materials and zero-waste management setups.' }}
                                            </p>
                                        </div>
                                        <div class="mt-6 pt-5 border-t border-gray-50 flex items-center justify-between">
                                            <div>
                                                <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-1">Starting Investment</p>
                                                <div class="text-xl font-bold text-[#2d4a22]">IDR {{ number_format((float)$pkg->price, 0, ',', '.') }}</div>
                                            </div>
                                            <div class="bg-[#2d4a22]/10 text-[#2d4a22] group-hover:bg-[#2d4a22] group-hover:text-white px-4 py-2.5 rounded-xl text-xs font-bold transition-colors">
                                                Book Now
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-[2.5rem] border border-dashed border-zinc-200 w-full block">
                    <p class="text-gray-400 italic">This studio profile hasn't published package menus yet.</p>
                </div>
            @endif
        </div>

    </div>
</main>
@endsection