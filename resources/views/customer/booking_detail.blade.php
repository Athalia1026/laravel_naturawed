@extends('layouts.customer')

@section('content')
<div class="min-h-screen bg-[#f9f8f3] font-sans text-[#2d4a22] pb-20">
    <main class="max-w-7xl mx-auto px-6 py-12">
        
        <!-- Header -->
        <div class="mb-12">
            <a href="{{ route('customer.bookings.history') }}" class="inline-flex items-center text-[#2d4a22] hover:text-[#1e3317] mb-6 text-sm font-semibold tracking-wide decoration-none transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to History
            </a>
            <h1 class="text-6xl font-serif mb-2">Booking Overview</h1>
            <p class="text-zinc-600">Review all the details of your wedding experience booking</p>
        </div>

        <!-- Status Banners -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-12">
            <!-- Booking Status Badge -->
            <div class="bg-white rounded-2xl p-6 border border-zinc-200 shadow-sm">
                <p class="text-[10px] uppercase text-zinc-500 font-semibold tracking-widest mb-2">Booking Status</p>
                @php
                    $bookingStatusClass = "bg-zinc-500";
                    $bookingStatusLabel = "Unknown";
                    $bookingStatusIcon = "📋";
                    
                    if($booking->booking_status === 'pending_review') {
                        $bookingStatusClass = "bg-amber-500";
                        $bookingStatusLabel = "Awaiting Vendor Approval";
                        $bookingStatusIcon = "⏳";
                    } elseif($booking->booking_status === 'approved') {
                        $bookingStatusClass = "bg-[#2d4a22]";
                        $bookingStatusLabel = "Approved by Vendor";
                        $bookingStatusIcon = "✓";
                    } elseif($booking->booking_status === 'rejected') {
                        $bookingStatusClass = "bg-red-500";
                        $bookingStatusLabel = "Declined by Vendor";
                        $bookingStatusIcon = "✕";
                    }
                @endphp
                <div class="inline-block {{ $bookingStatusClass }} text-white px-4 py-2 rounded-xl text-sm font-bold">
                    {{ $bookingStatusIcon }} {{ $bookingStatusLabel }}
                </div>
            </div>

            <!-- Payment Status Badge -->
            <div class="bg-white rounded-2xl p-6 border border-zinc-200 shadow-sm">
                <p class="text-[10px] uppercase text-zinc-500 font-semibold tracking-widest mb-2">Payment Status</p>
                @php
                    $paymentStatusClass = "bg-zinc-500";
                    $paymentStatusLabel = "Unpaid";
                    $paymentStatusIcon = "💰";
                    
                    if($booking->payment_status === 'success') {
                        $paymentStatusClass = "bg-[#2d4a22]";
                        $paymentStatusLabel = "Payment Confirmed";
                        $paymentStatusIcon = "✓";
                    } elseif($booking->payment_status === 'pending_verification') {
                        $paymentStatusClass = "bg-amber-500";
                        $paymentStatusLabel = "Pending Verification";
                        $paymentStatusIcon = "⏳";
                    } elseif($booking->payment_status === 'unpaid') {
                        $paymentStatusClass = "bg-orange-400";
                        $paymentStatusLabel = "Not Yet Paid";
                        $paymentStatusIcon = "⚠";
                    }
                @endphp
                <div class="inline-block {{ $paymentStatusClass }} text-white px-4 py-2 rounded-xl text-sm font-bold">
                    {{ $paymentStatusIcon }} {{ $paymentStatusLabel }}
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Event Details -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Package & Vendor Card -->
                <div class="bg-white rounded-3xl p-8 border border-zinc-200 shadow-sm">
                    <div class="flex items-start gap-6">
                        <img src="{{ $booking->main_image ?: 'https://picsum.photos/400/300' }}" 
                             alt="{{ $booking->package_name }}" 
                             class="w-32 h-32 object-cover rounded-2xl flex-shrink-0">
                        <div class="flex-1">
                            <p class="text-[10px] uppercase text-zinc-500 font-semibold tracking-widest mb-1">Vendor & Package</p>
                            <h2 class="text-3xl font-serif mb-1">{{ $booking->package_name }}</h2>
                            <p class="text-lg text-zinc-600 mb-4">by <span class="font-semibold text-[#2d4a22]">{{ $booking->business_name ?: $booking->user_name }}</span></p>
                        </div>
                    </div>
                </div>

                <!-- Event Details Card -->
                <div class="bg-white rounded-3xl p-8 border border-zinc-200 shadow-sm">
                    <p class="text-[10px] uppercase text-zinc-500 font-semibold tracking-widest mb-6">Event Information</p>
                    
                    <div class="grid grid-cols-2 gap-8">
                        <!-- Event Date -->
                        <div>
                            <p class="text-[10px] uppercase text-zinc-400 font-semibold tracking-widest mb-2">Event Date</p>
                            <p class="text-2xl font-semibold text-[#2d4a22]">
                                {{ \Carbon\Carbon::parse($booking->event_date)->format('d F Y') }}
                            </p>
                            <p class="text-xs text-zinc-500 mt-1">
                                {{ \Carbon\Carbon::parse($booking->event_date)->format('l') }}
                            </p>
                        </div>

                        <!-- Estimated Guests -->
                        <div>
                            <p class="text-[10px] uppercase text-zinc-400 font-semibold tracking-widest mb-2">Estimated Guests</p>
                            <p class="text-2xl font-semibold text-[#2d4a22]">
                                {{ $booking->estimated_guests ?? 'N/A' }} Guests
                            </p>
                            <p class="text-xs text-zinc-500 mt-1">Total attendees expected</p>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="mt-8 pt-8 border-t border-zinc-100">
                        <p class="text-[10px] uppercase text-zinc-400 font-semibold tracking-widest mb-2">Event Location</p>
                        <p class="text-lg text-zinc-700">{{ $booking->event_location }}</p>
                    </div>
                </div>

                <!-- Notes Card -->
                @if($booking->notes)
                    <div class="bg-white rounded-3xl p-8 border border-zinc-200 shadow-sm">
                        <p class="text-[10px] uppercase text-zinc-500 font-semibold tracking-widest mb-4">Your Notes</p>
                        <p class="text-zinc-700 leading-relaxed italic">
                            "{{ $booking->notes }}"
                        </p>
                    </div>
                @endif

                <!-- Leave a Review Form -->
                @if($booking->payment_status === 'success' && !$hasReview)
                    <div class="bg-white rounded-3xl p-8 border border-zinc-200 shadow-sm">
                        <p class="text-[10px] uppercase text-zinc-500 font-semibold tracking-widest mb-6">Leave a Review</p>
                        
                        <form action="{{ route('customer.reviews.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                            <!-- Rating Selection -->
                          <div class="mb-6">
                                <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-3">Overall Rating</label>
                                
                                <input type="hidden" name="rating" id="rating-value" value="0" required>
                                
                                <div class="flex items-center gap-2 cursor-pointer" id="star-rating-container">
                                    <svg class="w-8 h-8 text-gray-300 fill-transparent star-icon transition-colors" data-value="1" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                    <svg class="w-8 h-8 text-gray-300 fill-transparent star-icon transition-colors" data-value="2" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                    <svg class="w-8 h-8 text-gray-300 fill-transparent star-icon transition-colors" data-value="3" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                    <svg class="w-8 h-8 text-gray-300 fill-transparent star-icon transition-colors" data-value="4" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                    <svg class="w-8 h-8 text-gray-300 fill-transparent star-icon transition-colors" data-value="5" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                </div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const stars = document.querySelectorAll('.star-icon');
                                    const ratingInput = document.getElementById('rating-value');

                                    stars.forEach(star => {
                                        star.addEventListener('click', function() {
                                            const value = this.getAttribute('data-value');
                                            ratingInput.value = value; 

                                            // Reset semua bintang ke abu-abu transparan
                                            stars.forEach(s => {
                                                s.classList.remove('text-yellow-400', 'fill-yellow-400');
                                                s.classList.add('text-gray-300', 'fill-transparent');
                                            });

                                            // Warnai bintang jadi kuning pekat sampai batas yang diklik
                                            for(let i = 0; i < value; i++) {
                                                stars[i].classList.remove('text-gray-300', 'fill-transparent');
                                                stars[i].classList.add('text-yellow-400', 'fill-yellow-400');
                                            }
                                        });
                                    });
                                });
                            </script>
                                
                            <!-- Comment Field -->
                            <div>
                                <label class="block text-[10px] uppercase text-zinc-500 font-semibold tracking-widest mb-3">Your Comment (Optional)</label>
                                <textarea name="comment" 
                                          placeholder="Share your experience with this vendor. What did they do well? Any suggestions for improvement?"
                                          maxlength="1000"
                                          class="w-full bg-[#f9f8f3] border border-zinc-200 rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2d4a22]/20 focus:bg-white outline-none transition-all resize-none h-32">{{ old('comment') }}</textarea>
                                <p class="text-xs text-zinc-400 mt-2"><span id="char_count">0</span>/1000 characters</p>
                                @error('comment')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="flex gap-3 pt-4">
                                <button type="submit" class="flex-1 bg-[#2d4a22] hover:bg-[#1e3317] text-white font-bold py-3 px-6 rounded-2xl transition-all duration-200 shadow-md hover:shadow-lg">
                                    Submit Review
                                </button>
                            </div>
                        </form>
                    </div>

                    <script>
                        // Character counter for textarea
                        const commentField = document.querySelector('textarea[name="comment"]');
                        const charCount = document.getElementById('char_count');
                        
                        if (commentField) {
                            commentField.addEventListener('input', function() {
                                charCount.textContent = this.value.length;
                            });
                        }
                    </script>
                @endif

            </div>

            <!-- Right Column: Summary & Action -->
            <div class="lg:col-span-1">
                
                <!-- Investment Summary Card -->
                <div class="bg-white rounded-3xl p-8 border border-zinc-200 shadow-sm sticky top-8">
                    <p class="text-[10px] uppercase text-zinc-500 font-semibold tracking-widest mb-4">Total Investment</p>
                    <div class="text-4xl font-bold text-[#2d4a22] mb-2">
                        IDR {{ number_format($booking->total_price ?? 0, 0, ',', '.') }}
                    </div>
                    <p class="text-xs text-zinc-400 mb-8">Complete package investment for your special day</p>

                    <div class="border-t border-zinc-100 pt-8 space-y-4">
                        
                        <!-- Conditional Actions -->
                        @if($booking->booking_status === 'rejected')
                            <!-- Rejected Status -->
                            <div class="bg-red-50 rounded-2xl p-4 border border-red-200">
                                <p class="text-sm text-red-600 font-semibold">❌ This booking was declined by the vendor</p>
                                <p class="text-xs text-red-500 mt-2">Please contact us to explore other vendors and packages.</p>
                            </div>

                        @elseif($booking->booking_status === 'approved' && $booking->payment_status !== 'success')
                            <!-- Proceed to Payment -->
                            <a href="{{ route('customer.payment.show', ['booking_id' => $booking->id]) }}" 
                               class="block w-full bg-[#2d4a22] hover:bg-[#1e3317] text-white font-bold py-4 px-6 rounded-2xl text-center transition-all duration-200 shadow-md hover:shadow-lg decoration-none">
                                💳 Proceed to Payment →
                            </a>
                            <p class="text-xs text-zinc-500 text-center mt-3">Your vendor is ready to confirm once payment is verified</p>

                        @elseif($booking->payment_status === 'success')
                            <!-- Payment Completed -->
                            <div class="bg-green-50 rounded-2xl p-6 border border-green-200 text-center">
                                <p class="text-3xl mb-2">✓</p>
                                <p class="text-sm font-semibold text-green-700">Payment Completed</p>
                                <p class="text-xs text-green-600 mt-2">Your booking is confirmed! The vendor will contact you with final details.</p>
                            </div>

                        @elseif($booking->booking_status === 'pending_review')
                            <!-- Awaiting Approval -->
                            <div class="bg-amber-50 rounded-2xl p-6 border border-amber-200 text-center">
                                <p class="text-3xl mb-2">⏳</p>
                                <p class="text-sm font-semibold text-amber-700">Awaiting Vendor Approval</p>
                                <p class="text-xs text-amber-600 mt-2">The vendor is reviewing your booking. You will receive a notification soon.</p>
                            </div>
                        @endif

                    </div>

                    <!-- Booking Details Footer -->
                    <div class="mt-8 pt-8 border-t border-zinc-100 space-y-3 text-xs text-zinc-500">
                        <div class="flex justify-between">
                            <span>Booking ID:</span>
                            <span class="font-mono font-semibold text-zinc-700">#{{ $booking->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Booked on:</span>
                            <span class="font-mono text-zinc-700">{{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </main>
</div>
@endsection
