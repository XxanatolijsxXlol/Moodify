<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">
            Search Results for "{{ $query }}"
        </h1>

        @if($users->isEmpty())
            <p class="text-gray-600 dark:text-gray-400">No users found matching your search.</p>
        @else
            <div class="grid grid-cols-1 gap-4">
                @foreach($users as $user)
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md flex items-center space-x-4">
                        <img src="{{ $user->profile && $user->profile->image ? asset('storage/' . $user->profile->image) : asset('images/default-profile.png') }}" 
                             alt="{{ $user->username }}" 
                             class="w-12 h-12 rounded-full">
                        <div>
                            <a href="/profiles/{{ $user->id }}" 
                               class="text-lg font-semibold text-indigo-600 dark:text-pink-400 hover:underline">
                                {{ $user->name }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>