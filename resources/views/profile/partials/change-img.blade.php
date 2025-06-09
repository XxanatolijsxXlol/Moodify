<form action="{{ route('profile.update', $user->id) }}" enctype="multipart/form-data" method="POST">
    @csrf
    @method('PATCH')

    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Change profile caption') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile caption") }}
        </p>
    </header>
    
    <div class="row">
        <div class="col-8 ring-offset-2">
            <div>
                <!-- Title Input -->
                <div class="mb-4">
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" name="title" type="text"
                                  class="mt-1 block w-full p-3 border border-gray-300 rounded-md"
                                  :value="old('title', $user->profile->title ?? '')" autocomplete="title" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <!-- Description Input -->
                <div class="mb-4">
                    <x-input-label for="description" :value="__('Description')" />
                    <x-text-input id="description" name="description" type="text"
                                  class="mt-1 block w-full p-3 border border-gray-300 rounded-md"
                                  :value="old('description', $user->profile->description ?? '')" autocomplete="description" />
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <!-- URL Input -->
                <div class="mb-4">
                    <x-input-label for="url" :value="__('URL')" />
                    <x-text-input id="url" name="url" type="url"
                                  class="mt-1 block w-full p-3 border border-gray-300 rounded-md"
                                  :value="old('url', $user->profile->url ?? '')" autocomplete="url" />
                    <x-input-error :messages="$errors->get('url')" class="mt-2" />
                </div>

                <!-- Image Upload -->
                <div class="mb-4">
                    <x-input-label for="image" :value="__('Image')" />
                    <input type="file" id="image" name="image"
                           class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" />
                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                </div>

                <!-- Save Button -->
                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>

                    @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition
                           x-init="setTimeout(() => show = false, 2000)"
                           class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Saved.') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>
