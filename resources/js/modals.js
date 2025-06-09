// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Followers Modal
    const followersBtn = document.getElementById('followers-btn');
    const followersModal = document.getElementById('followers-modal');
    const closeFollowers = document.getElementById('close-followers');

    // Only add event listeners if elements exist
    if (followersBtn && followersModal) {
        followersBtn.addEventListener('click', () => {
            followersModal.classList.remove('hidden');
        });
        
        if (closeFollowers) {
            closeFollowers.addEventListener('click', () => {
                followersModal.classList.add('hidden');
            });
        }
    }

    // Following Modal
    const followingBtn = document.getElementById('following-btn');
    const followingModal = document.getElementById('following-modal');
    const closeFollowing = document.getElementById('close-following');

    // Only add event listeners if elements exist
    if (followingBtn && followingModal) {
        followingBtn.addEventListener('click', () => {
            followingModal.classList.remove('hidden');
        });
        
        if (closeFollowing) {
            closeFollowing.addEventListener('click', () => {
                followingModal.classList.add('hidden');
            });
        }
    }

    // Close modals when clicking outside
    window.addEventListener('click', (e) => {
        // Only process if the modals exist
        if (followersModal && e.target === followersModal) {
            followersModal.classList.add('hidden');
        }
        if (followingModal && e.target === followingModal) {
            followingModal.classList.add('hidden');
        }
    });
});
