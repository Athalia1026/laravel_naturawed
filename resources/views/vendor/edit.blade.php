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
<body>
    <div class="min-h-screen flex bg-[#f8f9fa] font-sans text-[#1a1a1a] w-full">
        
        @include('layouts.vendor_sidebar')
        
        <main class="flex-1 flex flex-col overflow-y-auto bg-[#f8f9fa]">
            <header class="h-20 px-12 flex items-center justify-between sticky top-0 bg-white/80 backdrop-blur-md z-10 border-b border-gray-100">
                <h2 class="text-xl font-serif italic text-[#2d3e2d]">Studio Profile</h2>
            </header>

            <div class="px-12 py-10 max-w-4xl mx-auto w-full">
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl text-sm font-medium border border-green-200">
                        {{ session('success') }}
                    </div>
                @endif

                
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-[2rem] p-10 shadow-sm border border-gray-50 space-y-6">
                    @csrf
                    
                    <div class="mb-8 flex items-center gap-6">
                        <div class="relative group">
                            <label for="profile_image_input" class="w-24 h-24 rounded-full bg-gray-200 overflow-hidden border-4 border-white shadow-lg relative group cursor-pointer flex items-center justify-center">
                                @if ($vendorProfile && $vendorProfile->profile_image)
                                    <img id="avatar_preview" src="{{ asset($vendorProfile->profile_image) }}" alt="Studio Logo" class="w-full h-full object-cover">
                                @else
                                    <img id="avatar_preview" src="https://picsum.photos/seed/vendor/200/200" alt="Studio Logo" class="w-full h-full object-cover">
                                @endif
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i data-lucide="camera" class="w-6 h-6 text-white"></i>
                                </div>
                            </label>
                            <input type="file" id="profile_image_input" name="profile_image" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden">
                        </div>
                        <div>
                            <h3 class="text-3xl font-serif text-[#2d3e2d] mb-1">Studio Branding</h3>
                            <p class="text-gray-500 text-sm">Update your public identity and contact information.</p>
                        </div>
                    </div>
                    @csrf <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-2">Studio / Business Name</label>
                            <input type="text" name="business_name" value="{{ old('business_name', Auth::user()->name) }}" required
                                   class="w-full bg-[#f0f2f0] border-transparent rounded-xl px-4 py-3 text-sm focus:border-[#2d3e2d] focus:bg-white outline-none transition-all">
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-2">Contact Email (Readonly)</label>
                            <input type="email" value="{{ Auth::user()->email }}" readonly
                                   class="w-full bg-gray-100 text-gray-500 border-transparent rounded-xl px-4 py-3 text-sm cursor-not-allowed outline-none">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-2">Physical Address</label>
                            <input type="text" name="address" value="{{ old('address', $vendorProfile->address ?? '') }}" placeholder="e.g., 123 Botanical Ave, Jakarta" required
                                   class="w-full bg-[#f0f2f0] border-transparent rounded-xl px-4 py-3 text-sm focus:border-[#2d3e2d] focus:bg-white outline-none transition-all">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-2">Studio Bio / Description</label>
                            <textarea name="bio" placeholder="Tell us about your studio, style, and what makes you unique (max 1000 characters)..." maxlength="1000"
                                      class="w-full bg-[#f0f2f0] border-transparent rounded-xl px-4 py-3 text-sm focus:border-[#2d3e2d] focus:bg-white outline-none transition-all resize-none h-32"
                                      >{{ old('bio', $vendorProfile->bio ?? '') }}</textarea>
                            <p class="text-xs text-gray-400 mt-1">
                                <span id="char_count">{{ old('bio', $vendorProfile->bio ?? '') ? strlen(old('bio', $vendorProfile->bio ?? '')) : 0 }}</span>/1000 characters
                            </p>
                        </div>

                        <div class="col-span-2 pt-6 mt-4 border-t border-gray-100">
                            <h4 class="text-xl font-serif text-[#2d3e2d] mb-2">Meet the Team Showcase</h4>
                            <p class="text-xs text-gray-500 mb-6">Upload a group photo of your creative team and provide a compelling introduction story for potential clients.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                                <div class="md:col-span-1">
                                    <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-2">Team Group Photograph</label>
                                    <div class="relative aspect-[4/3] rounded-2xl overflow-hidden bg-gray-100 border-2 border-dashed border-gray-200 hover:border-[#2d3e2d] transition-all flex flex-col items-center justify-center p-2 text-center group cursor-pointer">
                                        <label for="team_image_input" class="absolute inset-0 z-10 cursor-pointer"></label>
                                        <input type="file" id="team_image_input" name="team_image" accept="image/*" class="hidden">
                                        
                                        @if ($vendorProfile && $vendorProfile->team_image)
                                            <img id="team_preview" src="{{ asset($vendorProfile->team_image) }}" class="absolute inset-0 w-full h-full object-cover">
                                        @else
                                            <img id="team_preview" class="absolute inset-0 w-full h-full object-cover hidden">
                                            <div id="team_placeholder" class="flex flex-col items-center justify-center text-zinc-400">
                                                <i data-lucide="image" class="w-8 h-8 mb-2 opacity-60"></i>
                                                <span class="text-xs font-medium">Select team photo</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-2">Team Introduction / Motto</label>
                                    <input type="text" name="team_description" value="{{ old('team_description', $vendorProfile->team_description ?? '') }}" 
                                           placeholder="e.g., Meet our award-winning wedding directors who turn dream milestones into zero-waste realities."
                                           class="w-full bg-[#f0f2f0] border-transparent rounded-xl px-4 py-3 text-sm focus:border-[#2d3e2d] focus:bg-white outline-none transition-all">
                                    <p class="text-[11px] text-gray-400 mt-2 leading-relaxed">Give a brief summary outlining your collective crew's spirit or combined service excellence (max 255 characters).</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="flex justify-end gap-4 pt-6 mt-6 border-t border-gray-100">
                        <button type="reset" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold rounded-xl text-xs tracking-widest uppercase transition-colors">
                            Discard Changes
                        </button>
                        <button type="submit" class="px-6 py-3 bg-[#2d3e2d] hover:bg-[#1e2a1e] text-white font-semibold rounded-xl text-xs tracking-widest uppercase transition-colors">
                            Save Profile
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            lucide.createIcons();
            
            // 1. Profile Image Upload Preview Handler
            const profileImageInput = document.getElementById('profile_image_input');
            const avatarPreview = document.getElementById('avatar_preview');
            
            if (profileImageInput) {
                profileImageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            avatarPreview.src = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // 2. MODIFIKASI: Live Preview Handler Gambar Foto Tim Baru
            const teamImageInput = document.getElementById('team_image_input');
            const teamPreview = document.getElementById('team_preview');
            const teamPlaceholder = document.getElementById('team_placeholder');

            if (teamImageInput) {
                teamImageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            if (teamPreview) {
                                teamPreview.src = event.target.result;
                                teamPreview.classList.remove('hidden');
                            }
                            if (teamPlaceholder) {
                                teamPlaceholder.classList.add('hidden');
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
            
            // 3. Bio Character Counter Handler
            const bioTextarea = document.querySelector('textarea[name="bio"]');
            const charCount = document.getElementById('char_count');
            
            if (bioTextarea && charCount) {
                bioTextarea.addEventListener('input', function() {
                    charCount.textContent = this.value.length;
                });
            }
        });
    </script>
</body>
</html>