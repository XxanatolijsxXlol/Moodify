<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-indigo-800 dark:text-gray-100">Available Themes</h1>
            <a href="{{ route('themes.create') }}"
               class="inline-flex items-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-lg shadow-md hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create New Theme
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- Default Theme Card (Now interactive) --}}
            <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                <div class="h-48 w-full">
                    <img src="{{ asset('storage/themes/thumbnails/default-featured-image.jpg') }}"
                         alt="Default Theme Thumbnail"
                         class="w-full h-full object-cover rounded-t-xl">
                </div>

                <div class="p-6">
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="text-xl font-semibold text-indigo-700 dark:text-gray-100">Default Theme</h2>
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                            System
                        </span>
                    </div>

                    <p class="text-sm text-gray-500 dark:text-gray-300 line-clamp-3 mb-4">
                        The default system theme. Cannot be customized or deleted.
                    </p>

                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Created by <span class="font-medium">System</span>
                    </p>

                    <div class="flex items-center space-x-2">
                        {{-- Form to activate default theme --}}
                        <form action="{{ route('themes.activateDefault') }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit"
                                    class="w-full inline-flex justify-center items-center bg-gradient-to-r {{ !$activeTheme || ($activeTheme && $activeTheme->id === null) ? 'from-gray-400 to-gray-500 opacity-75 cursor-not-allowed' : 'from-blue-600 to-indigo-600' }} text-white px-4 py-2 rounded-lg shadow-md hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                    {{ !$activeTheme || ($activeTheme && $activeTheme->id === null) ? 'disabled' : '' }}>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" {{ !$activeTheme || ($activeTheme && $activeTheme->id === null) ? '' : 'hidden' }}>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ !$activeTheme || ($activeTheme && $activeTheme->id === null) ? 'Active' : 'Activate' }}
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Check if the activeTheme is null or has a specific identifier for default --}}
                @if (!$activeTheme || ($activeTheme && $activeTheme->id === null))
                    <div class="absolute top-4 right-4 bg-blue-600 text-white text-xs font-semibold px-2.5 py-1 rounded-full">
                        Current Theme
                    </div>
                @endif
            </div>

            @forelse ($themes as $theme)
                <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="h-48 w-full">
                        <img src="{{ $theme->thumbnail ? asset('storage/' . $theme->thumbnail) : asset('images/default-theme.png') }}"
                             alt="{{ $theme->name }} Thumbnail"
                             class="w-full h-full object-cover rounded-t-xl">
                    </div>

                    <div class="p-6">
                        <div class="flex justify-between items-center mb-3">
                            <h2 class="text-xl font-semibold text-indigo-700 dark:text-gray-100">{{ $theme->name }}</h2>
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $theme->is_public ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                {{ $theme->is_public ? 'Public' : 'Private' }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-500 dark:text-gray-300 line-clamp-3 mb-4">
                            {{ $theme->description ?? 'No description available.' }}
                        </p>

                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Created by <span class="font-medium">{{ $theme->creator->username ?? 'System' }}</span>
                        </p>

                        <div class="flex items-center space-x-2">
                            <form action="{{ route('themes.activate', $theme->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit"
                                        class="w-full inline-flex justify-center items-center bg-gradient-to-r {{ $activeTheme && $activeTheme->id === $theme->id ? 'from-gray-400 to-gray-500 opacity-75 cursor-not-allowed' : 'from-blue-600 to-indigo-600' }} text-white px-4 py-2 rounded-lg shadow-md hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ $activeTheme && $activeTheme->id === $theme->id ? 'disabled' : '' }}>
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" {{ $activeTheme && $activeTheme->id === $theme->id ? '' : 'hidden' }}>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ $activeTheme && $activeTheme->id === $theme->id ? 'Active' : 'Activate' }}
                                </button>
                            </form>

                            @if (Auth::id() === $theme->creator_id || (Auth::user()->is_admin ?? false)) {{-- Added null coalescing for is_admin --}}
                                <form action="{{ route('themes.destroy', $theme->id) }}" method="POST" x-data="{ confirmDelete: false }">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            @click.prevent="confirmDelete = true"
                                            class="inline-flex items-center bg-red-600 text-white px-3 py-2 rounded-lg shadow-md hover:bg-red-700 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                            x-show="!confirmDelete">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete
                                    </button>
                                    <div x-show="confirmDelete" class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Confirm delete?</span>
                                        <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded-lg hover:bg-red-700">Yes</button>
                                        <button type="button" @click="confirmDelete = false" class="bg-gray-300 text-gray-800 px-3 py-1 rounded-lg hover:bg-gray-400 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">No</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>

                    @if ($activeTheme && $activeTheme->id === $theme->id)
                        <div class="absolute top-4 right-4 bg-blue-600 text-white text-xs font-semibold px-2.5 py-1 rounded-full">
                            Current Theme
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-lg text-gray-500 dark:text-gray-400">No custom themes available yet.</p>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        .hover\:shadow-xl:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        button, a {
            transition: all 0.3s ease-in-out;
        }
    </style>
</x-app-layout>