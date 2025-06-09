<x-app-layout>

    <div class="fixed inset-0 flex items-center justify-center  bg-opacity-75 dark:bg-opacity-90 z-50 overflow-y-auto p-2 sm:p-6 social-post-wrapper" data-selector=".social-post-wrapper" id="social-post-modal">
        <div class="rounded-lg shadow-2xl flex flex-col lg:flex-row max-w-6xl w-full h-[90vh] sm:h-[85vh] overflow-hidden post-container" data-selector=".post-container" id="post-container">
            <button class="absolute lg:hidden top-2 right-2 z-50 text-white hover:text-gray-300 focus:outline-none close-modal-mobile" aria-label="Close post">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <button class="absolute z-50 text-white hover:text-gray-300 focus:outline-none close-modal hidden lg:block" id="close-modal" aria-label="Close post">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <div class="flex-none w-full lg:w-3/5 relative bg-black image-container">
                <img src="/storage/{{ $post->image }}" class="w-full h-full object-contain rounded-t-lg lg:rounded-l-lg lg:rounded-t-none" alt="{{ $post->caption }}">
            </div>

            <div class="flex-none w-full lg:w-2/5 p-4 sm:p-6 flex flex-col border-l border-gray-200 dark:border-gray-700 rounded-b-lg lg:rounded-r-lg lg:rounded-b-none comments-and-actions-section" data-selector=".post-container-inner">
                <div class="flex items-center justify-between border-b border-gray-300 dark:border-gray-600 pb-3 mb-4 top-post-bar" data-selector=".top-post-bar">
                    <a href="/profiles/{{ $post->user->id }}" class="flex items-center space-x-3">
                        <img src="/storage/{{ $post->user->profile->image ?? 'default-profile.jpg' }}"
                             class="w-10 h-10 rounded-full object-cover border-2 border-indigo-300 dark:border-purple-500 profile-bar-image">
                        <div class="font-semibold text-base truncate top-post-bar-text text-gray-900 dark:text-gray-100" data-selector=".top-post-bar-text">
                            {{ $post->user->name }}
                        </div>
                    </a>
                    @auth
                        <div class="flex justify-end items-center ml-auto relative">
                            <button class="focus:outline-none" id="menu-button-{{ $post->id }}" aria-label="More options" aria-haspopup="true">
                                <svg class="w-6 h-6 text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg z-10 hidden" id="menu-dropdown-{{ $post->id }}">
                                <div class="py-1">
                                    <form action="/p/{{ $post->id }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" onclick="return confirm('Are you sure you want to delete this post?')">Delete Post</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>

                <div class="flex items-start space-x-3 mb-4 caption-container" data-selector=".caption-container">
                    <img src="/storage/{{ $post->user->profile->image ?? 'default-profile.jpg' }}"
                         class="w-8 h-8 rounded-full object-cover border-indigo-300 dark:border-purple-500 flex-shrink-0 caption-profile-image">
                    <div class="text-sm text-gray-900 dark:text-gray-100">
                        <span class="font-semibold caption-username" data-selector=".caption-username">{{ $post->user->name }}</span>
                        <span class="ml-2 caption-text" data-selector=".caption-text"
                            x-data="{
                                rawCaption: @js($post->caption),
                                showFull: false,
                                truncatedLength: 150,
                                get displayCaptionText() {
                                    if (!this.showFull && this.rawCaption.length > this.truncatedLength) {
                                        let truncatedRaw = this.rawCaption.substring(0, this.truncatedLength);
                                        let lastSpace = truncatedRaw.lastIndexOf(' ');
                                        let finalTruncated = lastSpace !== -1 ? truncatedRaw.substring(0, lastSpace) : truncatedRaw;
                                        return finalTruncated + '...';
                                    }
                                    return this.rawCaption;
                                },
                                get needsTruncation() {
                                    return this.rawCaption.length > this.truncatedLength;
                                }
                            }">
                            <span x-html="formatCaption(displayCaptionText)"></span>
                            <template x-if="needsTruncation">
                                <button @click="showFull = !showFull"
                                        class="text-blue-600 hover:underline dark:text-blue-400 dark:hover:text-blue-300 text-xs ml-1">
                                    <span x-text="showFull ? 'less' : 'more'"></span>
                                </button>
                            </template>
                        </span>
                    </div>
                </div>

                @auth
          <div class="flex-grow pr-2 comments-section" id="comments-section-{{ $post->id }}" data-selector=".comments-section">
        <div class="comment-list space-y-3" id="comment-list-{{ $post->id }}">
            @foreach ($post->comments->take(5) as $comment)
                <div class="flex items-start space-x-2 comment-item">
                    <img src="/storage/{{ $comment->user->profile->image ?? 'default-profile.jpg' }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0 comment-profile-image">
                    <div class="flex-1">
                        <div class="flex items-baseline space-x-1">
                            <span class="font-semibold text-sm comment-username text-indigo-700 dark:text-gray-100" data-selector=".comment-username">{{ $comment->user->name }}</span>
                            <p class="text-sm break-words comment-text text-gray-700 dark:text-gray-300" data-selector=".comment-text">{{ $comment->body }}</p>
                        </div>
                        <span class="text-xs comment-time text-gray-500" data-selector=".comment-time">{{ $comment->created_at ? $comment->created_at->diffForHumans() : 'N/A' }}</span>
                    </div>
                </div>
            @endforeach
        </div>
        @if ($post->comments()->count() > 5)
            <button id="load-more-{{ $post->id }}" class="mt-2 w-full text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-semibold focus:outline-none" data-offset="5">
                Load more comments ({{ $post->comments()->count() - 5 }} remaining)
            </button>
        @endif
    </div>

                   <div class="mt-2 p-2 flex flex-col post-actions-section">
        <div class="flex justify-start mb-2 space-x-4">
            <button class="like-button flex items-center space-x-1 {{ $post->likedBy(auth()->user()) ? 'liked' : '' }} text-gray-700 dark:text-gray-300" data-post-id="{{ $post->id }}" data-selector=".like-button">
                <svg class="w-5 h-5 heart-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-selector=".heart-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span class="like-count text-sm" data-selector=".like-count">{{ $post->likes()->count() }}</span>
                <span class="comment-count hidden sm:inline text-sm">Like{{ $post->likes()->count() !== 1 ? 's' : '' }}</span>
            </button>
            <button class="comment-button flex items-center space-x-1 text-gray-700 dark:text-gray-300" data-post-id="{{ $post->id }}" data-selector=".comment-button">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                <span class="comment-count text-sm" data-selector=".comment-count">{{ $post->comments()->count() }}</span>
                <span class="hidden sm:inline text-sm">Comment{{ $post->comments()->count() !== 1 ? 's' : '' }}</span>
            </button>
        </div>
    
    </div>

                        @php
                            $followedUsersWhoLiked = $post->likes()->whereIn('user_id', auth()->user()->following()->pluck('follows.followee_id'))->with(['user.profile'])->get();
                            $likeCount = $post->likes()->count();
                            $displayedText = '';
                            $displayedImages = collect();
                            $explicitlyNamedUsers = collect();

                            foreach ($followedUsersWhoLiked as $like) {
                                if (!$explicitlyNamedUsers->contains('id', $like->user->id)) {
                                    $explicitlyNamedUsers->push($like->user);
                                }
                            }

                            $usersToDisplay = $explicitlyNamedUsers->take(2);
                            foreach ($usersToDisplay as $user) {
                                $displayedImages->push($user->profile->image ? Storage::url($user->profile->image) : asset('storage/profile_images/default-profile.png'));
                            }

                            $othersCountOfFollowedUsers = $explicitlyNamedUsers->count() - $usersToDisplay->count();

                            if ($usersToDisplay->isNotEmpty()) {
                                $names = $usersToDisplay->map(function($user) { return $user->name; })->toArray();
                                $numDisplayed = count($names);

                                if ($numDisplayed === 1) {
                                    $displayedText = $names[0];
                                } else {
                                    $displayedText = $names[0] . ' and ' . $names[1];
                                }

                                if ($othersCountOfFollowedUsers > 0) {
                                    $displayedText .= ' and ' . $othersCountOfFollowedUsers . ' ' . ($othersCountOfFollowedUsers === 1 ? 'other' : 'others');
                                }
                            }
                        @endphp

                        @if ($displayedText)
                            <div class="likedby-container text-sm mb-4 flex items-center space-x-2 text-gray-700 dark:text-gray-300" data-selector=".likedby-container">
                                <div class="flex -space-x-2">
                                    @foreach($displayedImages as $index => $image)
                                        <img src="{{ $image }}"
                                             class="w-6 h-6 rounded-full object-cover border-2 border-indigo-300 dark:border-blue-500 {{ $index > 0 ? 'ml-[-0.5rem]' : '' }}"
                                             style="z-index: {{ 10 - $index }};"
                                             alt="Profile picture">
                                    @endforeach
                                </div>
                                <span class="flex items-center gap-x-1">
                                    <span class="likedby-text" data-selector=".likedby-text">Liked by </span>
                                    <span class="likedby-users font-semibold likedby-username text-gray-900 dark:text-gray-100" data-selector=".likedby-username">
                                        {!! $displayedText !!}
                                    </span>
                                </span>
                            </div>
                        @elseif ($likeCount > 0)
                            <div class="text-sm mb-4 likedby-container text-gray-700 dark:text-gray-300" data-selector=".likedby-container">
                                <span class="likedby-text" data-selector=".likedby-text">Liked by <span class="font-semibold likedby-others text-gray-900 dark:text-gray-100" data-selector=".likedby-others">{{ $likeCount }} others</span></span>
                            </div>
                        @endif

                        <form class="comment-form flex items-center space-x-2" data-post-id="{{ $post->id }}">
                            <input type="text" name="body" class="flex-1 p-2 rounded-md post-comment-input bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" placeholder="Add a comment..." required data-selector=".post-comment-input">
                            <button type="submit" class="p-2 rounded-md post-comment-button bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition" data-selector=".post-comment-button">Post</button>
                        </form>
                    </div>
                @endauth

                @guest
                    <div class="flex justify-start space-x-6 mb-4 post-actions-section text-gray-700 dark:text-gray-300">
                        <span class="flex items-center space-x-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span>{{ $post->likes()->count() }} Like{{ $post->likes()->count() !== 1 ? 's' : '' }}</span>
                        </span>
                        <span class="flex items-center space-x-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            <span>{{ $post->comments()->count() }} Comment{{ $post->comments()->count() !== 1 ? 's' : '' }}</span>
                        </span>
                    </div>
                    <div class="flex-grow  mt-auto comments-section">
                        <div class="comment-list max-h-60 overflow-y-auto space-y-4" id="comment-list-{{ $post->id }}">
                            @foreach ($post->comments->take(5) as $comment)
                                <div class="flex items-start space-x-3 comment-item">
                                    <img src="/storage/{{ $comment->user->profile->image ?? 'default-profile.jpg' }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0 comment-profile-image">
                                    <div class="flex-1">
                                        <div class="flex items-baseline space-x-2">
                                            <span class="font-semibold text-base comment-username text-indigo-700 dark:text-gray-100" data-selector=".comment-username">{{ $comment->user->name }}</span>
                                            <p class="text-base break-words comment-text text-gray-700 dark:text-gray-300" data-selector=".comment-text">{{ $comment->body }}</p>
                                        </div>
                                        <span class="text-xs comment-time text-gray-500" data-selector=".comment-time">{{ $comment->created_at ? $comment->created_at->diffForHumans() : 'N/A' }}</span>
                                    </div>
                                </div>
                                <hr class="comment-divider border-gray-200 dark:border-gray-700" data-selector=".comment-divider">
                            @endforeach
                        </div>
                        @if ($post->comments->count() > 5)
                            <button id="load-more-{{ $post->id }}" class="mt-4 w-full text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-semibold focus:outline-none" data-offset="5">
                                Load more comments ({{ $post->comments()->count() - 5 }} remaining)
                            </button>
                        @endif
                    </div>
                @endguest
            </div>
        </div>
    </div>

  <script>
    function formatCaption(caption) {
        let formatted = caption.replace(/&/g, '&').replace(/</g, '<').replace(/>/g, '>');
        formatted = formatted.replace(/#(\w+)/g, '<a href="/hashtag/$1" class="text-blue-600 hover:underline dark:text-blue-400">#$1</a>');
        formatted = formatted.replace(/@(\w+)/g, '<a href="/profiles/$1" class="text-blue-600 hover:underline dark:text-blue-400">@$1</a>');
        formatted = formatted.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline dark:text-blue-400">$1</a>');
        return formatted;
    }

    document.addEventListener('DOMContentLoaded', () => {
        const postId = {{ $post->id }};
        const defaultProfileImage = "{{ asset('storage/profile_images/default-profile.png') }}";
        const limit = 5;

        const socialPostModal = document.getElementById('social-post-modal');
        const postContainer = document.getElementById('post-container');
        const closeModalButtonDesktop = document.getElementById('close-modal'); // Existing desktop button
        const closeModalButtonMobile = document.querySelector('.close-modal-mobile'); // New mobile button

        const commentsSection = document.getElementById(`comments-section-${postId}`);
        const commentList = document.getElementById(`comment-list-${postId}`);
        const loadMoreButton = document.getElementById(`load-more-${postId}`);

        // Initialize offset in commentsSection dataset
        if (commentsSection) {
            commentsSection.dataset.offset = 0;
        }

        function closePostModal() {
            socialPostModal.classList.add('hidden');
            window.history.back();
        }

        // Event listeners for both desktop and mobile close buttons
        if (closeModalButtonDesktop) {
            closeModalButtonDesktop.addEventListener('click', () => {
                closePostModal();
            });
        }
        if (closeModalButtonMobile) {
            closeModalButtonMobile.addEventListener('click', () => {
                closePostModal();
            });
        }


        if (socialPostModal && postContainer) {
            socialPostModal.addEventListener('click', (event) => {
                if (event.target === socialPostModal) {
                    closePostModal();
                }
            });
        }

        // Like button functionality
        const likeButton = document.querySelector(`.like-button[data-post-id="${postId}"]`);
        if (likeButton && !likeButton.classList.contains('event-attached')) {
            likeButton.classList.add('event-attached');
            likeButton.addEventListener('click', function() {
                const likeCountSpan = this.querySelector('.like-count');
                const likeTextSpan = this.querySelector('.comment-count.hidden.sm\\:inline');
                let initialCount = parseInt(likeCountSpan.textContent);
                const wasLiked = this.classList.contains('liked');

                if (wasLiked) {
                    this.classList.remove('liked');
                    initialCount--;
                } else {
                    this.classList.add('liked');
                    initialCount++;
                }
                likeCountSpan.textContent = initialCount;
                if (likeTextSpan) {
                    likeTextSpan.textContent = `Like${initialCount !== 1 ? 's' : ''}`;
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
                            initialCount++;
                        } else {
                            this.classList.remove('liked');
                            initialCount--;
                        }
                        likeCountSpan.textContent = initialCount;
                        if (likeTextSpan) {
                            likeTextSpan.textContent = `Like${initialCount !== 1 ? 's' : ''}`;
                        }
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    likeCountSpan.textContent = data.count;
                    if (likeTextSpan) {
                        likeTextSpan.textContent = `Like${data.count !== 1 ? 's' : ''}`;
                    }
                    this.classList.toggle('liked', data.liked);
                })
                .catch(error => {
                    console.error('Error liking post:', error);
                    const messageBox = document.createElement('div');
                    messageBox.className = 'fixed inset-0 flex items-center justify-center z-[100]';
                    messageBox.innerHTML = `
                        <div class="bg-red-500 text-white p-4 rounded-lg shadow-lg">
                            Failed to update like. Please try again.
                            <button class="ml-4 bg-red-700 hover:bg-red-800 px-3 py-1 rounded" onclick="this.parentElement.remove()">OK</button>
                        </div>
                    `;
                    document.body.appendChild(messageBox);
                });
            });
        }

        // Load more comments functionality
        if (loadMoreButton && !loadMoreButton.classList.contains('event-attached')) {
            loadMoreButton.classList.add('event-attached');
            loadMoreButton.addEventListener('click', function() {
                let currentOffset = parseInt(commentsSection.dataset.offset || 0);
                currentOffset += limit;
                commentsSection.dataset.offset = currentOffset;
                loadComments(postId, commentList, this, currentOffset, limit, defaultProfileImage);
            });
        }

        // Comment form submission
        const commentForm = document.querySelector(`.comment-form[data-post-id="${postId}"]`);
        if (commentForm && !commentForm.classList.contains('event-attached')) {
            commentForm.classList.add('event-attached');
            commentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const commentInput = this.querySelector('input[name="body"]');
                const commentBody = commentInput.value.trim();

                if (!commentBody) {
                    const messageBox = document.createElement('div');
                    messageBox.className = 'fixed inset-0 flex items-center justify-center z-[100]';
                    messageBox.innerHTML = `
                        <div class="bg-yellow-500 text-white p-4 rounded-lg shadow-lg">
                            Comment cannot be empty.
                            <button class="ml-4 bg-yellow-700 hover:bg-yellow-800 px-3 py-1 rounded" onclick="this.parentElement.remove()">OK</button>
                        </div>
                    `;
                    document.body.appendChild(messageBox);
                    return;
                }

                fetch(`/posts/${postId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ body: commentBody })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw new Error(err.message || `HTTP error! status: ${response.status}`); });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const newCommentHtml = `
                            <div class="flex items-start space-x-2 comment-item">
                                <img src="${data.comment.profile_image ? data.comment.profile_image : defaultProfileImage}" class="w-8 h-8 rounded-full object-cover flex-shrink-0 comment-profile-image">
                                <div class="flex-1">
                                    <div class="flex items-baseline space-x-2">
                                        <span class="font-semibold text-sm comment-username text-indigo-700 dark:text-gray-100" data-selector=".comment-username">${data.comment.user}</span>
                                        <p class="text-sm break-words comment-text text-gray-700 dark:text-gray-300" data-child="comment-text">${data.comment.body}</p>
                                    </div>
                                    <span class="text-xs comment-time text-gray-500" data-child="timestamp">${data.comment.created_at}</span>
                                </div>
                            </div>
                        `;
                        commentList.insertAdjacentHTML('afterbegin', newCommentHtml);
                        commentInput.value = '';

                        const commentCountSpan = document.querySelector(`.comment-button .comment-count`);
                        if (commentCountSpan) {
                            commentCountSpan.textContent = data.total_comments;
                            const commentTextSpan = commentCountSpan.nextElementSibling;
                            if (commentTextSpan && commentTextSpan.tagName === 'SPAN') {
                                commentTextSpan.textContent = `Comment${data.total_comments !== 1 ? 's' : ''}`;
                            }
                        }
                    } else {
                        const messageBox = document.createElement('div');
                        messageBox.className = 'fixed inset-0 flex items-center justify-center z-[100]';
                        messageBox.innerHTML = `
                            <div class="bg-red-500 text-white p-4 rounded-lg shadow-lg">
                                ${data.message || 'Failed to post comment.'}
                                <button class="ml-4 bg-red-700 hover:bg-red-800 px-3 py-1 rounded" onclick="this.parentElement.remove()">OK</button>
                            </div>
                        `;
                        document.body.appendChild(messageBox);
                    }
                })
                .catch(error => {
                    console.error('Error posting comment:', error);
                    const messageBox = document.createElement('div');
                    messageBox.className = 'fixed inset-0 flex items-center justify-center z-[100]';
                    messageBox.innerHTML = `
                        <div class="bg-red-500 text-white p-4 rounded-lg shadow-lg">
                            Failed to post comment: ${error.message}.
                            <button class="ml-4 bg-red-700 hover:bg-red-800 px-3 py-1 rounded" onclick="this.parentElement.remove()">OK</button>
                        </div>
                    `;
                    document.body.appendChild(messageBox);
                });
            });
        }

        // Menu dropdown functionality
        const menuButton = document.getElementById(`menu-button-${postId}`);
        const menuDropdown = document.getElementById(`menu-dropdown-${postId}`);
        if (menuButton && menuDropdown) {
            menuButton.addEventListener('click', function(event) {
                event.stopPropagation();
                menuDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function(event) {
                if (!menuButton.contains(event.target) && !menuDropdown.contains(event.target)) {
                    menuDropdown.classList.add('hidden');
                }
            });
        }

        function updateResponsiveElements() {
            const isMobile = window.innerWidth < 640;
            const likeTextSpan = document.querySelector('.like-button .comment-count.hidden.sm\\:inline');
            if (likeTextSpan) {
                likeTextSpan.classList.toggle('hidden', isMobile);
                likeTextSpan.classList.toggle('inline', !isMobile);
            }

            const commentTextSpan = document.querySelector('.comment-button span:last-child');
            if (commentTextSpan) {
                commentTextSpan.classList.toggle('hidden', isMobile);
                commentTextSpan.classList.toggle('inline', !isMobile);
            }
        }

        updateResponsiveElements();
        window.addEventListener('resize', updateResponsiveElements);

        function loadComments(postId, commentList, loadMoreButton, currentOffset, limit, defaultProfileImage) {
            fetch(`/posts/${postId}/comments?offset=${currentOffset}&limit=${limit}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                if (!data.comments || !Array.isArray(data.comments)) {
                    throw new Error('Invalid or empty comments data received');
                }

                const commentsHtml = data.comments.map(comment => `
                    <div class="flex items-start space-x-2 comment-item">
                        <img src="${comment.profile_image ? comment.profile_image : defaultProfileImage}" class="w-8 h-8 rounded-full object-cover flex-shrink-0 comment-profile-image">
                        <div class="flex-1">
                            <div class="flex items-baseline space-x-2">
                                <span class="font-semibold text-sm comment-username text-indigo-700 dark:text-gray-100" data-selector=".comment-username">${comment.user}</span>
                                <p class="text-sm break-words comment-text text-gray-700 dark:text-gray-300" data-child="comment-text">${comment.body}</p>
                            </div>
                            <span class="text-xs comment-time text-gray-500" data-child="timestamp">${comment.created_at}</span>
                        </div>
                    </div>
                `).join('');

                if (currentOffset === 0) {
                    commentList.innerHTML = commentsHtml;
                } else {
                    commentList.insertAdjacentHTML('beforeend', commentsHtml);
                }

                if (loadMoreButton) {
                    const totalComments = data.total || 0;
                    const loadedComments = currentOffset + data.comments.length;
                    if (totalComments > loadedComments) {
                        loadMoreButton.style.display = 'block';
                        loadMoreButton.textContent = `Load more comments (${totalComments - loadedComments} remaining)`;
                    } else {
                        loadMoreButton.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                console.error('Error loading comments:', error);
                const messageBox = document.createElement('div');
                messageBox.className = 'fixed inset-0 flex items-center justify-center z-[100]';
                messageBox.innerHTML = `
                    <div class="bg-red-500 text-white p-4 rounded-lg shadow-lg">
                        Failed to load comments: ${error.message}.
                        <button class="ml-4 bg-red-700 hover:bg-red-800 px-3 py-1 rounded" onclick="this.parentElement.remove()">OK</button>
                    </div>
                `;
                document.body.appendChild(messageBox);
                if (loadMoreButton) {
                    loadMoreButton.style.display = 'none';
                }
            });
        }
    });
