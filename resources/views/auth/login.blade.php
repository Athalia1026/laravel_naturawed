<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign In NaturaWed</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body class="bg-[#d1e2da] flex justify-center items-center min-h-screen overflow-x-hidden">
    <main class="flex flex-col md:flex-row w-full max-w-[1100px] h-auto min-h-[600px] items-center justify-center p-8 gap-12 md:gap-[50px]">
      
      <div class="flex-1 flex flex-col items-center justify-center text-center mb-5 md:mb-0">
        <a href="{{ url('/') }}">
          <img src="{{ asset('assets/image/logo.svg') }}" alt="logo" class="w-[200px] mb-5" />
          <h1 class="text-[32px] md:text-[42px] font-bold text-[#0b452c] tracking-[1px]">Naturawed</h1>
        </a>
      </div>

      <div class="flex-1 flex justify-center w-full">
        <div class="bg-white p-10 rounded-[24px] w-full max-w-[450px] shadow-[0_10px_30px_rgba(0,0,0,0.05)]">
          <div class="text-center mb-[30px]">
            <h2 class="text-[28px] font-semibold text-[#1a1a1a]">Sign In to Your Account</h2>
            <p class="text-sm text-[#7a7a7a] mb-[30px]">Sign in to access your account.</p>
          </div>

          <x-auth-session-status class="mb-4" :status="session('status')" />

          <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5">
            @csrf <div class="w-full relative">
              <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Email" required autofocus
                     class="w-full px-5 py-4 border border-[#e0e0e0] rounded-xl text-sm text-[#333] outline-none transition-colors duration-300 placeholder:text-[#a0a0a0] focus:border-[#0b452c]" />
              <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs" />
            </div>

            <div class="w-full relative">
  <input type="password" name="password" id="password" placeholder="Password" required
         class="w-full px-5 py-4 border border-[#e0e0e0] rounded-xl text-sm text-[#333] outline-none transition-colors duration-300 placeholder:text-[#a0a0a0] focus:border-[#0b452c]" />
  
  <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs" />
  
  <button
    type="button"
    id="togglePassword"
    class="absolute right-5 top-1/2 -translate-y-1/2 cursor-pointer text-[#a0a0a0] hover:text-[#0b452c] transition-colors focus:outline-none"
    aria-label="Toggle password visibility"
  >
    <svg id="eyeShow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
    </svg>

    <svg id="eyeHide" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
      <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
    </svg>
  </button>
</div>

            

            <div class="flex items-center gap-2 my-2">
              <input type="checkbox" id="remember_me" name="remember" class="rounded border-gray-300 text-[#0b452c] focus:ring-[#0b452c]" />
              <label for="remember_me" class="text-sm text-[#666]">Remember Me</label>
            </div>

            <button type="submit" class="w-full p-4 mt-2 border-none rounded-xl bg-[#06402b] text-white text-base font-semibold transition-colors duration-300 hover:bg-[#042e1f]">
              Sign In
            </button>
          </form>

          <div class="mt-[30px] text-center text-sm text-[#7a7a7a]">
            <p>Don't have an account? <a class="text-[#0b452c] font-semibold hover:underline" href="{{ route('register') }}">Sign Up</a></p>
          </div>
        </div>
      </div>
    </main>
    <script>
  document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const toggleButton = document.getElementById('togglePassword');
    const eyeShowIcon = document.getElementById('eyeShow');
    const eyeHideIcon = document.getElementById('eyeHide');

    if (toggleButton && passwordInput) {
      toggleButton.addEventListener('click', function () {
        // 1. Tentukan tipe input baru
        const isPassword = passwordInput.getAttribute('type') === 'password';
        const type = isPassword ? 'text' : 'password';
        
        // 2. Terapkan tipe input baru
        passwordInput.setAttribute('type', type);

        // 3. Logika Ganti Ikon Minimalis (Gunakan class 'hidden')
        if (isPassword) {
          // Jika sekarang teks muncul (Show), sembunyikan mata terbuka, tampilkan mata tertutup
          eyeShowIcon.classList.add('hidden');
          eyeHideIcon.classList.remove('hidden');
        } else {
          // Jika sekarang teks sembunyi (Hide), tampilkan mata terbuka, sembunyikan mata tertutup
          eyeShowIcon.classList.remove('hidden');
          eyeHideIcon.classList.add('hidden');
        }
      });
    }
  });
    </script>
  </body>
</html>