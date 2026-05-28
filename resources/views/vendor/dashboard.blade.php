<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard - NaturaWed</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
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
            <header class="h-20 px-12 flex items-center justify-between sticky top-0 bg-white/80 backdrop-blur-md z-10">
                <h2 class="text-xl font-serif italic text-[#2d3e2d]">NaturaWed</h2>
                
                <div class="flex items-center gap-8">
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-[18px] h-[18px]"></i>
                        <input 
                            type="text" 
                            placeholder="Search orders..." 
                            class="pl-12 pr-6 py-2.5 bg-[#f0f2f0] border-none rounded-full text-sm w-64 focus:ring-1 focus:ring-[#2d3e2d]/20 transition-all outline-none"
                        />
                    </div>
                    <div class="flex items-center gap-5 text-gray-500">
                        <button class="hover:text-[#2d3e2d] transition-colors relative">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 border-2 border-white rounded-full"></span>
                        </button>
                        <button class="hover:text-[#2d3e2d] transition-colors"><i data-lucide="mail" class="w-5 h-5"></i></button>
                        <button class="hover:text-[#2d3e2d] transition-colors"><i data-lucide="settings" class="w-5 h-5"></i></button>
                        
                        <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden border border-gray-100 cursor-pointer">
                            <img 
                                src="https://picsum.photos/seed/vendor/100/100" 
                                alt="Profile" 
                                class="w-full h-full object-cover"
                                referrerpolicy="no-referrer"
                            />
                        </div>
                    </div>
                </div>
            </header>

            <div class="px-12 py-8 flex gap-12">
                <div class="flex-1 space-y-12">
                    <section>
                        <div class="flex justify-between items-end mb-4">
                            <div>
                                <h3 class="text-5xl font-serif text-[#2d3e2d] leading-tight">
                                    Welcome back,<br />
                                    <span class="italic">{{ Auth::user()->name }}</span>
                                </h3>
                                <p class="text-gray-500 mt-4">
                                    Your studio has <strong class="text-[#2d3e2d]">{{ $newInquiriesCount }}</strong> new inquiries and <strong class="text-[#2d3e2d]">0</strong> pending reviews this morning.
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-1">Current Status</p>
                                <div class="flex items-center gap-2 text-[#2d3e2d] font-bold">
                                    <div class="w-2 h-2 bg-[#4caf50] rounded-full"></div>
                                    Accepting Bookings
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6 mt-10">
                            <div class="bg-[#f8f9fa] p-8 rounded-[2rem] border border-gray-50 hover:-translate-y-1 transition-transform cursor-default">
                                <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-6">Total Orders</p>
                                <div class="flex items-baseline gap-3">
                                    <span class="text-5xl font-serif text-[#2d3e2d]">{{ $totalOrdersCount }}</span>
                                    <span class="text-[10px] font-bold text-[#4caf50]">All time</span>
                                </div>
                            </div>
                            <div class="bg-[#e1e8e1] p-8 rounded-[2rem] border border-gray-50 hover:-translate-y-1 transition-transform cursor-default">
                                <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-6">New Reviews</p>
                                <div class="flex items-center gap-3">
                                    <span class="text-5xl font-serif text-[#2d3e2d]">0</span>
                                    <i data-lucide="star" class="text-[#2d3e2d] fill-[#2d3e2d] w-6 h-6"></i>
                                </div>
                            </div>
                            <div class="bg-[#f0f2f0] p-8 rounded-[2rem] border border-gray-50 hover:-translate-y-1 transition-transform cursor-default">
                                <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-6">Active Packages</p>
                                <div class="flex items-baseline gap-3">
                                    <span class="text-5xl font-serif text-[#2d3e2d]">{{ $activePackagesCount }}</span>
                                    <span class="text-[10px] font-bold text-gray-400">Live on store</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <div class="flex justify-between items-center mb-6">
                            <h4 class="text-2xl font-serif text-[#2d3e2d]">Recent Orders</h4>
                            <button class="text-[10px] font-bold tracking-widest text-gray-400 uppercase hover:text-[#2d3e2d] transition-colors">View All</button>
                        </div>
                        <div class="bg-white rounded-[2rem] border border-gray-100 overflow-hidden shadow-sm">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="bg-[#f8f9fa] text-[10px] font-bold tracking-widest text-gray-400 uppercase">
                                        <th class="px-8 py-4">Client</th>
                                        <th class="px-8 py-4">Package</th>
                                        <th class="px-8 py-4">Guests</th>
                                        <th class="px-8 py-4">Status</th>
                                        <th class="px-8 py-4">Amount</th>
                                        <th class="px-8 py-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse ($recentOrders as $order)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-8 py-5 font-semibold text-sm">{{ $order->client_name }}</td>
                                            <td class="px-8 py-5 text-sm text-gray-500">{{ $order->package_name }}</td>
                                            <td class="px-8 py-5 text-sm font-semibold text-[#2d3e2d]">{{ $order->estimated_guests ?? 'N/A' }}</td>
                                            <td class="px-8 py-5">
                                                <span class="px-3 py-1 rounded-full text-[9px] font-bold tracking-wider {{ $order->statusColor }}">
                                                    {{ strtoupper($order->statusLabel) }}
                                                </span>
                                            </td>
                                            <td class="px-8 py-5 font-semibold text-sm">Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
                                            <td class="px-8 py-5">
                                                @if($order->booking_status === 'pending_review')
                                                    <div class="flex gap-2">
                                                        <form action="{{ route('vendor.bookings.approve', $order->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="px-3 py-1.5 bg-[#2d3e2d] text-white text-[10px] font-bold rounded-lg hover:opacity-90 transition-opacity">Approve</button>
                                                        </form>
                                                        <form action="{{ route('vendor.bookings.reject', $order->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="px-3 py-1.5 bg-red-500 text-white text-[10px] font-bold rounded-lg hover:opacity-90 transition-opacity">Reject</button>
                                                        </form>
                                                    </div>
                                                @elseif($order->booking_status === 'approved')
                                                    <span class="text-[10px] text-gray-500 italic">—</span>
                                                @else
                                                    <span class="text-[10px] text-gray-500 italic">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-8 py-6 text-center text-sm text-gray-400 italic">No recent bookings found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <section>
                        <div class="flex justify-between items-center mb-6">
                            <h4 class="text-2xl font-serif text-[#2d3e2d]">New Reviews</h4>
                            <button class="text-[10px] font-bold tracking-widest text-gray-400 uppercase hover:text-[#2d3e2d] transition-colors">Manage All</button>
                        </div>
                        <div class="bg-[#f8f9fa] p-8 rounded-[2rem] border border-gray-50">
                            <div class="flex justify-between items-start mb-6">
                                <div class="flex gap-4">
                                    <img src="https://picsum.photos/seed/clara/100/100" alt="Clara" class="w-12 h-12 rounded-full object-cover" referrerpolicy="no-referrer" />
                                    <div>
                                        <h5 class="font-bold text-sm">Clara Beaumont</h5>
                                        <div class="flex gap-0.5 mt-1">
                                            @for($i=0; $i<5; $i++)
                                                <i data-lucide="star" class="text-[#2d3e2d] fill-[#2d3e2d] w-3 h-3"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">2 Days Ago</span>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed italic">
                                "The attention to detail in the floral arrangements was simply breathtaking. NaturaWed captured our vision perfectly and went above and beyond."
                            </p>
                            <div class="flex gap-3 mt-8">
                                <button class="px-6 py-2.5 bg-[#2d3e2d] text-white rounded-full text-[10px] font-bold tracking-widest uppercase hover:opacity-90 transition-opacity">Quick Reply</button>
                                <button class="px-6 py-2.5 bg-white border border-gray-200 text-gray-500 rounded-full text-[10px] font-bold tracking-widest uppercase hover:bg-gray-50 transition-colors">Dismiss</button>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="w-80 flex flex-col gap-8 shrink-0">
                    <div>
                        <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-4">Quick Actions</p>
                        <div class="flex flex-col gap-4">
                            
                            <a href="{{ route('profile.edit') }}" class="w-full bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-5 hover:border-[#2d3e2d]/20 transition-all group text-left cursor-pointer">
                                <div class="w-12 h-12 rounded-xl bg-[#e1f5e1] flex items-center justify-center text-[#2d3e2d] group-hover:scale-110 transition-transform shrink-0">
                                    <i data-lucide="edit-3" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h6 class="font-bold text-sm text-[#1a1a1a]">Edit Profile</h6>
                                    <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-0.5 line-clamp-1">Studio Details & Branding</p>
                                </div>
                            </a>

                            <a href="{{ route('profile.edit') }}" class="w-full bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-5 hover:border-[#2d3e2d]/20 transition-all group text-left cursor-pointer">
                                <div class="w-12 h-12 rounded-xl bg-[#f8f9fa] flex items-center justify-center text-gray-400 group-hover:scale-110 transition-transform shrink-0">
                                    <i data-lucide="image" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h6 class="font-bold text-sm text-[#1a1a1a]">Update Portfolio</h6>
                                    <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-0.5 line-clamp-1">Manage your visual gallery</p>
                                </div>
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-[#2d3e2d] p-6 rounded-2xl shadow-lg flex items-center gap-5 hover:opacity-95 transition-all group text-left cursor-pointer">
                                    <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center text-white group-hover:scale-110 transition-transform shrink-0">
                                        <i data-lucide="log-out" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="font-bold text-sm text-white">Log Out Account</h6>
                                        <p class="text-[10px] text-white/50 uppercase tracking-widest mt-0.5 line-clamp-1">Securely exit session</p>
                                    </div>
                                </button>
                            </form>

                        </div>
                    </div>

                    <div class="relative rounded-[2.5rem] overflow-hidden aspect-[3/4] group cursor-pointer w-full shadow-sm border border-gray-100">
                        <img src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&q=80&w=800" alt="Featured" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" referrerpolicy="no-referrer" />
                        <div class="absolute inset-0 bg-gradient-to-t from-[#2d3e2d] via-[#2d3e2d]/40 to-transparent"></div>
                        <div class="absolute inset-0 flex flex-col justify-end p-8">
                            <p class="text-[10px] font-bold tracking-widest text-white/60 uppercase mb-2">Portfolio Featured</p>
                            <h5 class="text-3xl font-serif text-white leading-tight mb-6">Spring Solstice Gathering</h5>
                            <button class="flex items-center gap-2 text-[10px] font-bold tracking-widest text-white uppercase group/btn">
                                Manage Gallery
                                <i data-lucide="chevron-right" class="w-[14px] h-[14px] group-hover/btn:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>