@php
    $formattedPrice = number_format((float)$package['price'], 0, ',', '.');
    $featuresList = !empty($package['features']) ? explode("\n", $package['features']) : [];
@endphp

@include('layouts.header')

<div class="min-h-screen bg-white font-sans text-[#333]">
    <main class="mx-auto max-w-7xl px-6 py-10 lg:px-12">
        <section class="relative mb-16 overflow-hidden rounded-[40px] bg-[#3a4d32]">
            <div class="relative h-[600px] w-full">
                <img src="{{ $package['main_image'] }}" class="h-full w-full object-cover opacity-60" alt="{{ $package['package_name'] }}">
                <div class="absolute inset-0 flex flex-col justify-end p-12 text-white">
                    <h1 class="text-7xl font-serif font-light tracking-tight">{{ $package['package_name'] }}</h1>
                    <p class="mt-4 text-sm font-semibold tracking-[0.3em] opacity-80 uppercase">{{ $package['category_name'] ?? 'PREMIER WEDDING EXPERIENCE' }}</p>
                </div>
            </div>
        </section>

        <div class="flex flex-col gap-12 lg:flex-row">
            <div class="flex-1">
                <div class="mb-12 flex items-center justify-between rounded-3xl bg-[#f8f9f5] p-6">
                    <div class="flex items-center space-x-4">
                        <div class="relative h-14 w-14 overflow-hidden rounded-full border-2 border-white shadow-sm bg-gray-200">
                            <img src="{{ $package['designer_img'] ?? 'https://picsum.photos/seed/vendor/100/100' }}" alt="Designer" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400">Designed by</p>
                            <h3 class="text-xl font-bold text-[#2d4a22]">{{ $package['business_name'] ?? 'NaturaWed Vendor' }}</h3>
                        </div>
                    </div>
                </div>

                <section class="mb-16">
                    <h2 class="mb-6 text-3xl font-bold text-gray-900">Description</h2>
                    <div class="space-y-6 text-lg leading-relaxed text-gray-600">
                        <p>{!! nl2br(e($package['description'])) !!}</p>
                    </div>
                </section>

                <section class="mb-16">
                    <h2 class="mb-8 text-3xl font-bold text-gray-900">Package Features</h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        @foreach($featuresList as $feature)
                            <div class="rounded-3xl bg-[#f3f4f0] p-8 transition-transform hover:-translate-y-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-2 h-2 rounded-full bg-[#3a4d32]"></div>
                                    <h4 class="text-xl font-bold text-gray-900">{{ trim($feature) }}</h4>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            <aside class="lg:w-[400px]">
                <div class="sticky top-32 rounded-[40px] bg-white p-8 shadow-2xl shadow-gray-200/50 border border-gray-50">
                    <div class="mb-8 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Total Investment</p>
                            <div class="text-4xl font-bold text-gray-900">IDR {{ $formattedPrice }}</div>
                        </div>
                    </div>
                    <form action="{{ route('checkout', $package['id']) }}" method="GET">
                        <button type="submit" class="w-full rounded-2xl bg-[#3a4d32] py-5 text-lg font-bold text-white shadow-lg transition-all hover:scale-[1.02] active:scale-95">
                            Proceed to Booking
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    </main>
</div>
@include('layouts.footer')