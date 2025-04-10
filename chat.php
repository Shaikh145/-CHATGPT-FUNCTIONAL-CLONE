<?php
session_start();
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'dbeapc9efu29hp';
$username = 'uegmsyle2bt8u';
$password = 'vigivpdbybdx';
$GEMINI_API_KEY = 'AIzaSyCS1xSEgDXOrtJuB4F1InEQOlP0nywNB3o';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die(json_encode(['error' => 'Connection failed: ' . $e->getMessage()]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'] ?? '';
    $session_id = session_id();
    
    // Save user message
    $stmt = $pdo->prepare("INSERT INTO chat_messages (session_id, message, is_user) VALUES (?, ?, 1)");
    $stmt->execute([$session_id, $message]);
    
    // Get response from Gemini API
    $response = getGeminiResponse($message, $GEMINI_API_KEY);
    
    // Save AI response
    $stmt = $pdo->prepare("INSERT INTO chat_messages (session_id, message, is_user) VALUES (?, ?, 0)");
    $stmt->execute([$session_id, $response]);
    
    echo json_encode(['response' => $response]);
}

function getGeminiResponse($message, $apiKey) {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;
    
    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $message]
                ]
            ]
        ]
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        return "Sorry, I couldn't process your request at the moment.";
    }
    
    curl_close($ch);
    
    $responseData = json_decode($response, true);
    
    // Extract the response text from Gemini API response
    if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
        return $responseData['candidates'][0]['content']['parts'][0]['text'];
    }
    
    // Fallback response if API fails
    return "I apologize, but I couldn't generate a proper response at the moment.";
}
?> 
