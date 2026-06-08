<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article - NaturaWed</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="flex min-h-screen bg-white font-sans text-[#1a1a1a]">

    @include('layouts.journalist_sidebar')

    <main class="flex-1 bg-white overflow-y-auto">
        
        <header class="px-12 py-10 border-b border-gray-50 flex items-center justify-between sticky top-0 bg-white/90 backdrop-blur-md z-10">
            <div>
                <h2 class="text-2xl font-serif font-bold text-[#2d3e2d]">Edit Inspiration Article</h2>
                <p class="text-xs text-gray-500 mt-1">Updating: <span class="font-medium text-gray-800">{{ Str::limit($article->title, 40) }}</span></p>
            </div>
            
            <div class="flex items-center gap-4">
                <a href="{{ route('journalist.dashboard') }}" class="px-6 py-2.5 text-gray-500 hover:text-gray-800 text-xs font-bold tracking-widest uppercase transition-colors decoration-none">
                    Cancel
                </a>
                <button type="submit" form="articleForm" class="px-6 py-2.5 bg-[#2d3e2d] text-white rounded-full text-xs font-bold tracking-widest uppercase hover:bg-[#1e291e] transition-colors shadow-md flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Save Changes
                </button>
            </div>
        </header>

        <div class="px-12 py-10 max-w-4xl">
            
            {{-- Alert Error (Jika validasi gagal) --}}
            @if ($errors->any())
                <div class="mb-8 p-6 bg-red-50 rounded-2xl border border-red-100">
                    <div class="flex items-center gap-2 text-red-600 font-bold mb-2 text-sm uppercase tracking-widest">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i> Please check your inputs
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-500 pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form mengarah ke route UPDATE dengan method POST --}}
            <form id="articleForm" enctype="multipart/form-data" action="{{ route('journalist.article.update', $article->id) }}" method="POST" class="bg-white rounded-[2rem] p-10 shadow-[0_0_40px_rgba(0,0,0,0.03)] border border-gray-50">
                @csrf
                
                <div class="space-y-8">
                    <div>
                        <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-3">Article Title</label>
                        <input type="text" name="title" value="{{ old('title', $article->title) }}" placeholder="e.g. The Art of Subtlety: Designing a Minimalist Forest Ceremony" required
                               class="w-full px-6 py-4 bg-[#f8f9fa] border-none rounded-xl text-gray-900 focus:ring-1 focus:ring-[#2d3e2d]/20 transition-all outline-none text-base placeholder-gray-400 font-serif" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-3">Author Name</label>
                            <input type="text" name="author" value="{{ old('author', $article->author_name) }}" placeholder="e.g. Isabella Thorne" required
                                   class="w-full px-6 py-4 bg-[#f8f9fa] border-none rounded-xl text-gray-900 focus:ring-1 focus:ring-[#2d3e2d]/20 transition-all outline-none text-sm placeholder-gray-400" />
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-3">Category / Tag</label>
                            <input type="text" name="category" value="{{ old('category', $article->category) }}" placeholder="e.g. EDITORIAL" required
                                   class="w-full px-6 py-4 bg-[#f8f9fa] border-none rounded-xl text-gray-900 focus:ring-1 focus:ring-[#2d3e2d]/20 transition-all outline-none text-sm placeholder-gray-400 uppercase" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-3">Cover Image File</label>
                        
                        {{-- Preview Gambar Saat Ini --}}
                        <div class="flex items-center gap-6 p-4 bg-[#f8f9fa] rounded-xl mb-4 border border-gray-100">
                            <div class="w-20 h-20 rounded-lg overflow-hidden shrink-0 bg-gray-200 shadow-sm border border-white">
                                <img src="{{ $article->image_url ? asset($article->image_url) : 'https://picsum.photos/200' }}" alt="Current Cover" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-700 mb-1">Current Image</p>
                                <p class="text-[10px] text-gray-400 leading-snug">Uploading a new file below will replace this cover. Leave it empty if you want to keep the current one.</p>
                            </div>
                        </div>

                        <div class="relative">
                            {{-- Input file sekarang TANPA required --}}
                            <input type="file" name="cover_image" accept="image/*"
                                class="w-full px-6 py-4 bg-[#f8f9fa] border-none rounded-xl text-gray-900 focus:ring-1 focus:ring-[#2d3e2d]/20 transition-all outline-none text-sm cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#2d3e2d] file:text-white hover:file:bg-[#1e291e]" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-3">Article Content</label>
                        {{-- Nilai textarea diletakkan di antara tag pembuka dan penutup --}}
                        <textarea name="content" rows="14" placeholder="Write your inspiration article here..." required
                                  class="w-full px-6 py-6 bg-[#f8f9fa] border-none rounded-xl text-gray-900 focus:ring-1 focus:ring-[#2d3e2d]/20 transition-all outline-none resize-none text-sm placeholder-gray-400 leading-relaxed">{{ old('content', $article->content) }}</textarea>
                    </div>
                </div>

            </form>
        </div>

    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>