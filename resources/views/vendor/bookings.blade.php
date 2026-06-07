<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Orders - NaturaWed Vendor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-serif { font-family: 'Playfair Display', Georgia, serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-white font-sans text-zinc-900 antialiased">
    <div class="min-h-screen flex">

        {{-- SIDEBAR UTAMA VENDOR --}}
        @include('layouts.vendor_sidebar')

        {{-- AREA KONTEN UTAMA --}}
        <main class="flex-1 p-10 overflow-y-auto max-w-7xl mx-auto">
            
            {{-- HEADER HALAMAN --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
                <div>
                    <h1 class="text-4xl font-serif font-bold text-[#2d4a22]">Manage Orders</h1>
                    <p class="text-sm text-zinc-500 mt-1">Pantau, setujui, atau batalkan pesanan layanan dekorasi pengantin Anda.</p>
                </div>
                <a href="{{ route('vendor.dashboard') }}" class="px-5 py-2.5 bg-white border border-zinc-200 text-zinc-600 rounded-xl text-xs font-bold tracking-widest uppercase hover:bg-zinc-50 transition-all flex items-center gap-2 shadow-sm decoration-none">
                    ← Back to Dashboard
                </a>
            </div>

            {{-- 🌿 PILIHAN STRUKTUR TAB FILTER (CONSISTENCY UI) --}}
            <div class="flex border-b border-zinc-200 gap-2 mb-8">
                @foreach(['All', 'Pending', 'Approved', 'Rejected'] as $tab)
                    <a href="{{ route('vendor.bookings.index', ['tab' => $tab]) }}" 
                       class="px-6 py-3 text-sm font-semibold tracking-wide transition-all border-b-2 decoration-none {{ $activeTab === $tab ? 'border-[#2d4a22] text-[#2d4a22]' : 'border-transparent text-zinc-400 hover:text-zinc-600' }}">
                        {{ $tab }} Orders
                    </a>
                @endforeach
            </div>

            {{-- TABEL RANCANGAN PREMIUM MELAYANG --}}
            <div class="bg-white rounded-[2rem] shadow-xl border border-zinc-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse m-0">
                        <thead>
                            <tr class="bg-zinc-50/75 border-b border-zinc-100 text-[11px] font-bold tracking-widest text-zinc-400 uppercase">
                                <th class="py-5 px-8">Client</th>
                                <th class="py-5 px-6">Package</th>
                                <th class="py-5 px-6">Event Date</th>
                                <th class="py-5 px-6 text-center">Guests</th>
                                <th class="py-5 px-6 text-center">Status</th>
                                <th class="py-5 px-6 text-right">Amount</th>
                                <th class="py-5 px-8 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 text-sm font-medium text-zinc-700">
                            @forelse($orders as $order)
                                <tr class="hover:bg-zinc-50/50 transition-colors">
                                    {{-- NAMA CLIENT --}}
                                    <td class="py-5 px-8 font-bold text-zinc-900">{{ $order->customer_name }}</td>
                                    
                                    {{-- NAMA PAKET --}}
                                    <td class="py-5 px-6 text-zinc-500">{{ $order->package_name }}</td>
                                    
                                    {{-- TANGGAL ACARA --}}
                                    <td class="py-5 px-6 font-mono text-xs text-zinc-500">
                                        {{ \Carbon\Carbon::parse($order->event_date)->format('d M Y') }}
                                    </td>
                                    
                                    {{-- ESTIMASI TAMU --}}
                                    <td class="py-5 px-6 text-center font-mono text-zinc-900">{{ $order->estimated_guests ?? '-' }}</td>
                                    
                                    {{-- STATUS LABEL INDEX --}}
                                    <td class="py-5 px-6 text-center">
                                        @if($order->booking_status === 'approved')
                                            <span class="inline-block px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold tracking-wider uppercase rounded-full">APPROVED</span>
                                        @elseif($order->booking_status === 'pending_review')
                                            <span class="inline-block px-3 py-1 bg-amber-50 text-amber-600 text-[10px] font-bold tracking-wider uppercase rounded-full">PENDING</span>
                                        @else
                                            <span class="inline-block px-3 py-1 bg-red-50 text-red-500 text-[10px] font-bold tracking-wider uppercase rounded-full">REJECTED</span>
                                        @endif
                                    </td>
                                    
                                    {{-- TOTAL HARGA --}}
                                    <td class="py-5 px-6 text-right font-bold text-[#2d4a22]">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    
                                    {{-- BUTTON AKSI (APPROVE / REJECT KONDISIONAL) --}}
                                    <td class="py-5 px-8 text-center">
                                        @if($order->booking_status === 'pending_review')
                                            <div class="flex items-center justify-center gap-2">
                                                {{-- TOMBOL APPROVE --}}
                                                <form action="{{ route('vendor.bookings.approve', $order->booking_id) }}" method="POST" class="m-0 p-0">
                                                    @csrf
                                                    <button type="submit" class="p-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-lg transition-colors border-none cursor-pointer outline-none shadow-sm flex items-center justify-center">
                                                        <i data-lucide="check" class="w-4 h-4"></i>
                                                    </button>
                                                </form>

                                                {{-- TOMBOL REJECT --}}
                                                <form action="{{ route('vendor.bookings.reject', $order->booking_id) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Apakah Anda yakin ingin menolak pesanan ini?');">
                                                    @csrf
                                                    <button type="submit" class="p-2 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-lg transition-colors border-none cursor-pointer outline-none shadow-sm flex items-center justify-center">
                                                        <i data-lucide="x" class="w-4 h-4"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-zinc-400 text-xs">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td col-span="7" class="text-center py-16 text-zinc-400 italic">
                                        Tidak ada data transaksi pesanan masuk pada kategori ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION LINK (PERFORMA AMAN) --}}
                @if($orders->hasPages())
                    <div class="px-8 py-5 bg-zinc-50 border-t border-zinc-100">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            lucide.createIcons();
        });
    </script>
</body>

</html>