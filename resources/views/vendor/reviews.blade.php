<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Management - NaturaWed</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body>
    <div class="min-h-screen flex bg-white font-sans text-[#1a1a1a]">
        
        @include('layouts.vendor_sidebar')

        <main class="flex-1 flex flex-col overflow-y-auto">
            <header class="h-20 px-12 flex items-center justify-between sticky top-0 bg-white/80 backdrop-blur-md z-10 border-b border-gray-100">
                <h2 class="text-xl font-serif italic text-[#2d3e2d]">Review Management</h2>
            </header>

            <div class="px-12 py-10 max-w-6xl mx-auto w-full">
                <!-- Page Header -->
                <div class="mb-8">
                    <h1 class="text-4xl font-serif text-[#2d3e2d] mb-2">Reviews & Feedback</h1>
                    <p class="text-gray-500">Read and respond to your clients' feedback. Each response helps build trust with future customers.</p>
                </div>

                <!-- Stats Banner -->
                <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-[#f8f9fa] rounded-2xl p-6 border border-gray-100">
                        <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-3">Total Reviews</p>
                        <p class="text-3xl font-serif font-bold text-[#2d3e2d]">{{ $reviews->total() }}</p>
                    </div>
                    <div class="bg-[#f8f9fa] rounded-2xl p-6 border border-gray-100">
                        <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-3">Replied</p>
                        <p class="text-3xl font-serif font-bold text-[#2d3e2d]">{{ $reviews->getCollection()->filter(fn($r) => $r->vendor_reply)->count() }}</p>
                    </div>
                    <div class="bg-[#f8f9fa] rounded-2xl p-6 border border-gray-100">
                        <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-3">Pending Reply</p>
                        <p class="text-3xl font-serif font-bold text-[#2d3e2d]">{{ $reviews->getCollection()->filter(fn($r) => !$r->vendor_reply)->count() }}</p>
                    </div>
                </div>

                <!-- Reviews List -->
                <div class="space-y-6">
                    @forelse($reviews as $review)
                        <div class="bg-white rounded-3xl border border-gray-200 p-8 shadow-sm hover:shadow-md transition-shadow">
                            <!-- Review Header -->
                            <div class="flex justify-between items-start mb-6">
                                <div class="flex gap-4">
                                    <img src="https://ui-avatars.com/api/?name=Anonymous&background=f0f2f0&color=2d3e2d&rounded=true&bold=true" 
                                        alt="Anonymous Customer" 
                                        class="w-14 h-14 rounded-full object-cover border border-gray-200 p-1 flex-shrink-0" />
                                    <div>
                                        <h3 class="font-bold text-sm text-[#2d3e2d]">{{ $review->masked_name }}</h3>
                                        <div class="flex gap-0.5 mt-2">
                                            @for($i = 0; $i < $review->rating; $i++)
                                                <i data-lucide="star" class="text-yellow-400 fill-yellow-400 w-4 h-4"></i>
                                            @endfor
                                            @for($i = $review->rating; $i < 5; $i++)
                                                <i data-lucide="star" class="text-gray-300 w-4 h-4"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">{{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</span>
                            </div>

                            <!-- Customer Comment -->
                            @if($review->comment)
                                <p class="text-sm text-gray-700 leading-relaxed mb-6 italic">
                                    "{{ $review->comment }}"
                                </p>
                            @endif

                            <!-- Vendor Reply Display -->
                            @if($review->vendor_reply)
                                <div class="mt-6 pl-4 border-l-2 border-[#2d4a22] bg-zinc-50 p-4 rounded-r-2xl mb-6">
                                    <p class="text-[10px] font-bold text-[#2d4a22] uppercase tracking-widest mb-2">Your Reply</p>
                                    <p class="text-sm text-gray-700">{{ $review->vendor_reply }}</p>
                                    <p class="text-[9px] text-gray-400 mt-2">{{ \Carbon\Carbon::parse($review->replied_at)->diffForHumans() }}</p>
                                </div>
                            @endif

                            <!-- Quick Reply Section -->
                            <div x-data="{ replyOpen: false }" class="mt-6 pt-6 border-t border-gray-100">
                                @if(!$review->vendor_reply)
                                    <div class="flex gap-3 mb-4">
                                        <button 
                                            @click="replyOpen = !replyOpen"
                                            class="px-6 py-2.5 bg-[#2d3e2d] text-white rounded-full text-[10px] font-bold tracking-widest uppercase hover:opacity-90 transition-opacity">
                                            Write Reply
                                        </button>
                                    </div>

                                    <!-- Reply Form -->
                                    <form action="{{ route('vendor.reviews.reply', $review->id) }}" method="POST" x-show="replyOpen" class="mt-4" x-transition>
                                        @csrf
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Your Response</label>
                                        <textarea 
                                            name="vendor_reply" 
                                            placeholder="Share your thoughts on this review and thank the customer..."
                                            maxlength="1000"
                                            required
                                            @input="$dispatch('input')"
                                            class="w-full bg-white border border-gray-200 rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2d3e2d]/20 focus:border-[#2d3e2d] outline-none transition-all resize-none h-24">{{ old('vendor_reply') }}</textarea>
                                        <p class="text-xs text-gray-400 mt-2"><span class="char_count" data-review-id="{{ $review->id }}">0</span>/1000 characters</p>
                                        <div class="flex gap-3 mt-4">
                                            <button type="submit" class="px-6 py-2.5 bg-[#2d3e2d] text-white rounded-full text-[10px] font-bold tracking-widest uppercase hover:opacity-90 transition-opacity">
                                                Post Reply
                                            </button>
                                            <button type="button" @click="replyOpen = false" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-500 rounded-full text-[10px] font-bold tracking-widest uppercase hover:bg-gray-50 transition-colors">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    <p class="text-xs text-gray-400 italic">You have already replied to this review.</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center">
                            <i data-lucide="star" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                            <p class="text-lg font-semibold text-gray-600 mb-2">No Reviews Yet</p>
                            <p class="text-sm text-gray-500">When clients leave reviews, they'll appear here. Keep delivering excellent service!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $reviews->links() }}
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            
            // Character counter for reply textareas
            document.querySelectorAll('textarea[name="vendor_reply"]').forEach(textarea => {
                const updateCounter = () => {
                    const reviewId = textarea.closest('[x-data*="replyOpen"]')?.parentElement?.querySelector('.char_count')?.getAttribute('data-review-id');
                    const counter = document.querySelector(`.char_count[data-review-id="${reviewId}"]`);
                    if (counter) {
                        counter.textContent = textarea.value.length;
                    }
                };
                
                textarea.addEventListener('input', updateCounter);
            });
        });
    </script>
</body>
</html>
