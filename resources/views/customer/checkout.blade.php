@include('layouts.header')
<body class="min-h-screen bg-[#f9f8f3] font-sans text-zinc-900 pb-20">
    <main class="max-w-7xl mx-auto px-12 py-8">
        <form action="{{ route('process.booking') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-16">
            @csrf
            <input type="hidden" name="package_id" value="{{ $package['id'] }}">
            
            <div class="lg:col-span-2 space-y-16">
                <section>
                    <div class="flex items-center gap-4 mb-8">
                        <span class="text-zinc-300 font-mono text-sm">01</span>
                        <h2 class="text-2xl serif text-[#2d4a22]">Contact Details</h2>
                    </div>
                    <div class="bg-zinc-100/50 rounded-[32px] p-10 space-y-8 border border-zinc-100">
                        <input type="text" name="full_name" placeholder="Full Name" required class="w-full bg-zinc-100 border-none rounded-2xl py-4 px-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <input type="text" name="phone" placeholder="Phone" required class="w-full bg-zinc-100 border-none rounded-2xl py-4 px-6">
                            <input type="email" name="email" value="{{ Auth::user()->email }}" readonly class="w-full bg-zinc-200/50 border-none rounded-2xl py-4 px-6 text-zinc-500 cursor-not-allowed">
                        </div>
                    </div>
                </section>

                <section>
                    <div class="flex items-center gap-4 mb-8">
                        <span class="text-zinc-300 font-mono text-sm">02</span>
                        <h2 class="text-2xl serif text-[#2d4a22]">Wedding Details</h2>
                    </div>
                    <div class="bg-zinc-100/50 rounded-[32px] p-10 space-y-8 border border-zinc-100">
                        <input type="date" name="event_date" required class="w-full bg-zinc-100 border-none rounded-2xl py-4 px-6">
                        <input type="text" name="event_location" required placeholder="Venue Name" class="w-full bg-zinc-100 border-none rounded-2xl py-4 px-6">
                        <textarea name="notes" placeholder="Notes..." class="w-full bg-zinc-100 border-none rounded-2xl py-4 px-6 h-32"></textarea>
                    </div>
                </section>
            </div>

            <div class="space-y-8">
                <section class="bg-white rounded-[40px] overflow-hidden shadow-xl sticky top-12">
                    <img src="{{ $package['main_image'] }}" class="w-full h-64 object-cover">
                    <div class="p-10 space-y-6">
                        <p class="text-2xl font-bold text-[#2d4a22]">IDR {{ number_format($package['price'], 0, ',', '.') }}</p>
                        <button type="submit" class="w-full py-6 bg-[#3a4d39] text-white rounded-2xl font-bold text-lg">COMPLETE PURCHASE</button>
                    </div>
                </section>
            </div>
        </form>
    </main>
</body>