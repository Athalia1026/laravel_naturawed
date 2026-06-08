
@extends('layouts.customer')

@section('content')
<main class="min-h-screen bg-white font-sans text-gray-900">
    <div class="max-w-7xl mx-auto px-6 py-12">
        
        {{-- HERO SECTION: FEATURED ARTICLE --}}
        @if (!empty($featuredArticle))
        <a href="{{ route('articles.show', $featuredArticle->id) }}" class="relative block h-[600px] w-full rounded-3xl overflow-hidden cursor-pointer group mb-24 shadow-sm">
            <img 
                src="{{ asset($featuredArticle->image_url) }}" 
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

        {{-- SECTION HEADER & FILTER --}}
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

        {{-- GRID SECTION: OTHER ARTICLES --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-20 mb-24">
            @forelse ($otherArticles as $article)
                <a href="{{ route('articles.show', $article->id) }}" class="group block cursor-pointer">
                    <div class="relative aspect-[4/5] rounded-2xl overflow-hidden mb-6 bg-zinc-100">
                        <img 
                            src="{{ asset($article->image_url) }}" 
                            alt="{{ $article->title }}"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            referrerpolicy="no-referrer"
                        />
                        
                        {{-- Logika Dummy Bookmark --}}
                        <button onclick="event.preventDefault();" class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-zinc-900 shadow-sm hover:bg-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                        </button>
                    </div>
                    
                    <h3 class="text-xl font-serif leading-snug mb-4 group-hover:text-zinc-600 transition-colors line-clamp-2">
                        {{ $article->title }}
                    </h3>
                    
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-zinc-200 overflow-hidden shrink-0">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($article->author_name) }}&background=random" alt="{{ $article->author_name }}" class="w-full h-full object-cover" />
                        </div>
                        <div class="text-[10px] tracking-wider uppercase font-semibold">
                            <p class="text-zinc-900">{{ $article->author_name }}</p>
                            <p class="text-zinc-400">{{ \Carbon\Carbon::parse($article->created_at)->format('d M Y') }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-20 text-center bg-zinc-50 rounded-[2.5rem] border border-dashed border-zinc-200">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-zinc-300 mb-4 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8l-4 4v14a2 2 0 0 0 2 2z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path></svg>
                    </div>
                    <h3 class="text-xl font-serif text-zinc-800 mb-2">No Inspiration Found</h3>
                    <p class="text-zinc-500 text-sm max-w-sm">Our journalists are currently curating new stories. Check back soon for more wedding inspirations.</p>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION (Opsional / Dummy UI) --}}
        @if(count($otherArticles) > 0)
        <div class="flex items-center justify-center space-x-2 mb-24">
            <button class="w-10 h-10 rounded-full flex items-center justify-center text-zinc-400 hover:text-zinc-900 hover:bg-zinc-100 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <button class="w-10 h-10 rounded-full bg-[#2d4a22] text-white text-sm font-semibold">1</button>
            <button class="w-10 h-10 rounded-full text-zinc-400 hover:text-zinc-900 hover:bg-zinc-100 transition-all text-sm font-semibold">2</button>
            <button class="w-10 h-10 rounded-full text-zinc-400 hover:text-zinc-900 hover:bg-zinc-100 transition-all text-sm font-semibold">3</button>
            <span class="px-2 text-zinc-300">...</span>
            <button class="w-10 h-10 rounded-full text-zinc-400 hover:text-zinc-900 hover:bg-zinc-100 transition-all text-sm font-semibold">12</button>
            <button class="w-10 h-10 rounded-full flex items-center justify-center text-zinc-400 hover:text-zinc-900 hover:bg-zinc-100 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </button>
        </div>
        @endif
        
    </div>
</main>
@endsection