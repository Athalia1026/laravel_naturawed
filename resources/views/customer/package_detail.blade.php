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
                        <div class="relative h-14 w-14 overflow-hidden rounded-full border-2 border-white shadow-sm bg-gray-200">
                            <img src="https://picsum.photos/seed/vendor/100/100" alt="Designer" class="w-full h-full object-cover">
                            <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-green-500"></div>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400">Designed by</p>
                            <h3 class="text-xl font-bold text-[#2d4a22]">
                                {{ $package->business_name ?? 'NaturaWed Vendor' }}
                            </h3>
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
            </div>

            <aside class="lg:w-[400px]">
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