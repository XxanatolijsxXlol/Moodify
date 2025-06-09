<x-app-layout>
    <div class="mx-auto py-6 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-indigo-800 dark:text-gray-100 mb-6" id="color-theme-title">Create Custom Theme</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg" id="color-error-container">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li id="color-error-text">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('themes.store') }}" method="POST" id="theme-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="css_content" id="css_content" value="">

            <div class="flex flex-col lg:flex-row gap-6"> {{-- Main flex container for left and right sections --}}

                {{-- Left Section: Form Inputs and Color Pickers --}}
                <div class="lg:w-1/3 p-6 bg-white dark:bg-gray-900 rounded-lg shadow-md lg:h-[calc(100vh-100px)] lg:overflow-y-auto"> {{-- Takes 1/3 width, fixed height with scroll --}}
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Theme Details</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-6">
                        <div class="sm:col-span-1">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300" id="color-label">Theme Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 text-black block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>
                        <div class="sm:col-span-1">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300" id="color-label">Description</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 text-black block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" required>{{ old('description') }}</textarea>
                        </div>
                        <div class="sm:col-span-1">
                            <label for="thumbnail" class="block text-sm font-medium text-gray-700 dark:text-gray-300" id="color-label">Theme Thumbnail</label>
                            <input type="file" name="thumbnail" id="thumbnail" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Upload a PNG, JPG, or JPEG. (Max 2MB)</p>
                        </div>

                        <div class="sm:col-span-1">
                            <label for="component" class="block text-sm font-medium text-gray-700 dark:text-gray-300" id="color-label">Component</label>
                            <select id="component" class="mt-1 text-black block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="post">Post</option>
                                <option value="profile">Profile</option>
                                <option value="chat">Chat</option>
                                <option value="navigation">Navigation</option>
                                <option value="theme_form">Theme Form</option>
                                <option value="social-post">Social Post</option>
                            </select>
                        </div>
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" id="color-label">Selected Element</label>
                            <div id="current-element" class="mt-1 p-2 rounded-md bg-gray-100 dark:bg-gray-800 text-sm truncate">Click an element below</div>
                        </div>

                        <div class="sm:col-span-1">
                            <label for="is_public" class="flex items-center">
                               <input type="hidden" name="is_public" value="0">
                                <input type="checkbox" name="is_public" id="is_public" value="1" class="mr-2 focus:ring-indigo-500" {{ old('is_public') ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700 dark:text-gray-300" id="color-checkbox-label">Make this theme public</span>
                            </label>
                        </div>
                    </div>

                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 mt-6">Color Customization</h2>
                    <div class="grid grid-cols-1 gap-6">
                        <div class="flex items-center space-x-2">
                            <label for="body_bg_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300" id="color-label">Page Background</label>
                            <input type="color" id="body_bg_color" class="mt-1 block w-10 h-10 rounded-md">
                            <input type="text" id="body_bg_hex" class="text-black mt-1 block w-24 rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100 text-sm p-2" placeholder="#RRGGBB">
                        </div>
                        <div class="flex items-center space-x-2">
                            <label for="bg_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300" id="color-label">Background</label>
                            <input type="color" id="bg_color" class="mt-1 block w-10 h-10 rounded-md disabled:opacity-50" disabled>
                            <input type="text" id="bg_hex" class=" text-black mt-1 block w-24 rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100 text-sm p-2 disabled:opacity-50" disabled>
                        </div>
                        <div class="flex items-center space-x-2">
                            <label for="text_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300" id="color-label">Text</label>
                            <input type="color" id="text_color" class="mt-1 block w-10 h-10 rounded-md disabled:opacity-50" disabled>
                            <input type="text" id="text_hex" class="text-black mt-1 block w-24 rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100 text-sm p-2 disabled:opacity-50" disabled>
                        </div>
                        <div class="flex items-center space-x-2">
                            <label for="border_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300" id="color-label">Border</label>
                            <input type="color" id="border_color" class="mt-1 block w-10 h-10 rounded-md disabled:opacity-50" disabled>
                            <input type="text" id="border_hex" class="text-black mt-1 block w-24 rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100 text-sm p-2 disabled:opacity-50" disabled>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Color Palette</label>
                            <div id="color-palette" class="flex flex-wrap gap-2"></div>
                        </div>
                    </div>
                    <button type="submit" class="mt-6 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" id="submit-button">Create Theme</button>
                </div>

                {{-- Right Section: Theme Preview --}}
                <div class="lg:w-2/3"> {{-- Takes 2/3 width on large screens --}}
                    <div id="theme-preview" class="p-4 rounded-lg mt-6 lg:mt-0">
                        <div id="post-preview" class="component-preview">
                            <div class="py-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" id="color-post-container" data-selector="#color-post-container">
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6" id="color-form" data-selector="#color-form">
                                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6" id="color-title" data-selector="#color-title">Create a New Post</h2>
                                    <div class="mb-6">
                                        <label class="block text-lg font-medium text-gray-700 dark:text-gray-300 mb-3" id="color-image-label" data-selector="#color-image-label">Add a Photo</label>
                                        <div class="flex flex-col items-center gap-6">
                                            <div class="relative flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700" id="color-image-upload" data-selector="#color-image-upload">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="color-image-icon" data-selector="#color-image-icon">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                                    </svg>
                                                    <p class="mt-3 text-base text-gray-600 dark:text-gray-400" id="color-upload-text" data-selector="#color-upload-text">
                                                        <span class="font-semibold text-indigo-600 dark:text-blue-400" id="color-upload-highlight" data-selector="#color-upload-highlight">Click to upload</span> or drag and drop
                                                    </p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1" id="color-upload-instruction" data-selector="#color-upload-instruction">PNG, JPG, or JPEG (Max 5MB)</p>
                                                </div>
                                            </div>
                                            <div class="mt-2 text-red-600 dark:text-red-400" id="color-image-error" data-selector="#color-image-error">Image error example</div>
                                        </div>
                                    </div>
                                    <div class="mb-6">
                                        <label class="block text-lg font-medium text-gray-700 dark:text-gray-300 mb-3" id="color-caption-label" data-selector="#color-caption-label">Caption</label>
                                        <textarea class="block w-full p-4 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" id="color-caption-input" data-selector="#color-caption-input" rows="3" placeholder="Write a caption..."></textarea>
                                        <div class="mt-2 text-red-600 dark:text-red-400" id="color-caption-error" data-selector="#color-caption-error">Caption error example</div>
                                    </div>
                                    <div class="mb-6">
                                        <label class="block text-lg font-medium text-gray-700 dark:text-gray-300 mb-3" id="color-location-label" data-selector="#color-location-label">Add Location (Optional)</label>
                                        <input class="block w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" id="color-location-input" data-selector="#color-location-input" type="text" placeholder="e.g., New York, NY">
                                        <div class="mt-2 text-red-600 dark:text-red-400" id="color-location-error" data-selector="#color-location-error">Location error example</div>
                                    </div>
                                    <div class="flex justify-end">
                                        <button class="bg-indigo-600 text-white font-semibold py-3 px-8 rounded-full" id="color-submit-button" data-selector="#color-submit-button">Share Post</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="profile-preview" class="component-preview hidden">
                            <div class="py-8 bg-gray-50 dark:bg-gray-900" id="color-main-container" data-selector="#color-main-container">
                                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6" id="color-profile-section" data-selector="#color-profile-section">
                                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('storage/profile_images/default-profile.png') }}" class="rounded-full w-24 h-24 object-cover border-2 border-gray-200 dark:border-gray-600">
                                            </div>
                                            <div class="flex-grow text-center sm:text-left">
                                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white" id="color-username" data-selector="#color-username">Username</h1>
                                                <div id="color-stats-text" data-selector="#color-stats-text" class="flex justify-center sm:justify-start space-x-8 mt-4 text-gray-700 dark:text-gray-300">
                                                    <div>0 posts</div>
                                                    <div><span>0</span> followers</div>
                                                    <div><span>0</span> following</div>
                                                </div>
                                                <div id="color-bio-text" data-selector="#color-bio-text" class="mt-4 text-gray-800 dark:text-gray-200">Bio description</div>
                                            </div>
                                            <button class="bg-blue-600 text-white px-4 py-1.5 rounded-full" id="color-follow-button" data-selector="#color-follow-button">Follow</button>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-1 mt-8">
                                        <div class="aspect-square bg-gray-200"></div>
                                        <div class="aspect-square bg-gray-200"></div>
                                        <div class="aspect-square bg-gray-200"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="chat-preview" class="component-preview hidden">
                            <div class="py-6 bg-gray-50 dark:bg-gray-900" id="color-message-container" data-selector="#color-message-container">
                                <div class="max-w-5xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg flex h-[50vh]" id="color-chat-container" data-selector="#color-chat-container">
                                    <div class="w-1/3 border-r border-gray-200 dark:border-gray-600">
                                        <div id="color-followed-users-header" class="flex items-center p-4 border-b border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 min-h-[64px]" data-selector="#color-followed-users-header">
                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 m-0">Chats</h2>
                                        </div>
                                        <div id="color-conversation-header" class="p-2">
                                            <button  class="flex items-center w-full p-3 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900" id="color-chat-user-button" data-selector="#color-chat-user-button">
                                                <img src="{{ asset('storage/profile_images/default-profile.png') }}" class="w-10 h-10 rounded-full">
                                                <div  class="ml-3 text-gray-900 dark:text-white">User</div>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="w-2/3 flex flex-col">
                                        <div class="flex items-center p-4 border-b border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 min-h-[64px]" id="color-conversation-header" data-selector="#color-conversation-header">
                                            <div class="flex items-center space-x-3">
                                                <img src="{{ asset('storage/profile_images/default-profile.png') }}" class="w-8 h-8 rounded-full">
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-white m-0">User</h3>
                                            </div>
                                        </div>
                                        <div class="flex-grow p-4 space-y-4 bg-gray-50 dark:bg-gray-900" id="color-message-area" data-selector="#color-message-area">
                                            <div class="flex justify-end">
                                                <div class="flex items-start gap-2 max-w-[70%]">
                                                    <div class="bg-indigo-100 dark:bg-indigo-800 p-3 rounded-lg" id="color-message-bubble" data-selector="#color-message-bubble">
                                                        <span class="font-semibold text-gray-900 dark:text-white block mb-1" id="color-message-sender" data-selector="#color-message-sender">You</span>
                                                        <p class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap" id="color-message-text" data-selector="#color-message-text">Hello!</p>
                                                        <div class="flex items-center justify-end mt-1 gap-1">
                                                            <span class="text-xs text-gray-500 dark:text-gray-400" id="color-message-time" data-selector="#color-message-time">12:00 PM</span>
                                                            <svg class="w-3 h-3 text-indigo-500" fill="currentColor" viewBox="0 0 20 20" id="color-read-receipt" data-selector="#color-read-receipt">
                                                                <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-red-500 dark:text-red-400 text-center hidden" id="color-error-message" data-selector="#color-error-message">Error example</div>
                                        </div>
                                        <div class="flex items-center p-4 border-t border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800" id="color-chat-input-form" data-selector="#color-chat-input-form">
                                            <input type="text" class="flex-1 p-3 rounded-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" id="color-message-input" data-selector="#color-message-input" placeholder="Type a message...">
                                            <button class="ml-2 p-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700" id="color-send-button" data-selector="#color-send-button">Send</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="navigation-preview" class="component-preview hidden">
                            <div class="relative h-96 bg-gray-50 dark:bg-gray-900 p-4 rounded-lg shadow-md" id="navigation-preview-container">
                                <nav class="bg-white dark:bg-gray-900 shadow-sm rounded-t-md h-10 md:h-12 w-full relative z-10" id="color-nav-container" data-selector="#color-nav-container">
                                    <div class="max-w-7xl mx-auto px-2 sm:px-4">
                                        <div class="flex justify-between items-center h-full py-1">
                                            <div class="flex items-center">
                                                <span class="text-lg md:text-xl font-bold text-indigo-600 dark:text-pink-400" id="color-nav-brand" data-selector="#color-nav-brand">Moodify</span>
                                            </div>
                                            <div class="flex items-center flex-1 justify-center">
                                                <button class="md:hidden p-1 rounded-full">
                                                    <svg class="w-4 h-4 text-indigo-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="color-search-icon" data-selector="#color-search-icon">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                    </svg>
                                                </button>
                                                <div class="hidden md:flex items-center w-full max-w-xs">
                                                    <form class="relative w-full">
                                                        <input type="text" placeholder="Search..." class="w-full px-3 py-1.5 text-sm rounded-full border-none shadow-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100">
                                                        <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2">
                                                            <svg class="w-4 h-4 text-indigo-500 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="color-search-icon" data-selector="#color-search-icon">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <button class="p-1">
                                                    <svg class="w-4 h-4 text-indigo-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="color-notification-icon" data-selector="#color-notification-icon">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V4a3 3 0 00-6 0v1.083A6 6 0 002 11v3.159c0 .538-.214 1.055-.595 1.436L0 17h5m5 0v1a3 3 0 006 0v-1m-6 0h6"></path>
                                                    </svg>
                                                </button>
                                                <button class="hidden md:block p-1">
                                                    <svg class="w-4 h-4 text-indigo-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="color-dark-mode-icon" data-selector="#color-dark-mode-icon">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                                    </svg>
                                                </button>
                                                <a href="/profile">
                                                    <img src="{{ asset('images/default-profile.png') }}" class="w-6 h-6 rounded-full object-cover">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </nav>
                                <nav class="hidden md:block bg-white dark:bg-gray-900 w-12 hover:w-48 h-48 mt-2 ml-2 rounded-md shadow-sm transition-all duration-300 ease-in-out group" id="color-nav-container" data-selector="#color-nav-container">
                                    <div class="flex flex-col h-full pt-2">
                                        <div class="flex flex-col space-y-2 w-full">
                                            <a href="#" class="flex items-center px-3 py-1.5 relative group">
                                                <div class="w-5 h-5 flex-shrink-0">
                                                    <svg class="w-5 h-5 text-indigo-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="color-sidebar-icon" data-selector="#color-sidebar-icon">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                                    </svg>
                                                </div>
                                                <span class="absolute left-10 text-indigo-600 group-hover:text-indigo-800 whitespace-nowrap transition-opacity duration-200 ease-in-out opacity-0 group-hover:opacity-100 dark:text-blue-400 dark:group-hover:text-blue-600 text-base" id="color-nav-text" data-selector="#color-nav-text">Home</span>
                                            </a>
                                            <a href="#" class="flex items-center px-3 py-1.5 relative group">
                                                <div class="w-5 h-5 flex-shrink-0">
                                                    <svg class="w-5 h-5 text-indigo-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="color-sidebar-icon" data-selector="#color-sidebar-icon">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </div>
                                                <span class="absolute left-10 text-indigo-600 group-hover:text-indigo-800 whitespace-nowrap transition-opacity duration-200 ease-in-out opacity-0 group-hover:opacity-100 dark:text-blue-400 dark:group-hover:text-blue-600 text-base" id="color-nav-text" data-selector="#color-nav-text">Post</span>
                                            </a>
                                            <a href="#" class="flex items-center px-3 py-1.5 relative group">
                                                <div class="w-5 h-5 flex-shrink-0">
                                                    <svg class="w-5 h-5 text-indigo-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="color-sidebar-icon" data-selector="#color-sidebar-icon">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                                <span class="absolute left-10 text-indigo-600 group-hover:text-indigo-800 whitespace-nowrap transition-opacity duration-200 ease-in-out opacity-0 group-hover:opacity-100 dark:text-blue-400 dark:group-hover:text-blue-600 text-base" id="color-nav-text" data-selector="#color-nav-text">Messages</span>
                                            </a>
                                            <a href="#" class="flex items-center px-3 py-1.5 relative group">
                                                <div class="w-5 h-5 flex-shrink-0">
                                                    <svg class="w-5 h-5 text-indigo-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="color-sidebar-icon" data-selector="#color-sidebar-icon">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                                <span class="absolute left-10 text-indigo-600 group-hover:text-indigo-800 whitespace-nowrap transition-opacity duration-200 ease-in-out opacity-0 group-hover:opacity-100 dark:text-blue-400 dark:group-hover:text-blue-600 text-base" id="color-nav-text" data-selector="#color-nav-text">Profile</span>
                                            </a>
                                            <a href="#" class="flex items-center px-3 py-1.5 relative group">
                                                <div class="w-5 h-5 flex-shrink-0">
                                                    <svg class="w-5 h-5 text-indigo-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="color-sidebar-icon" data-selector="#color-sidebar-icon">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                                    </svg>
                                                </div>
                                                <span class="absolute left-10 text-indigo-600 group-hover:text-indigo-800 whitespace-nowrap transition-opacity duration-200 ease-in-out opacity-0 group-hover:opacity-100 dark:text-blue-400 dark:group-hover:text-blue-600 text-base" id="color-nav-text" data-selector="#color-nav-text">Themes</span>
                                            </a>
                                            <hr class="border-t border-indigo-200 dark:border-purple-700 w-[calc(100%-0.5rem)] mx-1" id="color-sidebar-separator" data-selector="#color-sidebar-separator" />
                                        </div>
                                    </div>
                                </nav>
                                <nav class="md:hidden bg-white dark:bg-gray-900 h-12 max-w-md mx-auto absolute bottom-0 inset-x-0 flex justify-around items-center shadow-sm rounded-b-md" id="color-nav-container" data-selector="#color-nav-container">
                                    <a href="#" class="flex items-center p-1.5">
                                        <svg class="w-5 h-5 text-indigo-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="color-bottom-nav-icon" data-selector="#color-bottom-nav-icon">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                    </a>
                                    <a href="#" class="flex items-center p-1.5">
                                        <svg class="w-5 h-5 text-indigo-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="color-bottom-nav-icon" data-selector="#color-bottom-nav-icon">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <a href="#" class="flex items-center p-1.5">
                                        <svg class="w-5 h-5 text-indigo-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="color-bottom-nav-icon" data-selector="#color-bottom-nav-icon">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </a>
                                    <a href="#" class="flex items-center p-1.5">
                                        <svg class="w-5 h-5 text-indigo-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="color-bottom-nav-icon" data-selector="#color-bottom-nav-icon">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="#" class="flex items-center p-1.5">
                                        <svg class="w-5 h-5 text-indigo-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="color-bottom-nav-icon" data-selector="#color-bottom-nav-icon">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                        </svg>
                                    </a>
                                </nav>
                            </div>
                        </div>
                        <div id="theme_form-preview" class="component-preview hidden">
                            <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-md" id="color-form-container" data-selector="#color-form-container">
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" id="color-label" data-selector="#color-label">Sample Label</label>
                                        <input type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100" id="color-input" data-selector="#color-input">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" id="color-label" data-selector="#color-label">Sample Select</label>
                                        <select class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100" id="color-select" data-selector="#color-select">
                                            <option>Option</option>
                                        </select>
                                    </div>
                                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg" id="color-submit-button" data-selector="#color-submit-button">Sample Button</button>
                                </div>
                            </div>
                        </div>
                        <div id="social-post-preview" class="component-preview hidden">
                            <div class="social-post-wrapper  py-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" data-selector=".social-post-wrapper"> <div class="post-container relative w-full bg-white dark:bg-gray-800 p-6 border-l border-r border-b border-gray-300 dark:border-gray-600 rounded-none shadow-[2px_0_4px_rgba(0,0,0,0.1),-2px_0_4px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_4px_rgba(0,0,0,0.2),-2px_0_4px_rgba(0,0,0,0.2)]" data-selector=".post-container">
                                    <div class="top-post-bar absolute top-2 left-2 right-2 h-10 flex items-center justify-between bg-white dark:bg-gray-800 bg-opacity-90 dark:bg-opacity-90 rounded-md px-2" data-selector=".top-post-bar">
                                        <div class="flex items-center space-x-2">
                                            <img src="{{ asset('storage/profile_images/default-profile.png') }}" class="w-8 h-8 rounded-full object-cover" alt="User's profile picture">
                                            <span class="top-post-bar-text font-semibold text-base text-indigo-600 dark:text-gray-100 truncate" data-selector=".top-post-bar-text">Username</span>
                                            <p class="top-post-bar-time text-sm text-indigo-500 dark:text-gray-400 hidden md:block" data-selector=".top-post-bar-time">2 hours ago</p>
                                        </div>
                                        <button class="message-button text-indigo-600 dark:text-indigo-400 text-sm" data-selector=".message-button">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="mt-8 mb-6 -mx-6">
                                        <img src="{{ asset('storage/assets/green-thumbnail.png') }}" class="w-full h-[400px] sm:h-[480px] object-cover" alt="Sample post image">
                                    </div>
                                    <div class="flex justify-start space-x-6 mb-4">
                                        <button class="like-button flex items-center space-x-2 text-indigo-600 dark:text-purple-400" id="color-like-button" data-selector=".like-button">
                                            <svg class="w-6 h-6 heart-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-selector=".like-button .heart-icon">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                            <span class="like-count" id="color-like-count" data-selector=".like-count">5 Likes</span>
                                        </button>
                                        <button class="comment-button flex items-center space-x-2 text-indigo-600 dark:text-purple-400" id="color-comment-button" data-selector=".comment-button">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                            </svg>
                                            <span class="comment-count" data-selector=".comment-count">3 Comments</span>
                                        </button>
                                    </div>
                                    <div class="likedby-container text-sm text-gray-600 dark:text-gray-400 mb-4 flex items-center space-x-2" data-selector=".likedby-container">
                                        <img src="{{ asset('storage/profile_images/default-profile.png') }}" class="w-6 h-6 rounded-full object-cover border-2 border-indigo-300 dark:border-purple-500" alt="User's profile picture">
                                        <span class="flex items-center">
                                            <div class="likedby-text" data-selector=".likedby-text">Liked by </div>
                                            <a href="#" class="likedby-username font-semibold text-indigo-600 dark:text-gray-100" data-selector=".likedby-username">Username</a>
                                            <span class="likedby-others" data-selector=".likedby-others"> and 4 others</span>
                                        </span>
                                    </div>
                                    <div class="caption-container text-sm text-indigo-800 dark:text-gray-200 mb-4" data-selector=".caption-container">
                                        <span class="caption-username font-semibold text-indigo-600 dark:text-gray-100" data-selector=".caption-username">Username</span>
                                        <span class="caption-text ml-2" data-selector=".caption-text">This is a sample caption.</span>
                                    </div>
                                    <div class="comments-section mb-4" id="color-comments-section" data-selector=".comments-section" style="display: block;">
                                        <hr class="comment-divider border-t border-gray-200 dark:border-gray-600 mb-4" data-selector=".comment-divider">
                                        <div class="comment-list max-h-40 overflow-y-auto">
                                            <div class="comment flex items-start space-x-2 mb-2">
                                                <a href="#" class="flex-shrink-0">
                                                    <img src="{{ asset('storage/profile_images/default-profile.png') }}" class="w-6 h-6 rounded-full object-cover hover:opacity-80 transition-opacity" alt="Commenter's profile picture">
                                                </a>
                                                <div>
                                                    <a class="comment-username font-semibold text-indigo-600 dark:text-gray-100 hover:underline" data-selector=".comment-username">Commenter</a>
                                                    <p class="comment-text text-sm text-gray-700 dark:text-gray-300" data-selector=".comment-text">Great post!</p>
                                                    <span class="comment-time text-xs text-gray-500" data-selector=".comment-time">1 hour ago</span>
                                                </div>
                                            </div>
                                        </div>
                                        <form class="comment-form mt-2 flex items-center space-x-2">
                                            <input type="text" class="post-comment-input flex-1 p-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 placeholder-gray-400" placeholder="Add a comment..." data-selector=".post-comment-input">
                                            <button type="submit" class="post-comment-button p-2 bg-indigo-600 text-white rounded-md" data-selector=".post-comment-button">Post</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> {{-- End of Main flex container --}}
        </form>
    </div>
       <style id="preview-style"></style>
    <style>
        /* Liked state fill and stroke are controlled dynamically via componentElements */
        #color-caption-container { word-break: break-word; overflow-wrap: break-word; }
        .comment-list { max-height: 10rem; overflow-y: auto; padding-right: 0.5rem; }
        .comment-list::-webkit-scrollbar { width: 6px; }
        .comment-list::-webkit-scrollbar-thumb { background: #888; border-radius: 3px; }
        .comment-list::-webkit-scrollbar-thumb:hover { background: #555; }

        /* Responsive scaling for theme-preview - adjust for new layout */
        @media (max-width: 1023px) { /* Applies to screens smaller than large (lg) */
            #theme-preview {
                transform: scale(0.9); /* Slightly scale down on smaller screens if needed */
                transform-origin: top center;
                width: 111%; /* Adjust width to compensate for scaling */
                margin-left: -5.5%; /* Center the scaled content */
            }
        }
        @media (min-width: 1024px) { /* For large screens and up, remove scaling */
            #theme-preview {
                transform: none;
                width: auto;
                margin-left: 0;
            }
        }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const componentElements = {
            global: {
                'body': { allowed: ['bg_color'], bg_color: '#f9fafb' }
            },
            post: {
                '#color-post-container': { allowed: ['bg_color'], bg_color: 'transparent' },
                '#color-form': { allowed: ['bg_color'], bg_color: '#ffffff' },
                '#color-title': { allowed: ['text_color'], text_color: '#1f2937' },
                '#color-image-label': { allowed: ['text_color'], text_color: '#374151' },
                '#color-image-upload': { allowed: ['bg_color', 'border_color'], bg_color: '#f9fafb', border_color: '#d1d5db' },
                '#color-image-icon': { allowed: ['text_color'], text_color: '#9ca3af' },
                '#color-upload-text': { allowed: ['text_color'], text_color: '#4b5563' },
                '#color-upload-highlight': { allowed: ['text_color'], text_color: '#4f46e5' },
                '#color-upload-instruction': { allowed: ['text_color'], text_color: '#6b7280' },
                '#color-image-error': { allowed: ['text_color'], text_color: '#dc2626' },
                '#color-caption-label': { allowed: ['text_color'], text_color: '#374151' },
                '#color-caption-input': { allowed: ['bg_color', 'border_color'], bg_color: '#ffffff', border_color: '#d1d5db' },
                '#color-caption-error': { allowed: ['text_color'], text_color: '#dc2626' },
                '#color-location-label': { allowed: ['text_color'], text_color: '#374151' },
                '#color-location-input': { allowed: ['bg_color', 'border_color'], bg_color: '#ffffff', border_color: '#d1d5db' },
                '#color-location-error': { allowed: ['text_color'], text_color: '#dc2626' },
                '#color-submit-button': { allowed: ['bg_color', 'text_color'], bg_color: '#4f46e5', text_color: '#ffffff' }
            },
            profile: {
                '#color-main-container': { allowed: ['bg_color'], bg_color: 'transparent' },
                '#color-profile-section': { allowed: ['bg_color'], bg_color: '#ffffff' },
                '#color-username': { allowed: ['text_color'], text_color: '#111827' },
                '#color-follow-button': { allowed: ['bg_color', 'text_color'], bg_color: '#2563eb', text_color: '#ffffff' },
                 '#color-stats-text': { allowed: ['text_color'], text_color: '#374151' },
                '#color-bio-text': { allowed: ['text_color'], text_color: '#374151' },
                '#color-follow-button': { allowed: ['bg_color', 'text_color'], bg_color: '#2563eb', text_color: '#ffffff' },
            },
            chat: {
                '#color-message-container': { allowed: ['bg_color'], bg_color: 'transparent' },
                '#color-chat-container': { allowed: ['bg_color', 'border_color'], bg_color: '#ffffff', border_color: '#e5e7eb' },
                '#color-followed-users-header': { allowed: ['bg_color', 'text_color', 'border_color'], bg_color: '#ffffff', text_color: '#111827', border_color: '#e5e7eb' },
                '#color-chat-user-button': { allowed: ['bg_color', 'text_color'], bg_color: '', text_color: '#111827' },
                '#color-conversation-header': { allowed: ['bg_color', 'text_color', 'border_color'], bg_color: '#ffffff', text_color: '#111827', border_color: '#e5e7eb' },
                '#color-message-area': { allowed: ['bg_color'], bg_color: '#f3f4f6' },
                '#color-chat-input-form': { allowed: ['bg_color', 'border_color'], bg_color: '#ffffff', border_color: '#e5e7eb' },
                '#color-message-input': { allowed: ['bg_color', 'text_color', 'border_color'], bg_color: '#ffffff', text_color: '#1f2937', border_color: '#d1d5db' },
                '#color-send-button': { allowed: ['bg_color', 'text_color'], bg_color: '#4f46e5', text_color: '#ffffff' },
                '#color-message-bubble': { allowed: ['bg_color'], bg_color: '#e0e7ff' },
                '#color-message-sender': { allowed: ['text_color'], text_color: '#111827' },
                '#color-message-text': { allowed: ['text_color'], text_color: '#1f2937' },
                '#color-message-time': { allowed: ['text_color'], text_color: '#6b7280' },
                '#color-read-receipt': { allowed: ['text_color'], text_color: '#4f46e5' },
                '#color-error-message': { allowed: ['text_color'], text_color: '#ef4444' }
            },
            navigation: {
                '#navigation-preview-container': { allowed: ['bg_color'], bg_color: 'transparent' },
                '#color-nav-container': { allowed: ['bg_color'], bg_color: '#ffffff' },
                '#color-nav-brand': { allowed: ['text_color'], text_color: '#4f46e5' },
                '#color-search-icon': { allowed: ['text_color'], text_color: '#6366f1' },
                '#color-notification-icon': { allowed: ['text_color'], text_color: '#4f46e5' },
                '#color-dark-mode-icon': { allowed: ['text_color'], text_color: '#4f46e5' },
                '#color-sidebar-separator': { allowed: ['border_color'], border_color: '#e0e7ff' },
                '#color-sidebar-icon': { allowed: ['text_color'], text_color: '#4f46e5' },
                '#color-bottom-nav-icon': { allowed: ['text_color'], text_color: '#4f46e5' },
                '#color-nav-text': { allowed: ['text_color'], text_color: '#4f46e5' }
            },
            theme_form: {
                '#color-form-container': { allowed: ['bg_color'], bg_color: 'transparent' },
                '#color-label': { allowed: ['text_color'], text_color: '#374151' },
                '#color-input': { allowed: ['bg_color', 'text_color', 'border_color'], bg_color: '#ffffff', text_color: '#111827', border_color: '#d1d5db' },
                '#color-select': { allowed: ['bg_color', 'text_color', 'border_color'], bg_color: '#ffffff', text_color: '#111827', border_color: '#d1d5db' },
                '#color-current-element': { allowed: ['bg_color', 'text_color'], bg_color: '#f3f4f6', text_color: '#111827' },
                '#color-bg-input': { allowed: ['bg_color'], bg_color: '#ffffff' },
                '#color-text-input': { allowed: ['bg_color'], bg_color: '#ffffff' },
                '#color-border-input': { allowed: ['bg_color'], bg_color: '#ffffff' },
                '#color-checkbox': { allowed: ['border_color'], border_color: '#d1d5db' },
                '#color-checkbox-label': { allowed: ['text_color'], text_color: '#374151' },
                '#color-submit-button': { allowed: ['bg_color', 'text_color'], bg_color: '#2563eb', text_color: '#ffffff' },
                '#color-error-container': { allowed: ['bg_color', 'text_color'], bg_color: '#fee2e2', text_color: '#b91c1c' },
                '#color-error-text': { allowed: ['text_color'], text_color: '#b91c1c' },
                '#color-theme-title': { allowed: ['text_color'], text_color: '#3730a3' }
            },
        'social-post': {
                '.social-post-wrapper': { allowed: ['bg_color'], bg_color: 'transparent' },
                '.post-container': { allowed: ['bg_color', 'border_color'], bg_color: '#ffffff', border_color: '#d1d5db' },
                '.top-post-bar': { allowed: ['bg_color'], bg_color: '#ffffff' },
                '.top-post-bar-text': { allowed: ['text_color'], text_color: '#4f46e5' },
                '.top-post-bar-time': { allowed: ['text_color'], text_color: '#6b7280' },
                '.message-button': { allowed: ['text_color'], text_color: '#4f46e5' },
                '.like-button': { allowed: ['text_color'], text_color: '#4f46e5' },
                '.like-button .heart-icon': {
                    allowed: ['text_color', 'bg_color'],
                    text_color: '#4f46e5', // Controls the icon's stroke (outline)
                    bg_color: '#4f46e5'    // Controls the icon's fill when liked
                },
                '.like-count': { allowed: ['text_color'], text_color: '#4f46e5' },
                '.comment-button': { allowed: ['text_color'], text_color: '#4f46e5' },
                '.comment-count': { allowed: ['text_color'], text_color: '#4f46e5' },
                '.likedby-container': { allowed: ['text_color'], text_color: '#4b5563' },
                '.likedby-text': { allowed: ['text_color'], text_color: '#4b5563' },
                '.likedby-username': { allowed: ['text_color'], text_color: '#4f46e5' },
                '.likedby-others': { allowed: ['text_color'], text_color: '#4b5563' },
                '.caption-container': { allowed: ['text_color'], text_color: '#1e40af' },
                '.caption-username': { allowed: ['text_color'], text_color: '#4f46e5' },
                '.caption-text': { allowed: ['text_color'], text_color: '#1e40af' },
                '.comments-section': { allowed: ['bg_color'], bg_color: '#ffffff' },
                '.comment-divider': { allowed: ['border_color'], border_color: '#e5e7eb' },
                '.comment-username': { allowed: ['text_color'], text_color: '#4f46e5' },
                '.comment-text': { allowed: ['text_color'], text_color: '#374151' },
                '.comment-time': { allowed: ['text_color'], text_color: '#6b7280' },
                '.post-comment-input': { allowed: ['bg_color', 'text_color', 'border_color'], bg_color: '#ffffff', text_color: '#1f2937', border_color: '#d1d5db' },
                '.post-comment-button': { allowed: ['bg_color', 'text_color'], bg_color: '#4f46e5', text_color: '#ffffff' }
            }
        };

        let currentComponent = 'post';
        let currentSelectedElement = null;

        // Load saved palette from localStorage or use default
        let savedPalette = JSON.parse(localStorage.getItem('themePalette')) || [];
        const defaultPalette = [
            '#ffffff', '#000000', '#f44336', '#e91e63', '#9c27b0', '#673ab7',
            '#3f51b5', '#2196f3', '#03a9f4', '#00bcd4', '#009688', '#4caf50',
            '#8bc34a', '#cddc39', '#ffeb3b', '#ffc107', '#ff9800', '#ff5722'
        ];
        // Combine and ensure uniqueness, limit to 20
        let currentPalette = [...new Set([...savedPalette, ...defaultPalette])].slice(0, 20);

        function populatePalette() {
            const paletteContainer = document.getElementById('color-palette');
            paletteContainer.innerHTML = '';
            currentPalette.forEach(color => {
                const colorBox = document.createElement('div');
                colorBox.className = 'w-8 h-8 rounded-md cursor-pointer border-2 border-transparent hover:border-blue-500';
                colorBox.style.backgroundColor = color;
                colorBox.dataset.color = color;
                paletteContainer.appendChild(colorBox);
            });
        }

        function addColorToPalette(color) {
            // Only add if the color is not already in the palette
            if (!currentPalette.includes(color)) {
                currentPalette.unshift(color); // Add to the beginning
                if (currentPalette.length > 20) { // Keep palette size limited
                    currentPalette.pop();
                }
                localStorage.setItem('themePalette', JSON.stringify(currentPalette));
                populatePalette();
            }
        }

        function updatePreview() {
            let css = '';

            // Handle global body background for the preview wrapper
            const globalProps = componentElements.global.body;
            if (globalProps.bg_color) {
                css += `#theme-preview { background-color: ${globalProps.bg_color} !important; }\n`;
            }

            // Handle component-specific styles
            const elements = componentElements[currentComponent];
            for (const [selector, props] of Object.entries(elements)) {
                if (selector === '.like-button .heart-icon') {
                    css += `${selector} { stroke: ${props.text_color} !important; fill: none !important; }\n`;
                    css += `.like-button.liked ${selector} { stroke: ${props.text_color} !important; fill: ${props.bg_color} !important; }\n`;
                    continue;
                }

                if (props.bg_color && props.allowed.includes('bg_color')) {
                    css += `${selector} { background-color: ${props.bg_color} !important; }\n`;
                }
                if (props.text_color && props.allowed.includes('text_color')) {
                    css += `${selector} { color: ${props.text_color} !important; }\n`;
                }
                if (props.border_color && props.allowed.includes('border_color')) {
                    css += `${selector} { border-color: ${props.border_color} !important; }\n`;
                }
            }
            document.getElementById('preview-style').textContent = css;
        }

        function updateColorPickers() {
            const pickers = {
                bg: { picker: document.getElementById('bg_color'), hex: document.getElementById('bg_hex') },
                text: { picker: document.getElementById('text_color'), hex: document.getElementById('text_hex') },
                border: { picker: document.getElementById('border_color'), hex: document.getElementById('border_hex') }
            };
            const currentElementDisplay = document.getElementById('current-element');

            if (!currentSelectedElement || !componentElements[currentComponent][currentSelectedElement]) {
                Object.values(pickers).forEach(({ picker, hex }) => {
                    picker.disabled = true;
                    hex.disabled = true;
                    picker.value = '#ffffff'; // Reset to a default visually when disabled
                    hex.value = '#ffffff'; // Reset to a default visually when disabled
                });
                currentElementDisplay.textContent = 'Click an element below';
                return;
            }

            const elementData = componentElements[currentComponent][currentSelectedElement];
            const allowed = elementData.allowed || [];

            pickers.bg.picker.disabled = !allowed.includes('bg_color');
            pickers.bg.hex.disabled = !allowed.includes('bg_color');
            pickers.text.picker.disabled = !allowed.includes('text_color');
            pickers.text.hex.disabled = !allowed.includes('text_color');
            pickers.border.picker.disabled = !allowed.includes('border_color');
            pickers.border.hex.disabled = !allowed.includes('border_color');

            pickers.bg.picker.value = allowed.includes('bg_color') && elementData.bg_color && elementData.bg_color !== 'none' ? elementData.bg_color : '#ffffff';
            pickers.bg.hex.value = pickers.bg.picker.value;
            pickers.text.picker.value = allowed.includes('text_color') && elementData.text_color ? elementData.text_color : '#000000';
            pickers.text.hex.value = pickers.text.picker.value;
            pickers.border.picker.value = allowed.includes('border_color') && elementData.border_color ? elementData.border_color : '#000000';
            pickers.border.hex.value = pickers.border.picker.value;
            currentElementDisplay.textContent = currentSelectedElement;
        }

        function switchComponent(component) {
            currentComponent = component;
            currentSelectedElement = null;
            document.querySelectorAll('.component-preview').forEach(preview => {
                preview.classList.add('hidden');
            });
            document.getElementById(`${component}-preview`).classList.remove('hidden');
            updateColorPickers();
            updatePreview();
        }

        function handleElementClick(e) {
            e.preventDefault();
            e.stopPropagation();
            const target = e.target.closest('[data-selector]');
            if (!target) return;
            const selector = target.getAttribute('data-selector');
            if (!selector || !componentElements[currentComponent][selector]) return;
            currentSelectedElement = selector;
            updateColorPickers();
            document.querySelectorAll('[data-selector]').forEach(el => {
                el.classList.remove('ring-2', 'ring-blue-500');
            });
            target.classList.add('ring-2', 'ring-blue-500');
        }

        function syncColorInputs(picker, hexInput) {
            picker.addEventListener('input', () => {
                hexInput.value = picker.value;
                if (!picker.disabled) { // Only add to palette if the picker is enabled for the selected element
                     addColorToPalette(picker.value);
                }
            });
            hexInput.addEventListener('input', () => {
                if (/^#[0-9A-F]{6}$/i.test(hexInput.value)) {
                    picker.value = hexInput.value;
                    if (!picker.disabled) { // Only add to palette if the picker is enabled for the selected element
                         addColorToPalette(hexInput.value);
                    }
                    picker.dispatchEvent(new Event('input')); // Trigger picker's input event to update preview
                }
            });
        }

        syncColorInputs(document.getElementById('body_bg_color'), document.getElementById('body_bg_hex'));
        syncColorInputs(document.getElementById('bg_color'), document.getElementById('bg_hex'));
        syncColorInputs(document.getElementById('text_color'), document.getElementById('text_hex'));
        syncColorInputs(document.getElementById('border_color'), document.getElementById('border_hex'));

        document.getElementById('social-post-preview').addEventListener('click', (e) => {
           const likeButton = e.target.closest('.like-button');
            if (likeButton) {
                likeButton.classList.toggle('liked');
                updatePreview();
            }
        });

        document.getElementById('theme-preview').addEventListener('click', handleElementClick);

        document.querySelectorAll('#bg_color, #text_color, #border_color').forEach(input => {
            input.addEventListener('input', () => {
                if (!currentSelectedElement || !componentElements[currentComponent][currentSelectedElement]) return;
                const elementData = componentElements[currentComponent][currentSelectedElement];
                if (input.id === 'bg_color' && elementData.allowed.includes('bg_color')) {
                    elementData.bg_color = input.value;
                } else if (input.id === 'text_color' && elementData.allowed.includes('text_color')) {
                    elementData.text_color = input.value;
                } else if (input.id === 'border_color' && elementData.allowed.includes('border_color')) {
                    elementData.border_color = input.value;
                }
                updatePreview();
            });
        });

        document.getElementById('body_bg_color').addEventListener('input', (e) => {
            componentElements.global.body.bg_color = e.target.value;
            addColorToPalette(e.target.value); // Add page background color to palette
            updatePreview();
        });

        document.getElementById('color-palette').addEventListener('click', (e) => {
            if (e.target.dataset.color && currentSelectedElement) {
                const color = e.target.dataset.color;
                const elementData = componentElements[currentComponent][currentSelectedElement];

                // Determine which color property to set based on which input is *not* disabled
                // This assumes only one color input (bg, text, or border) will be active for a given element.
                if (!document.getElementById('bg_color').disabled) {
                    elementData.bg_color = color;
                    document.getElementById('bg_color').value = color;
                    document.getElementById('bg_hex').value = color;
                } else if (!document.getElementById('text_color').disabled) {
                    elementData.text_color = color;
                    document.getElementById('text_color').value = color;
                    document.getElementById('text_hex').value = color;
                } else if (!document.getElementById('border_color').disabled) {
                    elementData.border_color = color;
                    document.getElementById('border_color').value = color;
                    document.getElementById('border_hex').value = color;
                }
                addColorToPalette(color); // Add selected palette color to used colors, handles duplicates
                updatePreview();
            }
        });

        document.getElementById('component').addEventListener('change', (e) => {
            switchComponent(e.target.value);
        });

        document.getElementById('theme-form').addEventListener('submit', (e) => {
            let allCss = '';
            for (const component in componentElements) {
                allCss += `/* ${component} styles */\n`;
                const elements = componentElements[component];

                for (const [selector, props] of Object.entries(elements)) {
                    if (component === 'global') {
                        if (props.bg_color) allCss += `${selector} { background-color: ${props.bg_color} !important; }\n`;
                        continue;
                    }
                    if (selector === '.like-button .heart-icon') {
                        allCss += `${selector} { stroke: ${props.text_color} !important; fill: none !important; }\n`;
                        allCss += `.like-button.liked ${selector} { stroke: ${props.text_color} !important; fill: ${props.bg_color} !important; }\n`;
                        continue;
                    }

                    if (props.bg_color && props.allowed.includes('bg_color')) {
                        allCss += `${selector} { background-color: ${props.bg_color} !important; }\n`;
                    }
                    if (props.text_color && props.allowed.includes('text_color')) {
                        allCss += `${selector} { color: ${props.text_color} !important; }\n`;
                    }
                    if (props.border_color && props.allowed.includes('border_color')) {
                        allCss += `${selector} { border-color: ${props.border_color} !important; }\n`;
                    }
                }
            }
           document.getElementById('css_content').value = allCss;
        });

        document.getElementById('submit-button').addEventListener('click', (e) => {
            e.stopPropagation();
            const nameInput = document.getElementById('name');
            if (!nameInput.value.trim()) {
                e.preventDefault();
                nameInput.focus();
                nameInput.classList.add('border-red-500');
                setTimeout(() => nameInput.classList.remove('border-red-500'), 2000);
            }
        });

        // Initialize
        document.getElementById('body_bg_color').value = componentElements.global.body.bg_color;
        document.getElementById('body_bg_hex').value = componentElements.global.body.bg_color;
        populatePalette();
        switchComponent('post');
    });
</script>
</x-app-layout>