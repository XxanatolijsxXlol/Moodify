<x-guest-layout>
    <div class="login-register-card relative bg-gray-800 rounded-xl px-6 py-8 sm:px-10 sm:py-10">
        <h2 class="text-3xl font-extrabold text-white mb-6 text-center leading-tight tracking-tight">
            Create your <span class="moodify-text">moodify</span> account
        </h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <x-input-label for="name" :value="__('Name')" class="block text-base font-medium text-white mb-1" />
                <x-text-input id="name" class="moodify-input block w-full px-4 py-2.5 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-blue-400 focus:border-blue-400 placeholder-gray-400 transition duration-300 ease-in-out" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
                <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-400 text-sm" />
            </div>

            <div class="mb-4">
                <x-input-label for="email" :value="__('Email')" class="block text-base font-medium text-white mb-1" />
                <x-text-input id="email" class="moodify-input block w-full px-4 py-2.5 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-blue-400 focus:border-blue-400 placeholder-gray-400 transition duration-300 ease-in-out" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="your@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-400 text-sm" />
            </div>

            <div class="mb-4">
                <x-input-label for="username" :value="__('Username')" class="block text-base font-medium text-white mb-1" />
                <x-text-input id="username" class="moodify-input block w-full px-4 py-2.5 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-blue-400 focus:border-blue-400 placeholder-gray-400 transition duration-300 ease-in-out" type="text" name="username" :value="old('username')" required autocomplete="username" placeholder="johndoe123" />
                <x-input-error :messages="$errors->get('username')" class="mt-1 text-red-400 text-sm" />
            </div>

            <div class="mb-4">
                <x-input-label for="password" :value="__('Password')" class="block text-base font-medium text-white mb-1" />
                <x-text-input id="password" class="moodify-input block w-full px-4 py-2.5 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-blue-400 focus:border-blue-400 placeholder-gray-400 transition duration-300 ease-in-out" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-400 text-sm" />
            </div>

            <div class="mb-6">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="block text-base font-medium text-white mb-1" />
                <x-text-input id="password_confirmation" class="moodify-input block w-full px-4 py-2.5 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-blue-400 focus:border-blue-400 placeholder-gray-400 transition duration-300 ease-in-out" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-400 text-sm" />
            </div>

            <x-primary-button class="moodify-button w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white text-base font-semibold rounded-lg shadow-lg hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 focus:ring-offset-gray-900 transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105">
                {{ __('Register') }}
            </x-primary-button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm text-gray-300">
                Already have an account?
                <a class="text-blue-400 hover:text-blue-300 font-semibold transition-colors duration-200" href="{{ route('login') }}">
                    {{ __('Log in') }}
                </a>
            </p>
        </div>
    </div>

    <style>
        /* Ensure the entire page is a flex container for centering */
        html, body {
            min-height: 100vh;
            margin: 0;
            overflow-y: auto;
            box-sizing: border-box;
            /* Background color removed */
        }

        #app {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1.5rem 0;
            box-sizing: border-box;
        }

        .min-h-screen {
            min-height: 100vh;
        }

        /* Card specific styles */
        .login-register-card {
            max-width: 480px;
            width: 90%;
            padding: 2rem 1.5rem;
            z-index: 10;
            animation: card-appear 0.5s ease-out forwards;
            position: relative;
            margin: 1rem auto;
        }

        @media (min-width: 640px) {
            .login-register-card {
                padding: 2.5rem 2.5rem;
            }
        }

        @keyframes card-appear {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        /* Moodify text styling */
        .moodify-text {
            font-size: 1.1em;
            font-weight: 900;
            display: inline-block;
            background: linear-gradient(90deg, #845ef7, #f76707, #fcc419, #20c997, #845ef7);
            background-size: 500% auto;
            color: #fff;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
            animation: gradient-animation 8s linear infinite;
            padding: 0 0.2em;
            line-height: 1.2;
        }

        @keyframes gradient-animation {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }

        /* Input styling */
        .moodify-input {
            color: white !important;
        }

        .moodify-input::placeholder {
           
            opacity: 1;
             background: linear-gradient(90deg, #845ef7, #f76707, #fcc419, #20c997, #845ef7);
            background-size: 500% auto;
            color: #fff;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
            animation: gradient-animation 8s linear infinite;
            padding: 0 0.2em;
            line-height: 1.2;
        }

        .dark .moodify-input::placeholder {
            color: #90a4ae;
        }
    </style>
</x-guest-layout>