<?php
session_start();
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    header("Location: /pages/account/login");
    exit();
}

include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $content = $_POST['content'] ?? '';

    // Basale validatie
    if ($title === '' || $slug === '') {
        die("Titel en slug zijn verplicht.");
    }

    // Slug check: alleen letters, cijfers, koppeltekens
    if (!preg_match('/^[a-z0-9\-]+$/', $slug)) {
        die("Slug mag alleen kleine letters, cijfers en koppeltekens bevatten.");
    }

    // Check of slug al bestaat
    $stmt = $conn->prepare("SELECT id FROM pages WHERE slug = ?");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        die("Deze slug bestaat al, kies een andere.");
    }
    $stmt->close();

    // Invoegen
    $stmt = $conn->prepare("INSERT INTO pages (title, slug, content, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("sss", $title, $slug, $content);
    if ($stmt->execute()) {
        header("Location: /admin/add_pages/index.php?success=1");
        exit();
    } else {
        die("Fout bij opslaan: " . $stmt->error);
    }
}
?>
