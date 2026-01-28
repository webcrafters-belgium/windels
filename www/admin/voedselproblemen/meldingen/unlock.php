<?php
require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "UPDATE meldingen SET locked_by = NULL WHERE id = $id";
    if ($pdo_voedselproblemen->query($sql) === TRUE) {
        echo "Unlock successful";
    } else {
        echo "Error: " . $pdo_voedselproblemen->error;
    }
} else {
    echo "Invalid request";
}
?>

