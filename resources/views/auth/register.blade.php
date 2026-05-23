<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up Naturawed</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#d1e2da] flex justify-center items-center min-h-screen overflow-x-hidden">
  <main class="flex flex-col md:flex-row w-full max-w-[1100px] items-center justify-center p-8 gap-12">
    <div class="flex-1 flex flex-col items-center justify-center text-center">
      <a href="{{ url('/') }}">
        <img src="{{ asset('assets/image/logo.svg') }}" alt="logo" class="w-[200px] mb-5" />
        <h1 class="text-[32px] md:text-[42px] font-bold text-[#0b452c]">Naturawed</h1>
      </a>
    </div>

    <div class="flex-1 flex justify-center w-full">
      <div class="bg-white p-10 rounded-[24px] w-full max-w-[450px] shadow-[0_10px_30px_rgba(0,0,0,0.05)]">
        <div class="text-center mb-[30px]">
      <h2 class="text-[28px] font-semibold text-[#1a1a1a]">
        @if($role === 'journalist')
          Join as Journalist
        @elseif($role === 'vendor')
          Join as Vendor
        @else
          Create Account
        @endif
      </h2>
      
      <p class="text-sm text-[#7a7a7a] {{ $role === 'journalist' || $role === 'vendor' ? 'mb-[30px]' : '' }}">
        @if($role === 'journalist')
          Share your editorial voice and wedding inspirations.
        @else
          Let's get started with a new account.
        @endif
      </p>
    </div>

        <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-5" id="SignUpForm">
          @csrf
          <input type="hidden" id="role" name="role" value="{{ $role }}" />

          <div class="w-full relative">
            <input
              class="w-full px-5 py-4 border border-[#e0e0e0] rounded-xl text-sm text-[#333] outline-none transition-colors duration-300 placeholder:text-[#a0a0a0] placeholder:font-medium focus:border-[#0b452c]"
              type="text" name="name" id="name" value="{{ old('name') }}"
              placeholder="{{ $role === 'vendor' ? 'Business Name' : ($role === 'journalist' ? 'Full Name / Pen Name' : 'Full Name') }}"
              required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
          </div>

          <div class="w-full relative">
            <input
              class="w-full px-5 py-4 border border-[#e0e0e0] rounded-xl text-sm text-[#333] outline-none transition-colors duration-300 placeholder:text-[#a0a0a0] placeholder:font-medium focus:border-[#0b452c]"
              type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Email" required />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
          </div>

          @if($role === 'vendor')
            <div class="w-full relative">
              <input
                class="w-full px-5 py-4 border border-[#e0e0e0] rounded-xl text-sm text-[#333] outline-none transition-colors duration-300 placeholder:text-[#a0a0a0] placeholder:font-medium focus:border-[#0b452c]"
                type="text" name="address" id="address" value="{{ old('address') }}" placeholder="Business Address"
                required />
              <x-input-error :messages="$errors->get('address')" class="mt-1" />
            </div>
          @endif

          <div class="w-full relative">
            <input
              class="w-full px-5 py-4 border border-[#e0e0e0] rounded-xl text-sm text-[#333] outline-none transition-colors duration-300 placeholder:text-[#a0a0a0] placeholder:font-medium focus:border-[#0b452c]"
              type="password" name="password" id="inputPassword" placeholder="Password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />

            <button type="button" id="togglePasswordMain"
              class="absolute right-5 top-1/2 -translate-y-1/2 cursor-pointer text-[#a0a0a0] hover:text-[#0b452c] transition-colors focus:outline-none"
              aria-label="Toggle password visibility">
              <svg id="eyeShowMain" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <svg id="eyeHideMain" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
              </svg>
            </button>
          </div>

          <div class="w-full relative">
            <input
              class="w-full px-5 py-4 border border-[#e0e0e0] rounded-xl text-sm text-[#333] outline-none transition-colors duration-300 placeholder:text-[#a0a0a0] placeholder:font-medium focus:border-[#0b452c]"
              type="password" name="password_confirmation" id="inputConfirmPassword" placeholder="Confirm Password"
              required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />

            <button type="button" id="togglePasswordConfirm"
              class="absolute right-5 top-1/2 -translate-y-1/2 cursor-pointer text-[#a0a0a0] hover:text-[#0b452c] transition-colors focus:outline-none"
              aria-label="Toggle password visibility">
              <svg id="eyeShowConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <svg id="eyeHideConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
              </svg>
            </button>
          </div>

          <button type="submit"
            class="w-full p-4 mt-2.5 border-none rounded-xl bg-[#06402b] text-white text-base font-semibold cursor-pointer transition-colors duration-300 hover:bg-[#042e1f]">
            {{ $role === 'journalist' ? 'Create Journalist Account' : 'Sign Up' }}
          </button>
        </form>

        <div class="mt-[30px] text-center text-sm text-[#7a7a7a]">
          <p>Already have an account? <a class="text-[#0b452c] font-semibold hover:underline"
              href="{{ route('login') }}">Log in</a></p>
        </div>

        <div class="mt-[20px] text-center text-sm text-[#7a7a7a]">
          <p>
            @if($role === 'vendor')
              <a class="text-[#0b452c] font-semibold hover:underline" href="{{ route('register') }}">Are you a couple?</a>
              <b> OR </b>
              <a class="text-[#0b452c] font-semibold hover:underline"
                href="{{ route('register', ['role' => 'journalist']) }}">Be a journalist?</a>

            @elseif($role === 'journalist')
              <a class="text-[#0b452c] font-semibold hover:underline"
                href="{{ route('register', ['role' => 'vendor']) }}">Be a vendor?</a>
              <b> OR </b>
              <a class="text-[#0b452c] font-semibold hover:underline" href="{{ route('register') }}">Are you a couple?</a>

            @else
              <a class="text-[#0b452c] font-semibold hover:underline"
                href="{{ route('register', ['role' => 'vendor']) }}">Be a vendor?</a>
              <b> OR </b>
              <a class="text-[#0b452c] font-semibold hover:underline"
                href="{{ route('register', ['role' => 'journalist']) }}">Be a journalist?</a>
            @endif
          </p>
        </div>
      </div>
    </div>
  </main>
  <script>
    document.addEventListener('DOMContentLoaded', function () {

      // Helper function agar kita tidak menulis kode berulang-ulang
      function setupPasswordToggle(inputFieldId, buttonId, showIconId, hideIconId) {
        const inputField = document.getElementById(inputFieldId);
        const toggleButton = document.getElementById(buttonId);
        const showIcon = document.getElementById(showIconId);
        const hideIcon = document.getElementById(hideIconId);

        if (inputField && toggleButton) {
          toggleButton.addEventListener('click', function () {
            const isPassword = inputField.getAttribute('type') === 'password';

            // Ubah tipe input secara dinamis
            inputField.setAttribute('type', isPassword ? 'text' : 'password');

            // Tukar visibilitas ikon mata menggunakan class 'hidden' Tailwind
            if (isPassword) {
              showIcon.classList.add('hidden');
              hideIcon.classList.remove('hidden');
            } else {
              showIcon.classList.remove('hidden');
              hideIcon.classList.add('hidden');
            }
          });
        }
      }

      // Aktifkan fungsi mata untuk Kolom Utama
      setupPasswordToggle('inputPassword', 'togglePasswordMain', 'eyeShowMain', 'eyeHideMain');

      // Aktifkan fungsi mata untuk Kolom Konfirmasi
      setupPasswordToggle('inputConfirmPassword', 'togglePasswordConfirm', 'eyeShowConfirm', 'eyeHideConfirm');
    });
  </script>
</body>

</html>