<?php
require$_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

header('Content-Type: application/json');


$sql = "SELECT role, message FROM chat_history WHERE user_ip = ? ORDER BY timestamp DESC LIMIT 20";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $user_ip);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
