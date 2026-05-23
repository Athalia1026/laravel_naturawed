@extends('layouts.app') {{-- Asumsi kamu punya layout utama --}}

@section('content')
<main class="min-h-screen bg-white font-sans text-gray-900">
    
    <section class="relative h-[600px] w-full overflow-hidden">
        <img src="..." class="h-full w-full object-cover" />
        <div class="absolute inset-0 flex items-center justify-center">
            <form action="{{ route('search') }}" method="GET" class="relative flex w-full max-w-3xl items-center px-6">
                <input type="text" name="q" placeholder="Find your eco-friendly wedding vendors" class="h-16 w-full rounded-full bg-white pl-10 pr-40 text-lg shadow-2xl focus:outline-none" />
                <button type="submit" class="absolute right-8 rounded-full bg-[#2d4a22] px-10 py-3 text-lg font-bold text-white">Search</button>
            </form>
        </div>
    </section>

    <section class="bg-[#d9e4c3] px-12 py-16">
        <h2 class="mb-12 text-4xl font-bold text-[#2d4a22]">Eco Elegance</h2>
        
        <div class="flex overflow-x-auto gap-6 hide-scrollbar">
            @forelse($ecoPackages as $package)
                <div class="flex-none w-[340px] bg-white rounded-[2rem] p-6 shadow-xl">
                    <h3 class="text-xl font-bold">{{ $package->package_name }}</h3>
                    <p class="text-sm mt-4">{{ $package->description }}</p>
                    <div class="mt-8 font-bold">IDR {{ number_format($package->price, 0, ',', '.') }}</div>
                </div>
            @empty
                <p>No packages available yet.</p>
            @endforelse
        </div>
    </section>
</main>
@endsection