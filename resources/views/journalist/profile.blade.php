<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - NaturaWed</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="flex min-h-screen bg-white font-sans text-[#1a1a1a]">

    <!-- Memanggil Sidebar Jurnalis -->
    @include('layouts.journalist_sidebar')

    <!-- Main Container: Padding konsisten (p-12) dan putih polos (bg-white) -->
    <main class="flex-1 flex flex-col overflow-y-auto bg-white p-12">
        <div class="max-w-4xl mx-auto w-full">
            
            @if(session('success'))
                <div class="mb-8 p-4 bg-[#e1f5e1] text-[#2d4a22] rounded-xl text-sm font-semibold border border-[#2d4a22]/20">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-10 flex items-center gap-6">
                <!-- Foto Profil dengan Hover Kamera & ID untuk Live Preview -->
                <div onclick="document.getElementById('profile_image').click()" class="w-28 h-28 rounded-full bg-gray-200 overflow-hidden border-4 border-white shadow-lg relative group cursor-pointer shrink-0">
                    <img id="avatar-preview" src="{{ !empty($profile->profile_image) ? asset($profile->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode($profile->full_name ?? Auth::user()->name).'&background=2d3e2d&color=fff' }}" alt="Profile Avatar" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <i data-lucide="camera" class="w-6 h-6 text-white"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-4xl font-serif text-[#2d3e2d] mb-2">Editorial Branding</h3>
                    <p class="text-gray-500 text-sm">Update your public identity and editorial focus.</p>
                </div>
            </div>

            <!-- Form Edit: Dibuat polos TANPA bingkai putih/card (shadow dan border dihapus) -->
            <form action="{{ route('journalist.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                <!-- Input File Tersembunyi untuk Avatar -->
                <input type="file" id="profile_image" name="profile_image" accept="image/*" class="hidden" onchange="previewImage(event, 'avatar-preview')" />

                <div class="grid grid-cols-2 gap-8">
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-3">Pen Name / Full Name</label>
                        <input type="text" name="full_name" value="{{ old('full_name', $profile->full_name ?? Auth::user()->name) }}" required
                               class="w-full bg-[#f8f9fa] border-transparent rounded-xl px-5 py-3.5 text-sm focus:border-[#2d3e2d] focus:ring-1 focus:ring-[#2d3e2d] outline-none transition-all">
                    </div>
                    
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-3">Contact Email (Readonly)</label>
                        <input type="email" value="{{ Auth::user()->email }}" readonly
                               class="w-full bg-gray-100 text-gray-400 border-transparent rounded-xl px-5 py-3.5 text-sm cursor-not-allowed">
                    </div>
                    
                    <div class="col-span-2">
                        <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-3">Author Bio</label>
                        <textarea name="bio" rows="6" placeholder="Tell readers about your journalistic experience..." 
                                  class="w-full bg-[#f8f9fa] border-transparent rounded-xl px-5 py-4 text-sm focus:border-[#2d3e2d] focus:ring-1 focus:ring-[#2d3e2d] outline-none resize-none transition-all">{{ old('bio', $profile->bio ?? '') }}</textarea>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-3">Header Cover Image</label>
                        
                        <!-- Tempat Gambar Header Muncul (Live Preview) -->
                        <img id="header-preview" src="{{ !empty($profile->header_image) ? asset($profile->header_image) : '' }}" 
                             alt="Header Cover" 
                             class="w-full h-48 object-cover rounded-xl mb-4 opacity-90 border border-gray-200 {{ empty($profile->header_image) ? 'hidden' : '' }}">
                        
                        <input type="file" name="header_image" accept="image/*" onchange="previewImage(event, 'header-preview')"
                               class="w-full bg-[#f8f9fa] border-transparent rounded-xl px-5 py-3.5 text-sm focus:border-[#2d3e2d] file:mr-4 file:py-2 file:px-5 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:uppercase file:tracking-widest file:bg-[#2d3e2d] file:text-white hover:file:bg-[#1e291e] cursor-pointer transition-all">
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-8 mt-8 border-t border-gray-100">
                    <button type="reset" class="px-8 py-3 rounded-full border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50 transition-colors">
                        Discard Changes
                    </button>
                    <button type="submit" class="px-8 py-3 rounded-full bg-[#2d4a22] text-white text-sm font-bold hover:bg-[#1e3317] transition-colors shadow-md">
                        Save Profile
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Script Javascript Ajaib untuk Live Preview -->
    <script> 
        lucide.createIcons(); 

        function previewImage(event, targetId) {
            const input = event.target;
            const preview = document.getElementById(targetId);
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    // Jika elemen sebelumnya disembunyikan (hidden), maka tampilkan
                    preview.classList.remove('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>