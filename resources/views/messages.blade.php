<x-app-layout>
    <div class="min-h-screen py-12 bg-gray-50 dark:bg-gray-900" id="color-message-container">
        <div class="container mx-auto max-w-5xl">
            {{-- Main chat container with fixed height and no overflow --}}
            <div class="flex h-[80vh] rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700" id="color-chat-container">
                {{-- Left Pane: Followed Users / Chat List --}}
                {{-- Added 'md:w-1/3' and 'w-full' for mobile responsiveness --}}
                <div class="w-full md:w-1/3 flex flex-col border-r border-gray-200 dark:border-gray-300" id="chat-list-pane">
                    <div id="color-followed-users-header" class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-300 z-10 flex items-center p-4 min-h-[64px]">
                        <h2 id="color-followed-users-header" class="text-lg font-medium text-gray-900 dark:text-gray-100 m-0">Chats</h2>
                    </div>
                    {{-- Scrollable chat users list --}}
                    <div class="flex-1 overflow-y-auto p-2 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-indigo-400">
                        <div class="space-y-1">
                            {{-- Changed $followedUsers to $chatUsers --}}
                            @foreach ($chatUsers as $chatUser)
                                @php
                                    // Determine if the current chatUser corresponds to the active conversation
                                    $isActiveConversation = isset($conversation) && (
                                        ($conversation->user1_id === $chatUser->id && $conversation->user2_id === auth()->id()) ||
                                        ($conversation->user2_id === $chatUser->id && $conversation->user1_id === auth()->id())
                                    );
                                @endphp
                                <form action="{{ route('messages.show', $chatUser->conversation_id) }}" method="GET" class="chat-user-form">
                                    @csrf
                                    <button type="submit"
                                            class="flex items-center w-full p-3 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900 {{ $isActiveConversation ? 'bg-indigo-100 dark:bg-indigo-900' : '' }} transition-colors" id="color-chat-user-button">
                                        <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 mr-3 border-2 border-indigo-200 dark:border-indigo-500">
                                            <img src="{{ asset('storage/' . ($chatUser->profile->image ?? 'profile_images/default-profile.png')) }}"
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex-1 truncate">
                                            <div class="font-semibold text-gray-900 dark:text-white truncate">{{ $chatUser->name }}</div>
                                        </div>
                                    </button>
                                </form>
                            @endforeach
                            {{-- Updated empty state message --}}
                            @if ($chatUsers->isEmpty())
                                <div class="text-gray-500 dark:text-gray-400 p-3" id="color-no-users-message">You have no active chats.</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right Pane: Conversation Area --}}
                {{-- Added 'hidden' and 'md:flex' for mobile responsiveness --}}
                <div class="flex-1 flex flex-col h-full {{ isset($conversation) ? '' : 'hidden md:flex' }}" id="conversation-pane">
                    @if (isset($conversation))
                        {{-- Conversation Header (fixed at top) --}}
                        <div id="color-conversation-header" class="sticky top-0 bg-white dark:bg-gray-800 p-4 border-b border-gray-200 dark:border-gray-300 z-10 flex items-center min-h-[64px]">
                            {{-- Back button for mobile --}}
                            <button id="back-to-chats" class="md:hidden mr-3 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                            </button>
                            @php
                                $otherUser = $conversation->user1_id === auth()->id() ? $conversation->user2 : $conversation->user1;
                            @endphp
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0 border-2 border-indigo-200 dark:border-indigo-500">
                                    <img src="{{ asset('storage/' . ($otherUser->profile->image ?? 'profile_images/default-profile.png')) }}"
                                         class="w-full h-full object-cover">
                                </div>
                                <h3 id="color-conversation-header" class="text-lg font-medium text-gray-900 dark:text-white m-0">{{ $otherUser->name }}</h3>
                            </div>
                        </div>

                        {{-- Scrollable Message Area --}}
                        <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50 dark:bg-gray-900 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-indigo-400" id="color-message-area">
                            @foreach ($messages as $message)
                                <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}" data-message-id="{{ $message->id }}">
                                    <div class="flex items-start gap-2 max-w-[65%]">
                                        {{-- Profile image for incoming messages --}}
                                        @if ($message->user_id !== auth()->id())
                                            <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0 mt-2 border">
                                                <img src="{{ asset('storage/' . ($message->user->profile->image ?? 'profile_images/default-profile.png')) }}"
                                                     class="w-full h-full object-cover">
                                            </div>
                                        @endif
                                        <div class="message-bubble {{ $message->user_id === auth()->id() ? 'bg-indigo-100 dark:bg-indigo-800' : 'bg-white dark:bg-gray-700' }} p-3 rounded-lg shadow-sm relative" id="color-message-bubble">
                                            <span class="font-semibold text-gray-900 dark:text-white block mb-1" id="color-message-sender">{{ $message->user->name }}</span>
                                            <p class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap break-words pr-12" id="color-message-text">{{ $message->text }}</p>
                                            <div class="flex items-center justify-end absolute bottom-1 right-2 message-status-footer">
                                                <span class="text-xs text-gray-500 dark:text-gray-400 mr-1 leading-none message-timestamp" data-timestamp="{{ $message->created_at->toIso8601String() }}" id="color-message-time"></span>
                                                @if ($message->user_id === auth()->id())
                                                    <div class="message-checkmarks flex items-center">
                                                        @if ($message->status == 'read')
                                                            <svg class="w-3 h-3 text-indigo-500 -ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                                            <svg class="w-3 h-3 text-indigo-500 -ml-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                                        @elseif ($message->status == 'delivered')
                                                            <svg class="w-3 h-3 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                                        @else {{-- 'sent' --}}
                                                            <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        {{-- Profile image for outgoing messages (can add here if desired, but typically not in bubble) --}}
                                        @if ($message->user_id === auth()->id())
                                            {{-- If you want your own image on the right --}}
                                            <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0 mt-2 border">
                                                <img src="{{ asset('storage/' . (auth()->user()->profile->image ?? 'profile_images/default-profile.png')) }}"
                                                     class="w-full h-full object-cover">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Message Input Form (fixed at bottom) --}}
                        @auth
                            <form id="chat-form-{{ $conversation->id }}" class="flex items-center p-4 border-t color-chat-input-form border-gray-200 dark:border-gray-300 bg-white dark:bg-gray-800 shrink-0">
                                @csrf
                                <input type="text" name="chat_message" class="flex-1 p-3 rounded-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Type a message..." required id="color-message-input">
                                <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                                <button type="submit" class="ml-2 p-3 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500" id="color-send-button">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                </button>
                            </form>
                        @endauth
                    @else
                        {{-- No Conversation Selected State --}}
                        <div class="flex-1 flex items-center justify-center bg-gray-50 dark:bg-gray-900" id="color-no-conversation">
                            <div class="text-center p-6">
                                <svg class="w-16 h-16 mx-auto text-indigo-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="color-no-conversation-icon">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <p class="text-lg font-medium text-gray-500 dark:text-gray-400" id="color-no-conversation-text">Select a user to start messaging</p>
                            </div>
                        </div>
                    @endif

                    <div id="chat-error" class="text-red-500 dark:text-red-400 text-center p-2 hidden" id="color-error-message"></div>
                </div>
            </div>
        </div>
    </div>

    @auth
        @if (isset($conversation))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const authUserId = {{ auth()->id() }};
                    const conversationId = {{ $conversation->id }};
                    const defaultProfileImage = '{{ asset('storage/profile_images/default-profile.png') }}';
                    const authUserName = '{{ auth()->user()->name }}';
                    const authUserProfileImage = '{{ auth()->user()->profile->image ? Storage::url(auth()->user()->profile->image) : asset('storage/profile_images/default-profile.png') }}';

                    // DOM elements for panes
                    const chatListPane = document.getElementById('chat-list-pane');
                    const conversationPane = document.getElementById('conversation-pane');
                    const backToChatsButton = document.getElementById('back-to-chats');
                    const chatUserForms = document.querySelectorAll('.chat-user-form');


                    console.log(`User ID: ${authUserId}, Conversation ID: ${conversationId}`);
                    console.log('Echo setup started...');

                    const chatList = document.getElementById('color-message-area'); // Target the scrollable message area
                    if (chatList) {
                        chatList.scrollTop = chatList.scrollHeight; // Scroll to bottom on load
                    }

                    // --- Mobile View Toggling Logic ---
                    function showChatList() {
                        chatListPane.classList.remove('hidden');
                        chatListPane.classList.add('w-full'); // Take full width on mobile
                        conversationPane.classList.add('hidden');
                        conversationPane.classList.remove('md:flex'); // Hide on mobile
                    }

                    function showConversation() {
                        chatListPane.classList.add('hidden');
                        chatListPane.classList.remove('w-full');
                        conversationPane.classList.remove('hidden');
                        conversationPane.classList.add('flex'); // Ensure it's flex for vertical stacking
                        // Also, ensure it takes full width on mobile if it's currently hidden
                        if (window.innerWidth < 768) { // Tailwind's 'md' breakpoint is 768px
                             conversationPane.classList.add('w-full');
                        }
                    }

                    // Initial setup for mobile view
                    if (window.innerWidth < 768) {
                        if (conversationId) {
                            showConversation(); // If a conversation is loaded, show it immediately
                        } else {
                            showChatList(); // Otherwise, show the chat list
                        }
                    } else {
                        // On desktop, ensure both panes are visible
                        chatListPane.classList.remove('hidden', 'w-full');
                        chatListPane.classList.add('w-1/3');
                        conversationPane.classList.remove('hidden', 'w-full');
                        conversationPane.classList.add('flex');
                    }

                    // Event listener for the back button
                    if (backToChatsButton) {
                        backToChatsButton.addEventListener('click', (e) => {
                            e.preventDefault();
                            showChatList();
                            // Optionally, you might want to clear the conversation state or navigate back
                            // window.history.pushState(null, '', '/messages'); // Example for history manipulation
                        });
                    }

                    // Event listeners for chat user buttons to show conversation pane
                    chatUserForms.forEach(form => {
                        form.addEventListener('submit', (e) => {
                            // Let the form submission happen, but after it, if on mobile, swap panes
                            if (window.innerWidth < 768) {
                                // This assumes the page will reload with the new conversation.
                                // If using AJAX for loading conversations, you'd call showConversation() here.
                                // For now, since it's a full page load, the initial setup will handle it.
                            }
                        });
                    });


                    // --- Helper Function to Format Time (24-hour, local timezone) ---
                    function formatLocalTime(isoString) {
                        const date = new Date(isoString);
                        // Use options for 24-hour format (hourCycle: 'h23') and no seconds
                        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hourCycle: 'h23' });
                    }

                    // --- Helper Function to Render Checkmarks ---
                    function renderCheckmarks(isOwnMessage, status) {
                        if (!isOwnMessage) return ''; // Only show for own messages

                        let checkmarksHtml = '';

                        if (status === 'read') {
                            // Two blue checkmarks for read
                            checkmarksHtml += `
                                <svg class="w-3 h-3 text-indigo-500 -ml-0.5" fill="currentColor" viewBox="0 0 20 20" id="color-read-receipt"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                <svg class="w-3 h-3 text-indigo-500 -ml-1.5" fill="currentColor" viewBox="0 0 20 20" id="color-read-receipt"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                            `;
                        } else if (status === 'delivered') {
                            // One blue checkmark for delivered
                            checkmarksHtml += `
                                <svg class="w-3 h-3 text-indigo-500" fill="currentColor" viewBox="0 0 20 20" id="color-read-receipt"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                            `;
                        } else { // status === 'sent'
                            // One gray checkmark for sent
                            checkmarksHtml += `
                                <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20" id="color-read-receipt"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                            `;
                        }
                        return checkmarksHtml;
                    }

                    // --- WebSocket Listeners ---
                    try {
                        if (typeof Echo !== 'undefined') {
                            // Listener for new messages
                            Echo.private(`conversation.${conversationId}`)
                                .listen('MessageSent', (e) => {
                                    console.log('Received new message via Echo:', e);
                                    const isOwnMessage = e.user_id === authUserId;
                                    const profileImage = e.profile_image || defaultProfileImage;
                                    // Format the time using the new helper function
                                    const messageTimeFormatted = formatLocalTime(e.created_at);

                                    // Remove optimistic message if temp_id matches
                                    if (e.temp_id) {
                                        const existingTempMessage = chatList.querySelector(`[data-temp-id="${e.temp_id}"]`);
                                        if (existingTempMessage) {
                                            existingTempMessage.remove();
                                            console.log('Removed optimistic message with temp_id:', e.temp_id);
                                        }
                                    }

                                    // Append the real message
                                    const messageHtml = `
                                        <div class="flex ${isOwnMessage ? 'justify-end' : 'justify-start'}" data-message-id="${e.id}">
                                            <div class="flex items-start gap-2 max-w-[65%]">
                                                ${!isOwnMessage ? `<div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0 mt-2 border"><img src="${profileImage}" class="w-full h-full object-cover"></div>` : ''}
                                                <div class="message-bubble ${isOwnMessage ? 'bg-indigo-100 dark:bg-indigo-800' : 'bg-white dark:bg-gray-700'} p-3 rounded-lg shadow-sm relative" id="color-message-bubble">
                                                    <span class="font-semibold text-gray-900 dark:text-white block mb-1" id="color-message-sender">${e.name || 'Unknown'}</span>
                                                    <p class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap break-words pr-12" id="color-message-text">${e.text}</p>
                                                    <div class="flex items-center justify-end absolute bottom-1 right-2 message-status-footer">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400 mr-1 leading-none message-timestamp" data-timestamp="${e.created_at}" id="color-message-time">${messageTimeFormatted}</span>
                                                        <div class="message-checkmarks flex items-center">
                                                            ${renderCheckmarks(isOwnMessage, e.status)}
                                                        </div>
                                                    </div>
                                                </div>
                                                ${isOwnMessage ? `<div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0 mt-2 border"><img src="${authUserProfileImage}" class="w-full h-full object-cover"></div>` : ''}
                                            </div>
                                        </div>
                                    `;
                                    chatList.insertAdjacentHTML('beforeend', messageHtml);
                                    chatList.scrollTop = chatList.scrollHeight;

                                    // If this message is sent by the other user (i.e., we are the recipient),
                                    // mark it as delivered AND then as read immediately.
                                    if (!isOwnMessage) {
                                        markMessageAsDelivered(e.id).then(() => {
                                            markMessagesAsRead([e.id]);
                                        }).catch(error => {
                                            console.error('Error marking message as delivered and then read:', error);
                                        });
                                    }
                                });

                            // Listener for message status updates (delivered, read)
                            Echo.private(`conversation.${conversationId}`)
                                .listen('MessageStatusUpdated', (e) => {
                                    console.log('Received message status update:', e);
                                    const messageElement = chatList.querySelector(`[data-message-id="${e.message_id}"]`);
                                    if (messageElement) {
                                        const isOwnMessage = messageElement.classList.contains('justify-end');
                                        const checkmarksContainer = messageElement.querySelector('.message-checkmarks');
                                        if (checkmarksContainer) {
                                            checkmarksContainer.innerHTML = renderCheckmarks(isOwnMessage, e.status);
                                        }
                                    }
                                });

                            console.log(`Listening on private conversation.${conversationId}`);
                        } else {
                            console.warn('Echo is not defined. Real-time messaging will not work.');
                            showError('Real-time messaging is unavailable. Please refresh the page.', 'color-error-message');
                        }
                    } catch (error) {
                        console.error('Echo setup failed:', error);
                        showError('Failed to connect to chat server for real-time updates.', 'color-error-message');
                    }

                    // --- Form Submission Handler ---
                    const chatForm = document.getElementById(`chat-form-${conversationId}`);
                    if (chatForm) {
                        chatForm.addEventListener('submit', async (e) => {
                            e.preventDefault();
                            const messageInput = chatForm.querySelector('input[name="chat_message"]');
                            const text = messageInput.value.trim();

                            if (!text) {
                                showError('Message cannot be empty.', 'color-error-message');
                                return;
                            }

                            const tempMessageId = `temp-${Date.now()}-${Math.random().toString(36).substring(2, 9)}`;
                            const currentTimeIso = new Date().toISOString(); // Use ISO string for optimistic time

                            // Optimistic UI update: Add message with single gray checkmark immediately
                            const tempMessageHtml = `
                                <div class="flex justify-end" data-temp-id="${tempMessageId}">
                                    <div class="flex items-start gap-2 max-w-[65%]">
                                        <div class="message-bubble bg-indigo-100 dark:bg-indigo-800 p-3 rounded-lg shadow-sm opacity-70 relative" id="color-message-bubble">
                                            <span class="font-semibold text-gray-900 dark:text-white block mb-1" id="color-message-sender">${authUserName}</span>
                                            <p class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap break-words pr-12" id="color-message-text">${text}</p>
                                            <div class="flex items-center justify-end absolute bottom-1 right-2 message-status-footer">
                                                <span class="text-xs text-gray-500 dark:text-gray-400 mr-1 leading-none message-timestamp" data-timestamp="${currentTimeIso}" id="color-message-time">${formatLocalTime(currentTimeIso)}</span>
                                                <div class="message-checkmarks flex items-center">
                                                    ${renderCheckmarks(true, 'sent')}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0 mt-2 border">
                                            <img src="${authUserProfileImage}" class="w-full h-full object-cover">
                                        </div>
                                    </div>
                                </div>
                            `;
                            chatList.insertAdjacentHTML('beforeend', tempMessageHtml);
                            chatList.scrollTop = chatList.scrollHeight;
                            messageInput.value = ''; // Clear input immediately
                            hideError('color-error-message');

                            try {
                                const response = await fetch('/messages/send', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                    },
                                    body: JSON.stringify({
                                        text: text,
                                        conversation_id: conversationId,
                                        temp_id: tempMessageId
                                    }),
                                });

                                if (!response.ok) {
                                    const errorData = await response.json().catch(() => ({}));
                                    throw new Error(errorData.error || `HTTP error! Status: ${response.status}`);
                                }

                                const data = await response.json();
                                if (!data.success) {
                                    throw new Error(data.error || 'Failed to send message.');
                                }
                                // Success: The MessageSent broadcast will handle the final message update
                            } catch (error) {
                                console.error('Error sending message:', error);
                                showError('Failed to send message: ' + error.message, 'color-error-message');
                                // Revert optimistic update on failure
                                const tempMessageElement = chatList.querySelector(`[data-temp-id="${tempMessageId}"]`);
                                if (tempMessageElement) {
                                    tempMessageElement.remove();
                                }
                                addFailedMessage(text, authUserProfileImage);
                            }
                        });
                    } else {
                        console.error('Chat form not found. Check ID `chat-form-{{ $conversation->id }}`');
                        showError('Chat input is not available.', 'color-error-message');
                    }

                    // --- Error and UI Helper Functions ---
                    function showError(message, id) {
                        const errorDiv = document.getElementById(id);
                        if (errorDiv) {
                            errorDiv.textContent = message;
                            errorDiv.classList.remove('hidden');
                            setTimeout(() => {
                                errorDiv.classList.add('hidden');
                            }, 5000);
                        }
                    }

                    function hideError(id) {
                        const errorDiv = document.getElementById(id);
                        if (errorDiv) {
                            errorDiv.textContent = '';
                            errorDiv.classList.add('hidden');
                        }
                    }

                    function addFailedMessage(text, profileImage) {
                        const failedMessageHtml = `
                            <div class="flex justify-end">
                                <div class="flex items-start gap-2 max-w-[65%]">
                                    <div class="message-bubble bg-red-100 dark:bg-red-800 p-3 rounded-lg shadow-sm" id="color-message-bubble">
                                        <span class="font-semibold text-red-900 dark:text-red-100 block mb-1" id="color-message-sender">Failed to send</span>
                                        <p class="text-red-800 dark:text-red-200 whitespace-pre-wrap break-words pr-12" id="color-message-text">${text}</p>
                                        <div class="flex items-center justify-end absolute bottom-1 right-2 message-status-footer">
                                            <span class="text-xs text-red-500 dark:text-red-400 mr-1 leading-none" id="color-message-time">${formatLocalTime(new Date().toISOString())}</span>
                                        </div>
                                    </div>
                                    <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0 mt-2 border">
                                        <img src="${profileImage}" class="w-full h-full object-cover">
                                    </div>
                                </div>
                            </div>
                        `;
                        chatList.insertAdjacentHTML('beforeend', failedMessageHtml);
                        chatList.scrollTop = chatList.scrollHeight;
                    }

                    // --- API Calls for Status Updates ---
                    async function markMessageAsDelivered(messageId) {
                        try {
                            const response = await fetch(`/messages/${messageId}/delivered`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({}),
                            });
                            const data = await response.json();
                            if (!data.success) {
                                console.error('Failed to mark message as delivered:', data.error);
                                return false;
                            } else {
                                console.log(`Message ${messageId} marked as delivered.`);
                                return true;
                            }
                        } catch (error) {
                            console.error('Error marking message as delivered:', error);
                            return false;
                        }
                    }

                    async function markMessagesAsRead(messageIds) {
                        if (messageIds.length === 0) return;
                        try {
                            const response = await fetch(`/conversations/${conversationId}/mark-messages-as-read`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({ message_ids: messageIds }),
                            });
                            const data = await response.json();
                            if (!data.success) {
                                console.error('Failed to mark messages as read:', data.error);
                            } else {
                                console.log('Messages marked as read:', data.count, 'messages updated.');
                            }
                        } catch (error) {
                            console.error('Error marking messages as read:', error);
                        }
                    }

                    // Initial call to mark all currently visible messages as read when conversation loads
                    async function initialMarkConversationAsRead() {
                        try {
                            const response = await fetch(`/conversations/${conversationId}/mark-as-read`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({}),
                            });
                            const data = await response.json();
                            if (!data.success) {
                                console.error('Failed to mark conversation as read on load:', data.error);
                            } else {
                                console.log('Initial conversation mark as read:', data.count, 'messages updated.');
                            }
                        } catch (error) {
                            console.error('Error initial marking conversation as read:', error);
                        }
                    }

                    // Mark conversation as read when loaded
                    if (conversationId) {
                        initialMarkConversationAsRead();
                    }

                    // Populate timestamps for already loaded messages
                    chatList.querySelectorAll('.message-timestamp').forEach(span => {
                        const isoString = span.getAttribute('data-timestamp');
                        if (isoString) {
                            span.textContent = formatLocalTime(isoString);
                        }
                    });
                });
            </script>
        @endif
    @endauth

    <style>
        /* Scrollbar Styling */
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .dark .scrollbar-thin::-webkit-scrollbar-track {
            background: #1e293b;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #c7d2fe;
            border-radius: 3px;
        }
        .dark .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #4f46e5;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #818cf8;
        }
        .dark .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #6366f1;
        }

        /* Ensure main chat container does not scroll, only its children */
        #color-chat-container {
            overflow: hidden; /* Prevent scrolling of the whole chat box */
            height: 80vh; /* Fixed height for the chat box */
            display: flex; /* Ensure its children (left/right panels) flex correctly */
        }

        /* The message display area needs to be the one that scrolls */
        #color-message-area {
            flex-grow: 1; /* Allows it to take up available vertical space */
            overflow-y: auto; /* This is the only part that scrolls vertically */
            display: flex;
            flex-direction: column; /* Ensure messages stack top-to-bottom */
            justify-content: flex-end; /* Stick content to bottom */
            padding-bottom: 4rem; /* Add padding to prevent last message from being hidden by input */
        }

        /* Message Input Form should shrink and stay at the bottom */
        #color-chat-input-form {
            flex-shrink: 0; /* Prevents it from shrinking */
            width: 100%; /* Ensure it spans full width */
            /* Add any specific positioning if `position: sticky` or `fixed` is preferred for extreme cases,
               but with flex-col on parent, `shrink-0` usually suffices. */
        }

        /* Text Formatting */
        .whitespace-pre-wrap {
            white-space: pre-wrap;
        }
        .break-words {
            word-break: break-word;
        }

        /* New WhatsApp-like checkmark positioning and sizing */
        .message-status-footer {
            padding-left: 0.5rem;
            bottom: 0.25rem;
            right: 0.5rem;
            white-space: nowrap;
            font-size: 0.75rem;
            color: #6b7280;
        }

        .dark .message-status-footer span {
            color: #9ca3af;
        }

        .message-checkmarks {
            display: flex;
            align-items: center;
            line-height: 1;
            margin-left: 0.25rem;
            min-width: 14px;
        }

        .message-checkmarks svg {
            width: 0.75rem;
            height: 0.75rem;
        }

        .message-checkmarks svg:nth-child(2) {
            margin-left: -0.25rem;
        }

        #color-message-bubble p {
            padding-right: 3rem;
            min-height: 1.25rem;
        }

        /* For the `color-message-container` on the `x-app-layout` level */
        /* This ID was originally on the message scroll area, but your theme config
           has it on the `py-6 bg-gray-50` div. I've adjusted the HTML structure
           to match this, making the outer-most `div` the target for this ID. */

        /* Mobile specific adjustments */
        @media (max-width: 767px) { /* This corresponds to Tailwind's 'md' breakpoint */
            #color-chat-container {
                height: 90vh; /* Make the chat box taller on small screens */
            }

            #chat-list-pane.w-full {
                width: 100%; /* Ensure it takes full width when active */
                flex-shrink: 0; /* Prevent shrinking */
            }

            #conversation-pane.flex {
                width: 100%; /* Ensure it takes full width when active */
                flex-shrink: 0; /* Prevent shrinking */
            }

            /* When a conversation is active, hide the left pane */
            #chat-list-pane.hidden-on-mobile {
                display: none;
            }

            /* When no conversation is active, hide the right pane */
            #conversation-pane.hidden-on-mobile {
                display: none;
            }
        }
    </style>
</x-app-layout>