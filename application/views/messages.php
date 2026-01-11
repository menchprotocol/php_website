<?php

//TITLE
$website_id = website_setting(0);
$user_session = user_session();

if(in_array($website_id, $this->config->item('userids___30984'))){
    echo ' <script> $(document).ready(function () { $(\'body\').addClass(\'homecss_black_font\'); }); </script> ';
} else {
    echo ' <script> $(document).ready(function () { $(\'body\').addClass(\'homecss_white_font\'); }); </script> ';
}

// Set page title
echo ' <script> $(document).ready(function () { $(document).prop(\'title\', \''.get_domain('m__name').' | Messages\'); }); </script> ';

?>

<style>
.messages-container {
    max-width: 935px;
    margin: 89px auto 50px;
    background: #fff;
    border: 1px solid #dbdbdb;
    border-radius: 4px;
    display: flex;
    height: calc(100vh - 200px);
    min-height: 600px;
    overflow: hidden;
}

.messages-conversation-list {
    width: 350px;
    border-right: 1px solid #dbdbdb;
    display: flex;
    flex-direction: column;
    background: #fff;
    overflow-y: auto;
}

.messages-header {
    padding: 20px;
    border-bottom: 1px solid #dbdbdb;
    font-weight: 600;
    font-size: 16px;
}

.messages-search {
    padding: 12px 20px;
    border-bottom: 1px solid #dbdbdb;
}

.messages-search input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #dbdbdb;
    border-radius: 8px;
    font-size: 14px;
    background: #fafafa;
}

.messages-search input:focus {
    outline: none;
    border-color: #8e8e8e;
    background: #fff;
}

.messages-conversation-item {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    border-bottom: 1px solid #fafafa;
    cursor: pointer;
    transition: background-color 0.2s;
}

.messages-conversation-item:hover {
    background-color: #fafafa;
}

.messages-conversation-item.active {
    background-color: #f0f0f0;
}

.messages-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: #dbdbdb;
    margin-right: 12px;
    flex-shrink: 0;
    overflow: hidden;
}

.messages-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.messages-conversation-info {
    flex: 1;
    min-width: 0;
}

.messages-conversation-name {
    font-weight: 600;
    font-size: 14px;
    color: #262626;
    margin-bottom: 4px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.messages-conversation-preview {
    font-size: 14px;
    color: #8e8e8e;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.messages-conversation-time {
    font-size: 12px;
    color: #8e8e8e;
    margin-top: 4px;
}

.messages-thread {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #fff;
    overflow: hidden;
}

.messages-thread-empty {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    color: #8e8e8e;
    font-size: 14px;
}

.messages-thread-empty-icon {
    width: 96px;
    height: 96px;
    border: 2px solid #262626;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
    font-size: 48px;
}

.messages-thread-header {
    padding: 16px 20px;
    border-bottom: 1px solid #dbdbdb;
    display: flex;
    align-items: center;
}

.messages-thread-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #dbdbdb;
    margin-right: 12px;
    overflow: hidden;
}

.messages-thread-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.messages-thread-name {
    font-weight: 600;
    font-size: 16px;
    color: #262626;
}

.messages-thread-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    display: flex;
    flex-direction: column;
}

.messages-message {
    display: flex;
    margin-bottom: 16px;
    align-items: flex-end;
}

.messages-message.sent {
    justify-content: flex-end;
}

.messages-message-bubble {
    max-width: 65%;
    padding: 12px 16px;
    border-radius: 18px;
    font-size: 14px;
    line-height: 1.4;
    word-wrap: break-word;
}

.messages-message.received .messages-message-bubble {
    background: #efefef;
    color: #262626;
    border-bottom-left-radius: 4px;
}

.messages-message.sent .messages-message-bubble {
    background: #0095f6;
    color: #fff;
    border-bottom-right-radius: 4px;
}

.messages-message-time {
    font-size: 11px;
    color: #8e8e8e;
    margin: 0 8px 4px;
}

.messages-thread-input {
    padding: 16px 20px;
    border-top: 1px solid #dbdbdb;
    display: flex;
    align-items: center;
}

