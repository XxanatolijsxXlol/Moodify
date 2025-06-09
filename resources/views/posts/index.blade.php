<x-app-layout>
    <div class="flex w-full dark:bg-gray-900">
        <div id="content" class="social-post-wrapper w-full max-w-screen-xl sm:px-0 lg:px-8 mx-auto">
            @guest
                <!-- Guest Layout: Centered posts, no sidebars -->
                <div class="w-full max-w-[700px] mx-auto">
                    <div id="post-container">
                        @foreach ($posts as $post)
                            @include('components.post', ['post' => $post])
                        @endforeach
                    </div>

                    <div id="loading" class="text-center py-4 hidden">
                        <svg class="animate-spin h-6 w-6 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                    </div>
                </div>
            @endguest

            @auth
                <!-- Authenticated Layout: Three-column layout with sidebars -->
                <div class="flex flex-col lg:flex-row lg:justify-center">
                    <!-- Mobile Suggested to Follow Box -->
                    <div class="lg:hidden w-full mb-4">
                        <div class="dark:text-gray-100">
                            <h2 id="suggestion-title" class="text-lg font-semibold mb-2 text-indigo-800 dark:text-gray-100 px-4">Suggested to Follow</h2>
                            <div class="flex overflow-x-auto pb-0 space-x-4 px-4 no-scrollbar">
                                @foreach ($suggestedUsers as $user)
                                    <div class="w-20">
                                        <div class="flex flex-col items-center">
                                            <img src="/storage/{{ $user->profile->image ?? 'default-profile.png' }}"
                                                 class="w-14 h-14 rounded-full object-cover">
                                            <a href="/profiles/{{ $user->id }}" class="text-indigo-700 dark:text-gray-100 hover:text-indigo-500 dark:hover:text-purple-400 mt-1 text-center">
                                                <p id="suggestion-username" class="text-xs text-gray-600 dark:text-gray-400 truncate w-full">{{ $user->username }}</p>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Left Spacer (Desktop) -->
                    <div class="hidden lg:block lg:w-1/5"></div>

                    <!-- Posts Container -->
                    <div class="w-full lg:w-3/5 max-w-[700px] mx-auto lg:mx-0">
                        <div id="post-container">
                            @foreach ($posts as $post)
                                @include('components.post', ['post' => $post])
                            @endforeach
                        </div>

                        <div id="loading" class="text-center py-4 hidden">
                            <svg class="animate-spin h-6 w-6 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Right Sidebar (Desktop) -->
                    <div class="hidden lg:block lg:w-1/5 lg:ml-6">
                        <div id="color-form" class="dark:text-gray-100 p-4 rounded-lg shadow-md sticky top-16">
                            <h2 class="text-xl top-post-bar-text font-semibold mb-4 text-indigo-800 dark:text-gray-100">Profiles you may like</h2>
                            @foreach ($suggestedUsers as $user)
                                <div class="flex items-center space-x-3 mb-4">
                                    <img src="/storage/{{ $user->profile->image ?? 'default-profile.png' }}"
                                         class="w-10 h-10 rounded-full object-cover">
                                    <div class="flex items-center justify-between flex-1 min-w-0">
                                        <a href="/profiles/{{ $user->id }}" class="text-indigo-700 dark:text-gray-100 hover:text-indigo-500 dark:hover:text-purple-400">
                                            <p class="top-post-bar-text text-gray-600 dark:text-gray-400 truncate">{{ $user->username }}</p>
                                        </a>
                                        <button class="post-comment-button bg-blue-600 dark:bg-blue-500 text-white hover:bg-blue-700 dark:hover:bg-blue-600 px-3 py-1 rounded-full text-sm font-semibold transition">
                                            <a href="/profiles/{{$user->id}}">View</a>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>

    <script>
        let page = 1;
        let loading = false;
        let hasMorePosts = true;
        let updateResponsiveElements = () => {
            // Update the layout of elements based on the current viewport size
            const isMobile = window.innerWidth < 640;
            document.querySelectorAll('.post').forEach(post => {
                post.classList.toggle('mobile', isMobile);
            });
        };
        // Infinite Scroll
        window.addEventListener('scroll', function () {
            if (loading || !hasMorePosts) return;

            let scrollHeight = document.documentElement.scrollHeight;
            let scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
            let clientHeight = document.documentElement.clientHeight;

            if (scrollHeight - scrollTop <= clientHeight + 100) {
                loadMorePosts();
            }
        });

        function loadMorePosts() {
            if (!hasMorePosts) return;

            loading = true;
            document.getElementById('loading').classList.remove('hidden');

            fetch(`?page=${page + 1}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.text();
                })
                .then(data => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data, "text/html");
                    const newPosts = doc.querySelector("#post-container").innerHTML.trim();

                    if (!newPosts || newPosts === document.getElementById("post-container").innerHTML.trim()) {
                        hasMorePosts = false;
                        document.getElementById('loading').classList.add('hidden');
                        loading = false;
                        return;
                    }

                    document.getElementById("post-container").insertAdjacentHTML('beforeend', newPosts);
                    page++;
                    document.getElementById('loading').classList.add('hidden');
                    loading = false;
                    attachLikeListeners();
                    attachCommentListeners();
                })
                .catch(error => {
                    console.error('Error loading more posts:', error);
                    document.getElementById('loading').classList.add('hidden');
                    loading = false;
                });
        }

        // Update responsive elements on page load and window resize
        window.addEventListener('DOMContentLoaded', () => {
            attachLikeListeners();
            attachCommentListeners();
            updateResponsiveElements();
        });

        window.addEventListener('resize', updateResponsiveElements);

        function attachLikeListeners() {
            const defaultProfileImage = "{{ asset('storage/profile_images/default-profile.png') }}";
            document.querySelectorAll('.like-button:not(.event-attached)').forEach(button => {
                button.classList.add('event-attached');
                button.addEventListener('click', function() {
                    const postId = this.getAttribute('data-post-id');
                    const likeCountSpan = this.querySelector('.like-count');
                    const initialCount = parseInt(likeCountSpan.textContent.split(' ')[0]);
                    const wasLiked = this.classList.contains('liked');

                    if (wasLiked) {
                        this.classList.remove('liked');
                        likeCountSpan.textContent = `${initialCount - 1} Likes`;
                    } else {
                        this.classList.add('liked');
                        likeCountSpan.textContent = `${initialCount + 1} Likes`;
                    }

                    fetch(`/posts/${postId}/like`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            if (wasLiked) {
                                this.classList.add('liked');
                                likeCountSpan.textContent = `${initialCount} Likes`;
                            } else {
                                this.classList.remove('liked');
                                likeCountSpan.textContent = `${initialCount} Likes`;
                            }
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Optional: re-sync with server count if needed
                    })
                    .catch(error => {
                        console.error('Error liking post:', error);
                        alert('Failed to update like. Please try again.');
                    });
                });
            });
        }

        function attachCommentListeners() {
            const defaultProfileImage = "{{ asset('storage/profile_images/default-profile.png') }}";
            let offset = 0;
            const limit = 5;

            document.querySelectorAll('.comment-toggle:not(.event-attached)').forEach(button => {
                button.classList.add('event-attached');
                button.addEventListener('click', function() {
                    const postId = this.getAttribute('data-post-id');
                    const postContainer = document.querySelector(`[data-post-id="${postId}"]`);
                    if (!postContainer) return;

                    const commentsSection = postContainer.querySelector('.comments-section');
                    if (!commentsSection) return;

                    const commentList = commentsSection.querySelector('.comment-list');
                    if (!commentList) return;

                    const loadMoreButton = commentsSection.querySelector('.load-more-comments');

                    if (commentsSection.style.display === 'none' || commentsSection.style.display === '') {
                        commentsSection.style.display = 'block';
                        commentsSection.dataset.offset = 0;
                        loadComments(postId, commentList, loadMoreButton, 0, limit);
                    } else {
                        commentsSection.style.display = 'none';
                    }
                });
            });

            document.querySelectorAll('.load-more-comments:not(.event-attached)').forEach(button => {
                button.classList.add('event-attached');
                button.addEventListener('click', function() {
                    const postId = this.getAttribute('data-post-id');
                    const commentsSection = this.closest('.comments-section');
                    const commentList = commentsSection.querySelector('.comment-list');
                    let currentOffset = parseInt(commentsSection.dataset.offset || 0);
                    currentOffset += limit;
                    commentsSection.dataset.offset = currentOffset;
                    loadComments(postId, commentList, this, currentOffset, limit);
                });
            });

            document.querySelectorAll('.comment-form:not(.event-attached)').forEach(form => {
                form.classList.add('event-attached');
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const postId = this.getAttribute('data-post-id');
                    const body = this.querySelector('input[name="body"]').value;

                    fetch(`/posts/${postId}/comments`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ body })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        const comment = data.comment;
                        const commentList = this.closest('.comments-section').querySelector('.comment-list');
                        const commentHtml = `
                            <div class="comment flex items-start space-x-2 mb-2">
                                <a href="/profiles/${comment.user_id}" class="flex-shrink-0">
                                    <img src="${comment.profile_image ? comment.profile_image : defaultProfileImage}"
                                         class="w-6 h-6 rounded-full object-cover hover:opacity-80 transition-opacity"
                                         alt="${comment.user}'s profile picture">
                                </a>
                                <div class="comment-content">
                                    <a href="/profiles/${comment.user_id}" class="comment-username font-semibold text-indigo-600 dark:text-gray-100 hover:underline" data-selector="#color-comment-username">${comment.user}</a>
                                    <p class="comment-text text-sm text-gray-700 dark:text-gray-300" data-selector="#color-comment-text">${comment.body}</p>
                                    <span class="comment-time text-xs text-gray-500" data-selector="#color-comment-time">${comment.created_at}</span>
                                </div>
                            </div>
                        `;
                        commentList.insertAdjacentHTML('afterbegin', commentHtml);
                        this.reset();
                        const commentCountSpan = document.querySelector(`[data-post-id="${postId}"] .comment-count`);
                        const currentCount = parseInt(commentCountSpan.textContent) || 0;
                        commentCountSpan.textContent = `${currentCount + 1} Comments`;
                    })
                    .catch(error => {
                        console.error('Error posting comment:', error);
                        alert('Failed to post comment. Please try again.');
                    });
                });
            });
        }

        function loadComments(postId, commentList, loadMoreButton, currentOffset, limit) {
            const defaultProfileImage = "{{ asset('storage/profile_images/default-profile.png') }}";
            fetch(`/posts/${postId}/comments?offset=${currentOffset}&limit=${limit}`)
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    const comments = data.comments || [];
                    const total = data.total || 0;
                    const commentsHtml = comments.map(comment => `
                        <div class="comment flex items-start space-x-2 mb-2">
                            <a href="/profiles/${comment.user_id}" class="flex-shrink-0">
                                <img src="${comment.profile_image ? comment.profile_image : defaultProfileImage}"
                                     class="w-6 h-6 rounded-full object-cover hover:opacity-80 transition-opacity"
                                     alt="${comment.user}'s profile picture">
                            </a>
                            <div>
                                <a href="/profiles/${comment.user_id}" class="comment-username font-semibold text-indigo-600 dark:text-gray-100 hover:underline" data-selector="#color-comment-username">${comment.user}</a>
                                <p class="comment-text text-sm text-gray-700 dark:text-gray-300" data-selector="#color-comment-text">${comment.body}</p>
                                <span class="comment-time text-xs text-gray-500" data-selector="#color-comment-time">${comment.created_at}</span>
                            </div>
                        </div>
                    `).join('');

                    if (currentOffset === 0) {
                        commentList.innerHTML = commentsHtml;
                    } else {
                        commentList.insertAdjacentHTML('beforeend', commentsHtml);
                    }

                    if (total > currentOffset + limit) {
                        loadMoreButton.style.display = 'block';
                    } else {
                        loadMoreButton.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error loading comments:', error);
                    commentList.innerHTML = `<p class="text-sm text-red-500">Failed to load comments. Please try again later.</p>`;
                    loadMoreButton.style.display = 'none';
                });
        }

        function formatCaption(caption) {
            let formatted = caption.replace(/&/g, '&').replace(/</g, '<').replace(/>/g, '>');
            formatted = formatted.replace(/#(\w+)/g, '<a href="/hashtag/$1" class="text-blue-500 hover:underline dark:text-blue-400">#$1</a>');
            formatted = formatted.replace(/@(\w+)/g, '<a href="/profiles/$1" class="text-blue-500 hover:underline dark:text-blue-400">@$1</a>');
            formatted = formatted.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:underline dark:text-blue-400">$1</a>');
            return formatted;
        }
    </script>

    <style>
        .like-button.liked .heart-icon { fill: #ff0000; stroke: #ff0000; }

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .overflow-x-auto { -webkit-overflow-scrolling: touch; }
            .overflow-x-auto::-webkit-scrollbar { height: 4px; }
            .overflow-x-auto::-webkit-scrollbar-thumb { background: #888; border-radius: 2px; }
        }

        /* Hide scrollbar for a cleaner look */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none; /* IE and Edge */
            scrollbar-width: none; /* Firefox */
        }
    </style>
</x-app-layout>