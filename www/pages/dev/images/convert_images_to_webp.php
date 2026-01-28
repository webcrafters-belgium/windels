<?php
set_time_limit(0);
session_start();

$baseDir = $_SERVER['DOCUMENT_ROOT'] . '/images/products/';
$limit = 20;
$converted = 0;

// 1. Verzamel alle afbeeldingspaden (eenmalig)
if (!isset($_SESSION['image_conversion_list'])) {
    function collectImages($dir) {
        $list = [];
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                $list = array_merge($list, collectImages($path));
            } else {
                $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $list[] = $path;
                }
            }
        }
        return $list;
    }

    $_SESSION['image_conversion_list'] = collectImages($baseDir);
    $_SESSION['image_conversion_index'] = 0;
}

// 2. Verwerk volgende batch
$imageList = $_SESSION['image_conversion_list'];
$index = $_SESSION['image_conversion_index'];
$total = count($imageList);

function convertToWebp($filepath) {
    $info = @getimagesize($filepath);
    $mime = $info['mime'] ?? null;
    $filename = pathinfo($filepath, PATHINFO_FILENAME);
    $dir = dirname($filepath);
    $newPath = $dir . '/' . $filename . '.webp';

    if (file_exists($newPath)) return false;

    switch ($mime) {
        case 'image/jpeg': $image = @imagecreatefromjpeg($filepath); break;
        case 'image/png':  $image = @imagecreatefrompng($filepath);  break;
        case 'image/gif':  $image = @imagecreatefromgif($filepath);  break;
        default: return false;
    }

    if (!$image) return false;

    $success = imagewebp($image, $newPath, 80);
    imagedestroy($image);

    return $success;
}

// 3. Batch run
$batchEnd = min($index + $limit, $total);
for ($i = $index; $i < $batchEnd; $i++) {
    if (convertToWebp($imageList[$i])) $converted++;
}
$_SESSION['image_conversion_index'] = $batchEnd;

// 4. Afronden
$progress = round(($batchEnd / $total) * 100);
$done = $batchEnd >= $total;

if ($done) {
    unset($_SESSION['image_conversion_list']);
    unset($_SESSION['image_conversion_index']);
}

// 5. JSON-response
header('Content-Type: application/json');
echo json_encode([
    'converted' => $converted,
    'progress' => $progress,
    'next' => !$done
]);
