<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }} - NaturaWed</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#faf9f6] min-h-screen font-sans text-gray-900 flex flex-col">

    <div class="flex-1">
        <div class="relative w-full h-[60vh] md:h-[80vh] overflow-hidden">
            <img src="{{ !empty($article->image_url) ? asset($article->image_url) : 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&q=80' }}" 
                 alt="Article Hero" class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-black/20"></div>
            
            <a href="{{ url('/') }}" class="absolute top-8 left-8 w-12 h-12 rounded-full bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-white hover:bg-white/40 transition-all group z-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-0.5 transition-transform"><path d="m15 18-6-6 6-6"/></svg>
            </a>
        </div>

        <div class="max-w-4xl mx-auto px-6 -mt-32 relative z-10 bg-[#faf9f6] rounded-t-[48px] pt-20 pb-20 shadow-[0_-8px_30px_rgb(0,0,0,0.04)]">
            <div class="text-center mb-16">
                <span class="text-[10px] font-semibold tracking-[0.3em] uppercase text-zinc-400 mb-6 block">
                    {{ $article->category ?? 'Atelier Journals' }}
                </span>
                
                <h1 class="text-3xl md:text-5xl font-serif leading-tight mb-10 max-w-3xl mx-auto text-[#2d3e2d]">
                    {{ $article->title }}
                </h1>
                
                <div class="flex flex-col items-center space-y-2">
                    <p class="text-xs font-semibold tracking-wider text-zinc-600">By {{ $article->author_name }}</p>
                    <p class="text-[10px] text-zinc-400 tracking-widest uppercase">
                        {{ \Carbon\Carbon::parse($article->created_at)->format('F j, Y') }}
                    </p>
                </div>
            </div>

            <div class="prose prose-zinc max-w-none text-lg leading-relaxed text-zinc-700 text-justify">
                <p>{!! nl2br(e($article->content)) !!}</p>
            </div>

            <a href="{{ route('customer.author.profile', $article->journalist_id) }}" class="mt-24 pt-10 border-t border-zinc-200 flex items-start space-x-6 group cursor-pointer hover:bg-zinc-50 p-6 rounded-3xl transition-all -mx-6">
                
                <div class="w-16 h-16 rounded-full overflow-hidden flex-shrink-0 bg-zinc-200 border-2 border-transparent group-hover:border-[#2d4a22] transition-all">
                    <img src="{{ !empty($article->profile_image) ? asset($article->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode($article->author_name).'&background=2d3e2d&color=fff' }}" 
                         alt="Author" class="w-full h-full object-cover" />
                </div>
                
                <div>
                    <h4 class="text-lg font-serif mb-1 text-[#2d3e2d] group-hover:text-[#1e3317]">{{ $article->author_name }}</h4>
                    <p class="text-sm text-zinc-500 leading-relaxed max-w-xl">
                        View full profile and explore more editorial inspirations curated by {{ $article->author_name }}. &rarr;
                    </p>
                </div>
            </a>
        </div>
    </div>

    <footer class="w-full bg-white border-t border-zinc-200 py-10 mt-auto">
        <div class="text-center">
            <h2 class="text-xl font-serif text-[#2d3e2d] mb-2">NaturaWed</h2>
            <p class="text-[10px] text-zinc-400 tracking-widest uppercase mb-4">Editorial & Inspiration</p>
            <p class="text-xs text-zinc-500">&copy; {{ date('Y') }} NaturaWed. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>