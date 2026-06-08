@php
    // Set judul tab browser secara dinamis
    $pageTitle = ($article->title ?? 'Article') . " - NaturaWed";
@endphp

{{-- Gunakan Master Layout Customer agar Header & Footer otomatis terpasang --}}
@extends('layouts.customer')

@section('content')
<div class="bg-[#faf9f6] min-h-screen"> 
    
    {{-- HERO SECTION --}}
    <div class="relative w-full h-[80vh] overflow-hidden">
        <img 
            src="{{ $article->image_url ? asset($article->image_url) : 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&q=80' }}" 
            alt="Article Hero"
            class="w-full h-full object-cover"
            referrerpolicy="no-referrer"
        />
        <div class="absolute inset-0 bg-black/20"></div>
        
        {{-- TOMBOL KEMBALI KE INSPIRATION --}}
        <a 
            href="{{ route('inspiration') }}"
            class="absolute top-8 left-8 w-12 h-12 rounded-full bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-white hover:bg-white/40 transition-all group z-50"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-0.5 transition-transform"><path d="m15 18-6-6 6-6"/></svg>
        </a>
    </div>

    {{-- KONTEN ARTIKEL --}}
    <div class="max-w-4xl mx-auto px-6 -mt-32 relative z-10 bg-[#faf9f6] rounded-t-[48px] pt-20 pb-32">
        <div class="text-center mb-20">
            <span class="text-[10px] font-semibold tracking-[0.3em] uppercase text-zinc-400 mb-6 block">
                {{ $article->category ?? 'Atelier Journals' }}
            </span>
            <h1 class="text-5xl md:text-7xl font-serif leading-[1.1] mb-12 max-w-3xl mx-auto text-[#2d4a22]">
                {{ $article->title ?? 'Untitled Article' }}
            </h1>
            <div class="flex flex-col items-center space-y-2">
                <p class="text-xs font-semibold tracking-wider text-zinc-700">By {{ $article->author_name ?? 'Journalist' }}</p>
                <p class="text-[10px] text-zinc-400 tracking-widest uppercase">
                    {{ \Carbon\Carbon::parse($article->created_at ?? now())->format('F j, Y') }}
                </p>
            </div>
        </div>

        {{-- PARAGRAF ARTIKEL --}}
        <div class="prose prose-zinc max-w-none text-lg leading-relaxed text-zinc-700">
            <p>{!! nl2br(e($article->content ?? '')) !!}</p>
        </div>

        {{-- AUTHOR BIO --}}
        <div class="mt-32 pt-16 border-t border-zinc-200 flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8 text-center md:text-left">
            <div class="w-20 h-20 rounded-full overflow-hidden flex-shrink-0 bg-zinc-200 shadow-md">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($article->author_name ?? 'Author') }}&background=random&color=2d4a22&background=f0f2f0" alt="Author" class="w-full h-full object-cover" />
            </div>
            <div>
                <h4 class="text-lg font-serif mb-2 text-zinc-900">{{ $article->author_name ?? 'Author' }}</h4>
                <p class="text-sm text-zinc-500 leading-relaxed max-w-xl">
                    NaturaWed Editorial Voice. Specializing in the intersection of traditional craftsmanship and modern sustainable wedding aesthetics.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection