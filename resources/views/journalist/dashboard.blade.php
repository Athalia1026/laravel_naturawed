<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspiration Articles - NaturaWed</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="flex min-h-screen bg-white font-sans text-[#1a1a1a]">

    {{-- Memanggil Sidebar Khusus Jurnalis --}}
    @include('layouts.journalist_sidebar')

    <main class="flex-1 p-12 overflow-y-auto bg-[#f8f9fa]">
        
        <header class="flex justify-between items-start mb-8">
            <div>
                <h2 class="text-4xl font-serif text-[#2d3e2d] mb-2">Inspiration Articles</h2>
                <p class="text-gray-500 text-sm">Manage your published editorial content and wedding inspirations.</p>
            </div>
            
            <a href="{{ route('journalist.article.create') ?? '#' }}" class="flex items-center gap-2 px-6 py-3.5 bg-[#2a3f24] text-white rounded-xl text-xs font-bold tracking-widest uppercase hover:opacity-90 transition-all shadow-lg hover:shadow-xl active:scale-95">
                <i data-lucide="plus" class="w-4 h-4"></i> Write Article
            </a>
        </header>

        <div class="flex items-center gap-4 mb-10">
            <div class="relative flex-1">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                <input type="text" placeholder="Search articles by title or category..." 
                       class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#2d3e2d] focus:ring-1 focus:ring-[#2d3e2d] transition-all shadow-sm">
            </div>
            <button class="flex items-center gap-2 px-6 py-3.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm active:scale-95">
                <i data-lucide="filter" class="w-4 h-4 text-gray-500"></i> Filters
            </button>
        </div>

        @if (!empty($myArticles) && count($myArticles) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                @foreach ($myArticles as $article)
                    <div class="group bg-white rounded-[2rem] overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                        
                        <div class="relative h-56 overflow-hidden bg-gray-100 shrink-0">
                            <img src="{{ $article['image_url'] ?? 'https://picsum.photos/600/400' }}" 
                                 alt="{{ $article['title'] }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" 
                                 referrerpolicy="no-referrer">
                            
                            <div class="absolute top-4 right-4 px-3 py-1.5 bg-white/95 backdrop-blur-md rounded-lg text-[9px] font-bold tracking-widest text-[#2d3e2d] uppercase shadow-sm border border-gray-100">
                                {{ $article['category'] ?? 'Uncategorized' }}
                            </div>
                        </div>

                        <div class="p-6 flex items-start justify-between flex-1">
                            <div class="pr-4">
                                <h3 class="text-xl font-serif text-gray-900 mb-2 leading-snug line-clamp-2">
                                    {{ $article['title'] }}
                                </h3>
                                <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">
                                    By {{ $article['author_name'] ?? 'Journalist' }} &nbsp;•&nbsp; 
                                    {{ \Carbon\Carbon::parse($article['created_at'] ?? now())->format('d M Y') }}
                                </p>
                            </div>
                            
                            <button class="text-gray-400 hover:text-[#2d3e2d] hover:bg-gray-50 p-2 rounded-full transition-colors shrink-0">
                                <i data-lucide="more-vertical" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="w-full bg-white rounded-[2.5rem] border border-gray-100 border-dashed p-16 flex flex-col items-center justify-center text-center shadow-sm">
                <div class="w-20 h-20 bg-[#f0f2f0] rounded-full flex items-center justify-center text-[#2d3e2d] mb-6">
                    <i data-lucide="newspaper" class="w-10 h-10"></i>
                </div>
                <h3 class="text-2xl font-serif text-gray-900 mb-2">No Articles Published</h3>
                <p class="text-gray-500 text-sm max-w-md mb-8">You haven't written any inspiration articles yet. Start sharing wedding ideas and tips to inspire our customers.</p>
                <a href="{{ route('journalist.article.create') ?? '#' }}" class="px-8 py-4 bg-[#2a3f24] text-white rounded-xl text-xs font-bold tracking-widest uppercase hover:opacity-90 transition-all shadow-lg active:scale-95">
                    Write First Article
                </a>
            </div>
        @endif

    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>