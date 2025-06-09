<x-app-layout>
    <div class="py-8 bg-white dark:bg-gray-900 min-h-screen" id="color-main-container" data-selector="#color-main-container">
        <div class="max-w-5xl mx-auto px-0 sm:px-6 lg:px-8"> {{-- Mobile: no horizontal padding for full width profile --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-2 sm:p-6 mb-6 sm:mb-8" id="color-profile-section" data-selector="#color-profile-section"> {{-- p-2 for tighter mobile padding --}}
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-2 sm:gap-6"> {{-- gap-2 for tighter mobile spacing --}}
                    <div class="flex-shrink-0">
                        <img src="{{ isset($user->profile) && $user->profile->image ? asset('storage/' . $user->profile->image) : asset('images/default-profile.png') }}"
                             alt="{{ $user->username }} profile image"
                             class="rounded-full w-28 h-28 sm:w-32 sm:h-32 md:w-40 md:h-40 object-cover border-2 border-gray-100 dark:border-gray-700">
                        <input type="hidden" id="auth-user-id" value="{{ auth()->user()->id ?? '' }}">
                        <input type="hidden" id="profile-user-id" value="{{ $user->id }}">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                    </div>
                    <div class="flex-grow text-center sm:text-left">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-4">
                            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white" id="color-username" data-selector="#color-username">{{ $user->username }}</h1>
                            @auth
                                @if (auth()->user()->id === $user->id)
                                    {{-- "Add new post" button for desktop/tablet --}}
                                    <a href="/p/create" id="add-new-post-desktop"
                                       class="hidden sm:inline-flex items-center justify-center p-2 text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 transition duration-150 ease-in-out">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                        <span class="ml-1">New Post</span>
                                    </a>
                                @else
                                    <div>
                                        @if ($isFollowing)
                                            <button id="follow-button" data-user-id="{{ $user->id }}"
                                                    data-is-following="true"
                                                    class="bg-gray-200 text-gray-900 px-3 sm:px-4 py-1 sm:py-1.5 rounded-full text-xs sm:text-sm font-medium hover:bg-gray-300 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                                                Following
                                            </button>
                                        @else
                                            <button id="follow-button" data-user-id="{{ $user->id }}"
                                                    data-is-following="false"
                                                    class="bg-blue-600 text-white px-3 sm:px-4 py-1 sm:py-1.5 rounded-full text-xs sm:text-sm font-medium hover:bg-blue-700 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-700">
                                                Follow
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            @endauth
                        </div>

                        <div class="flex justify-center sm:justify-start space-x-2 sm:space-x-8 mt-3 sm:mt-4 text-gray-700 dark:text-gray-300 text-sm sm:text-base" id="color-stats-text" data-selector="#color-stats-text"> {{-- space-x-2 for tighter mobile spacing --}}
                            <div><strong class="font-semibold">{{ $user->posts->count() }}</strong> posts</div>
                            <div>
                                <strong class="font-semibold"><span id="follower-count">{{ $user->followers->count() }}</span></strong>
                                <button id="show-followers-modal" class="ml-1 text-gray-700 dark:text-gray-300 hover:underline focus:outline-none"><div id="color-stats-text">followers </div> </button>
                            </div>
                            <div>
                                <strong class="font-semibold"><span id="following-count">{{ $user->following->count() }}</span></strong>
                                <button id="show-following-modal" class="ml-1 text-gray-700 dark:text-gray-300 hover:underline focus:outline-none"><div id="color-stats-text">following</div></button>
                            </div>
                        </div>

                        <div class="mt-3 sm:mt-4 text-gray-800 dark:text-gray-200 text-sm sm:text-base" id="color-bio-text" data-selector="#color-bio-text">
                            <p class="font-semibold">{{ $user->profile->title ?? '' }}</p>
                            <p class="mt-1">{{ $user->profile->description ?? '' }}</p>
                            @if($user->profile && $user->profile->url)
                                <a href="{{ $user->profile->url }}" target="_blank" class="text-blue-600 dark:text-gray-300 hover:underline mt-1 inline-block">{{ $user->profile->url }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Gallery Separator Line with Text --}}
            <div class="relative flex py-5 items-center w-full">
                <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
                <span id="color-sidebar-icon" class="flex-shrink mx-4 text-gray-500 dark:text-gray-400 font-semibold text-lg">GALLERY</span>
                <div id="color-sidebar-icon" class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
            </div>

            <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 sm:gap-4">
                @foreach($user->posts as $post)
                    <a href="/p/{{ $post->id }}" class="group relative overflow-hidden aspect-square">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center">
                            <div class="text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex space-x-2 sm:space-x-4">
                                <span class="flex items-center text-xs sm:text-sm">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                    {{ $post->likes_count }}
                                </span>
                                <span class="flex items-center text-xs sm:text-sm">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
                                    </svg>
                                    {{ $post->comments_count }}
                                </span>
                            </div>
                        </div>
                        <img src="/storage/{{ $post->image }}"
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                             alt="Post image">
                    </a>
                @endforeach
            </div>
        </div>

      
        @auth
            @if (auth()->user()->id === $user->id)
                <a href="/p/create" id="add-new-post-mobile"
                   class="fixed bottom-6 right-6 bg-blue-600 text-white p-3 rounded-full shadow-lg sm:hidden
                          hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 transition duration-150 ease-in-out z-50">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </a>
            @endif
        @endauth


        <div id="followers-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 sm:p-6 w-full max-w-md mx-4 shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">Followers</h2>
                    <button id="close-followers" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-gray-400 text-2xl focus:outline-none">×</button>
                </div>
                <div class="max-h-64 sm:max-h-96 overflow-y-auto" id="followers-list-container">
                    <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base text-center py-4" id="loading-followers-message">Loading followers...</p>
                </div>
            </div>
        </div>

        <div id="following-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 sm:p-6 w-full max-w-md mx-4 shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">Following</h2>
                    <button id="close-following" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-gray-400 text-2xl focus:outline-none">×</button>
                </div>
                <div class="max-h-64 sm:max-h-96 overflow-y-auto" id="following-list-container">
                    <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base text-center py-4" id="loading-following-message">Loading following...</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Existing mobile optimizations (largely retained as they are sensible) */
        @media (max-width: 480px) {
            #color-profile-section {
                padding: 0.5rem; /* Equivalent to p-2 */
            }
            #color-username {
                font-size: 1.25rem;
            }
            #color-stats-text {
                font-size: 0.875rem;
                gap: 0.5rem; /* Equivalent to space-x-2 */
            }
            #color-bio-text {
                font-size: 0.875rem;
            }
            /* Hide the desktop "Add new post" button on mobile */
            #add-new-post-desktop {
                display: none !important;
            }
        }

        /* Adjustments for tablets/smaller desktops */
        @media (min-width: 481px) {
            /* Hide the mobile FAB on larger screens */
            #add-new-post-mobile {
                display: none !important;
            }
        }

        /* Larger screen profile section adjustment */
        @media (min-width: 768px) {
            #color-profile-section {
                margin-top: 2rem;
            }
        }

        /* Specific overrides for button colors to ensure consistency */
        button.bg-blue-600.dark\:bg-gray-600 {
            background-color: #2563eb !important; /* Original light mode blue */
        }
        button.bg-gray-200.dark\:bg-gray-700 {
            background-color: #e5e7eb !important; /* Original light mode gray */
        }

        /* Ensure the mobile FAB uses the specified dark mode color */
        #add-new-post-mobile.dark\:bg-blue-700 {
            background-color: #1d4ed8 !important; /* Dark mode blue for FAB */
        }
        #add-new-post-mobile.dark\:hover\:bg-blue-800:hover {
            background-color: #1e40af !important; /* Dark mode hover blue for FAB */
        }
    </style>
   
 @vite('resources/js/follow.js')
</x-app-layout>