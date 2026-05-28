<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>{{ isset($package) ? 'Edit Package - NaturaWed' : 'Create New Package - NaturaWed' }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="flex min-h-screen bg-white font-sans text-[#1a1a1a]">

    @include('layouts.vendor_sidebar')

    <main class="flex-1 p-12 overflow-y-auto bg-[#f8f9fa]">
        
        <form action="{{ isset($package) ? route('vendor.packages.update') : route('vendor.packages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf @if(isset($package))
                <input type="hidden" name="package_id" value="{{ $package->id }}">
            @endif    

            <header class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="text-4xl font-serif text-[#2d3e2d] mb-2">
                        {{ isset($package) ? 'Edit Wedding Package' : 'Create New Wedding Package' }}
                    </h2>
                    <p class="text-gray-500 text-sm max-w-xl">
                        Craft an exquisite experience for our clients. Define the botanical essence, structural elements, and pricing of your signature offering.
                    </p>
                </div>
                <div class="flex items-center gap-6">
                    <button type="button" onclick="window.history.back()" class="text-sm font-bold tracking-widest uppercase text-gray-500 hover:text-[#2d3e2d] transition-colors outline-none">
                        CANCEL
                    </button>
                    <button type="submit" class="px-8 py-4 bg-[#2a3f24] text-white rounded-xl text-xs font-bold tracking-widest uppercase hover:opacity-90 transition-opacity shadow-lg cursor-pointer outline-none">
                        {{ isset($package) ? 'SAVE CHANGES' : 'PUBLISH PACKAGE' }}
                    </button>
                </div>
            </header>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <div class="xl:col-span-2 space-y-8">
                    
                    <div class="bg-white p-10 rounded-[2.5rem] border border-gray-100 shadow-sm">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-400">
                                <i data-lucide="info" class="w-4 h-4"></i>
                            </div>
                            <h3 class="text-2xl font-serif text-gray-900">Basic Information</h3>
                        </div>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-3">Package Name</label>
                                <input 
                                    type="text" 
                                    name="package_name" 
                                    value="{{ old('package_name', $package->package_name ?? '') }}" 
                                    required 
                                    placeholder="e.g., The Ethereal Conservatory Collection" 
                                    class="w-full px-6 py-4 bg-[#f8f9fa] border-none rounded-xl text-gray-900 focus:ring-2 focus:ring-[#2d3e2d]/20 transition-all outline-none text-sm placeholder-gray-400" 
                                />
                                <x-input-error :messages="$errors->get('package_name')" class="mt-1" />
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-3">Category</label>
                                <div class="relative">
                                    <select name="category_id" required class="w-full px-6 py-4 bg-[#f8f9fa] border-none rounded-xl text-gray-900 focus:ring-2 focus:ring-[#2d3e2d]/20 transition-all outline-none text-sm appearance-none cursor-pointer">
                                        <option value="" disabled {{ !isset($package) ? 'selected' : '' }}>Pilih Kategori...</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ (old('category_id', $package->category_id ?? '') == $cat->id) ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i data-lucide="chevron-down" class="absolute right-6 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                                </div>
                                <x-input-error :messages="$errors->get('category_id')" class="mt-1" />
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-3">Detailed Description</label>
                                <textarea 
                                    name="description" 
                                    required 
                                    rows="5" 
                                    placeholder="Describe the sensory experience, the materials used, and the artistic inspiration..." 
                                    class="w-full px-6 py-4 bg-[#f8f9fa] border-none rounded-xl text-gray-900 focus:ring-2 focus:ring-[#2d3e2d]/20 transition-all outline-none resize-none text-sm placeholder-gray-400"
                                >{{ old('description', $package->description ?? '') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-10 rounded-[2.5rem] border border-gray-100 shadow-sm">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-4">
                                <div class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-400">
                                    <i data-lucide="plus-square" class="w-4 h-4"></i>
                                </div>
                                <h3 class="text-2xl font-serif text-gray-900">Package Features</h3>
                            </div>
                        </div>
                        
                        <div class="p-4 bg-[#f0f2f0] rounded-xl border border-gray-200">
                            <textarea 
                                name="features" 
                                rows="6" 
                                placeholder="- Bespoke Floral Archway (Fresh Import)&#10;- Ambient Warm LED Uplighting (12 Units)&#10;- 4 Hours Professional Photography" 
                                class="w-full bg-transparent border-none focus:ring-0 transition-all outline-none text-sm placeholder-gray-400 resize-none"
                            >{{ old('features', $package->features ?? '') }}</textarea>
                            <p class="text-[10px] text-gray-500 mt-2 italic">* Pisahkan setiap fitur dengan baris baru (Enter).</p>
                        </div>
                        <x-input-error :messages="$errors->get('features')" class="mt-1" />
                    </div>
                </div>

                <div class="space-y-8">
                    
                    <div class="bg-[#2a3f24] p-10 rounded-[2.5rem] shadow-xl">
                        <div class="flex items-center gap-4 mb-8 text-white">
                            <div class="w-8 h-8 rounded-full border border-white/20 flex items-center justify-center">
                                <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                            </div>
                            <h3 class="text-2xl font-serif">Pricing</h3>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-bold tracking-widest text-white/50 uppercase mb-3">Base Price (IDR)</label>
                                <div class="relative">
                                    <div class="absolute left-6 top-1/2 -translate-y-1/2 flex items-center gap-2 pointer-events-none">
                                        <span class="text-sm font-bold text-white/40">Rp</span>
                                    </div>
                                    <input 
                                        type="number" 
                                        name="price" 
                                        required 
                                        value="{{ old('price', isset($package) ? (int)$package->price : '') }}"
                                        placeholder="25000000" 
                                        class="w-full pl-14 pr-6 py-4 bg-white/10 border-none rounded-xl text-xl font-serif text-[#a5d6a7] focus:ring-1 focus:ring-white/20 transition-all outline-none placeholder-white/30" 
                                    />
                                </div>
                                <x-input-error :messages="$errors->get('price')" class="mt-1 text-red-300" />
                            </div>
                        </div>
                    </div>

                  <div class="bg-white p-10 rounded-[2.5rem] border border-gray-100 shadow-sm">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-400">
                                <i data-lucide="image" class="w-4 h-4"></i>
                            </div>
                            <h3 class="text-2xl font-serif text-gray-900">Media Cover</h3>
                        </div>

                        <label for="coverUpload" class="relative block border-2 border-dashed border-gray-200 rounded-3xl p-8 flex flex-col items-center justify-center text-center cursor-pointer hover:border-[#2d3e2d]/20 hover:bg-gray-50 transition-all group overflow-hidden min-h-[250px]">
                            <input type="file" id="coverUpload" name="main_image" accept="image/*" class="hidden">

                            <div id="uploadPlaceholder" class="flex flex-col items-center z-10 {{ isset($package) && $package->main_image ? 'hidden' : '' }}">
                                <div class="w-12 h-12 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-[#2d3e2d] mb-4 shadow-sm group-hover:scale-110 transition-transform">
                                    <i data-lucide="upload" class="w-5 h-5"></i>
                                </div>
                                <p class="text-sm font-bold text-gray-900 mb-1">
                                    Upload Image
                                </p>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest leading-relaxed">High-res JPEG or PNG<br/>(Max 2MB)</p>
                            </div>

                            <img id="imagePreview" 
                                src="{{ isset($package) && $package->main_image ? asset($package->main_image) : '' }}" 
                                class="absolute inset-0 w-full h-full object-cover z-0 {{ isset($package) && $package->main_image ? '' : 'hidden' }}" 
                                alt="Preview">

                            <div id="hoverOverlay" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-20 {{ isset($package) && $package->main_image ? '' : 'hidden' }}">
                                <span class="text-white font-bold text-sm tracking-widest uppercase"><i data-lucide="edit-2" class="w-4 h-4 inline mb-1 mr-1"></i> Change Image</span>
                            </div>
                        </label>
                        <x-input-error :messages="$errors->get('main_image')" class="mt-2" />
                    </div>
                </div>
            </div>
        </form>
    </main>

   <script>
    document.addEventListener("DOMContentLoaded", function() {
        lucide.createIcons();
        
        const coverUpload = document.getElementById('coverUpload');
        const imagePreview = document.getElementById('imagePreview');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        const hoverOverlay = document.getElementById('hoverOverlay');

        if (coverUpload) {
            coverUpload.addEventListener('change', function(e) {
                const file = e.target.files[0];
                
                if (file) {
                    // Cek ukuran file (Maksimal 2MB = 2 * 1024 * 1024 bytes)
                    if (file.size > 2097152) {
                        alert('Ukuran gambar terlalu besar! Maksimal 2MB.');
                        this.value = ''; // Reset input
                        return;
                    }

                    // Logika Pratinjau Menggunakan FileReader
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        // Masukkan gambar ke tag img
                        imagePreview.src = event.target.result;
                        
                        // Sembunyikan ikon upload, tampilkan gambar & overlay ganti gambar
                        imagePreview.classList.remove('hidden');
                        hoverOverlay.classList.remove('hidden');
                        uploadPlaceholder.classList.add('hidden');
                    }
                    reader.readAsDataURL(file);
                } else {
                    // Jika user batal memilih gambar, kembalikan ke awal
                    imagePreview.src = '';
                    imagePreview.classList.add('hidden');
                    hoverOverlay.classList.add('hidden');
                    uploadPlaceholder.classList.remove('hidden');
                }
            });
        }
    });
</script>
</body>
</html>