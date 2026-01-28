<?php
require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Verwijder de melding en alle gerelateerde gegevens
    $sql = "DELETE FROM meldingen WHERE id = $id";

    if ($pdo_voedselproblemen->query($sql) === TRUE) {
        header("Location: view.php?success=1");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $pdo_voedselproblemen->error;
    }
} else {
    header("Location: view.php");
    exit();
}
?>
