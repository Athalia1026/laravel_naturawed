@extends('layouts.customer')

@section('content')
<style>
    .font-serif { font-family: 'Playfair Display', Georgia, serif; }
    .font-sans { font-family: 'Inter', sans-serif; }
    .bg-brand-cream { background-color: #fdfcf7; }
</style>

<div class="min-h-screen bg-brand-cream font-sans text-zinc-900 block relative w-full">
    
    <!-- Hero Section -->
    <section class="relative py-24 bg-white border-b border-zinc-100 overflow-hidden block">
        <!-- Subtle decorative calligraphic overlay -->
        <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/4 pointer-events-none opacity-[0.03] text-zinc-950 select-none hidden lg:block">
            <span class="font-serif italic text-[24rem]">NaturaWed</span>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10 block">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="space-y-8 block">
                    <span class="text-xs font-bold tracking-[0.25em] text-[#c5a059] uppercase block">
                        OUR SACRED MISSION
                    </span>
                    <h1 class="text-6xl lg:text-7xl font-serif text-[#2d4a22] leading-tight font-medium block">
                        Elegance with a Conscience
                    </h1>
                    <p class="text-xl text-zinc-500 leading-relaxed max-w-xl block">
                        NaturaWed is the world's premier curation workspace for carbon-neutral, zero-waste destination weddings. We bridge the gap between luxury high-fashion wedding setups and absolute environmental mindfulness.
                    </p>
                    <div class="flex flex-wrap gap-4 pt-4 block">
                        <a href="{{ route('customer.vendors') }}" 
                           class="bg-[#2d4a22] text-white px-8 py-5 rounded-2xl font-bold text-base shadow-xl hover:bg-[#1e3317] transition-all transform hover:-translate-y-0.5 flex items-center gap-2.5 active:scale-95 no-underline">
                            <span>Explore Eco-Vendors</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                        <a href="{{ route('inspiration') }}" 
                           class="border border-zinc-200 text-zinc-700 bg-white hover:bg-zinc-50 px-8 py-5 rounded-2xl font-bold text-base transition-all active:scale-95 no-underline flex items-center justify-center">
                            View Visual Stories
                        </a>
                    </div>
                </div>

                <div class="relative aspect-[4/3] rounded-[3.5rem] overflow-hidden shadow-2xl bg-zinc-50 border-4 border-white block">
                    <img 
                        src="https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=1200" 
                        alt="Beautiful Eco Wedding Canopy" 
                        class="w-full h-full object-cover block"
                        referrerpolicy="no-referrer"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-[#2d4a22]/30 via-transparent to-transparent"></div>
                    <div class="absolute bottom-8 left-8 right-8 bg-white/90 backdrop-blur-md p-6 rounded-3xl border border-white/50 shadow-lg flex items-center justify-between">
                        <div class="block">
                            <p class="text-[10px] uppercase tracking-widest text-[#c5a059] font-bold mb-0.5">Featured Concept</p>
                            <p class="font-serif text-lg font-medium text-zinc-900 mt-0.5">Forest Grove Wild Canopy • Bali</p>
                        </div>
                        <span class="text-xs font-mono font-bold text-[#2d4a22]">100% Zero-Waste</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sustainable Values Grid -->
    <section class="py-24 max-w-7xl mx-auto px-6 block">
        <div class="text-center max-w-3xl mx-auto mb-20 block">
            <span class="text-xs font-bold tracking-[0.2em] text-[#c5a059] uppercase block mb-3">CONSCIOUS PLATFORM STEWARDSHIP</span>
            <h2 class="text-4xl lg:text-5xl font-serif text-[#2d4a22] leading-tight block">The Core Pillars of NaturaWed</h2>
            <p class="text-zinc-500 mt-4 text-lg block">
                We believe that planning your life's greatest milestone shouldn't come at the cost of our planet's pristine ecosystems. Here is what we secure with every booking:
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Pillar 1 -->
            <div class="bg-white rounded-[2.5rem] p-10 border border-zinc-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all flex gap-8 items-start group">
                <div class="bg-[#2d4a22]/10 p-5 rounded-2xl text-[#2d4a22] group-hover:bg-[#2d4a22] group-hover:text-white transition-all flex-shrink-0 flex items-center justify-center">
                    <i data-lucide="leaf" class="w-7 h-7"></i>
                </div>
                <div class="space-y-3 block">
                    <h3 class="text-2xl font-bold text-zinc-900 group-hover:text-[#2d4a22] transition-colors">100% Zero-Waste Focus</h3>
                    <p class="text-zinc-500 leading-relaxed text-base">Every vendor listed on our platform is committed to zero-waste banquet practices, sustainable compost cycles, and non-toxic setup materials.</p>
                </div>
            </div>

            <!-- Pillar 2 -->
            <div class="bg-white rounded-[2.5rem] p-10 border border-zinc-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all flex gap-8 items-start group">
                <div class="bg-[#2d4a22]/10 p-5 rounded-2xl text-[#2d4a22] group-hover:bg-[#2d4a22] group-hover:text-white transition-all flex-shrink-0 flex items-center justify-center">
                    <i data-lucide="shield-check" class="w-7 h-7"></i>
                </div>
                <div class="space-y-3 block">
                    <h3 class="text-2xl font-bold text-zinc-900 group-hover:text-[#2d4a22] transition-colors">Eco-Strict Vetting Team</h3>
                    <p class="text-zinc-500 leading-relaxed text-base">We perform comprehensive audits of carbon footprints, supply chains, and local labor practices before any wedding planner or florist is approved.</p>
                </div>
            </div>

            <!-- Pillar 3 -->
            <div class="bg-white rounded-[2.5rem] p-10 border border-zinc-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all flex gap-8 items-start group">
                <div class="bg-[#2d4a22]/10 p-5 rounded-2xl text-[#2d4a22] group-hover:bg-[#2d4a22] group-hover:text-white transition-all flex-shrink-0 flex items-center justify-center">
                    <i data-lucide="compass" class="w-7 h-7"></i>
                </div>
                <div class="space-y-3 block">
                    <h3 class="text-2xl font-bold text-zinc-900 group-hover:text-[#2d4a22] transition-colors">Authentic Visuals First</h3>
                    <p class="text-zinc-500 leading-relaxed text-base">No stock photos. We showcase real verified weddings, organic table scapes, and physical portfolios so you can buy the atmosphere, not just a price tag.</p>
                </div>
            </div>

            <!-- Pillar 4 -->
            <div class="bg-white rounded-[2.5rem] p-10 border border-zinc-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all flex gap-8 items-start group">
                <div class="bg-[#2d4a22]/10 p-5 rounded-2xl text-[#2d4a22] group-hover:bg-[#2d4a22] group-hover:text-white transition-all flex-shrink-0 flex items-center justify-center">
                    <i data-lucide="refresh-cw" class="w-7 h-7"></i>
                </div>
                <div class="space-y-3 block">
                    <h3 class="text-2xl font-bold text-zinc-900 group-hover:text-[#2d4a22] transition-colors">Carbon Offset Curation</h3>
                    <p class="text-zinc-500 leading-relaxed text-base">A calculated portion of every reservation goes directly into local reforestation efforts and marine ecosystem protection projects.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive Platform Statistics -->
    <section class="bg-white py-24 border-y border-zinc-100 block w-full">
        <div class="max-w-7xl mx-auto px-6 block">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                <div class="space-y-2 block">
                    <span class="text-5xl lg:text-6xl font-serif font-bold text-[#2d4a22]">1,200+ Tons</span>
                    <p class="text-xs font-bold tracking-widest text-[#c5a059] uppercase block mt-1">CARBON OFFSET ANNUALLY</p>
                    <p class="text-zinc-500 text-sm max-w-xs mx-auto">Verified carbon emission neutralization verified independently through accredited sustainable forestry initiatives.</p>
                </div>
                <div class="space-y-2 border-y md:border-y-0 md:border-x border-zinc-100 py-8 md:py-0 block">
                    <span class="text-5xl lg:text-6xl font-serif font-bold text-[#2d4a22]">100% Zero-Waste</span>
                    <p class="text-xs font-bold tracking-widest text-[#c5a059] uppercase block mt-1">CURATED BOTANICAL SETUPS</p>
                    <p class="text-zinc-500 text-sm max-w-xs mx-auto">Exclusive integration of seasonal potted plants, live root decorations, compostable mechanics, and non-foam framing.</p>
                </div>
                <div class="space-y-2 block">
                    <span class="text-5xl lg:text-6xl font-serif font-bold text-[#2d4a22]">480+ Weddings</span>
                    <p class="text-xs font-bold tracking-widest text-[#c5a059] uppercase block mt-1">ECO-CHIC MEMORIES DRAFTED</p>
                    <p class="text-zinc-500 text-sm max-w-xs mx-auto">Intimate beachside vows, high-fashion woodlands, and breathtaking luxury events completed across Southeast Asia & Bali.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-24 max-w-7xl mx-auto px-6 block">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="relative aspect-[3/4] max-h-[580px] rounded-[3.5rem] overflow-hidden shadow-2xl border-4 border-white lg:order-last block">
                <img 
                    src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=1200" 
                    alt="Artisanal Dining Set Up" 
                    class="w-full h-full object-cover block"
                    referrerpolicy="no-referrer"
                />
                <div class="absolute inset-0 bg-[#2d4a22]/10"></div>
                <div class="absolute bottom-8 left-8 right-8 bg-[#2d4a22] text-white p-8 rounded-[2.5rem] shadow-xl block">
                    <i data-lucide="sparkles" class="text-[#c5a059] mb-3 w-6 h-6"></i>
                    <p class="font-serif text-xl font-medium leading-relaxed italic block">
                        "Our wedding was not just visually breathtaking; knowing we set a zero-waste standard for our loved ones gave us true peace of mind."
                    </p>
                    <p class="text-[10px] uppercase tracking-widest opacity-70 mt-4 font-bold block">— Julianne & Mark, Eloped June 2024</p>
                </div>
            </div>

            <div class="space-y-12 block">
                <div class="block">
                    <span class="text-xs font-bold tracking-[0.2em] text-[#c5a059] uppercase block mb-3 font-semibold">THE USER JOURNEY</span>
                    <h2 class="text-4xl lg:text-5xl font-serif text-[#2d4a22] leading-tight block">How NaturaWed Works</h2>
                    <p class="text-zinc-500 mt-4 text-base block">
                        We've redesigned the wedding planning workspace to make sustainable decision-making effortless, luxurious, and beautifully interactive for modern wedding couples.
                    </p>
                </div>

                <div class="space-y-10 block">
                    <!-- Step 1 -->
                    <div class="flex gap-6 items-start">
                        <span class="text-4xl font-serif font-black text-[#c5a059] leading-none opacity-60 flex-shrink-0">01</span>
                        <div class="space-y-1.5 block">
                            <h4 class="text-xl font-bold text-zinc-900 block">Explore Tailored Inspirations</h4>
                            <p class="text-zinc-500 leading-relaxed text-sm block">Browse the 'Inspiration' gallery of gorgeous, sustainable real weddings. Filter by ocean breezes, woodland reserves, or glasshouse ballrooms to find your perfect style.</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex gap-6 items-start">
                        <span class="text-4xl font-serif font-black text-[#c5a059] leading-none opacity-60 flex-shrink-0">02</span>
                        <div class="space-y-1.5 block">
                            <h4 class="text-xl font-bold text-zinc-900 block">Match Approved Local Artisans</h4>
                            <p class="text-zinc-500 leading-relaxed text-sm block">Read extensive vendor portfolios, view verified customer reviews, meet their core creative team, and see original photographs of past celebrations.</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex gap-6 items-start">
                        <span class="text-4xl font-serif font-black text-[#c5a059] leading-none opacity-60 flex-shrink-0">03</span>
                        <div class="space-y-1.5 block">
                            <h4 class="text-xl font-bold text-zinc-900 block">Eco-Material Checkout</h4>
                            <p class="text-zinc-500 leading-relaxed text-sm block">Our streamlined, transparent checkout specifies organic flower elements, local artisan catering options, and carbon-offset metrics explicitly.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sustainable Certification Badge Section -->
    <section class="bg-zinc-100 py-16 text-center border-t border-zinc-200 block w-full">
        <div class="max-w-4xl mx-auto px-6 block">
            <p class="text-[10px] font-bold tracking-[0.25em] text-zinc-400 uppercase mb-4">PLATFORM ACCREDITATIONS & CREDENTIALS</p>
            <div class="flex flex-wrap justify-center items-center gap-10 md:gap-16 opacity-50 grayscale hover:grayscale-0 transition-all duration-500 block">
                <span class="text-xs font-mono font-bold tracking-widest uppercase">GLOBAL ECO TRUSTEE</span>
                <span class="text-xs font-mono font-bold tracking-widest uppercase text-[#2d4a22]">★ ZERO-WASTE ALLIANCE</span>
                <span class="text-xs font-mono font-bold tracking-widest uppercase">CARBON OFFSET INTL</span>
                <span class="text-xs font-mono font-bold tracking-widest uppercase text-[#2d4a22]">GREEN BRIDE GUIDE</span>
            </div>
        </div>
    </section>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Render otomatis seluruh icon Lucide yang dipanggil via data-lucide
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection