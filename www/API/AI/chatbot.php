<?php
global $openAiKey;
header('Content-Type: application/json');

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// Limiet op aantal berichten per IP-adres per dag
$max_messages = 20;
$user_ip = $_SERVER['REMOTE_ADDR'];
$message_date = date('Y-m-d');

// ✅ **Controleer het aantal berichten dat vandaag is verzonden**
$messages_today = get_message_count($conn, $user_ip, $message_date);

if ($messages_today >= $max_messages) {
    echo json_encode(["success" => false, "reply" => "Je hebt het maximum aantal berichten bereikt voor vandaag."]);
    exit;
}

// ✅ **Controleer of een bericht is ontvangen**
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['message']) || empty(trim($data['message']))) {
    echo json_encode(["success" => false, "reply" => "Geen geldig bericht ontvangen."]);
    exit;
}

$message = htmlspecialchars($data['message'], ENT_QUOTES, 'UTF-8');

// ✅ **Verhoog het aantal berichten in de database**
increment_message_count($conn, $user_ip, $message_date);

// ✅ **Laad de bedrijfsinformatie uit het JSON-bestand**
$json_file = $_SERVER['DOCUMENT_ROOT'] . "/API/AI/trainingdata/windels_data.json";
$company_data = file_exists($json_file) ? file_get_contents($json_file) : "Geen bedrijfsinformatie gevonden.";

// ✅ **OpenAI API-aanvraag**
$openai_url = "https://api.openai.com/v1/chat/completions";
$payload = [
    "model" => "gpt-4o-mini",
    "messages" => [
        ["role" => "system", "content" => "Je bent een AI-assistent voor Windels Green & Deco Resin. Gebruik de volgende bedrijfsinformatie om vragen te beantwoorden:\n\n$company_data"],
        ["role" => "user", "content" => $message]
    ]
];

$response = openai_api_call($openai_url, $payload);
$responseData = json_decode($response, true);

// ✅ **Log API-response voor debugging**
error_log("🔹 OpenAI API Response: " . json_encode($responseData, JSON_PRETTY_PRINT));

// ✅ **Verwerk het antwoord van OpenAI**
if (isset($responseData['choices'][0]['message']['content'])) {
    echo json_encode(["success" => true, "reply" => $responseData['choices'][0]['message']['content']]);
} else {
    echo json_encode(["success" => false, "reply" => "❌ Geen geldig antwoord van OpenAI."]);
}

// ✅ **Functie om berichten per dag te tellen**
function get_message_count($conn, $user_ip, $message_date) {
    $stmt = $conn->prepare("SELECT message_count FROM chat_message_logs WHERE user_ip = ? AND message_date = ?");
    $stmt->bind_param("ss", $user_ip, $message_date);
    $stmt->execute();
    $stmt->bind_result($message_count);
    $stmt->fetch();
    $stmt->close();
    return $message_count ?: 0;
}

// ✅ **Functie om het aantal berichten te verhogen**
function increment_message_count($conn, $user_ip, $message_date) {
    $stmt = $conn->prepare("
        INSERT INTO chat_message_logs (user_ip, message_date, message_count) 
        VALUES (?, ?, 1) 
        ON DUPLICATE KEY UPDATE message_count = message_count + 1
    ");
    $stmt->bind_param("ss", $user_ip, $message_date);
    $stmt->execute();
    $stmt->close();
}

// ✅ **Functie om OpenAI API-aanvragen te doen**
function openai_api_call($url, $payload) {
    global $openAiKey;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $openAiKey"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}