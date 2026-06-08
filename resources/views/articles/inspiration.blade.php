@extends('layouts.customer')

@section('content')
<main class="min-h-screen bg-white font-sans text-gray-900">
    <div class="max-w-7xl mx-auto px-6 py-12">
        
        @if ($featuredArticle)
        <a href="{{ route('customer.inspiration') }}?id={{ $featuredArticle->id }}" class="relative block h-[600px] w-full rounded-3xl overflow-hidden cursor-pointer group mb-24">
            <img 
                src="{{ $featuredArticle->image_url ? asset($featuredArticle->image_url) : 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&q=80' }}" 
                alt="{{ $featuredArticle->title }}"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                referrerpolicy="no-referrer"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
            
            <div class="absolute bottom-12 left-12 right-12 text-white">
                <span class="inline-block px-3 py-1 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full text-[10px] font-semibold tracking-[0.2em] uppercase mb-6">
                    {{ $featuredArticle->category }}
                </span>
                <h1 class="text-5xl md:text-6xl font-serif max-w-3xl leading-tight mb-8">
                    {{ $featuredArticle->title }}
                </h1>
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 rounded-full bg-zinc-400 overflow-hidden border border-white/20">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($featuredArticle->author_name) }}&background=random" alt="{{ $featuredArticle->author_name }}" class="w-full h-full object-cover" />
                    </div>
                    <div class="text-[11px] tracking-wide">
                        <p class="font-semibold">{{ $featuredArticle->author_name }}</p>
                        <p class="opacity-60">{{ \Carbon\Carbon::parse($featuredArticle->created_at)->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </a>
        @endif

        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-8">
            <div>
                <h2 class="text-4xl font-serif mb-4">Curated Inspiration</h2>
                <p class="text-zinc-500 max-w-md leading-relaxed">
                    Explore our weekly selection of artisanal celebrations, timeless decor concepts, and editorial bridal journals.
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <button class="px-6 py-2.5 rounded-full border border-zinc-200 text-xs font-semibold tracking-wider hover:bg-zinc-50 transition-colors flex items-center space-x-2">
                    <span>Refine</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="-rotate-90"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <button class="px-6 py-2.5 rounded-full border border-zinc-200 text-xs font-semibold tracking-wider hover:bg-zinc-50 transition-colors flex items-center space-x-2">
                    <span>Latest</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="-rotate-90"><path d="m15 18-6-6 6-6"/></svg>
                </button>
            </div>
        </div>

        <!-- Container Grid Utama: Membagi layar jadi 3 kolom menyamping -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-10">
    
    @forelse($otherArticles as $article)
        <!-- Kartu Artikel (Bentuk Persegi Panjang Tidur Kecil) -->
        <a href="{{ route('customer.article.show', $article->id ?? $article->id) ?? '#' }}" class="group bg-white rounded-[1.5rem] overflow-hidden border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col">
            
            <!-- Area Gambar: Dikunci tingginya (h-48) agar bentuknya memanjang (landscape) -->
            <div class="h-48 w-full overflow-hidden relative shrink-0 bg-gray-100">
                <img src="{{ !empty($article->image_url) ? asset($article->image_url) : 'https://picsum.photos/600/400' }}" 
                     alt="{{ $article->title }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                
                <!-- Tag Bookmark / Kategori di Kanan Atas -->
                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-full text-[9px] font-bold uppercase tracking-widest text-[#2d3e2d] shadow-sm">
                    {{ $article->category ?? 'Editorial' }}
                </div>
            </div>

            <!-- Area Teks Konten -->
            <div class="p-6 flex flex-col justify-between flex-1">
                <h3 class="text-xl font-serif text-gray-900 leading-snug mb-3 line-clamp-2 group-hover:text-[#2d4a22] transition-colors">
                    {{ $article->title }}
                </h3>
                
                <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mt-auto">
                    By {{ $article->author_name }} &nbsp;•&nbsp; 
                    {{ \Carbon\Carbon::parse($article->created_at)->format('d M Y') }}
                </p>
            </div>
        </a>
    @empty
        <!-- Pesan kalau belum ada artikel -->
        <div class="col-span-3 py-12 text-center text-gray-500">
            No inspiration articles available at the moment.
        </div>
    @endforelse

</div>
        
    </div>
</main>
@endsection