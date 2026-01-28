<?php
// GEEN CACHE
if (!headers_sent()) {
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: 0');
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// Connectie checken
if ($conn->connect_error) {
    die("Connectie mislukt: " . $conn->connect_error);
}

$pages_dir = $_SERVER['DOCUMENT_ROOT'] . '/pages/';

// Prepared statements
$stmt_select = $conn->prepare("SELECT id FROM pages WHERE slug = ?");
$stmt_insert = $conn->prepare("INSERT INTO pages (title, slug, content, visible) VALUES (?, ?, ?, ?)");
$stmt_update = $conn->prepare("UPDATE pages SET title = ?, content = ?, visible = ? WHERE slug = ?");

if (!$stmt_select || !$stmt_insert || !$stmt_update) {
    die("Fout bij voorbereiden statements: " . $conn->error);
}

function extractTitle($html) {
    if (preg_match('/<title>(.*?)<\/title>/is', $html, $matches)) {
        return trim($matches[1]);
    }
    return null;
}

$do_write = true;

echo "<h2>Preview van pagina's binnen submappen die toegevoegd of geüpdatet worden:</h2>";

// Lees submappen van /pages/
$dirs = array_filter(glob($pages_dir . '*'), 'is_dir');

foreach ($dirs as $dir) {
    $index_file = $dir . '/index.php';

    if (file_exists($index_file)) {
        $slug = basename($dir); // Naam van submap als slug

        $content = file_get_contents($index_file);
        if ($content === false) {
            echo "Kon bestand $index_file niet lezen.<br>";
            continue;
        }

        $title = extractTitle($content);
        if (!$title) {
            $title = str_replace(['-', '_'], ' ', ucfirst($slug));
        }

        $visible = 1;

        $stmt_select->bind_param("s", $slug);
        $stmt_select->execute();
        $stmt_select->store_result();

        if ($stmt_select->num_rows > 0) {
            if ($do_write) {
                $stmt_update->bind_param("siss", $title, $content, $visible, $slug);
                if ($stmt_update->execute()) {
                    echo "Geüpdatet: '$title' (slug: $slug)<br>";
                } else {
                    echo "Fout bij updaten '$title': " . $stmt_update->error . "<br>";
                }
            } else {
                echo "[Preview] Zou geüpdatet worden: '$title' (slug: $slug)<br>";
            }
        } else {
            if ($do_write) {
                $stmt_insert->bind_param("sssi", $title, $slug, $content, $visible);
                if ($stmt_insert->execute()) {
                    echo "Toegevoegd: '$title' (slug: $slug)<br>";
                } else {
                    echo "Fout bij toevoegen '$title': " . $stmt_insert->error . "<br>";
                }
            } else {
                echo "[Preview] Zou toegevoegd worden: '$title' (slug: $slug)<br>";
            }
        }
    }
}

$stmt_select->close();
$stmt_insert->close();
$stmt_update->close();
$conn->close();
