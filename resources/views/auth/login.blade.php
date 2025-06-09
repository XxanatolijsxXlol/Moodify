<x-guest-layout>
  

    <x-auth-session-status class="mb-4 text-center text-red-400" :status="session('status')" />

    <div class="login-register-card relative bg-gray-800 rounded-xl px-8 py-10 sm:px-12 sm:py-14 overflow-hidden">
        
        <h2 class="text-4xl font-extrabold text-white mb-10 text-center leading-tight tracking-tight">
            Log in to <span class="moodify-text">moodify</span><br>account
        </h2>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-6">
                <x-input-label for="email" :value="__('Email')" class="block text-base font-medium text-white mb-2" />
                <x-text-input id="email" class="moodify-input block w-full px-5 py-3 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-blue-400 focus:border-blue-400 placeholder-gray-400 transition duration-300 ease-in-out" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="your@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400 text-sm" />
            </div>

            <div class="mb-8">
                <x-input-label for="password" :value="__('Password')" class="block text-base font-medium text-white mb-2" />
                <x-text-input id="password" class="moodify-input block w-full px-5 py-3 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-blue-400 focus:border-blue-400 placeholder-gray-400 transition duration-300 ease-in-out"
                                type="password"
                                name="password"
                                required autocomplete="current-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400 text-sm" />
            </div>

            <div class="flex items-center justify-between mb-10">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-600 bg-gray-700 text-purple-500 shadow-sm focus:ring-purple-500 focus:ring-offset-gray-900" name="remember">
                    <span class="ms-2 text-sm text-gray-300">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-blue-400 hover:text-blue-300 font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 focus:ring-offset-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <x-primary-button class="moodify-button w-full px-6 py-3.5 bg-gradient-to-r from-purple-600 to-blue-600 text-white text-lg font-semibold rounded-lg shadow-lg hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 focus:ring-offset-gray-900 transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105">
                {{ __('Log in') }}
            </x-primary-button>
        </form>

        <div class="mt-12 text-center">
            <p class="text-base text-gray-300">
                Don't have an account?
                <a class="text-blue-400 hover:text-blue-300 font-semibold transition-colors duration-200" href="{{ route('register') }}">
                    {{ __('Register now') }}
                </a>
            </p>
        </div>
    </div>

    <style>
        /* Ensures the entire page is a flex container for centering */
        html, body, #app {
            height: 100%;
            margin: 0;
            display: flex;
            align-items: center; /* Center vertically */
            justify-content: center; /* Center horizontally */
            overflow: hidden; /* Prevent scrollbars */
            box-sizing: border-box; /* Include padding in element's total width and height */
        }
        
        /* Adjust guest-layout if it has its own padding/margin that causes issues */
        .min-h-screen {
            min-height: 100vh;
        }

        /* Card specific styles */
        .login-register-card {
            max-width: 480px; /* Constrain width for elegance */
            width: 95%; /* Ensure it's responsive on smaller screens */
            padding: 2.5rem 2rem; /* Default padding */
            z-index: 10; /* Ensure card is above other fixed background elements */
            /* Removed box-shadow and backdrop-blur-md */
            animation: card-appear 0.5s ease-out forwards; /* Simple animation to appear */
            position: relative; /* Needed for any future internal absolute positioning */
        }
        @media (min-width: 640px) { /* sm breakpoint */
            .login-register-card {
                padding: 3.5rem 3rem; /* Larger padding on sm screens and up */
            }
        }
        
        @keyframes card-appear {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        /* Moodify text styling for the title */
        .moodify-text {
            font-size: 1.2em; /* Bigger "moodify" within the heading */
            font-weight: 900; /* Extra bold */
            display: inline-block;
            background: linear-gradient(90deg, #845ef7, #f76707, #fcc419, #20c997, #845ef7);
            background-size: 500% auto;
            color: #fff; /* Fallback for older browsers */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
            animation: gradient-animation 8s linear infinite; /* Slower, smoother animation */
            padding: 0 0.2em; /* Add some padding around the text */
            line-height: 1.2; /* Adjust line height if needed */
        }

        @keyframes gradient-animation {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }

        /* Input specific styling */
        .moodify-input {
            color: white !important; /* Force text color to white */
        }

        .moodify-input::placeholder {
            color: #a0aec0; /* A good grey for dark inputs */
            opacity: 1; /* For Firefox */
             background: linear-gradient(90deg, #845ef7, #f76707, #fcc419, #20c997, #845ef7);
            background-size: 500% auto;
            color: #fff; /* Fallback for older browsers */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
            animation: gradient-animation 8s linear infinite; /* Slower, smoother animation */
            padding: 0 0.2em; /* Add some padding around the text */
            line-height: 1.2; /* Adjust line height if needed */
        }
        /* Dark mode specific placeholder */
        .dark .moodify-input::placeholder {
            color: #90a4ae; 
        }

        /* Removed Animated background blobs and their keyframes */
    </style>
</x-guest-layout>