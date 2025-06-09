// resources/js/follow.js
(function() {
    window.alert = function() {};
    window.confirm = function() { return true; };
    window.prompt = function() { return null; };
})();

document.addEventListener('DOMContentLoaded', function () {
    // Only run on profile pages (e.g., /profiles/*)

    // DOM elements
    const followersModal = document.getElementById('followers-modal');
    const followingModal = document.getElementById('following-modal');
    const showFollowersBtn = document.getElementById('show-followers-modal');
    const closeFollowersBtn = document.getElementById('close-followers');
    const showFollowingBtn = document.getElementById('show-following-modal');
    const closeFollowingBtn = document.getElementById('close-following');
    const mainFollowButton = document.getElementById('follow-button');
    const followerCountSpan = document.getElementById('follower-count');
    const followingCountSpan = document.getElementById('following-count');
    const followersListContainer = document.getElementById('followers-list-container');
    const followingListContainer = document.getElementById('following-list-container');
    const profileUserIdElement = document.getElementById('profile-user-id');
    const authUserIdElement = document.getElementById('auth-user-id');

    // Check for critical elements
    if (!profileUserIdElement || !authUserIdElement) {
        console.warn('Profile or auth user ID element not found. Follow functionality disabled.');
        return;
    }

    const mainProfileUserId = profileUserIdElement.value;
    const authUserId = authUserIdElement.value;

    // Function to create a user list item for modals
    function createUserListItem(user, authFollowingIds) {
        const listItem = document.createElement('div');
        listItem.id = `modal-user-${user.id}`;
        listItem.className = 'flex items-center justify-between py-2 sm:py-3 px-2 hover:bg-gray-100 dark:hover:bg-blue-700 rounded-lg';

        let buttonHtml = '';
        const isAuthFollowingThisUser = authFollowingIds.includes(user.id);

        if (String(authUserId) !== String(user.id)) {
            buttonHtml = `
                <button id="modal-follow-button-${user.id}" data-user-id="${user.id}"
                        data-is-following="${isAuthFollowingThisUser}"
                        class="${isAuthFollowingThisUser ? 'bg-gray-200 text-gray-900 hover:bg-gray-300' : 'bg-blue-600 text-white hover:bg-blue-700'} px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-medium">
                    ${isAuthFollowingThisUser ? 'Following' : 'Follow'}
                </button>
            `;
        }

        const profileImage = user.profile_image ? `/storage/${user.profile_image}` : '/storage/profile_images/default-profile.png';

        listItem.innerHTML = `
            <div class="flex items-center space-x-2 sm:space-x-3">
                <img src="${profileImage}" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover border border-gray-100 dark:border-blue-600">
                <a href="/profiles/${user.id}" class="text-sm sm:text-base text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                    ${user.username}
                </a>
            </div>
            ${buttonHtml}
        `;
        return listItem;
    }

    // Function to fetch and display followers
    async function fetchAndDisplayFollowers(userId = mainProfileUserId) {
        if (!followersListContainer) return;
        followersListContainer.innerHTML = '<p class="text-gray-600 dark:text-blue-400 text-sm sm:text-base text-center py-4" id="loading-followers-message">Loading followers...</p>';
        try {
            const response = await fetch(`/profiles/${userId}/followers-list`);
            if (!response.ok) {
                console.error('Failed to fetch followers:', response.status, response.statusText);
                followersListContainer.innerHTML = '<p class="text-red-500 text-sm sm:text-base text-center py-4">Failed to load followers. Please try again.</p>';
                return;
            }
            const data = await response.json();
            followersListContainer.innerHTML = '';

            if (data.followers.length > 0) {
                data.followers.forEach(follower => {
                    followersListContainer.appendChild(createUserListItem(follower, data.auth_following_ids));
                });
            } else {
                followersListContainer.innerHTML = '<p class="text-gray-600 dark:text-blue-400 text-sm sm:text-base" id="no-followers-message">No followers yet.</p>';
            }
        } catch (error) {
            console.error('Error fetching followers:', error);
            followersListContainer.innerHTML = '<p class="text-red-500 text-sm sm:text-base text-center py-4">Failed to load followers. Please try again.</p>';
        }
    }

    // Function to fetch and display following
    async function fetchAndDisplayFollowing(userId = mainProfileUserId) {
        if (!followingListContainer) return;
        followingListContainer.innerHTML = '<p class="text-gray-600 dark:text-blue-400 text-sm sm:text-base text-center py-4" id="loading-following-message">Loading following...</p>';
        try {
            const response = await fetch(`/profiles/${userId}/following-list`);
            if (!response.ok) {
                console.error('Failed to fetch following:', response.status, response.statusText);
                followingListContainer.innerHTML = '<p class="text-red-500 text-sm sm:text-base text-center py-4">Failed to load following. Please try again.</p>';
                return;
            }
            const data = await response.json();
            followingListContainer.innerHTML = '';

            if (data.following.length > 0) {
                data.following.forEach(followed => {
                    followingListContainer.appendChild(createUserListItem(followed, data.auth_following_ids));
                });
            } else {
                followingListContainer.innerHTML = '<p class="text-gray-600 dark:text-blue-400 text-sm sm:text-base" id="no-following-message">Not following anyone yet.</p>';
            }
        } catch (error) {
            console.error('Error fetching following:', error);
            followingListContainer.innerHTML = '<p class="text-red-500 text-sm sm:text-base text-center py-4">Failed to load following. Please try again.</p>';
        }
    }

    // Show/Hide Followers Modal
    if (showFollowersBtn && followersModal) {
        showFollowersBtn.addEventListener('click', () => {
            followersModal.classList.remove('hidden');
            fetchAndDisplayFollowers(mainProfileUserId);
        });
    }
    if (closeFollowersBtn && followersModal) {
        closeFollowersBtn.addEventListener('click', () => {
            followersModal.classList.add('hidden');
        });
    }

    // Show/Hide Following Modal
    if (showFollowingBtn && followingModal) {
        showFollowingBtn.addEventListener('click', () => {
            followingModal.classList.remove('hidden');
            fetchAndDisplayFollowing(mainProfileUserId);
        });
    }
    if (closeFollowingBtn && followingModal) {
        closeFollowingBtn.addEventListener('click', () => {
            followingModal.classList.add('hidden');
        });
    }

    // Close modals when clicking outside
    window.addEventListener('click', (event) => {
        if (event.target === followersModal) {
            followersModal.classList.add('hidden');
        }
        if (event.target === followingModal) {
            followingModal.classList.add('hidden');
        }
    });

    // Function to handle follow/unfollow logic
    async function handleFollowToggle(button) {
        const userIdToFollow = button.dataset.userId;
        const isCurrentlyFollowing = button.dataset.isFollowing === 'true';

        // Disable button to prevent duplicate clicks
        button.disabled = true;

        let requestMethod = isCurrentlyFollowing ? 'DELETE' : 'POST';
        let requestUrl = isCurrentlyFollowing ? `/users/unfollow/${userIdToFollow}` : `/users/follow/${userIdToFollow}`;

        try {
         

            const response = await fetch(requestUrl, {
                method: requestMethod,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                })
            });

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({ message: 'Unknown error' }));
                console.error(`Follow action failed: ${response.status} ${response.statusText}`, errorData);
                if (response.status === 400) {
                    // Re-fetch lists to sync with server state
                    if (followersModal && !followersModal.classList.contains('hidden')) {
                        fetchAndDisplayFollowers(mainProfileUserId);
                    }
                    if (followingModal && !followingModal.classList.contains('hidden')) {
                        fetchAndDisplayFollowing(mainProfileUserId);
                    }
                }
                button.disabled = false;
                return;
            }

            const data = await response.json();
         

            if (data.status === 'followed') {
                button.textContent = 'Following';
                button.classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-700');
                button.classList.add('bg-gray-200', 'text-gray-900', 'hover:bg-gray-300');
                button.dataset.isFollowing = 'true';

                // Update counts
                if (String(userIdToFollow) === String(mainProfileUserId) && followerCountSpan) {
                    followerCountSpan.textContent = parseInt(followerCountSpan.textContent || 0) + 1;
                }
                if (String(authUserId) === String(mainProfileUserId) && followingCountSpan) {
                    followingCountSpan.textContent = parseInt(followingCountSpan.textContent || 0) + 1;
                }

                // Sync other buttons
                document.querySelectorAll(`[data-user-id="${userIdToFollow}"][id^="modal-follow-button-"], #follow-button[data-user-id="${userIdToFollow}"]`).forEach(btn => {
                    if (btn !== button) {
                        btn.textContent = 'Following';
                        btn.classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-700');
                        btn.classList.add('bg-gray-200', 'text-gray-900', 'hover:bg-gray-300');
                        btn.dataset.isFollowing = 'true';
                    }
                });
            } else if (data.status === 'unfollowed') {
                button.textContent = 'Follow';
                button.classList.remove('bg-gray-200', 'text-gray-900', 'hover:bg-gray-300');
                button.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700');
                button.dataset.isFollowing = 'false';

                // Update counts
                if (String(userIdToFollow) === String(mainProfileUserId) && followerCountSpan) {
                    followerCountSpan.textContent = Math.max(parseInt(followerCountSpan.textContent || 0) - 1, 0);
                }
                if (String(authUserId) === String(mainProfileUserId) && followingCountSpan) {
                    followingCountSpan.textContent = Math.max(parseInt(followingCountSpan.textContent || 0) - 1, 0);
                }

                // Sync other buttons
                document.querySelectorAll(`[data-user-id="${userIdToFollow}"][id^="modal-follow-button-"], #follow-button[data-user-id="${userIdToFollow}"]`).forEach(btn => {
                    if (btn !== button) {
                        btn.textContent = 'Follow';
                        btn.classList.remove('bg-gray-200', 'text-gray-900', 'hover:bg-gray-300');
                        btn.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700');
                        btn.dataset.isFollowing = 'false';
                    }
                });

                // Remove from Following modal
                if (followingModal && !followingModal.classList.contains('hidden') && followingListContainer) {
                    const listItemToRemove = document.getElementById(`modal-user-${userIdToFollow}`);
                    if (listItemToRemove) {
                        listItemToRemove.remove();
                        if (followingListContainer.children.length === 0) {
                            followingListContainer.innerHTML = '<p class="text-gray-600 dark:text-blue-400 text-sm sm:text-base" id="no-following-message">Not following anyone yet.</p>';
                        }
                    }
                }

                // Remove from Followers modal if unfollowing profile owner
                if (String(userIdToFollow) === String(mainProfileUserId) && followersModal && !followersModal.classList.contains('hidden') && followersListContainer) {
                    const listItemToRemoveFromFollowers = document.getElementById(`modal-user-${authUserId}`);
                    if (listItemToRemoveFromFollowers) {
                        listItemToRemoveFromFollowers.remove();
                        if (followersListContainer.children.length === 0) {
                            followersListContainer.innerHTML = '<p class="text-gray-600 dark:text-blue-400 text-sm sm:text-base" id="no-followers-message">No followers yet.</p>';
                        }
                    }
                }
            }

            button.disabled = false;
        } catch (error) {
            console.error('Error in handleFollowToggle:', error);
            button.disabled = false;
        }
    }

    // Attach event listener to the main follow button
    if (mainFollowButton) {
        mainFollowButton.addEventListener('click', () => handleFollowToggle(mainFollowButton));
    }

    // Event delegation for modal follow buttons
    let handleModalClick = function(event) {
        if (event.target && event.target.id.startsWith('modal-follow-button-')) {
            handleFollowToggle(event.target);
        }
    };
    document.body.removeEventListener('click', handleModalClick); // Prevent duplicate listeners
    document.body.addEventListener('click', handleModalClick);

    // Expose functions globally
    window.handleFollowToggle = handleFollowToggle;
    window.fetchAndDisplayFollowers = fetchAndDisplayFollowers;
    window.fetchAndDisplayFollowing = fetchAndDisplayFollowing;
});