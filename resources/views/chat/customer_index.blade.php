@extends('layouts.customer')

@section('content')
<div class="max-w-5xl mx-auto mt-10 p-4 h-[700px] flex gap-4">
    <div class="w-1/3 bg-white rounded-3xl shadow-sm border border-zinc-100 p-4 overflow-y-auto">
        <h3 class="font-bold mb-4 px-2 text-gray-900">Conversations</h3>
        <div class="p-4 text-center mt-10">
            <p class="text-sm text-gray-400 italic">No conversations yet.</p>
        </div>
    </div>

    <div class="w-2/3 bg-white rounded-3xl shadow-sm border border-zinc-100 flex flex-col items-center justify-center p-4 text-center">
        <div class="w-24 h-24 bg-zinc-50 rounded-full flex items-center justify-center mb-6">
            <i data-lucide="message-square-dashed" class="w-10 h-10 text-zinc-400"></i>
        </div>
        <h3 class="text-2xl font-serif font-bold text-zinc-800 mb-3">Your Messages</h3>
        <p class="text-gray-500 text-sm max-w-sm leading-relaxed">
            When you start a conversation with a vendor, your chat history will appear right here.
        </p>
    </div>
</div>
@endsection