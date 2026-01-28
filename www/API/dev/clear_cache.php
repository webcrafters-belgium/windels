<?php
header('Content-Type: application/json');

// Browser-cache uitschakelen
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

echo json_encode([
    "success" => true,
    "message" => "Browser instructed to bypass cache"
]);
exit;
