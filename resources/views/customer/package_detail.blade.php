@extends('layouts.customer')

@section('content')
<div class="min-h-screen bg-white font-sans text-[#333]">
    <style>
        .clip-path-triangle { clip-path: polygon(50% 0%, 0% 100%, 100% 100%); }
    </style>

    <main class="mx-auto max-w-7xl px-6 py-10 lg:px-12">
        
        <section class="relative mb-16 overflow-hidden rounded-[40px] bg-[#3a4d32]">
            <div class="relative h-[600px] w-full">
                <img src="{{ $package->main_image ?: 'https://picsum.photos/1200/600' }}" 
                     class="h-full w-full object-cover opacity-60"
                     alt="{{ $package->package_name }}">
                
                <div class="absolute inset-0 flex flex-col justify-end p-12 text-white">
                    <h1 class="text-7xl font-serif font-light tracking-tight">
                        {{ $package->package_name }}
                    </h1>
                    <p class="mt-4 text-sm font-semibold tracking-[0.3em] opacity-80 uppercase">
                        {{ $package->category_name ?? 'PREMIER WEDDING EXPERIENCE' }}
                    </p>
                </div>
            </div>
        </section>

        <div class="flex flex-col gap-12 lg:flex-row">
            
            <div class="flex-1">
                
                <div class="mb-12 flex items-center justify-between rounded-3xl bg-[#f8f9f5] p-6">
                    <div class="flex items-center space-x-4">
                        <div class="relative h-14 w-14 overflow-hidden rounded-full border-2 border-white shadow-sm">
                            <img 
                                src="{{ $package->profile_image ? asset($package->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($package->business_name) . '&background=2d4a22&color=fff' }}" 
                                alt="Designer" 
                                class="w-full h-full object-cover">
                            <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-green-500"></div>
                        </div>
                        <div onclick="event.stopPropagation(); window.location.href='{{ route('vendor.show', ['id' => $package->vendor_id]) }}';" 
                            class="cursor-pointer group/vendor inline-block">
                            <p class="text-xs font-medium text-gray-400">Designed by</p>
                            <span class="text-[#2d4a22] font-semibold hover:underline group-hover/vendor:text-amber-700 transition-colors">
                                {{ $package->business_name ?? 'NaturaWed Vendor' }}
                            </span>
                        </div>
                    </div>
                </div>

                <section class="mb-16">
                    <h2 class="mb-6 text-3xl font-bold text-gray-900">Description</h2>
                    <div class="space-y-6 text-lg leading-relaxed text-gray-600">
                        <p>{!! nl2br(e($package->description)) !!}</p>
                    </div>
                </section>

                <section class="mb-16">
                    <h2 class="mb-8 text-3xl font-bold text-gray-900">Package Features</h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        
                        @php
                            // Memisahkan baris teks fitur menjadi potongan array secara dinamis
                            $featuresList = !empty($package->features) ? preg_split('/\r\n|\r|\n/', $package->features) : [];
                        @endphp

                        @forelse($featuresList as $feature)
                            @if(!empty(trim($feature)))
                                <div class="rounded-3xl bg-[#f3f4f0] p-8 transition-transform hover:-translate-y-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-2 h-2 rounded-full bg-[#3a4d32]"></div>
                                        <h4 class="text-xl font-bold text-gray-900">{{ trim($feature) }}</h4>
                                    </div>
                                    <p class="text-sm text-gray-500">Included in this premium arrangement.</p>
                                </div>
                            @endif
                        @empty
                            <p class="text-gray-400 italic col-span-full">No specific features listed for this package.</p>
                        @endforelse

                    </div>
                </section>

                <!-- Reviews & Ratings Section -->
                <section class="mb-16">
        <h2 class="mb-8 text-3xl font-bold text-gray-900">Reviews & Ratings</h2>
                    
                    @if($totalReviews > 0)
                        <div class="mb-8 rounded-3xl bg-[#f8f9f5] p-8 border border-zinc-200">
                            <div class="flex items-center gap-4">
                                <div>
                                    <p class="text-6xl font-serif font-bold text-[#2d4a22]">
                                        {{ number_format($averageRating, 1) }}
                                    </p>
                                </div>
                                <div>
                                   <div class="flex gap-1 mb-2">
                                        @for($i = 0; $i < floor($averageRating); $i++)
                                            <svg class="w-5 h-5 text-yellow-400 fill-yellow-400 shrink-0" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                        @endfor
                                        
                                        @if($averageRating - floor($averageRating) > 0)
                                            <div class="relative w-5 h-5 shrink-0">
                                                <svg class="absolute inset-0 w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                                <div class="absolute inset-0 overflow-hidden" style="width: {{ ($averageRating - floor($averageRating)) * 100 }}%;">
                                                    <svg class="w-5 h-5 text-yellow-400 fill-yellow-400" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @for($i = ceil($averageRating); $i < 5; $i++)
                                            <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                        @endfor
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        Based on <span class="font-bold text-[#2d4a22]">{{ $totalReviews }}</span> review{{ $totalReviews !== 1 ? 's' : '' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @forelse($reviews as $review)
                        <div class="mb-6 rounded-3xl bg-white border border-zinc-200 p-8">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex gap-4">
                                    <img src="https://ui-avatars.com/api/?name=Anonymous&background=f0f2f0&color=2d4a22&rounded=true&bold=true" 
                                        alt="Anonymous Customer" 
                                        class="w-12 h-12 rounded-full object-cover border border-gray-200 p-1" />
                                    <div>
                                        <h4 class="font-bold text-sm text-[#2d4a22]">{{ $review->masked_name }}</h4>
                                        <div class="flex gap-0.5 mt-1">
                                            @for($i = 0; $i < $review->rating; $i++)
                                                <svg class="w-3 h-3 text-yellow-400 fill-yellow-400" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                            @endfor
                                            @for($i = $review->rating; $i < 5; $i++)
                                                <svg class="w-3 h-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                            @endfor
                                        </div>  
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</span>
                            </div>

                            @if($review->comment)
                                <p class="text-sm text-gray-700 leading-relaxed mb-4">
                                    {{ $review->comment }}
                                </p>
                            @endif

                            @if($review->vendor_reply)
                                <div class="mt-6 pl-4 border-l-2 border-[#2d4a22] bg-zinc-50 p-4 rounded-r-2xl">
                                    <p class="text-[10px] font-bold text-[#2d4a22] uppercase tracking-widest mb-2">Studio Response</p>
                                    <p class="text-sm text-gray-700">{{ $review->vendor_reply }}</p>
                                    <p class="text-[9px] text-gray-400 mt-2">{{ \Carbon\Carbon::parse($review->replied_at)->diffForHumans() }}</p>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="rounded-3xl bg-zinc-50 p-8 text-center border border-zinc-200">
                            <p class="text-sm text-gray-500 italic">No reviews yet. Be the first to share your experience!</p>
                        </div>
                    @endforelse

                    @if($reviews->hasPages())
                        <div class="mt-8">
                            {{ $reviews->links() }}
                        </div>
                    @endif
                
                </section>
            
            </div> <aside class="lg:w-[400px]">
                <div class="sticky top-32 rounded-[40px] bg-white p-8 shadow-2xl shadow-gray-200/50 border border-gray-50">
                    <div class="mb-8 flex items-center justify-between">
                        <div>
                        <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Total Investment</p>
                            <div class="flex items-baseline space-x-1">
                                <span class="text-4xl font-bold text-gray-900">
                                    IDR {{ number_format((float)$package->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        <div class="rounded-full bg-[#e8f0e5] px-4 py-1.5 text-[10px] font-bold tracking-widest text-[#2d4a22]">
                            AVAILABLE
                        </div>
                    </div>

                    <div class="space-y-6">
                        <a href="{{ route('customer.checkout', ['id' => $package->id]) }}" class="block w-full text-center rounded-2xl bg-[#3a4d32] py-5 text-lg font-bold text-white shadow-lg transition-all hover:scale-[1.02] active:scale-95 cursor-pointer">
                            Proceed to Booking
                        </a>
                        <p class="text-center text-[10px] font-medium text-gray-400">Secure transaction via NaturaWed Escrow</p>
                    </div>
                </div>
            </aside>
            
        </div>
    </main>
</div>
@endsection