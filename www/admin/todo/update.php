<?php
declare(strict_types=1);

session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

header('Content-Type: application/json');

// ✅ Alleen admin
if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Geen toegang']);
    exit;
}

$todoFile = $_SERVER['DOCUMENT_ROOT'] . '/todo.php';

// Hulpje: update regel in todo.php
function updateTodoStatus(string $title, string $newStatus, string $todoFile): bool {
    $content = file_get_contents($todoFile);
    if ($content === false) return false;

    $pattern = "/('title'\\s*=>\\s*'".preg_quote($title, "/")."',[^\\]]*'status'\\s*=>\\s*')[^']+/";
    $replacement = "$1$newStatus";
    $newContent = preg_replace($pattern, $replacement, $content);

    if ($newContent === null) return false;

    return file_put_contents($todoFile, $newContent) !== false;
}

try {
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);

    $title = $data['title'] ?? '';
    $status = $data['status'] ?? 'open';

    if ($title === '' || !in_array($status, ['open', 'done'], true)) {
        throw new InvalidArgumentException("Ongeldige parameters");
    }

    if (!updateTodoStatus($title, $status, $todoFile)) {
        throw new RuntimeException("Kon todo.php niet bijwerken");
    }

    echo json_encode([
        'success' => true,
        'message' => "Taak '$title' gemarkeerd als $status"
    ]);
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
