<?php
require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';


if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Controleer of $id een geldig getal is om SQL-injectie te voorkomen
    if (filter_var($id, FILTER_VALIDATE_INT)) {
        // Voorbereide statement om SQL-injecties te voorkomen
        $stmt = $pdo_voedselproblemen->prepare("DELETE FROM terugroepacties WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: view_recall.php?success=1");
            exit();
        } else {
            // Fout afhandelen en loggen voor debuggen
            error_log("Error executing query: " . $stmt->error);
            echo "Er is een fout opgetreden. Probeer het later opnieuw.";
        }

        $stmt->close();
    } else {
        // Ongeldige ID, terug naar de overzichtspagina
        header("Location: view_recall.php?error=invalid_id");
        exit();
    }
} else {
    header("Location: view_recall.php");
    exit();
}

$pdo_voedselproblemen->close();
?>
