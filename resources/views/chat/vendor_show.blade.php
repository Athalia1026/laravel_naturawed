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

                <div class="flex-1 bg-white rounded-[2rem] border border-gray-100 shadow-sm flex flex-col overflow-hidden relative">
                    <div class="px-8 py-5 border-b border-gray-50 bg-white/80 backdrop-blur-sm z-10 shrink-0 flex items-center gap-4">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <h3 class="font-bold text-[#2d3e2d]">Active Conversation</h3>
                    </div>

                    <div class="flex-1 overflow-y-auto p-8 space-y-6 bg-zinc-50/30 hide-scrollbar" id="chatContainer">
                        @foreach($messages as $msg)
                            <div class="flex {{ $msg->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[70%]">
                                    <div class="px-5 py-3.5 text-sm leading-relaxed shadow-sm {{ $msg->sender_id == Auth::id() ? 'bg-[#2d3e2d] text-white rounded-2xl rounded-tr-sm' : 'bg-white border border-gray-100 text-gray-700 rounded-2xl rounded-tl-sm' }}">
                                        {{ $msg->message }}
                                    </div>
                                    <p class="text-[9px] text-gray-400 mt-1.5 px-1 {{ $msg->sender_id == Auth::id() ? 'text-right' : 'text-left' }}">
                                        {{ \Carbon\Carbon::parse($msg->created_at)->format('H:i') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="p-4 bg-white border-t border-gray-50 shrink-0">
                        <form action="{{ route('chat.send') }}" method="POST" class="flex gap-3 bg-[#f8f9fa] p-2 rounded-2xl border border-gray-100 focus-within:border-[#2d3e2d]/30 transition-colors">
                            @csrf
                            <input type="hidden" name="conversation_id" value="{{ $id }}">
                            <input type="text" name="message" class="flex-1 bg-transparent px-4 text-sm focus:outline-none" placeholder="Write a reply..." required autocomplete="off">
                            <button type="submit" class="bg-[#2d3e2d] text-white w-10 h-10 rounded-xl flex items-center justify-center hover:opacity-90 transition-opacity shrink-0">
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
            const container = document.getElementById('chatContainer');
            if(container) container.scrollTop = container.scrollHeight;
        });
    </script>
</body>
</html>