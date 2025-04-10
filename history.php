<?php
session_start();

$host = 'localhost';
$dbname = 'dbeapc9efu29hp';
$username = 'uegmsyle2bt8u';
$password = 'vigivpdbybdx';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

$session_id = session_id();
$stmt = $pdo->prepare("SELECT * FROM chat_messages WHERE session_id = ? ORDER BY timestamp ASC");
$stmt->execute([$session_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat History - ChatGPT Clone</title>
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
            min-height: 100vh;
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

        .history-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        .message {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
            max-width: 80%;
        }

        .user-message {
            background: #40414f;
            margin-left: auto;
        }

        .ai-message {
            background: #444654;
        }

        .timestamp {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }

        .no-messages {
            text-align: center;
            padding: 40px;
            font-size: 18px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">Chat History</div>
        <div class="nav-buttons">
            <a href="index.php">Back to Chat</a>
        </div>
    </div>
    
    <div class="history-container">
        <?php if (empty($messages)): ?>
            <div class="no-messages">No chat history found.</div>
        <?php else: ?>
            <?php foreach ($messages as $message): ?>
                <div class="message <?php echo $message['is_user'] ? 'user-message' : 'ai-message'; ?>">
                    <?php echo htmlspecialchars($message['message']); ?>
                    <div class="timestamp">
                        <?php echo date('M d, Y H:i:s', strtotime($message['timestamp'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html> 
