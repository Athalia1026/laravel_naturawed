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
            <h2 class="text-[28px] font-semibold text-[#1a1a1a]">Create Account</h2>
            <p class="text-sm text-[#7a7a7a]">Let's get started with a new account.</p>
          </div>

          <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-5">
            @csrf
            
            <input type="hidden" name="role" value="{{ request('role', 'customer') }}" />

            <div class="w-full relative">
              <input type="text" name="name" value="{{ old('name') }}" placeholder="Full Name / Business Name" required autofocus
                     class="w-full px-5 py-4 border border-[#e0e0e0] rounded-xl text-sm focus:border-[#0b452c]" />
              <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500 text-xs" />
            </div>

            <div class="w-full relative">
              <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required
                     class="w-full px-5 py-4 border border-[#e0e0e0] rounded-xl text-sm focus:border-[#0b452c]" />
              <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs" />
            </div>

            <div class="w-full relative">
              <input type="password" name="password" placeholder="Password" required
                     class="w-full px-5 py-4 border border-[#e0e0e0] rounded-xl text-sm focus:border-[#0b452c]" />
              <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs" />
            </div>

            <div class="w-full relative">
              <input type="password" name="password_confirmation" placeholder="Confirm Password" required
                     class="w-full px-5 py-4 border border-[#e0e0e0] rounded-xl text-sm focus:border-[#0b452c]" />
              <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 text-xs" />
            </div>

            <button type="submit" class="w-full p-4 mt-2 border-none rounded-xl bg-[#06402b] text-white font-semibold hover:bg-[#042e1f]">
              Sign Up
            </button>
          </form>

          <div class="mt-[30px] text-center text-sm text-[#7a7a7a]">
            <p>Already have an account? <a class="text-[#0b452c] font-semibold hover:underline" href="{{ route('login') }}">Log in</a></p>
          </div>
          
          <div class="mt-[20px] text-center text-sm text-[#7a7a7a]">
            <p>
              <a class="text-[#0b452c] font-semibold hover:underline" href="{{ route('register', ['role' => 'vendor']) }}">Be a vendor?</a>
              <b> OR </b>
              <a class="text-[#0b452c] font-semibold hover:underline" href="{{ route('register', ['role' => 'journalist']) }}">Be a journalist?</a>
            </p>
          </div>
        </div>
      </div>
    </main>
  </body>
</html>