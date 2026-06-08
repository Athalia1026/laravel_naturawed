<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $author->name }} - NaturaWed</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white min-h-screen font-sans text-gray-900">

    <nav class="absolute top-0 w-full p-8 z-50">
        <a href="{{ url('/') }}" class="text-white text-sm font-bold tracking-widest uppercase flex items-center gap-2 hover:opacity-80">
            &larr; Back to Inspiration
        </a>
    </nav>

    <div class="w-full h-[45vh] bg-[#2d3e2d] relative">
        @if(!empty($author->header_image))
            <img src="{{ asset($author->header_image) }}" class="w-full h-full object-cover opacity-80" alt="Cover">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent"></div>
    </div>

    <div class="max-w-6xl mx-auto px-6 relative -mt-24 mb-24 flex flex-col items-center text-center">
        <div class="w-40 h-40 rounded-full border-8 border-white overflow-hidden bg-zinc-200 mb-6 shadow-md">
            <img src="{{ !empty($author->profile_image) ? asset($author->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode($author->name).'&background=2d3e2d&color=fff' }}" 
                 class="w-full h-full object-cover" alt="{{ $author->name }}">
        </div>

        <h1 class="text-5xl font-serif text-[#2d3e2d] mb-4">{{ $author->name }}</h1>
        <p class="text-[11px] font-bold tracking-[0.2em] uppercase text-zinc-400 mb-6">NaturaWed Editorial Journalist</p>
        
        <p class="text-zinc-600 max-w-2xl leading-relaxed">
            {{ $author->bio ?? 'No biography available yet. Enjoy the curated inspirations below.' }}
        </p>
    </div>

    <div class="max-w-7xl mx-auto px-6 mb-32">
        <h2 class="text-3xl font-serif mb-10 text-[#2d3e2d] border-b border-zinc-100 pb-4">Articles by {{ $author->name }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse ($articles as $art)
                <a href="{{ route('articles.show', $art->id) }}" class="group bg-white rounded-[1.5rem] overflow-hidden border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col">
                    
                    <div class="h-48 w-full overflow-hidden relative shrink-0 bg-gray-100">
                        <img src="{{ !empty($art->image_url) ? asset($art->image_url) : 'https://picsum.photos/600/400' }}" 
                             alt="{{ $art->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-full text-[9px] font-bold uppercase tracking-widest text-[#2d3e2d]">
                            {{ $art->category }}
                        </div>
                    </div>

                    <div class="p-6 flex flex-col justify-between flex-1">
                        <h3 class="text-xl font-serif text-gray-900 leading-snug mb-3 line-clamp-2 group-hover:text-[#2d4a22] transition-colors">
                            {{ $art->title }}
                        </h3>
                        <p class="text-[10px] tracking-wider uppercase font-semibold text-zinc-400 mt-auto">
                            {{ \Carbon\Carbon::parse($art->created_at)->format('d M Y') }} &nbsp;&bull;&nbsp; {{ $art->views_count ?? 0 }} Views
                        </p>
                    </div>
                </a>
            @empty
                <p class="text-zinc-500 col-span-full py-12 text-center">Belum ada artikel yang dipublikasikan.</p>
            @endforelse
        </div>
    </div>

</body>
</html>