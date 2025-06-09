<div class="post-container relative w-full dark:bg-gray-800 p-6 border-b border-gray-300 dark:border-gray-600 rounded-none shadow-[2px_0_4px_rgba(0,0,0,0.1),-2px_0_4px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_4px_rgba(0,0,0,0.2),-2px_0_4px_rgba(0,0,0,0.2)]" data-post-id="{{ $post->id }}">
    <div class="top-post-bar absolute top-2 left-2 right-2 h-10 flex items-center justify-between bg-white dark:bg-gray-800 bg-opacity-90 dark:bg-opacity-90 rounded-md px-2">
        <a href="/profiles/{{ $post->user->id }}" class="flex items-center space-x-2">
            <img src="{{ $post->user->profile->image ? Storage::url($post->user->profile->image) : asset('storage/profile_images/default-profile.png') }}"
                 class="w-8 h-8 rounded-full object-cover"
                 alt="{{ $post->user->name }}'s profile picture">
            <span class="top-post-bar-text font-semibold text-base text-indigo-600 dark:text-blue-400 truncate">{{ $post->user->name }}</span>
            <p class="top-post-bar-time text-sm text-indigo-500 dark:text-gray-400 hidden md:block">{{ $post->created_at->diffForHumans() }}</p>
        </a>
        <form action="{{ route('messages.start') }}" method="POST">
            @csrf
            <input type="hidden" name="recipient_id" value="{{ $post->user_id }}">
            <button type="submit" class="message-button text-indigo-600 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            </button>
        </form>
    </div>

    <div class="mt-8 mb-6 -mx-6">
        <img src="{{ Storage::url($post->image) }}" class="w-full h-[400px] sm:h-[480px] object-cover" alt="{{ $post->caption }}">
    </div>

    @auth
        <div class="flex justify-start space-x-6 mb-4">
            <button class="like-button flex items-center space-x-2 text-indigo-600 dark:text-blue-200 hover:text-pink-500 dark:hover:text-pink-400 {{ $post->likedBy(auth()->user()) ? 'liked' : '' }}" data-post-id="{{ $post->id }}">
                <svg class="w-6 h-6 heart-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span class="like-count">{{ $post->likes()->count() }} Likes</span>
            </button>
            <button class="comment-toggle flex items-center space-x-2 text-indigo-600 dark:text-blue-200 hover:text-blue-500 dark:hover:text-blue-400" data-post-id="{{ $post->id }}">
                <svg class="comment-button w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                <span class="comment-count">{{ $post->comments()->count() }} Comments</span>
            </button>
        </div>

        @php
            $currentUserLiked = $post->likedBy(auth()->user());
            $followedUsersWhoLiked = $post->likes()
                ->whereIn('user_id', auth()->user()->following()->pluck('follows.followee_id'))
                ->with(['user.profile'])
                ->get();
            $likeCount = $post->likes()->count(); // Total likes for the 'X Likes' button

            $displayedText = '';
            $displayedImages = collect(); // Collection of profile image URLs to display

            $explicitlyNamedUsers = collect(); // Collection of users explicitly named (followed users)

            // Add followed users who liked
            foreach ($followedUsersWhoLiked as $like) {
                if (!$explicitlyNamedUsers->contains('id', $like->user->id)) {
                    $explicitlyNamedUsers->push($like->user);
                }
            }

            // Get up to 2 users for explicit image/name display
            $usersToDisplay = $explicitlyNamedUsers->take(2);
            foreach ($usersToDisplay as $user) {
                $displayedImages->push($user->profile->image ? Storage::url($user->profile->image) : asset('storage/profile_images/default-profile.png'));
            }

            // Calculate 'others' count based *only* on remaining followed users
            // Total followed users who liked minus the ones already explicitly displayed.
            $othersCountOfFollowedUsers = $explicitlyNamedUsers->count() - $usersToDisplay->count();


            // Determine what to display in text
            if ($usersToDisplay->isNotEmpty()) {
                $names = $usersToDisplay->map(function($user) {
                    return $user->name;
                })->toArray();

                $numDisplayed = count($names);

                if ($numDisplayed === 1) {
                    $displayedText = $names[0];
                } else { // numDisplayed is 2
                    $displayedText = $names[0] . ' and ' . $names[1];
                }

                if ($othersCountOfFollowedUsers > 0) {
                    $displayedText .= ' and ' . $othersCountOfFollowedUsers . ' ' . ($othersCountOfFollowedUsers === 1 ? 'other' : 'others');
                }
            }
            // If $usersToDisplay is empty, $displayedText will remain empty,
            // effectively hiding the "Liked by" container as per the latest instruction.
        @endphp

        @if ($displayedText)
            <div class="likedby-container text-sm text-gray-600 dark:text-gray-400 mb-4 flex items-center space-x-2">
                <div class="flex -space-x-2"> {{-- Flex container for overlapping images --}}
                    {{-- Display up to 2 profile pictures of followed users who liked --}}
                    @foreach($displayedImages as $index => $image)
                        <img src="{{ $image }}"
                             class="w-6 h-6 rounded-full object-cover border-2 border-indigo-300 dark:border-blue-500 {{ $index > 0 ? 'ml-[-0.5rem]' : '' }}" {{-- Apply negative margin from second image --}}
                             style="z-index: {{ 2 - $index }};" {{-- Ensure correct overlap order, higher z-index for images on top --}}
                             alt="Profile picture">
                    @endforeach
                </div>
                <span class="flex items-center gap-x-1">
                    <span class="likedby-text">Liked by </span>
                    <span class="likedby-users font-semibold text-indigo-600 dark:text-gray-100">
                        {!! $displayedText !!}
                    </span>
                </span>
            </div>
        @endif

        <div class="caption-container text-sm text-indigo-800 dark:text-gray-200 mb-4 border-t border-gray-200 dark:border-gray-700 pt-4 mt-4"
             x-data="{
                 rawCaption: @js($post->caption), // Store raw caption
                 showFull: false,
                 truncatedLength: 150, // Characters after which to truncate the raw text
                 get displayCaptionText() {
                     if (!this.showFull && this.rawCaption.length > this.truncatedLength) {
                         let truncatedRaw = this.rawCaption.substring(0, this.truncatedLength);
                         // Find the last space to avoid cutting words in half
                         let lastSpace = truncatedRaw.lastIndexOf(' ');
                         let finalTruncated = lastSpace !== -1 ? truncatedRaw.substring(0, lastSpace) : truncatedRaw;
                         return finalTruncated + '...'; // Add '...'
                     }
                     return this.rawCaption; // Return full raw string
                 },
                 get needsTruncation() {
                     return this.rawCaption.length > this.truncatedLength;
                 }
             }">
            <span class="caption-username font-semibold text-indigo-600 dark:text-gray-100">{{ $post->user->name }}</span>
            {{-- Apply formatCaption to the text determined by Alpine.js --}}
            <span class="caption-text ml-2" x-html="formatCaption(displayCaptionText)"></span>
            <template x-if="needsTruncation">
                <button @click="showFull = !showFull"
                        class="text-indigo-500 hover:underline dark:text-blue-400 dark:hover:text-blue-300 text-xs ml-1">
                    <span x-text="showFull ? 'less' : 'more'"></span>
                </button>
            </template>
        </div>

        <div class="comments-section mb-4" style="display: none;" data-post-id="{{ $post->id }}">
            <hr class="comment-divider border-t border-gray-200 dark:border-gray-600 mb-4">
            <div class="comment-list max-h-40 overflow-y-auto"></div>
            <button class="load-more-comments mt-2 text-sm text-indigo-600 hover:text-indigo-800" style="display: none;" data-post-id="{{ $post->id }}">Load more comments</button>
            <form class="comment-form mt-2 flex items-center space-x-2" data-post-id="{{ $post->id }}">
                <input type="text" name="body" class="post-comment-input flex-1 p-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 placeholder-gray-400" placeholder="Add a comment..." required>
                <button type="submit" class="post-comment-button p-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Post</button>
            </form>
        </div>
    @endauth
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        let offset = 0;
        const limit = 5;
        // Define the default profile image URL for use in JS
        const defaultProfileImage = "{{ asset('storage/profile_images/default-profile.png') }}";

        // Check if in theme preview mode
        const isThemePreview = !!document.getElementById('theme-preview');

        // Comment toggle for actual posts
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
                    offset = 0;
                    loadComments(postId, commentList, loadMoreButton, offset, limit);
                } else {
                    commentsSection.style.display = 'none';
                }
            });
        });

        // Comment toggle for theme preview
        if (isThemePreview) {
            document.querySelectorAll('#color-comment-button:not(.event-attached)').forEach(button => {
                button.classList.add('event-attached');
                button.addEventListener('click', function() {
                    const commentsSection = document.querySelector('#color-comments-section');
                    if (!commentsSection) return;

                    const commentList = commentsSection.querySelector('.comment-list');
                    if (!commentList) return;

                    const loadMoreButton = commentsSection.querySelector('.load-more-comments');

                    if (commentsSection.style.display === 'none' || commentsSection.style.display === '') {
                        commentsSection.style.display = 'block';
                        commentList.innerHTML = `
                            <div class="comment flex items-start space-x-2 mb-2">
                                <a href="#" class="flex-shrink-0">
                                    <img src="{{ asset('storage/profile_images/default-profile.png') }}"
                                         class="w-6 h-6 rounded-full object-cover"
                                         alt="Commenter's profile picture">
                                </a>
                                <div>
                                    <a href="#" class="comment-username font-semibold text-indigo-600 dark:text-gray-100" data-selector="#color-comment-username">Commenter</a>
                                    <p class="comment-text text-sm text-gray-700 dark:text-gray-300" data-selector="#color-comment-text">Great post!</p>
                                    <span class="comment-time text-xs text-gray-500" data-selector="#color-comment-time">1 hour ago</span>
                                </div>
                            </div>
                        `;
                        loadMoreButton.style.display = 'none';
                    } else {
                        commentsSection.style.display = 'none';
                    }
                });
            });
        }

        // Load more comments
        document.querySelectorAll('.load-more-comments:not(.event-attached)').forEach(button => {
            button.classList.add('event-attached');
            button.addEventListener('click', function() {
                const postId = this.getAttribute('data-post-id');
                const commentList = this.closest('.comments-section').querySelector('.comment-list');
                offset += limit;
                loadComments(postId, commentList, this, offset, limit);
            });
        });

        // Comment form submission
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


        // Like button for actual posts (Optimistic UI Update)
        document.querySelectorAll('.like-button:not(.event-attached)').forEach(button => {
            button.classList.add('event-attached');
            button.addEventListener('click', function() {
                const postId = this.getAttribute('data-post-id');
                const likeCountSpan = this.querySelector('.like-count');
                const initialCount = parseInt(likeCountSpan.textContent.split(' ')[0]);
                const wasLiked = this.classList.contains('liked');

                // Optimistically update UI
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
                            // If server error, revert UI
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
                      
                        const likeButtonContainer = likeCountSpan.closest('.flex.justify-start.space-x-6');
                        if (likeButtonContainer) {
                             const updateEvent = new CustomEvent('postLikedStateChanged', {
                                 detail: { postId: postId, likeCount: data.count, liked: data.liked }
                             });
                             document.dispatchEvent(updateEvent);
                         }

                    })
                    .catch(error => {
                        console.error('Error liking post:', error);
                        alert('Failed to update like. Please try again.');
                    });
            });
        });

        // Like button for theme preview
        if (isThemePreview) {
            document.querySelectorAll('#color-like-button:not(.event-attached)').forEach(button => {
                button.classList.add('event-attached');
                button.addEventListener('click', function() {
                    this.classList.toggle('liked');
                    const likeCount = this.querySelector('#color-like-count');
                    let count = parseInt(likeCount.textContent) || 0;
                    likeCount.textContent = `${this.classList.contains('liked') ? count + 1 : count - 1} Likes`;
                });
            });
        }

        function loadComments(postId, commentList, loadMoreButton, offset, limit) {
            fetch(`/posts/${postId}/comments?offset=${offset}&limit=${limit}`)
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

                    if (offset === 0) {
                        commentList.innerHTML = commentsHtml;
                    } else {
                        commentList.innerHTML += commentsHtml;
                    }

                    if (total > offset + limit) {
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
    });

    // Existing formatCaption function
    function formatCaption(caption) {
        let formatted = caption.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        formatted = formatted.replace(/#(\w+)/g, '<a href="/hashtag/$1" class="text-blue-500 hover:underline dark:text-blue-400">#$1</a>');
        formatted = formatted.replace(/@(\w+)/g, '<a href="/profiles/$1" class="text-blue-500 hover:underline dark:text-blue-400">@$1</a>');
        formatted = formatted.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:underline dark:text-blue-400">$1</a>');
        return formatted;
    }
</script>

<style>
    .like-button.liked .heart-icon {
        fill: #ff0000;
        stroke: #ff0000;
    }
    .caption-container {
        word-break: break-word;
        overflow-wrap: break-word;
    }
    .caption-container a {
        font-weight: 500;
    }
    .comment-list {
        max-height: 10rem;
        overflow-y: auto;
        padding-right: 0.5rem;
    }
    .comment-list::-webkit-scrollbar {
        width: 6px;
    }
    .comment-list::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }
    .comment-list::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>