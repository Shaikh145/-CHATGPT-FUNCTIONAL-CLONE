<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatGPT Clone</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #343541;
            color: white;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: #202123;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: #19c37d;
            font-size: 24px;
            font-weight: bold;
        }

        .nav-buttons a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            padding: 8px 15px;
            border-radius: 5px;
            background: #40414f;
        }

        .chat-container {
            flex: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .chat-box {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            margin-bottom: 20px;
        }

        .message {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
            max-width: 80%;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .user-message {
            background: #40414f;
            margin-left: auto;
        }

        .ai-message {
            background: #444654;
        }

        .input-area {
            display: flex;
            gap: 10px;
            padding: 20px;
            background: #40414f;
            border-radius: 10px;
            position: sticky;
            bottom: 0;
        }

        input[type="text"] {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background: #40414f;
            color: white;
            font-size: 16px;
            outline: 1px solid #565869;
        }

        button {
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            background: #19c37d;
            color: white;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        button:hover {
            background: #15a66c;
            transform: scale(1.05);
        }

        .typing-indicator {
            padding: 15px;
            background: #444654;
            border-radius: 10px;
            display: none;
            margin-bottom: 10px;
        }

        .dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: #fff;
            border-radius: 50%;
            margin-right: 4px;
            animation: bounce 1.4s infinite;
        }

        .dot:nth-child(2) { animation-delay: 0.2s; }
        .dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes bounce {
            0%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">ChatGPT Clone</div>
        <div class="nav-buttons">
            <a href="index.php">Home</a>
            <a href="history.php">History</a>
        </div>
    </div>
    <div class="chat-container">
        <div class="chat-box" id="chatBox"></div>
        <div class="typing-indicator" id="typingIndicator">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
        <div class="input-area">
            <input type="text" id="userInput" placeholder="Type your message here...">
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>

    <script>
        const chatBox = document.getElementById('chatBox');
        const userInput = document.getElementById('userInput');
        const typingIndicator = document.getElementById('typingIndicator');

        function addMessage(message, isUser) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user-message' : 'ai-message'}`;
            messageDiv.textContent = message;
            chatBox.appendChild(messageDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function showTypingIndicator() {
            typingIndicator.style.display = 'block';
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function hideTypingIndicator() {
            typingIndicator.style.display = 'none';
        }

        async function sendMessage() {
            const message = userInput.value.trim();
            if (!message) return;

            addMessage(message, true);
            userInput.value = '';
            showTypingIndicator();

            try {
                const response = await fetch('chat.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `message=${encodeURIComponent(message)}`
                });

                const data = await response.json();
                setTimeout(() => {
                    hideTypingIndicator();
                    addMessage(data.response, false);
                }, 1000);
            } catch (error) {
                console.error('Error:', error);
                hideTypingIndicator();
                addMessage('Sorry, something went wrong.', false);
            }
        }

        userInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    </script>
</body>
</html> 
