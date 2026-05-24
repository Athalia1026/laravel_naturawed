@extends('layouts.customer')

@section('content')
<main class="min-h-screen bg-white font-sans text-gray-900">
    
    <section class="relative h-[600px] w-full overflow-hidden">
        <img 
            src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?q=80&w=2069&auto=format&fit=crop" 
            alt="Wedding couple in forest"
            class="h-full w-full object-cover brightness-95"
            referrerpolicy="no-referrer"
        />
        <div class="absolute inset-0 flex items-center justify-center">
            <form action="#" method="GET" class="relative flex w-full max-w-3xl items-center px-6">
                <input
                    type="text"
                    name="q"
                    placeholder="Find your eco-friendly wedding vendors"
                    class="h-16 w-full rounded-full bg-white pl-10 pr-40 text-lg shadow-2xl focus:outline-none"
                />
                <button
                    type="submit"
                    class="absolute right-8 rounded-full bg-[#2d4a22] px-10 py-3 text-lg font-bold text-white shadow-lg transition-all hover:scale-105 active:scale-95"
                >
                    Search
                </button>
            </form>
        </div>
    </section>

    <section class="bg-[#d9e4c3] px-12 py-16">
        <h2 class="mb-12 text-4xl font-bold text-[#2d4a22]">Eco Elegance for Your Perfect Day</h2>
        
        @if ($ecoPackages->isNotEmpty())
            <div class="relative w-full overflow-hidden">
                <div id="ecoCarousel" class="flex overflow-x-auto gap-6 pb-12 pt-4 px-4 hide-scrollbar">
                    
                    @foreach ($ecoPackages as $item)
                        <a href="#" class="group relative flex-none w-[340px] overflow-hidden rounded-[2rem] bg-white shadow-xl shadow-gray-200/50 transition-transform duration-300 hover:-translate-y-2 snap-start border border-gray-50">
                            <img src="{{ $item->main_image ?: 'https://picsum.photos/400/300' }}" 
                                 alt="{{ $item->package_name }}" 
                                 class="h-64 w-full object-cover transition-transform duration-700 group-hover:scale-105" 
                                 referrerpolicy="no-referrer" />
                            
                            <div class="p-6">
                                <div class="flex items-start justify-between gap-4">
                                    <h3 class="text-xl font-bold text-gray-900 leading-tight line-clamp-2">
                                        {{ $item->package_name }}
                                    </h3>
                                </div>
                                
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mt-3">
                                    By <span class="text-[#2d4a22]">{{ $item->business_name }}</span>
                                </span>
                                
                                <p class="mt-4 text-sm leading-relaxed text-gray-500 line-clamp-2">
                                    {{ $item->description }}
                                </p>
                                
                                <div class="mt-8 flex items-center justify-between border-t border-gray-100 pt-6">
                                    <div>
                                        <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-1">Starting at</p>
                                        <div class="text-lg font-bold text-gray-900">
                                            IDR {{ number_format((float)$item->price, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#f0f2f0] text-[#2d4a22] group-hover:bg-[#2d4a22] group-hover:text-white transition-colors">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                    
                    <a href="#" class="group relative flex-none w-[340px] overflow-hidden rounded-[2rem] bg-[#f8f9fa] border-2 border-dashed border-gray-200 transition-all duration-300 hover:border-[#2d4a22] snap-start flex items-center justify-center min-h-[400px]">
                        <div class="text-center p-8">
                            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#e1f5e1] text-[#2d4a22] group-hover:scale-110 transition-transform">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </div>
                            <h3 class="text-lg font-serif font-bold text-[#2d3e2d] mb-2">Explore More</h3>
                            <p class="text-xs text-gray-500">Discover all eco-friendly packages</p>
                        </div>
                    </a>
                </div>
            </div>
        @else
            <div class="w-full text-center py-16 bg-[#f8f9fa] rounded-[2rem] border border-gray-100">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-white shadow-sm text-gray-400">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 12H4M12 4v16"/></svg>
                </div>
                <h3 class="text-xl font-serif font-bold text-gray-400 mb-2">No Packages Yet</h3>
                <p class="text-sm text-gray-400">Vendors are currently preparing their best eco-friendly options.</p>
            </div>
        @endif
    </section>

    <section class="bg-white px-12 py-16">
        <h2 class="mb-12 text-4xl font-bold text-gray-900">Most Recommended Vendors</h2>
        <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
            @foreach ($recommendedVendors as $item)
                <a href="#" class="group block overflow-hidden rounded-3xl shadow-2xl transition-transform duration-300 hover:scale-105">
                    <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}" class="h-64 w-full object-cover" referrerpolicy="no-referrer" />
                    <div class="bg-[#800000] p-8 text-white">
                        <h3 class="text-2xl font-bold">{{ $item['name'] }}</h3>
                        <div class="mt-2 flex items-center space-x-1">
                            @for ($i = 0; $i < 4; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-400">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                            @endfor
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-300">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            <span class="text-xs opacity-80 pl-1">(4.0)</span>
                        </div>
                        <p class="mt-4 text-sm leading-relaxed opacity-90 line-clamp-2">
                            {{ $item['review'] }}
                        </p>
                        <div class="mt-4 text-xs font-bold uppercase tracking-widest opacity-70">by {{ $item['author'] }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <section class="bg-[#f4e1e1] px-12 py-16">
        <h2 class="mb-12 text-4xl font-bold text-gray-900">Unforgettable Wedding Deals</h2>
        <div class="grid grid-cols-1 gap-10 md:grid-cols-2">
            @foreach ($weddingDeals as $item)
                <a href="#" class="group block overflow-hidden rounded-[2.5rem] bg-white shadow-2xl transition-transform duration-300 hover:-translate-y-2">
                    <div class="relative h-80">
                        <img src="{{ $item['img'] }}" alt="{{ $item['title'] }}" class="h-full w-full object-cover" referrerpolicy="no-referrer" />
                        <div class="absolute top-6 right-6 rounded-lg bg-[#ff3b3b] px-5 py-2 text-sm font-bold text-white shadow-md">Recommend</div>
                    </div>
                    <div class="p-10">
                        <div class="flex items-center justify-between">
                            <h3 class="text-3xl font-bold text-gray-900">{{ $item['title'] }}</h3>
                            <span class="text-sm font-bold text-gray-500 uppercase tracking-widest hover:text-[#2d4a22]">
                                by {{ $item['author'] }}
                            </span>
                        </div>
                        <p class="mt-4 text-lg leading-relaxed text-gray-600">
                            {{ $item['desc'] }}
                        </p>
                        <div class="mt-8 flex items-baseline space-x-4">
                            <span class="text-2xl font-bold text-gray-900">{{ $item['price'] }}</span>
                            @if ($item['oldPrice'])
                                <span class="text-lg text-gray-400 line-through">{{ $item['oldPrice'] }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <section class="bg-white px-12 py-16">
        <h2 class="mb-12 text-4xl font-bold text-gray-900">Natura Video</h2>
        <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 md:grid-cols-5">
            @foreach ($videos as $video)
                <div class="group relative aspect-[3/4.5] cursor-pointer overflow-hidden rounded-2xl shadow-lg transition-transform duration-300 hover:scale-105">
                    <img src="{{ $video['img'] }}" alt="Video thumbnail" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" referrerpolicy="no-referrer" />
                    <div class="absolute inset-0 bg-black/20 transition-colors group-hover:bg-black/40"></div>
                    <div class="absolute top-4 left-4 flex items-center space-x-2 text-xs font-bold text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="6 3 20 12 6 21 6 3"/>
                        </svg>
                        <span>{{ $video['views'] }}</span>
                    </div>
                    <div class="absolute bottom-4 left-4 text-xs font-bold text-white opacity-90">
                        {{ $video['user'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carousel = document.getElementById('ecoCarousel');
        
        if (carousel) {
            let scrollInterval;
            const scrollSpeed = 1;

            const startScroll = () => {
                scrollInterval = setInterval(() => {
                    carousel.scrollLeft += scrollSpeed;
                    if (carousel.scrollLeft >= (carousel.scrollWidth - carousel.clientWidth) - 1) {
                        setTimeout(() => {
                            carousel.scrollLeft = 0;
                        }, 500); 
                    }
                }, 20);
            };

            const stopScroll = () => {
                clearInterval(scrollInterval);
            };

            startScroll();

            carousel.addEventListener('mouseenter', stopScroll);
            carousel.addEventListener('mouseleave', startScroll);
            carousel.addEventListener('touchstart', stopScroll);
            carousel.addEventListener('touchend', startScroll);
        }
    });
</script>
@endsection