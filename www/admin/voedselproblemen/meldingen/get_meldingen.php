<?php
require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';

$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

$sql = "SELECT * FROM meldingen WHERE 
        dossiernummer LIKE :search OR 
        naam_klant LIKE :search OR 
        email_klant LIKE :search OR 
        productnaam LIKE :search OR 
        probleem LIKE :search";

$stmt = $pdo_voedselproblemen->prepare($sql);
$searchParam = "%$search%";
$stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
$stmt->execute();

$meldingen = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $meldingen[] = $row;
}

echo json_encode($meldingen);
?>
