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
  </body>
</html>