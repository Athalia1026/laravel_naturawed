@if(Auth::user()->role === 'vendor')
    {{-- ========================================== --}}
    {{-- TAMPILAN RUANG CHAT AKTIF: VENDOR          --}}
    {{-- ========================================== --}}
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Active Chat - Vendor Portal</title>
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
    <body class="bg-white font-sans text-[#1a1a1a]">
        <div class="min-h-screen flex">
            
            @include('layouts.vendor_sidebar')

            <main class="flex-1 flex flex-col h-screen overflow-hidden">
                <header class="h-20 px-12 flex items-center justify-between shrink-0 bg-white/80 backdrop-blur-md border-b border-gray-50 z-10">
                    <h2 class="text-xl font-serif italic text-[#2d3e2d]">Messages</h2>
                    <div class="w-10 h-10 rounded-full bg-[#2d3e2d] overflow-hidden flex items-center justify-center text-white font-bold text-sm">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </header>

                <div class="px-12 py-8 flex gap-8 h-full overflow-hidden pb-12">
                    
                    {{-- KIRI: DAFTAR INBOX --}}
                    <div class="w-80 shrink-0 bg-[#f8f9fa] rounded-[2rem] border border-gray-50 flex flex-col overflow-hidden">
                        <div class="p-8 pb-4 shrink-0 border-b border-gray-100">
                            <h4 class="text-2xl font-serif text-[#2d3e2d]">Inbox</h4>
                        </div>
                        <div class="flex-1 overflow-y-auto p-4 space-y-2 hide-scrollbar">
                            @foreach($conversations as $conv)
                                @php
                                    $partnerName = $conv->user_one == Auth::id() ? $conv->partner_two_name : $conv->partner_one_name;
                                @endphp
                                <a href="{{ route('chat.show', $conv->id) }}" class="block p-4 rounded-2xl transition-all {{ $id == $conv->id ? 'bg-white shadow-sm border border-gray-100' : 'hover:bg-white/50 border border-transparent' }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-[#e1f5e1] flex items-center justify-center text-[#2d3e2d] font-bold text-xs shrink-0">
                                            {{ substr($partnerName, 0, 1) }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <p class="font-bold text-sm text-[#2d3e2d] truncate">{{ $partnerName }}</p>
                                            <p class="text-[10px] text-gray-400 mt-0.5 truncate">Client Inquiry</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- KANAN: AREA CHAT AKTIF --}}
                    <div class="flex-1 bg-white rounded-[2rem] border border-gray-100 shadow-sm flex flex-col overflow-hidden relative">
                        {{-- Header Lawan Bicara --}}
                        <div class="px-8 py-5 border-b border-gray-50 bg-white/80 backdrop-blur-sm z-10 shrink-0 flex items-center gap-4">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <h3 class="font-bold text-[#2d3e2d]">Active Conversation</h3>
                        </div>

                        {{-- Gelembung Pesan --}}
                        <div class="flex-1 overflow-y-auto p-8 space-y-6 bg-zinc-50/30 hide-scrollbar" id="chatContainer">
                            @foreach($messages as $msg)
                                <div class="flex {{ $msg->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-[70%]">
                                        <div class="px-5 py-3.5 text-sm leading-relaxed shadow-sm
                                            {{ $msg->sender_id == Auth::id() 
                                                ? 'bg-[#2d3e2d] text-white rounded-2xl rounded-tr-sm' 
                                                : 'bg-white border border-gray-100 text-gray-700 rounded-2xl rounded-tl-sm' }}">
                                            {{ $msg->message }}
                                        </div>
                                        <p class="text-[9px] text-gray-400 mt-1.5 px-1 {{ $msg->sender_id == Auth::id() ? 'text-right' : 'text-left' }}">
                                            {{ \Carbon\Carbon::parse($msg->created_at)->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Kotak Input Bawah --}}
                        <div class="p-4 bg-white border-t border-gray-50 shrink-0">
                            <form action="{{ route('chat.send') }}" method="POST" class="flex gap-3 bg-[#f8f9fa] p-2 rounded-2xl border border-gray-100 focus-within:border-[#2d3e2d]/30 transition-colors">
                                @csrf
                                <input type="hidden" name="conversation_id" value="{{ $id }}">
                                <input type="text" name="message" class="flex-1 bg-transparent px-4 text-sm focus:outline-none" placeholder="Write a reply..." required autocomplete="off">
                                <button class="bg-[#2d3e2d] text-white w-10 h-10 rounded-xl flex items-center justify-center hover:opacity-90 transition-opacity shrink-0">
                                    <i data-lucide="send" class="w-4 h-4 ml-0.5"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </main>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                lucide.createIcons();
                // Otomatis scroll ke bawah saat pesan baru
                const container = document.getElementById('chatContainer');
                container.scrollTop = container.scrollHeight;
            });
        </script>
    </body>
    </html>

@else
    {{-- ========================================== --}}
    {{-- TAMPILAN RUANG CHAT AKTIF: CUSTOMER        --}}
    {{-- ========================================== --}}
    @extends('layouts.customer')

    @section('content')
    <div class="max-w-5xl mx-auto mt-10 p-4 h-[700px] flex gap-4">
        
        <div class="w-1/3 bg-white rounded-3xl shadow-sm border border-zinc-100 p-4 overflow-y-auto hide-scrollbar">
            <h3 class="font-bold mb-4 px-2 text-gray-900">Conversations</h3>
            @foreach($conversations as $conv)
                @php
                    $partnerName = $conv->user_one == Auth::id() ? $conv->partner_two_name : $conv->partner_one_name;
                @endphp
                <a href="{{ route('chat.show', $conv->id) }}" class="block p-4 rounded-2xl transition-all {{ $id == $conv->id ? 'bg-[#2d4a22] text-white shadow-md' : 'hover:bg-zinc-50 border border-transparent' }}">
                    <p class="font-bold text-sm">{{ $partnerName }}</p>
                    <p class="text-[10px] opacity-70 mt-1">Vendor Studio</p>
                </a>
            @endforeach
        </div>

        <div class="w-2/3 bg-white rounded-3xl shadow-sm border border-zinc-100 flex flex-col overflow-hidden">
            <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-zinc-50/50" id="chatContainer">
                @foreach($messages as $msg)
                    <div class="flex {{ $msg->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                        <span class="inline-block px-5 py-3 shadow-sm text-sm {{ $msg->sender_id == Auth::id() ? 'bg-[#2d4a22] text-white rounded-2xl rounded-br-sm' : 'bg-white border border-gray-100 text-gray-800 rounded-2xl rounded-bl-sm' }}">
                            {{ $msg->message }}
                        </span>
                    </div>
                @endforeach
            </div>
            
            <div class="p-4 bg-white border-t border-zinc-100">
                <form action="{{ route('chat.send') }}" method="POST" class="flex gap-3">
                    @csrf
                    <input type="hidden" name="conversation_id" value="{{ $id }}">
                    <input type="text" name="message" class="flex-1 bg-zinc-50 border border-zinc-200 rounded-2xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-[#2d4a22]/20" placeholder="Type a message..." required autocomplete="off">
                    <button class="bg-[#2d4a22] text-white px-8 rounded-2xl font-bold hover:bg-[#1e3317] transition-colors">Send</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const container = document.getElementById('chatContainer');
            if(container) container.scrollTop = container.scrollHeight;
        });
    </script>
    @endsection
@endif