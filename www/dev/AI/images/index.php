<?php
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

$baseUrl = 'https://windelsgreen-decoresin.com';
$enhancedFolder = '/images/products-enhanced/';
$batchSize = 5; // hoeveel tegelijk bewerken

echo '
<div id="progress" class="text-center mb-4 fw-bold">Nog niet gestart...</div>
';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected'])) {
    $selected = array_map('intval', $_POST['selected']);
    $total = count($selected);
    $updated = 0;
    $errors = 0;

    $chunks = array_chunk($selected, $batchSize);

    foreach ($chunks as $batch) {
        foreach ($batch as $id) {
            $result = $conn->query("SELECT image_url FROM product_images WHERE id = $id");
            if (!$result || !$row = $result->fetch_assoc()) {
                echo "<div style='color:red;'>Fout: Geen afbeelding gevonden voor ID $id</div>";
                $errors++;
                continue;
            }

            $imageUrl = $row['image_url'];
            $path = $_SERVER['DOCUMENT_ROOT'] . parse_url($imageUrl, PHP_URL_PATH);

            if (!file_exists($path)) {
                echo "<div style='color:red;'>Fout: Bestand niet gevonden voor ID $id</div>";
                $errors++;
                continue;
            }

            $imageData = file_get_contents($path);
            $mimeType = mime_content_type($path);
            $filename = basename($path);

            $boundary = uniqid();
            $delimiter = '-------------' . $boundary;

            $post_data = build_multipart_data(
                array(
                    "image" => array(
                        "name" => $filename,
                        "type" => $mimeType,
                        "content" => $imageData
                    )
                ),
                [
                    "n" => 1,
                    "size" => "1024x1024",
                    "response_format" => "url"
                ],
                $delimiter
            );

            $ch = curl_init('https://api.openai.com/v1/images/variations');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer " . $openAiKey,
                "Content-Type: multipart/form-data; boundary=$delimiter"
            ]);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                echo "<div style='color:red;'>CURL Fout bij ID $id: " . curl_error($ch) . "</div>";
                $errors++;
                curl_close($ch);
                continue;
            }
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $data = json_decode($response, true);

            if ($httpCode !== 200 || !isset($data['data'][0]['url'])) {
                echo "<div style='color:red;'>OpenAI Fout bij ID $id:<br>HTTP-code: $httpCode<br>Response:<pre>";
                print_r($data);
                echo "</pre></div>";
                $errors++;
                continue;
            }

            $newImageContent = file_get_contents($data['data'][0]['url']);
            $newPath = $_SERVER['DOCUMENT_ROOT'] . str_replace('/images/products/', $enhancedFolder, parse_url($imageUrl, PHP_URL_PATH));

            if (!is_dir(dirname($newPath))) {
                mkdir(dirname($newPath), 0777, true);
            }

            file_put_contents($newPath, $newImageContent);

            $newUrl = $baseUrl . str_replace('/images/products/', $enhancedFolder, parse_url($imageUrl, PHP_URL_PATH));

            $stmt = $conn->prepare("UPDATE product_images SET image_url = ?, is_edited = 1 WHERE id = ?");
            $stmt->bind_param("si", $newUrl, $id);
            $stmt->execute();
            $stmt->close();

            $updated++;

            // Update voortgang live
            echo "<script>document.getElementById('progress').innerText = 'Bewerkt: $updated / $total';</script>";
            ob_flush();
            flush();
        }

        usleep(500000); // 0.5 seconde tussen batches
    }

    echo "<div class='alert alert-success mt-4'>✅ Klaar! $updated successen, $errors fouten.</div>";
}

// Functie voor multipart-form-data
function build_multipart_data($files, $fields, $boundary)
{
    $data = '';

    foreach ($fields as $name => $content) {
        $data .= "--$boundary\r\n";
        $data .= "Content-Disposition: form-data; name=\"$name\"\r\n\r\n";
        $data .= "$content\r\n";
    }

    foreach ($files as $name => $file) {
        $data .= "--$boundary\r\n";
        $data .= "Content-Disposition: form-data; name=\"$name\"; filename=\"" . $file['name'] . "\"\r\n";
        $data .= "Content-Type: " . $file['type'] . "\r\n\r\n";
        $data .= $file['content'] . "\r\n";
    }

    $data .= "--$boundary--\r\n";

    return $data;
}
?>
<?php
$result = $conn->query("SELECT id, image_url FROM product_images WHERE is_edited = 0 ORDER BY id ASC");
$images = [];
while ($row = $result->fetch_assoc()) {
    $images[] = $row;
}
?>

<form method="post">
    <div class="d-flex justify-content-center mb-4">
        <button type="button" id="selectAll" class="btn btn-primary me-2">Alles selecteren</button>
        <button type="submit" class="btn btn-success">Bewerk geselecteerde</button>
    </div>

    <div class="gallery d-flex flex-wrap gap-3">
        <?php foreach ($images as $img): ?>
            <div class="gallery-item" style="width:180px; text-align:center;">
                <label>
                    <input type="checkbox" name="selected[]" value="<?= htmlspecialchars($img['id']) ?>">
                    <img src="<?= htmlspecialchars($img['image_url']) ?>" alt="" style="width:100%; height:auto; border-radius:8px; box-shadow:0 0 6px rgba(0,0,0,0.2);">
                </label>
            </div>
        <?php endforeach; ?>
    </div>
</form>

<script>
    document.getElementById('selectAll').onclick = () => {
        const checkboxes = document.querySelectorAll('.gallery-item input[type="checkbox"]');
        const allSelected = Array.from(checkboxes).every(cb => cb.checked);
        checkboxes.forEach(cb => cb.checked = !allSelected);
    };
</script>
