@include('layouts.header')
<main class="max-w-7xl mx-auto px-6 py-16">
    <h1 class="text-6xl serif mb-12">Booking History</h1>
    
    <div class="flex items-center space-x-10 border-b border-zinc-200 mb-16">
        @foreach (['All', 'Ongoing', 'Completed', 'Canceled'] as $tab)
            <a href="{{ route('history', ['tab' => $tab]) }}" 
               class="pb-4 text-sm font-semibold tracking-wider transition-all {{ request('tab') == $tab ? 'text-zinc-900 border-b-2 border-[#2d4a22]' : 'text-zinc-400' }}">
                {{ $tab }}
            </a>
        @endforeach
    </div>

    <div class="space-y-12">
        @forelse ($historyItems as $item)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 bg-white rounded-[32px] p-2 shadow-sm border border-zinc-100">
                <img src="{{ $item['main_image'] }}" class="rounded-[28px] w-full h-full object-cover">
                <div class="lg:col-span-2 p-8 flex flex-col justify-between">
                    <div>
                        <h2 class="text-3xl serif">{{ $item['package_name'] }}</h2>
                        <p class="text-zinc-500 italic">Event Date: {{ date('d F Y', strtotime($item['event_date'])) }}</p>
                    </div>
                    <div class="flex items-center justify-between mt-8 pt-8 border-t border-zinc-50">
                        <p class="text-2xl font-semibold">IDR {{ number_format($item['total_price'], 0, ',', '.') }}</p>
                        @if($item['payment_status'] != 'success')
                            <a href="{{ route('payment.instruction', $item['id']) }}" class="bg-[#2d4a22] text-white px-8 py-4 rounded-xl font-semibold">Pay Now →</a>
                        @else
                            <button class="border border-zinc-200 px-8 py-4 rounded-xl font-semibold">View Details</button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-zinc-400 py-20">No arrangements found.</p>
        @endforelse
    </div>
</main>
@include('layouts.footer')