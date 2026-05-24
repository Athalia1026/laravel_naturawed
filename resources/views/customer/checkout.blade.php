@extends('layouts.customer')

@section('content')
<div class="min-h-screen bg-[#f9f8f3] font-sans text-zinc-900 pb-20">

    <main class="max-w-7xl mx-auto px-12 py-8">
        <div class="mb-16">
            <h1 class="text-6xl font-serif text-[#2d4a22] mb-4">Finalize Your Celebration</h1>
            <p class="text-zinc-500 max-w-2xl leading-relaxed">
                Secure your date with NaturaWed. Our artisans are ready to bring your botanical vision to life with the <span class="text-[#2d4a22] font-semibold">{{ $package->package_name }}</span> experience.
            </p>
        </div>

        <form action="{{ route('customer.bookings.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-16">
            @csrf <input type="hidden" name="package_id" value="{{ $package->id }}">
            <input type="hidden" name="total_price" value="{{ $package->price }}">

            <div class="lg:col-span-2 space-y-16">
                
                <section>
                    <div class="flex items-center gap-4 mb-8">
                        <span class="text-zinc-300 font-mono text-sm">01</span>
                        <h2 class="text-2xl font-serif text-[#2d4a22]">Contact Details</h2>
                    </div>
                    <div class="bg-zinc-100/50 rounded-[32px] p-10 space-y-8 border border-zinc-100">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold tracking-widest text-zinc-400 uppercase">Full Name</label>
                            <input type="text" 
                                name="full_name" 
                                placeholder="e.g. Julianne Sterling" 
                                required
                                value="{{ old('full_name', Auth::user()->name) }}"
                                class="w-full bg-zinc-100 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-[#2d4a22]/20 placeholder:text-zinc-400 outline-none">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold tracking-widest text-zinc-400 uppercase">Phone Number</label>
                                <input type="text" 
                                    name="phone" 
                                    placeholder="e.g. +62 812 3456 7890" 
                                    required
                                    value="{{ old('phone') }}"
                                    class="w-full bg-zinc-100 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-[#2d4a22]/20 outline-none">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold tracking-widest text-zinc-400 uppercase">Email Address</label>
                                <input type="email" name="email" value="{{ Auth::user()->email }}" readonly 
                                       class="w-full bg-zinc-200/50 border-none rounded-2xl py-4 px-6 text-zinc-500 cursor-not-allowed outline-none">
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="flex items-center gap-4 mb-8">
                        <span class="text-zinc-300 font-mono text-sm">02</span>
                        <h2 class="text-2xl font-serif text-[#2d4a22]">Wedding Details</h2>
                    </div>
                    <div class="bg-zinc-100/50 rounded-[32px] p-10 space-y-8 border border-zinc-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold tracking-widest text-zinc-400 uppercase">Event Date</label>
                                <input type="date" name="event_date" required required value="{{ old('event_date') }}"
                                       class="w-full bg-zinc-100 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-[#2d4a22]/20 text-zinc-600 outline-none">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold tracking-widest text-zinc-400 uppercase">Location / Venue</label>
                                <div class="relative">
                                    <div class="absolute left-6 top-1/2 -translate-y-1/2 text-zinc-400">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                    </div>
                                    <input type="text" name="event_location" required value="{{ old('event_location') }}" placeholder="City or Venue Name" 
                                           class="w-full bg-zinc-100 border-none rounded-2xl py-4 pl-14 pr-6 focus:ring-2 focus:ring-[#2d4a22]/20 outline-none">
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold tracking-widest text-zinc-400 uppercase">Notes (Optional)</label>
                            <textarea name="notes" placeholder="Tell us about your theme or specific requests..." 
                                      class="w-full bg-zinc-100 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-[#2d4a22]/20 h-32 resize-none outline-none">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </section>

                <section class="bg-[#2d4a22] rounded-[40px] p-12 text-white">
                    <h3 class="text-3xl font-serif mb-10">{{ $package->package_name }} Inclusion</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                        @php
                            $features = !empty($package->features) ? preg_split('/\r\n|\r|\n/', $package->features) : ["Premium Experience", "Custom Consultation", "Professional Artisans"];
                        @endphp
                        @foreach ($features as $item)
                            @if(trim($item) != "")
                                <div class="flex items-start gap-4">
                                    <div class="w-5 h-5 rounded-full border border-white/30 flex items-center justify-center flex-shrink-0 mt-1">
                                        <svg width="12" height="12" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                                    </div>
                                    <span class="text-sm opacity-90">{{ trim($item) }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </section>
            </div>

            <div class="space-y-8">
                <section class="bg-white rounded-[40px] overflow-hidden shadow-xl shadow-zinc-200/50 sticky top-12">
                    <div class="aspect-[4/3] relative">
                        <img src="{{ $package->main_image ?: 'https://picsum.photos/600/400' }}" class="w-full h-full object-cover" alt="Cover Image">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex flex-col justify-end p-8">
                            <h3 class="text-3xl font-serif text-white">{{ $package->package_name }}</h3>
                        </div>
                    </div>
                    
                    <div class="p-10 space-y-6">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-zinc-400">Total Amount</span>
                            <span class="font-bold text-2xl text-[#2d4a22]">
                                IDR {{ number_format($package->price, 0, ',', '.') }}
                            </span>
                        </div>
                        <button type="submit" class="w-full py-6 bg-[#3a4d39] hover:bg-[#2d4a22] text-white rounded-2xl font-bold text-lg transition-all outline-none shadow-md cursor-pointer">
                            COMPLETE PURCHASE
                        </button>
                    </div>
                </section>
            </div>
        </form>
    </main>
</div>
@endsection