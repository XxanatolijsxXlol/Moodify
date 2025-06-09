<style>
@keyframes animateGradient {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

.animate-moodify {
  background: linear-gradient(270deg, #ff0080, #7928ca, #2afadf, #00ff87);
  background-size: 800% 800%;
  animation: animateGradient 8s ease infinite;
  background-clip: text;
  -webkit-background-clip: text;
  color: transparent;
}
</style>

<nav class="bg-white fixed w-full top-0 z-50 shadow-md dark:bg-gray-900 dark:shadow-xl"
     id="color-nav-container" data-selector="#color-nav-container"
     x-data="{
         darkMode: localStorage.getItem('darkMode') === 'true',
         searchOpen: false,
         init() {
             if (this.darkMode) {
                 document.documentElement.classList.add('dark');
             } else {
                 document.documentElement.classList.remove('dark');
             }
         },
         toggleDarkMode() {
             this.darkMode = !this.darkMode;
             localStorage.setItem('darkMode', this.darkMode);
             document.documentElement.classList.toggle('dark', this.darkMode);
         }
     }"
     x-init="init()">
    <div class="absolute bottom-0 left-0 w-full h-1 animate-moodify"></div>

    <div class="hidden md:block fixed left-0 top-0 h-15 w-16 bg-white dark:bg-gray-900 z-10 shadow-sm" id="color-nav-container" data-selector="#color-nav-container"></div>

    <div class="max-w-7xl mx-auto mr-5 ml-2 md:ml-5 sm:px-4 lg:px-8 relative">
        <div class="flex justify-between items-center h-14 md:h-16 py-1 md:py-2 space-x-2 md:space-x-4">
            <div class="flex items-center">
                <span class="text-2xl pl-2 md:text-3xl font-bold animate-moodify hover:scale-110 transition-transform duration-500"
                      id="color-nav-brand" data-selector="#color-nav-brand">
                    <a href="/" class="block">Moodify</a>
                </span>
            </div>

            <div class="flex items-center flex-1 justify-center">
                <!-- Mobile search button -->
                <button @click="searchOpen = !searchOpen" class="md:hidden p-2 bg-gray-200 dark:bg-gray-700 rounded-full text-indigo-600 dark:text-pink-400 flex items-center justify-center">
                    <span id="color-search-icon" data-selector="#color-search-icon">
                        <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                </button>

                <!-- Desktop search -->
                <div class="hidden md:flex items-center flex-1 justify-center w-full ml-20 max-w-sm md:max-w-lg lg:max-w-xl"
                     x-data="{
                         query: '',
                         results: [],
                         dropdownOpen: false,
                         async fetchResults() {
                             if (this.query.length === 0) {
                                 this.results = [];
                                 this.dropdownOpen = false;
                                 return;
                             }
                             try {
                                 const response = await fetch(`/api/search?query=${encodeURIComponent(this.query)}`);
                                 if (!response.ok) {
                                     console.error('Search API error:', response.status, response.statusText);
                                     this.results = [];
                                     this.dropdownOpen = false;
                                     return;
                                 }
                                 const data = await response.json();
                                 if (Array.isArray(data.users)) {
                                     this.results = data.users;
                                     this.dropdownOpen = this.results.length > 0;
                                 } else {
                                     console.error('Search API returned unexpected data format:', data);
                                     this.results = [];
                                     this.dropdownOpen = false;
                                 }
                             } catch (error) {
                                 console.error('Error fetching search results:', error);
                                 this.results = [];
                                 this.dropdownOpen = false;
                             }
                         }
                     }"
                     @click.away="dropdownOpen = false">
                    <form action="{{ route('search') }}" method="GET" class="relative w-full">
                        <input id="search-input" type="text" name="query" placeholder="Search profiles..."
                               class="w-full px-4 py-2.5 rounded-full border-none shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white text-gray-800 dark:bg-gray-800 dark:focus:ring-pink-500 dark:text-gray-100 text-base md:text-lg"
                               x-model="query"
                               @input.debounce.300ms="fetchResults()"
                               @focus="dropdownOpen = query.length > 0 && results.length > 0"
                               value="{{ request('query') }}">
                        <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-indigo-500 dark:text-pink-400">
                            <span id="color-search-icon" data-selector="#color-search-icon">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </span>
                        </button>

                        <div x-show="dropdownOpen" id="search-results"
                             class="absolute top-full left-0 w-full mt-2 bg-white dark:bg-gray-800 rounded-lg shadow-lg z-20 max-h-64 overflow-y-auto">
                            <template x-if="results.length === 0">
                                <div class="px-4 py-2 text-gray-600 dark:text-gray-400">No users found.</div>
                            </template>
                            <template x-for="user in results" :key="user.id">
                                <a :href="'/profiles/' + user.id" class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <img :src="user.profile_image || '{{ asset('images/default-profile.png') }}'" alt="user.username" class="w-8 h-8 rounded-full mr-3 object-cover">
                                    <div>
                                        <span class="text-indigo-600 dark:text-pink-400 font-semibold" x-text="user.name"></span>
                                        <span class="text-gray-600 dark:text-gray-400 block" x-text="'@' + user.username"></span>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </form>
                </div>
            </div>

            <div class="flex items-center space-x-1 md:space-x-4">
                @if (Auth::check())
                <div x-data="{
                    showNotifications: false,
                    notifications: [],
                    unreadCount: 0,
                    authenticatedUserId: {{ Auth::check() ? Auth::id() : 'null' }},
                    async fetchNotifications() {
                        if (!this.authenticatedUserId) {
                            console.log('User not authenticated, skipping notification fetch.');
                            return;
                        }
                        try {
                            const response = await fetch('/notifications', {
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            });
                            if (!response.ok) {
                                if (response.status === 401) {
                                    console.error('Unauthenticated. Redirecting to login.');
                                    window.location.href = '/login';
                                }
                                throw new Error(`Network response was not ok, status: ${response.status}`);
                            }
                            const data = await response.json();
                            if (Array.isArray(data)) {
                                this.notifications = data.map(n => ({
                                    ...n,
                                    created_at: n.created_at ? String(n.created_at) : null
                                }));
                                this.unreadCount = this.notifications.filter(n => !n.read).length;
                            } else {
                                console.error('Unexpected data format:', data);
                                this.notifications = [];
                                this.unreadCount = 0;
                            }
                        } catch (error) {
                            console.error('Error fetching notifications:', error);
                            this.notifications = [];
                            this.unreadCount = 0;
                        }
                    },
                    async markAsRead(ids) {
                        if (!this.authenticatedUserId || ids.length === 0) return;
                        try {
                            const response = await fetch('/notifications/read', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ ids })
                            });
                            if (!response.ok) {
                                throw new Error(`Failed to mark notifications as read, status: ${response.status}`);
                            }
                            const responseData = await response.json();
                            if (responseData.success) {
                                this.notifications = this.notifications.map(notification => {
                                    if (ids.includes(notification.id)) {
                                        return { ...notification, read: true };
                                    }
                                    return notification;
                                });
                                this.unreadCount = this.notifications.filter(n => !n.read).length;
                            }
                        } catch (error) {
                            console.error('Error marking notifications as read:', error);
                        }
                    },
                    markAllAsRead() {
                        const unreadIds = this.notifications.filter(n => !n.read).map(n => n.id);
                        if (unreadIds.length > 0) {
                            this.markAsRead(unreadIds);
                        }
                    },
                    getTimeAgo(dateString) {
                        if (!dateString) return 'Just now';
                        if (typeof moment !== 'undefined') {
                            return moment(dateString).fromNow();
                        }
                        const date = new Date(dateString);
                        if (isNaN(date.getTime())) {
                            return 'Just now';
                        }
                        const now = new Date();
                        const seconds = Math.floor((now - date) / 1000);
                        const intervals = [
                            { label: 'year', seconds: 31536000 },
                            { label: 'month', seconds: 2592000 },
                            { label: 'day', seconds: 86400 },
                            { label: 'hour', seconds: 3600 },
                            { label: 'minute', seconds: 60 }
                        ];
                        for (const interval of intervals) {
                            const count = Math.floor(seconds / interval.seconds);
                            if (count >= 1) {
                                return `${count} ${interval.label}${count === 1 ? '' : 's'} ago`;
                            }
                        }
                        return 'Just now';
                    },
                    getNotificationUrl(notification) {
                        if (!notification.subject_type || !notification.subject_id) {
                            return '#';
                        }
                        switch (notification.subject_type) {
                            case 'App\\Models\\Post':
                                return `/p/${notification.subject_id}`;
                            case 'App\\Models\\Comment':
                                return `/p/${notification.subject_id}`;
                            case 'App\\Models\\User':
                                return `/profiles/${notification.subject_id}`;
                            default:
                                return '#';
                        }
                    },
                    listenForNotifications() {
                        if (!this.authenticatedUserId || typeof Echo === 'undefined') {
                            return;
                        }
                        Echo.private(`notifications.${this.authenticatedUserId}`)
                            .listen('.new-notification', (e) => {
                                this.notifications.unshift({
                                    id: e.id,
                                    actor_id: e.actor_id,
                                    type: e.type,
                                    message: e.message,
                                    subject_id: e.subject_id,
                                    subject_type: e.subject_type,
                                    read: e.read,
                                    created_at: e.created_at ? String(e.created_at) : null,
                                    profile_image: e.profile_image,
                                    data: e.data
                                });
                                if (!e.read) {
                                    this.unreadCount++;
                                }
                            })
                            .error((error) => {
                                console.error('Echo channel error:', error);
                            });
                    }
                }"
                @click.away="showNotifications = false"
                x-init="fetchNotifications(); listenForNotifications()"
                class="relative">
                    <button @click="showNotifications = !showNotifications"
                            class="relative p-1 md:p-2 text-indigo-600 hover:text-indigo-800 dark:text-purple-400 dark:hover:text-purple-600">
                        <span id="color-notification-icon" data-selector="#color-notification-icon">
                            <svg class="w-6 h-6 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V4a3 3 0 00-6 0v1.083A6 6 0 002 11v3.159c0 .538-.214 1.055-.595 1.436L0 17h5m5 0v1a3 3 0 006 0v-1m-6 0h6"></path>
                            </svg>
                        </span>
                        <span x-show="unreadCount > 0"
                              x-text="unreadCount > 9 ? '9+' : unreadCount"
                              class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                        </span>
                    </button>

                    <div x-show="showNotifications"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 w-80 mt-2 bg-white rounded-md shadow-lg overflow-hidden z-50 dark:bg-gray-800">
                        <div class="py-2">
                            <div class="flex items-center justify-between px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 border-b border-gray-100 dark:border-gray-700">
                                <span>Notifications</span>
                                <button @click="markAllAsRead()"
                                        x-show="unreadCount > 0"
                                        class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-pink-400 dark:hover:text-pink-600">
                                    Mark all as read
                                </button>
                            </div>

                            <div class="max-h-64 overflow-y-auto">
                                <template x-if="notifications.length === 0">
                                    <div class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        No notifications yet.
                                    </div>
                                </template>

                                <template x-for="notification in notifications" :key="notification.id">
                                    <div class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 cursor-pointer"
                                         :class="{'bg-blue-100 dark:bg-blue-900/20': !notification.read}"
                                         @click="if (!notification.read) markAsRead([notification.id]); window.location.href = getNotificationUrl(notification);">
                                        <div class="flex items-start space-x-3">
                                            <a :href="'/profiles/' + notification.actor_id">
                                                <img :src="notification.profile_image"
                                                     class="w-7 h-7 rounded-full object-cover border-2 border-indigo-300 dark:border-purple-500"/>
                                            </a>
                                            <p class="text-sm font-medium" x-html="notification.message"></p>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-text="getTimeAgo(notification.created_at)"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if (isset($activeTheme))
                    <link href="{{ asset($activeTheme->css_path) }}" rel="stylesheet">
                @else
                    <button @click="toggleDarkMode()" class="hidden md:block p-2 text-indigo-600 hover:text-indigo-800 dark:text-pink-400 dark:hover:text-pink-600">
                        <span id="color-dark-mode-icon" data-selector="#color-dark-mode-icon">
                            <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </span>
                    </button>
                @endif

                @if (Auth::check())
                    <a href="/profile">
                        <div class="flex items-center">
                            <img src="{{ Auth::check() && isset(Auth::user()->profile) ? ( Auth::user()->profile->image ? asset('storage/' . Auth::user()->profile->image) : asset('images/default-profile.png') ) : asset('images/default-profile.png') }}" alt="{{ Auth::check() ? Auth::user()->username : 'Profile' }}" class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover">
                        </div>
                    </a>
                @else
                    <div class="flex items-center space-x-1 md:space-x-4">
                        <x-nav-link href="{{ route('login') }}" :active="false" class="text-indigo-600 hover:text-indigo-800 dark:text-pink-400 dark:hover:text-pink-600 text-xs md:text-sm">
                            {{ __('Login') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('register') }}" :active="false" class="text-indigo-600 hover:text-indigo-800 dark:text-pink-400 dark:hover:text-pink-600 text-xs md:text-sm">
                            {{ __('Register') }}
                        </x-nav-link>
                    </div>
                @endif
            </div>
        </div>

        <!-- Mobile search -->
        <div x-show="searchOpen" class="md:hidden px-2 py-1 bg-white dark:bg-gray-900" id="color-nav-container" data-selector="#color-nav-container"
             x-data="{
                 query: '',
                 results: [],
                 dropdownOpen: false,
                 async fetchResults() {
                     if (this.query.length === 0) {
                         this.results = [];
                         this.dropdownOpen = false;
                         return;
                     }
                     try {
                         const response = await fetch(`/api/search?query=${encodeURIComponent(this.query)}`);
                         if (!response.ok) {
                             console.error('Search API error:', response.status, response.statusText);
                             this.results = [];
                             this.dropdownOpen = false;
                             return;
                         }
                         const data = await response.json();
                         if (Array.isArray(data.users)) {
                             this.results = data.users;
                             this.dropdownOpen = this.results.length > 0;
                         } else {
                             console.error('Search API returned unexpected data format:', data);
                             this.results = [];
                             this.dropdownOpen = false;
                         }
                     } catch (error) {
                         console.error('Error fetching search results:', error);
                         this.results = [];
                         this.dropdownOpen = false;
                     }
                 }
             }" @click.away="dropdownOpen = false">
            <form action="{{ route('search') }}" method="GET" class="relative">
                <input type="text" name="query" placeholder="Search profiles..."
                       class="w-full px-2 py-1 rounded-full border-none shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white text-gray-800 dark:bg-gray-800 dark:focus:ring-pink-500 dark:text-gray-100 text-xs"
                       x-model="query"
                       @input.debounce.300ms="fetchResults()"
                       @focus="dropdownOpen = query.length > 0 && results.length > 0"
                       value="{{ request('query') }}">
                <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-indigo-500 dark:text-pink-400">
                    <span id="color-search-icon" data-selector="#color-search-icon">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                </button>

                <div x-show="dropdownOpen"
                     class="absolute top-full left-0 w-full mt-1 bg-white dark:bg-gray-800 rounded-lg shadow-lg z-20 max-h-48 overflow-y-auto">
                    <template x-if="results.length === 0">
                        <div class="px-2 py-1 text-gray-600 dark:text-gray-400 text-xs">No users found.</div>
                    </template>
                    <template x-for="user in results" :key="user.id">
                        <a :href="'/profiles/' + user.id" class="flex items-center px-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <img :src="user.profile_image || '{{ asset('images/default-profile.png') }}'" alt="user.username" class="w-6 h-6 rounded-full mr-2 object-cover">
                            <div>
                                <span class="text-indigo-600 dark:text-pink-400 font-semibold text-xs" x-text="user.name"></span>
                                <span class="text-gray-600 dark:text-gray-400 block text-xs" x-text="'@' + user.username"></span>
                            </div>
                        </a>
                    </template>
                </div>
            </form>
        </div>
    </nav>

    @if (Auth::check())
    <nav class="hidden md:block z-20 bg-white h-screen w-16 hover:w-64 fixed top-16 left-0 transition-all duration-300 ease-in-out overflow-hidden dark:bg-gray-900 shadow-md dark:shadow-xl group"
         id="color-nav-container" data-selector="#color-nav-container">
        <div class="flex flex-col h-full pt-4">
            <div class="flex flex-col space-y-4 py-2 w-full">
                <x-nav-link href="/" :active="request()->is('/')" class="flex items-center px-4 py-2 relative">
                    <div class="w-6 h-6 flex-shrink-0">
                        <span data-selector="#color-sidebar-icon">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-blue-400" fill="none" id="color-sidebar-icon" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </span>
                    </div>
                    <span id="color-nav-text" class="absolute left-12 top-1/2 -translate-y-1/2 transform text-indigo-600 group-hover:text-indigo-800 whitespace-nowrap transition-opacity duration-200 ease-in-out opacity-0 group-hover:opacity-100 dark:text-blue-400 dark:group-hover:text-blue-600 text-lg">{{ __('Home') }}</span>
                </x-nav-link>
                <x-nav-link href="/p/create" :active="request()->is('p/create')" class="flex items-center px-4 py-2 relative">
                    <div class="w-6 h-6 flex-shrink-0">
                        <span id="color-sidebar-icon" data-selector="#color-sidebar-icon">
                           <svg  id="color-sidebar-icon" class="w-6 h-6 text-indigo-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                        </span>
                    </div>
                    <span id="color-nav-text" class="absolute left-12 top-1/2 -translate-y-1/2 transform text-indigo-600 group-hover:text-indigo-800 whitespace-nowrap transition-opacity duration-200 ease-in-out opacity-0 group-hover:opacity-100 dark:text-blue-400 dark:group-hover:text-blue-600 text-lg">{{ __('Post') }}</span>
                </x-nav-link>
                <x-nav-link href="/messages" :active="request()->is('messages') || request()->is('messages/*')" class="flex items-center px-4 py-2 relative">
                    <div class="w-6 h-6 flex-shrink-0">
                        <span id="color-sidebar-icon" data-selector="#color-sidebar-icon">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-blue-400" fill="none" id="color-sidebar-icon" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </span>
                    </div>
                    <span id="color-nav-text" class="absolute left-12 top-1/2 -translate-y-1/2 transform text-indigo-600 group-hover:text-indigo-800 whitespace-nowrap transition-opacity duration-200 ease-in-out opacity-0 group-hover:opacity-100 dark:text-blue-400 dark:group-hover:text-blue-600 text-lg">{{ __('Messages') }}</span>
                </x-nav-link>
                <x-nav-link href="/profile" :active="request()->is('profile')" class="flex items-center px-4 py-2 relative">
                    <div class="w-6 h-6 flex-shrink-0">
                        <span id="color-sidebar-icon" data-selector="#color-sidebar-icon">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-blue-400" fill="none" id="color-sidebar-icon" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </span>
                    </div>
                    <span id="color-nav-text" class="absolute left-12 top-1/2 -translate-y-1/2 transform text-indigo-600 group-hover:text-indigo-800 whitespace-nowrap transition-opacity duration-200 ease-in-out opacity-0 group-hover:opacity-100 dark:text-blue-400 dark:group-hover:text-blue-600 text-lg">{{ __('Profile') }}</span>
                </x-nav-link>
                <x-nav-link href="/themes" :active="request()->is('themes')" class="flex items-center px-4 py-2 relative">
                    <div class="w-6 h-6 flex-shrink-0">
                        <span id="color-sidebar-icon" data-selector="#color-sidebar-icon">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-blue-400" fill="none" id="color-sidebar-icon" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                            </svg>
                        </span>
                    </div>
                    <span id="color-nav-text" class="absolute left-12 top-1/2 -translate-y-1/2 transform text-indigo-600 group-hover:text-indigo-800 whitespace-nowrap transition-opacity duration-200 ease-in-out opacity-0 group-hover:opacity-100 dark:text-blue-400 dark:group-hover:text-blue-600 text-lg">{{ __('Themes') }}</span>
                </x-nav-link>
                <div class="mt-auto w-full">
                    <hr class="border-t border-indigo-200 dark:border-purple-700 w-[calc(100%-1rem)] mx-2"
                        id="color-sidebar-separator" data-selector="#color-sidebar-separator" />
                    <div class="pt-2">
                        <x-nav-link href="/profile/edit" :active="request()->is('profile/edit')" id="color-sidebar-icon" class="flex items-center px-4 py-2 relative">
                            <div class="w-6 h-6 flex-shrink-0">
                                <span id="color-sidebar-icon" data-selector="#color-sidebar-icon">
                                        <svg  id="color-sidebar-icon" class="w-6 h-6 text-indigo-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
             
                                </span>
                            </div>
                            <span id="color-nav-text" class="absolute left-12 top-1/2 -translate-y-1/2 transform text-indigo-600 group-hover:text-indigo-800 whitespace-nowrap transition-opacity duration-200 ease-in-out opacity-0 group-hover:opacity-100 dark:text-blue-400 dark:group-hover:text-blue-600 text-lg">{{ __('Settings') }}</span>
                        </x-nav-link>

                        <x-nav-link href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               class="md:block flex items-center px-4 py-2 relative">
                            <div class="w-6 h-6 flex-shrink-0">
                                <span id="color-sidebar-icon" data-selector="#color-sidebar-icon">
                                    <svg id="color-sidebar-icon" class="w-6 h-6 text-indigo-600 dark:text-blue-400 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                </span>
                            </div>
                            <span id="color-nav-text" class="absolute left-12 top-1/2 -translate-y-1/2 transform text-indigo-600 group-hover:text-indigo-800 whitespace-nowrap transition-opacity duration-200 ease-in-out opacity-0 group-hover:opacity-100 dark:text-blue-400 dark:group-hover:text-blue-600 text-lg">{{ __('Logout') }}</span>
                        </x-nav-link>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <nav class="md:hidden bg-white fixed bottom-0 left-0 w-full h-16 flex justify-around items-center shadow-md dark:bg-gray-900 dark:shadow-lg z-20"
         id="color-nav-container" data-selector="#color-nav-container">
        <x-nav-link href="/messages" :active="request()->is('messages') || request()->is('messages/*')" class="flex flex-col items-center p-2 text-xs">
            <span id="color-sidebar-icon" data-selector="#color-sidebar-icon">
                <svg id="color-sidebar-icon" class="w-6 h-6 text-indigo-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </span>
        </x-nav-link>

        <x-nav-link href="/" :active="request()->is('/')" class="flex flex-col items-center p-2 text-xs">
            <span id="color-sidebar-icon" data-selector="#color-sidebar-icon">
                <svg id="color-sidebar-icon" class="w-6 h-6 text-indigo-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </span>
        </x-nav-link>

        <x-nav-link href="/p/create" :active="request()->is('p/create')" class="flex flex-col items-center p-2 text-xs">
            <span id="color-sidebar-icon" data-selector="#color-sidebar-icon">
              <svg  id="color-sidebar-icon" class="w-6 h-6 text-indigo-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </span>
        </x-nav-link>

        <x-nav-link href="/themes" :active="request()->is('themes')" class="flex flex-col items-center p-2 text-xs">
            <span id="color-sidebar-icon" data-selector="#color-sidebar-icon">
                <svg id="color-sidebar-icon" class="w-6 h-6 text-indigo-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                </svg>
            </span>
        </x-nav-link>

        <x-nav-link href="/profile/edit" :active="request()->is('profile/edit')" class="flex flex-col items-center p-2 text-xs">
            <span id="color-sidebar-icon" data-selector="#color-sidebar-icon">
            
    <svg  id="color-sidebar-icon" class="w-6 h-6 text-indigo-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </span>
        </x-nav-link>
    </nav>
    @endif


 