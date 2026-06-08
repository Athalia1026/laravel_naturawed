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
                <button type="submit" class="bg-[#2d4a22] text-white px-8 rounded-2xl font-bold hover:bg-[#1e3317] transition-colors">Send</button>
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