</script>

    <style>
      
        /* General Styles for the modal */
        .comments-section.overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }
        .comments-section.overflow-y-auto::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        .comments-section.overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        .caption-container {
            word-break: break-word;
            overflow-wrap: break-word;
        }
        .caption-container a {
            font-weight: 500;
        }
        .comment-list {
            min-height: 0;
            padding-right: 0.5rem; /* Added for scrollbar space */
        }
        .bg-black {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #000;
        }

        /* Adjust top margin for the modal to clear the nav bar */
        .social-post-wrapper {
            top: 4rem; /* Adjust this value based on your actual nav bar height */
            height: calc(100% - 4rem); /* Occupy remaining height */
            padding: 0.5rem; /* Reduced overall padding for tighter mobile fit */
        }

        /* Mobile View (max-width: 1024px) */
        @media (max-width: 1024px) {
            .social-post-wrapper {
                top: 0; /* Align to the very top */
                height: 100vh; /* Take full viewport height */
                padding: 0; /* Remove all padding for full screen */
                z-index: 70;
            }
            .post-container {
                height: 100%; /* Take full height of the wrapper */
                width: 100%; /* Take full width of the wrapper */
                max-height: none; /* Remove previous max-height constraint */
                flex-direction: column;
                border-radius: 0; /* Remove rounded corners for full screen feel on mobile */
                align-items: stretch; /* Ensure children stretch to fill width */
            }

            /* Positioning for the mobile close button */
            .close-modal-mobile {
                position: absolute;
                top: 0.5rem;
                right: 0.5rem;
                width: 2.5rem;
                height: 2.5rem;
                padding: 0.25rem;
                z-index: 60; /* Ensure it's above other elements */
                display: block; /* Show on mobile */
            }

            .close-modal { /* Hide the desktop close button on mobile */
                display: none;
            }


            .image-container {
                flex-shrink: 0; /* Prevent shrinking */
                width: 100%;
                height: 50%; /* Image takes 50% of modal height */
                max-height: 50vh; /* Max height to ensure it doesn't get too big */
                border-radius: 0; /* Remove rounded corners */
            }

            .comments-and-actions-section {
                flex-grow: 1; /* Take all remaining vertical space */
                width: 100%;
                height: 50%; /* Comments take 50% of modal height */
                border-left: none;
                border-top: 1px solid #d1d5db; /* Default border color */
                padding: 1rem;
                overflow-y: auto;
                min-height: 0; /* Allow content to shrink if needed */
            }
            
            .comments-section {
                flex-grow: 1;
                overflow-y: auto;
                padding-right: 0.25rem;
                padding-bottom: 0.5rem;
                min-height: 0; /* Allow to shrink */
                height: auto; /* Remove fixed height for mobile, allow flex-grow to work */
                max-height: unset; /* Ensure no max-height prevents scrolling */
            }
            
            /* Remove border-bottom from comment-item */
            .comment-item {
                border-bottom: none !important;
            }
            .comment-list {
                space-y: 1rem !important; /* Adjust space between comments if borders are removed */
            }

            /* Font and profile pic sizes remain as per last update, which addressed those. */
            .profile-bar-image, .caption-profile-image, .comment-profile-image {
                width: 2rem; /* w-8 */
                height: 2rem; /* h-8 */
            }
            .top-post-bar-text {
                font-size: 0.875rem; /* text-sm */
            }
            .caption-username, .comment-username, .caption-text, .comment-text, .post-comment-input, .post-comment-button {
                font-size: 0.8125rem; /* ~13px */
            }
            .comment-time, .likedby-container, .likedby-text, .likedby-username, .likedby-others {
                font-size: 0.6875rem; /* ~11px */
            }
            .like-button, .comment-button {
                font-size: 0.875rem; /* text-sm for like/comment buttons */
            }
            .like-button svg, .comment-button svg {
                width: 1.25rem; /* w-5 h-5 for icons */
                height: 1.25rem;
            }
            .likedby-container img {
                width: 1.5rem; /* w-6 for liked by profile images */
                height: 1.5rem; /* h-6 for liked by profile images */
            }
        }

        /* Desktop View (min-width: 1025px) */
        @media (min-width: 1025px) {
            .max-w-6xl {
                max-height: 90vh; /* Allow the entire modal to be taller */
                flex-direction: row; /* Keep items side-by-side on larger screens */
            }
            .image-container {
                /* Make it fill the height of the post-container without fixed max-height */
                height: 100%; 
                max-height: none; 
            }

            .comments-and-actions-section {
                flex-grow: 1; /* Allow it to take up available space */
                overflow-y: hidden; /* Hide overflow on the section, comments-section handles its own scroll */
                display: flex; /* Make it a flex container */
                flex-direction: column; /* Stack children vertically */
            }

            .comments-section {
                flex-grow: 1; /* This will make the comments section take all available space */
                overflow-y: auto; /* Enable scrolling for comments on desktop */
                min-height: 0; /* Important for flex items with overflow */
                max-height: unset; /* Ensure no max height constraint */
            }

             .close-modal { /* Show the desktop close button on desktop */
                display: block;
                top: 0.5rem; /* Adjust positioning as needed for desktop */
                right: 0.5rem;
            }

            .close-modal-mobile { /* Hide the mobile close button on desktop */
                display: none;
            }
        }

        /* Dark mode colors matching index.blade.php */
        .dark .social-post-wrapper {
            background-color: rgba(17, 24, 39, 0.9); /* dark:bg-gray-900 with opacity */
        }

        .dark .post-container {
            background-color: #1f2937; /* dark:bg-gray-800 */
        }

        .dark .comments-and-actions-section {
            background-color: #1f2937; /* dark:bg-gray-800 */
            border-color: #374151; /* dark:border-gray-700 */
        }
        .dark .top-post-bar {
            border-color: #4b5563; /* dark:border-gray-600 */
        }
        .dark .comment-divider {
            border-color: #374151; /* dark:border-gray-700 */
        }

        /* Text colors */
        .dark .text-gray-900 {
            color: #f9fafb; /* dark:text-gray-100 */
        }
        .dark .text-gray-700 {
            color: #d1d5db; /* dark:text-gray-300 */
        }
        .dark .text-gray-600 {
            color: #9ca3af; /* dark:text-gray-400 */
        }
        .dark .text-gray-500 {
            color: #9ca3af; /* dark:text-gray-400 - for comment time */
        }
        .dark .text-indigo-700 {
            color: #d1d5db; /* dark:text-gray-100 (for usernames that were indigo) */
        }
        .dark .text-indigo-600 {
             color: #818cf8; /* dark:text-indigo-400 or a similar light purple */
        }
         .dark .text-indigo-500 {
             color: #818cf8; /* dark:text-blue-400 (for 'more'/'less' button) */
        }
        .dark .text-blue-600 {
            color: #60a5fa; /* dark:text-blue-400 for links in caption */
        }

        /* Buttons and inputs */
        .dark .post-comment-input {
            background-color: #374151; /* dark:bg-gray-700 */
            color: #f9fafb; /* dark:text-gray-100 */
            border-color: #4b5563; /* dark:border-gray-600 */
            &::placeholder {
                color: #9ca3af; /* dark:placeholder-gray-400 */
            }
        }
        .dark .post-comment-button {
            background-color: #3b82f6; /* dark:bg-blue-500 */
            &:hover {
                background-color: #2563eb; /* dark:hover:bg-blue-600 */
            }
        }

        /* Like button states */
        .like-button.liked .heart-icon { fill: #ff0000; stroke: #ff0000; }
        .dark .like-button.liked .heart-icon { fill: #dc2626; stroke: #dc2626; } /* Darker red for dark mode liked state */
        .dark .like-button svg { stroke: #d1d5db; } /* Ensure unliked heart is visible in dark mode */

        /* Profile image borders */
        .dark .border-indigo-300 {
            border-color: #6366f1; /* dark:border-blue-500 (approximating previous purple-500) */
        }
        .dark .border-purple-500 { /* Fallback/specific override for consistency */
            border-color: #6366f1; /* dark:border-blue-500 */
        }

        /* Overrides for specific text elements if needed */
        .dark [data-selector=".top-post-bar-text"],
        .dark [data-selector=".caption-username"],
        .dark [data-selector=".likedby-username"],
        .dark [data-selector=".likedby-others"] {
            color: #f9fafb !important; /* Force gray-100 for these specific elements */
        }

        .dark [data-selector=".caption-text"],
        .dark [data-selector=".comment-text"] {
            color: #d1d5db !important; /* Force gray-300 for caption and comment text */
        }
         .dark [data-selector=".comment-time"] {
            color: #9ca3af !important; /* Force gray-400 for comment time */
        }

        .dark [data-selector=".comment-username"] {
            color: #d1d5db !important; /* Force gray-100 for comment usernames */
        }

        /* Scrollbar for comments section in dark mode */
        .dark .comments-section.overflow-y-auto::-webkit-scrollbar-thumb {
            background: #4b5563; /* Darker thumb for dark mode */
        }
        .dark .comments-section.overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #6b7280; /* Even darker on hover */
        }


</style>

</x-app-layout>