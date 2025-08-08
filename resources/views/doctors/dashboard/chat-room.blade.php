@extends('layouts.doctors_dashboard')

@section('title', 'Chat dengan Admin')

@section('header', 'Chat dengan Admin')
@section('subheader', 'Chat dengan Admin jika ada suatu hal yang mendesak dan diperlukan.')

@section('content')
<div class="space-y-6">
    <!-- Chat Room Header -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $chatRoom->subject }}</h3>
                        <div class="flex items-center space-x-2 mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $chatRoom->statusColor }}">
                                {{ $chatRoom->statusText }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $chatRoom->priorityColor }}">
                                {{ $chatRoom->priorityText }}
                            </span>
                            @if($chatRoom->admin)
                                <span class="text-sm text-gray-500">
                                    Admin: {{ $chatRoom->admin->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button onclick="refreshMessages()" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh
                    </button>
                    <form action="{{ route('doctors.dashboard.chat.close', $chatRoom->room_id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menutup chat ini?')" class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Tutup Chat
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Messages -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <!-- Messages Container -->
            <div id="chatMessages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-2 text-sm text-gray-500">Memuat pesan...</p>
                </div>
            </div>

            <!-- Typing Indicator -->
            <div id="typingIndicator" class="hidden px-4 py-2 bg-gray-100 border-t">
                <div class="flex items-center space-x-2">
                    <div class="flex space-x-1">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    </div>
                    <span id="typingText" class="text-sm text-gray-600">Admin sedang mengetik...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Input -->
    <div class="border-t bg-white p-4">
        <form id="messageForm" class="flex space-x-2">
            @csrf
            <div class="flex-1">
                <textarea 
                    id="messageInput" 
                    name="message" 
                    rows="2" 
                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 resize-none"
                    placeholder="Ketik pesan Anda..."
                    onkeydown="handleKeyDown(event)"
                    oninput="handleTyping(event)"
                ></textarea>
            </div>
            <button 
                type="submit" 
                id="sendButton"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const chatRoomId = '{{ $chatRoom->room_id }}';
    let lastMessageId = 0;
    let isTyping = false;
    let typingTimer = null;
    let statusCheckTimer = null;
    let typingStatusTimer = null;

    // Get CSRF token from meta tag
    function getCSRFToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
               document.querySelector('input[name="_token"]')?.value ||
               '{{ csrf_token() }}';
    }

    // Load messages on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Chat room page loaded, room ID:', chatRoomId);
        loadMessages();
        
        // Start typing status polling
        startTypingStatusPolling();
        
        // Start status checking for doctor messages
        startStatusChecking();
    });

    // Form submission
    document.getElementById('messageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        sendMessage();
    });

    // Handle Enter key in textarea
    document.getElementById('messageInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    function loadMessages() {
        console.log('Loading messages for room:', chatRoomId);
        
        fetch(`/dokter/dashboard/chat/${chatRoomId}/messages`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCSRFToken()
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Messages data:', data);
            if (data.success === true) {
                displayMessages(data.messages);
                updateTypingIndicator(data.typing_status);
            } else {
                console.error('API returned success: false:', data);
                displayError('Gagal memuat pesan: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error loading messages:', error);
            displayError('Gagal memuat pesan: ' + error.message);
        });
    }

    function displayMessages(messages) {
        const messagesContainer = document.getElementById('chatMessages');
        
        console.log('Displaying messages:', messages);
        
        if (!messages || messages.length === 0) {
            messagesContainer.innerHTML = `
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pesan</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai percakapan dengan admin.</p>
                </div>
            `;
            return;
        }

        let messagesHTML = '';
        messages.forEach((message, index) => {
            console.log(`Processing message ${index}:`, message);
            
            const isFromDoctor = message.sender_type === 'doctor';
            const isSystem = message.sender_type === 'system';
            
            if (isSystem) {
                messagesHTML += `
                    <div class="flex justify-center">
                        <div class="bg-gray-100 rounded-lg px-3 py-2 max-w-xs">
                            <p class="text-xs text-gray-600 text-center">${message.message}</p>
                        </div>
                    </div>
                `;
            } else {
                messagesHTML += `
                    <div class="flex ${isFromDoctor ? 'justify-end' : 'justify-start'}">
                        <div class="max-w-xs lg:max-w-md">
                            <div class="bg-${isFromDoctor ? 'blue' : 'gray'}-100 rounded-lg px-4 py-2">
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="text-xs font-medium text-${isFromDoctor ? 'blue' : 'gray'}-800">
                                        ${message.sender_name || 'Unknown'}
                                    </span>
                                    <span class="text-xs text-${isFromDoctor ? 'blue' : 'gray'}-600">
                                        ${message.formatted_time || 'Unknown time'}
                                    </span>
                                </div>
                                <p class="text-sm text-${isFromDoctor ? 'blue' : 'gray'}-900">${message.message}</p>
                                ${isFromDoctor ? `
                                    <div class="flex items-center justify-end mt-1">
                                        <span class="text-xs text-gray-500 mr-1">${message.status_text}</span>
                                        <span class="message-status-icon" data-message-id="${message.id}">${message.status_icon}</span>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }
        });

        messagesContainer.innerHTML = messagesHTML;
        
        // Scroll to bottom
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
        
        console.log('Messages displayed successfully');
    }

    function displayError(message) {
        const messagesContainer = document.getElementById('chatMessages');
        messagesContainer.innerHTML = `
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Error</h3>
                <p class="mt-1 text-sm text-gray-500">${message}</p>
                <div class="mt-4">
                    <button onclick="loadMessages()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Coba Lagi
                    </button>
                </div>
            </div>
        `;
    }

    function handleTyping(event) {
        const messageInput = event.target;
        const sendButton = document.getElementById('sendButton');
        
        // Enable/disable send button based on input
        if (messageInput.value.trim()) {
            sendButton.disabled = false;
        } else {
            sendButton.disabled = true;
        }
        
        // Start typing indicator
        if (!isTyping) {
            isTyping = true;
            startTyping();
        }
        
        // Reset typing timer
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            isTyping = false;
            stopTyping();
        }, 2000); // Stop typing indicator after 2 seconds of no input
    }

    function handleKeyDown(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            sendMessage();
        }
    }

    function sendMessage() {
        const messageInput = document.getElementById('messageInput');
        const message = messageInput.value.trim();
        
        if (!message) return;
        
        // Disable input and button
        messageInput.disabled = true;
        const sendButton = document.getElementById('sendButton');
        sendButton.disabled = true;
        
        // Stop typing indicator
        stopTyping();
        clearTimeout(typingTimer);
        isTyping = false;
        
        fetch(`/dokter/dashboard/chat/${chatRoomId}/message`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCSRFToken()
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageInput.value = '';
                loadMessages(); // Reload messages to show new message
                
                // Mark message as delivered after a delay
                setTimeout(() => {
                    if (data.message_id) {
                        markMessageAsDelivered(data.message_id);
                    }
                }, 1000);
            } else {
                console.error('Failed to send message:', data.message);
                alert('Gagal mengirim pesan: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
            alert('Gagal mengirim pesan: ' + error.message);
        })
        .finally(() => {
            // Re-enable input and button
            messageInput.disabled = false;
            sendButton.disabled = false;
            messageInput.focus();
        });
    }

    function refreshMessages() {
        loadMessages();
    }

    function updateTypingIndicator(typingStatus) {
        const typingIndicator = document.getElementById('typingIndicator');
        const typingText = document.getElementById('typingText');
        
        if (typingStatus && typingStatus.is_typing) {
            typingIndicator.classList.remove('hidden');
            typingText.textContent = typingStatus.typing_text || 'Admin sedang mengetik...';
        } else {
            typingIndicator.classList.add('hidden');
        }
    }

    function startTypingStatusPolling() {
        typingStatusTimer = setInterval(() => {
            fetch(`/dokter/dashboard/chat/${chatRoomId}/typing/status`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCSRFToken()
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateTypingIndicator(data);
                }
            })
            .catch(error => {
                console.error('Error checking typing status:', error);
            });
        }, 2000); // Check every 2 seconds
    }

    function startStatusChecking() {
        statusCheckTimer = setInterval(() => {
            // Check for doctor messages that need status updates
            const doctorMessages = document.querySelectorAll('.message-status-icon[data-message-id]');
            doctorMessages.forEach(icon => {
                const messageId = icon.getAttribute('data-message-id');
                const currentStatus = icon.innerHTML;
                
                // If status is still 'sent', try to mark as delivered
                if (currentStatus.includes('text-gray-400')) {
                    markMessageAsDelivered(messageId);
                }
            });
        }, 3000); // Check every 3 seconds
    }

    function markMessageAsDelivered(messageId) {
        fetch(`/dokter/dashboard/chat/message/${messageId}/mark-delivered`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCSRFToken()
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the status icon
                const icon = document.querySelector(`[data-message-id="${messageId}"]`);
                if (icon) {
                    icon.innerHTML = '<svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
                }
            }
        })
        .catch(error => {
            console.error('Error marking message as delivered:', error);
        });
    }

    function startTyping() {
        fetch(`/dokter/dashboard/chat/${chatRoomId}/typing/start`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCSRFToken()
            }
        })
        .catch(error => {
            console.error('Error starting typing indicator:', error);
        });
    }

    function stopTyping() {
        fetch(`/dokter/dashboard/chat/${chatRoomId}/typing/stop`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCSRFToken()
            }
        })
        .catch(error => {
            console.error('Error stopping typing indicator:', error);
        });
    }

    // Cleanup timers when page unloads
    window.addEventListener('beforeunload', function() {
        if (typingTimer) clearTimeout(typingTimer);
        if (statusCheckTimer) clearInterval(statusCheckTimer);
        if (typingStatusTimer) clearInterval(typingStatusTimer);
        stopTyping();
    });
</script>
@endpush 