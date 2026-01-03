<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduHelperAgent</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .chat-container {
            width: 100%;
            max-width: 600px;
            height: 700px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            text-align: center;
            border-radius: 20px 20px 0 0;
        }

        .chat-header h2 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .chat-header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: #f8f9fa;
        }

        .chat-messages::-webkit-scrollbar {
            width: 8px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .message {
            margin-bottom: 15px;
            display: flex;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message.user {
            justify-content: flex-end;
        }

        .message-content {
            max-width: 70%;
            padding: 12px 18px;
            border-radius: 18px;
            word-wrap: break-word;
            line-height: 1.5;
        }

        .message.user .message-content {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom-right-radius: 4px;
        }

        .message.bot .message-content {
            background: white;
            color: #333;
            border-bottom-left-radius: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .chat-input-container {
            padding: 20px;
            background: white;
            border-top: 1px solid #e0e0e0;
        }

        .input-wrapper {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        #msg {
            flex: 1;
            padding: 14px 18px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            font-size: 15px;
            outline: none;
            transition: all 0.3s ease;
        }

        #msg:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        button {
            padding: 14px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        button:active {
            transform: translateY(0);
        }

        .suggestions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .suggestion-chip {
            padding: 8px 15px;
            background: #f0f0f0;
            border: 1px solid #e0e0e0;
            border-radius: 20px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .suggestion-chip:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .empty-state {
            text-align: center;
            color: #999;
            padding: 40px 20px;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .empty-state p {
            font-size: 16px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <h2>🤖 EduHelperAgent</h2>
            <p>Your friendly educational assistant</p>
        </div>

        <div class="chat-messages" id="chat">
            <div class="empty-state">
                <div class="empty-state-icon">👋</div>
                <p>Hello! I'm here to help you learn.</p>
                <p style="font-size: 14px; color: #aaa;">Try asking me about topics below!</p>
            </div>
        </div>

        <div class="chat-input-container">
            <div class="suggestions">
                <div class="suggestion-chip" onclick="fillMessage('Tell me about the Solar System')">🌍 Solar System</div>
                <div class="suggestion-chip" onclick="fillMessage('Explain fractions to me')">🔢 Fractions</div>
                <div class="suggestion-chip" onclick="fillMessage('How does the Water Cycle work?')">💧 Water Cycle</div>
            </div>
            <div class="input-wrapper">
                <input type="text" id="msg" placeholder="Type your question here..." onkeypress="handleKeyPress(event)">
                <button onclick="send()">Send</button>
            </div>
        </div>
    </div>

    <script>
        const chatContainer = document.getElementById('chat');
        const msgInput = document.getElementById('msg');

        function clearEmptyState() {
            const emptyState = chatContainer.querySelector('.empty-state');
            if (emptyState) {
                emptyState.remove();
            }
        }

        function addMessage(text, isUser) {
            clearEmptyState();
            
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user' : 'bot'}`;
            
            const contentDiv = document.createElement('div');
            contentDiv.className = 'message-content';
            contentDiv.textContent = text;
            
            messageDiv.appendChild(contentDiv);
            chatContainer.appendChild(messageDiv);
            
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        function send() {
            const message = msgInput.value.trim();
            if (!message) return;

            addMessage(message, true);
            msgInput.value = '';

            fetch('/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message: message })
            })
            .then(res => res.json())
            .then(data => {
                addMessage(data.reply, false);
            })
            .catch(error => {
                addMessage('Sorry, something went wrong. Please try again.', false);
                console.error('Error:', error);
            });
        }

        function fillMessage(text) {
            msgInput.value = text;
            msgInput.focus();
        }

        function handleKeyPress(event) {
            if (event.key === 'Enter') {
                send();
            }
        }
    </script>
</body>
</html>