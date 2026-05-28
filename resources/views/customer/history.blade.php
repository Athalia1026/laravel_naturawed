@extends('layouts.customer')

@section('content')
@php
    $tabs = ['All', 'Ongoing', 'Completed', 'Canceled'];
@endphp

<div class="min-h-screen bg-[#fdfcf7] font-sans text-zinc-900 pb-20">
    <main class="max-w-7xl mx-auto px-6 py-16">
        
        <div class="mb-12">
            <h1 class="text-6xl font-serif mb-6">Booking History</h1>
            <p class="text-zinc-500 max-w-2xl leading-relaxed">
                Review your curated wedding experiences and upcoming arrangements.
            </p>
        </div>

        <div class="flex items-center space-x-10 border-b border-zinc-200 mb-16">
            @foreach ($tabs as $tab)
                <a href="{{ route('customer.bookings.history', ['tab' => $tab]) }}" 
                   class="pb-4 text-sm font-semibold tracking-wider transition-all relative {{ $activeTab === $tab ? 'text-zinc-900 border-b-2 border-[#2d4a22]' : 'text-zinc-400 hover:text-zinc-600' }} decoration-none">
                    {{ $tab }}
                </a>
            @endforeach
        </div>

        <div class="space-y-12">
            @if($historyItems->isEmpty())
                <div class="text-center py-20 bg-white rounded-[32px] border border-dashed border-zinc-200">
                    <p class="text-zinc-400 italic">No arrangements found in this category.</p>
                </div>
            @else
                @foreach ($historyItems as $item)
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 bg-white rounded-[32px] overflow-hidden p-2 shadow-sm border border-zinc-100">
                        
                        <div class="lg:col-span-1 aspect-[4/3] rounded-[28px] overflow-hidden relative">
                            <img src="{{ $item->main_image ?: 'https://picsum.photos/600/450' }}" class="w-full h-full object-cover" alt="Package Cover">
                            
                            @php
                                $statusClass = "bg-zinc-500";
                                $statusLabel = "Unknown";
                                
                                if($item->booking_status === 'pending_review') {
                                    $statusClass = "bg-amber-500";
                                    $statusLabel = "Waiting Approval";
                                } elseif($item->booking_status === 'approved') {
                                    if($item->payment_status === 'success') {
                                        $statusClass = "bg-[#2d4a22]";
                                        $statusLabel = "Confirmed";
                                    } else {
                                        $statusClass = "bg-[#2d4a22]";
                                        $statusLabel = "Approved";
                                    }
                                } elseif($item->booking_status === 'rejected') {
                                    $statusClass = "bg-red-500";
                                    $statusLabel = "Rejected";
                                }
                            @endphp
                            
                            <div class="absolute top-6 left-6 px-3 py-1 {{ $statusClass }} text-white text-[10px] font-bold tracking-widest uppercase rounded-full shadow-sm">
                                {{ $statusLabel }}
                            </div>
                        </div>

                        <div class="lg:col-span-2 p-8 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start">
                                    <h2 class="text-3xl font-serif mb-2">{{ $item->package_name }}</h2>
                                    <span class="text-[10px] font-mono text-zinc-400 uppercase">
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                    </span>
                                </div>
                                <p class="text-zinc-500 mb-4 italic">
                                    Event Date: {{ \Carbon\Carbon::parse($item->event_date)->format('d F Y') }}
                                </p>
                                <p class="text-sm text-zinc-400 line-clamp-2">
                                    Location: {{ $item->event_location }}
                                </p>
                            </div>

                            <div class="flex items-center justify-between mt-8 pt-8 border-t border-zinc-50">
                                <div>
                                    <p class="text-[10px] uppercase text-zinc-400 mb-1">Total Investment</p>
                                    <p class="text-2xl font-semibold text-gray-900">
                                        IDR {{ number_format($item->total_price ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>

                                @if($item->booking_status === 'pending_review')
                                    <button class="border border-zinc-200 text-zinc-400 bg-zinc-50 px-8 py-4 rounded-xl font-semibold cursor-not-allowed outline-none" disabled>
                                        Awaiting Vendor Approval
                                    </button>
                                @elseif($item->booking_status === 'approved' && $item->payment_status !== 'success')
                                    <a href="{{ route('customer.payment.show', ['booking_id' => $item->id]) }}" 
                                       class="bg-[#2d4a22] hover:bg-[#1e3317] text-white px-8 py-4 rounded-xl font-semibold transition-colors decoration-none shadow-md cursor-pointer">
                                        Pay Now →
                                    </a>
                                @else
                                    <button class="border border-zinc-200 text-zinc-900 bg-white px-8 py-4 rounded-xl font-semibold hover:bg-zinc-50 transition-colors cursor-default outline-none">
                                        View Details
                                    </button>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            @endif
        </div>
    </main>
</div>
@endsection