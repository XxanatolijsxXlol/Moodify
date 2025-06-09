<x-app-layout>
    <div class="py-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <form id="create-post" action="/p" enctype="multipart/form-data" method="POST" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6" id="color-form">
            @csrf
            <h2 id="create-post-title" class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6" id="color-title">Create a New Post</h2>

            <!-- Image Upload and Preview -->
            <div class="mb-6">
                <x-input-label for="image" :value="__('Add a Photo')" class="block text-lg font-medium text-gray-700 dark:text-gray-300 mb-3" id="color-image-label" />
                <div class="flex flex-col items-center gap-6">
                    <!-- File Input with Custom Styling -->
                    <label for="image" class="relative flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition duration-300 ease-in-out overflow-hidden" id="color-image-upload">
                        <div id="image-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="color-image-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            <p class="mt-3 text-base text-gray-600 dark:text-gray-400" id="color-upload-text">
                                <span class="font-semibold text-indigo-600 dark:text-blue-400" id="color-upload-highlight">Click to upload</span> or drag and drop
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1" id="color-upload-instruction">PNG, JPG, or JPEG (Max 5MB)</p>
                        </div>
                        <input 
                            type="file" 
                            id="image" 
                            name="image" 
                            class="absolute inset-0 text-black opacity-0 cursor-pointer"
                            accept="image/png, image/jpeg, image/jpg"
                            onchange="previewImage(event)"
                            required
                        />
                        <div id="image-preview" class="hidden absolute inset-0 w-full h-full">
                            <img 
                                id="preview" 
                                class="w-full h-full object-cover rounded-xl"
                                alt="Image preview"
                            />
                        </div>
                    </label>
                    <x-input-error :messages="$errors->get('image')" class="mt-2 text-red-600 dark:text-red-400" id="color-image-error" />
                </div>
            </div>

            <!-- Caption Input -->
            <div class="mb-6">
                <x-input-label for="caption" :value="__('Caption')" class="block text-lg font-medium text-gray-700 dark:text-gray-300 mb-3" id="color-caption-label" />
                <textarea 
                    id="caption" 
                    name="caption" 
                    class="block w-full p-4 border text-black border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white resize-none" 
                    rows="3" 
                    placeholder="Write a caption..."
                    required
                    id="color-caption-input"
                >{{ old('caption') }}</textarea>
                <x-input-error :messages="$errors->get('caption')" class="mt-2 text-red-600 dark:text-red-400" id="color-caption-error" />
            </div>

            <!-- Optional: Location Field -->
            <div class="mb-6">
                <x-input-label for="location" :value="__('Add Location (Optional)')" class="block text-lg font-medium text-gray-700 dark:text-gray-300 mb-3" id="color-location-label" />
                <input 
                    id="location" 
                    class="block w-full p-3 text-black border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" 
                    type="text" 
                    name="location" 
                    value="{{ old('location') }}"
                    placeholder="e.g., New York, NY"
                    id="color-location-input"
                />
                <x-input-error :messages="$errors->get('location')" class="mt-2 text-red-600 dark:text-red-400" id="color-location-error" />
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button 
                    type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-full transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-not-allowed"
                    id="submit-btn"
                    id="color-submit-button"
                >
                    Share Post
                </button>
            </div>
        </form>
    </div>

    <!-- JavaScript for Image Preview -->
    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const imagePreview = document.getElementById('image-preview');
            const placeholder = document.getElementById('image-placeholder');
            const submitBtn = document.getElementById('submit-btn');
            const file = event.target.files[0];

            if (file) {
                // Validate file size (5MB = 5 * 1024 * 1024 bytes)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size exceeds 5MB limit.');
                    event.target.value = ''; // Clear the input
                    submitBtn.disabled = true;
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    submitBtn.disabled = false;
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                imagePreview.classList.add('hidden');
                placeholder.classList.remove('hidden');
                submitBtn.disabled = true;
            }
        }

        // Initially disable submit button if no image is selected
        document.addEventListener('DOMContentLoaded', function() {
            const submitBtn = document.getElementById('submit-btn');
            const imageInput = document.getElementById('image');
            submitBtn.disabled = !imageInput.files.length;
        });
    </script>
</x-app-layout>