.messages-thread-input input {
    flex: 1;
    padding: 12px 16px;
    border: 1px solid #dbdbdb;
    border-radius: 22px;
    font-size: 14px;
    background: #fafafa;
}

.messages-thread-input input:focus {
    outline: none;
    border-color: #8e8e8e;
    background: #fff;
}

.messages-send-button {
    margin-left: 12px;
    padding: 8px 16px;
    background: #0095f6;
    color: #fff;
    border: none;
    border-radius: 22px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.messages-send-button:hover {
    background: #1877f2;
}

.messages-send-button:disabled {
    background: #b2dffc;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .messages-container {
        margin: 89px 0 50px;
        height: calc(100vh - 150px);
        border-radius: 0;
        border-left: none;
        border-right: none;
    }
    
    .messages-conversation-list {
        width: 100%;
        display: none;
    }
    
    .messages-conversation-list.active {
        display: flex;
    }
    
    .messages-thread {
        width: 100%;
    }
    
    .messages-thread.active {
        display: flex;
    }
    
    .messages-thread:not(.active) {
        display: none;
    }
}
</style>

<div class="messages-container">
    <!-- Conversation List -->
    <div class="messages-conversation-list active" id="conversationList">
        <div class="messages-header">Messages</div>
        <div class="messages-search">
            <input type="text" placeholder="Search" id="messagesSearch">
        </div>
        <div class="messages-conversations" id="messagesConversations">
            <!-- Sample conversations -->
            <div class="messages-conversation-item" data-conversation-id="1">
                <div class="messages-avatar">
                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; font-size: 24px;">JD</div>
                </div>
                <div class="messages-conversation-info">
                    <div class="messages-conversation-name">John Doe</div>
                    <div class="messages-conversation-preview">Hey, how are you doing?</div>
                    <div class="messages-conversation-time">2m</div>
                </div>
            </div>
            <div class="messages-conversation-item" data-conversation-id="2">
                <div class="messages-avatar">
                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; font-size: 24px;">JS</div>
                </div>
                <div class="messages-conversation-info">
                    <div class="messages-conversation-name">Jane Smith</div>
                    <div class="messages-conversation-preview">Thanks for your help!</div>
                    <div class="messages-conversation-time">1h</div>
                </div>
            </div>
            <div class="messages-conversation-item" data-conversation-id="3">
                <div class="messages-avatar">
                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; font-size: 24px;">MW</div>
                </div>
                <div class="messages-conversation-info">
                    <div class="messages-conversation-name">Mike Wilson</div>
                    <div class="messages-conversation-preview">Are we still on for tomorrow?</div>
                    <div class="messages-conversation-time">3h</div>
                </div>
            </div>
            <div class="messages-conversation-item" data-conversation-id="4">
                <div class="messages-avatar">
                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; font-size: 24px;">SD</div>
                </div>
                <div class="messages-conversation-info">
                    <div class="messages-conversation-name">Sarah Davis</div>
                    <div class="messages-conversation-preview">Great to hear from you!</div>
                    <div class="messages-conversation-time">1d</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Message Thread -->
    <div class="messages-thread active" id="messageThread">
        <div class="messages-thread-empty" id="emptyThread">
            <div class="messages-thread-empty-icon">
                <i class="far fa-envelope"></i>
            </div>
            <div>Your messages</div>
            <div style="margin-top: 8px; font-size: 13px;">Send private messages to a friend.</div>
        </div>
        
        <div class="messages-thread-content" id="threadContent" style="display: none; flex: 1; flex-direction: column;">
            <div class="messages-thread-header">
                <div class="messages-thread-avatar">
                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; font-size: 14px;">JD</div>
                </div>
                <div class="messages-thread-name" id="threadName">John Doe</div>
            </div>
            <div class="messages-thread-messages" id="threadMessages">
                <!-- Messages will be populated here -->
            </div>
            <div class="messages-thread-input">
                <input type="text" placeholder="Message..." id="messageInput">
                <button class="messages-send-button" id="sendButton">Send</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const conversationItems = document.querySelectorAll('.messages-conversation-item');
    const emptyThread = document.getElementById('emptyThread');
    const threadContent = document.getElementById('threadContent');
    const threadMessages = document.getElementById('threadMessages');
    const threadName = document.getElementById('threadName');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const messagesSearch = document.getElementById('messagesSearch');
    
    // Sample messages data
    const messagesData = {
        1: [
            { text: 'Hey, how are you doing?', sent: false, time: '2:30 PM' },
            { text: 'I\'m doing great! Thanks for asking.', sent: true, time: '2:31 PM' },
            { text: 'That\'s awesome to hear!', sent: false, time: '2:32 PM' }
        ],
        2: [
            { text: 'Thanks for your help!', sent: false, time: '1:15 PM' },
            { text: 'No problem, happy to help!', sent: true, time: '1:16 PM' }
        ],
        3: [
            { text: 'Are we still on for tomorrow?', sent: false, time: '11:45 AM' }
        ],
        4: [
            { text: 'Great to hear from you!', sent: false, time: 'Yesterday' }
        ]
    };
    
    const conversationNames = {
        1: 'John Doe',
        2: 'Jane Smith',
        3: 'Mike Wilson',
        4: 'Sarah Davis'
    };
    
    // Handle conversation item click
    conversationItems.forEach(item => {
        item.addEventListener('click', function() {
            const conversationId = this.getAttribute('data-conversation-id');
            
            // Update active state
            conversationItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            // Show thread content
            emptyThread.style.display = 'none';
            threadContent.style.display = 'flex';
            
            // Update thread name
            threadName.textContent = conversationNames[conversationId];
            
            // Load messages
            loadMessages(conversationId);
        });
    });
    
    // Load messages for a conversation
    function loadMessages(conversationId) {
        threadMessages.innerHTML = '';
        const messages = messagesData[conversationId] || [];
        
        messages.forEach(msg => {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'messages-message ' + (msg.sent ? 'sent' : 'received');
            
            const bubble = document.createElement('div');
            bubble.className = 'messages-message-bubble';
            bubble.textContent = msg.text;
            
            const time = document.createElement('div');
            time.className = 'messages-message-time';
            time.textContent = msg.time;
            
            messageDiv.appendChild(bubble);
            messageDiv.appendChild(time);
            threadMessages.appendChild(messageDiv);
        });
        
        // Scroll to bottom
        threadMessages.scrollTop = threadMessages.scrollHeight;
    }
    
    // Handle send button click
    sendButton.addEventListener('click', function() {
        sendMessage();
    });
    
    // Handle enter key in input
    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
    
    // Send message function
    function sendMessage() {
        const text = messageInput.value.trim();
        if (!text) return;
        
        const activeConversation = document.querySelector('.messages-conversation-item.active');
        if (!activeConversation) return;
        
        const conversationId = activeConversation.getAttribute('data-conversation-id');
        
        // Create message element
        const messageDiv = document.createElement('div');
        messageDiv.className = 'messages-message sent';
        
        const bubble = document.createElement('div');
        bubble.className = 'messages-message-bubble';
        bubble.textContent = text;
        
        const now = new Date();
        const time = document.createElement('div');
        time.className = 'messages-message-time';
        time.textContent = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
        
        messageDiv.appendChild(bubble);
        messageDiv.appendChild(time);
        threadMessages.appendChild(messageDiv);
        
        // Clear input
        messageInput.value = '';
        
        // Scroll to bottom
        threadMessages.scrollTop = threadMessages.scrollHeight;
        
        // Update conversation preview
        const preview = activeConversation.querySelector('.messages-conversation-preview');
        preview.textContent = text;
        
        // Update time
        const timeEl = activeConversation.querySelector('.messages-conversation-time');
        timeEl.textContent = 'now';
    }
    
    // Handle search
    messagesSearch.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        conversationItems.forEach(item => {
            const name = item.querySelector('.messages-conversation-name').textContent.toLowerCase();
            const preview = item.querySelector('.messages-conversation-preview').textContent.toLowerCase();
            if (name.includes(searchTerm) || preview.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>
