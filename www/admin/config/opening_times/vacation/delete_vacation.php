<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && $_SESSION['user']['role'] === 'admin') {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("DELETE FROM vacation_periods WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: /admin/config/opening_times/vacation/index.php?delete=success");
exit;
