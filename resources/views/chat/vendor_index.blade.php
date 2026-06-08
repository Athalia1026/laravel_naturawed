<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Vendor Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
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
                    <div class="p-8 pb-4 shrink-0">
                        <h4 class="text-2xl font-serif text-[#2d3e2d]">Inbox</h4>
                        <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mt-1">Client Inquiries</p>
                    </div>
                    <div class="flex-1 overflow-y-auto p-8 pt-0 flex flex-col items-center justify-center text-center opacity-70">
                        <i data-lucide="inbox" class="w-8 h-8 text-gray-400 mb-3"></i>
                        <p class="text-xs text-gray-400 italic">No conversations yet.</p>
                    </div>
                </div>

                <div class="flex-1 bg-white rounded-[2rem] border border-gray-100 shadow-sm flex flex-col items-center justify-center p-12 text-center">
                    <div class="w-24 h-24 bg-[#e1f5e1] rounded-[1.5rem] flex items-center justify-center mb-6 transform -rotate-6">
                        <i data-lucide="message-square-dashed" class="w-10 h-10 text-[#2d3e2d]"></i>
                    </div>
                    <h3 class="text-4xl font-serif text-[#2d3e2d] mb-4">Your Messages</h3>
                    <p class="text-gray-500 text-sm max-w-md leading-relaxed mb-8">
                        When a client reaches out to inquire about your packages or services, their messages will appear directly in this secure channel.
                    </p>
                    <div class="px-6 py-2.5 bg-[#f0f2f0] text-[#2d3e2d] rounded-full text-[10px] font-bold tracking-widest uppercase">
                        Status: Online & Ready
                    </div>
                </div>
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