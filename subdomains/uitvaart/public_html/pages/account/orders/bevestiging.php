<?php
session_start();
$order_id = $_GET['order_id'] ?? null;
if (!$order_id) {
    header("Location: /pages/account/dashboard.php");
    exit;
}
$order_number = "ORD-" . date('Y') . "-" . str_pad($order_id, 4, '0', STR_PAD_LEFT);
?>

<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/header.php'; ?>
<style>
         body {
    background: url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;
    background-size: cover;
     }
     .bevestiging-container {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 3rem 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    margin: 3rem auto 2rem auto;
}
</style>
<main class="bevestiging-container">
    <h2>Bestelling succesvol geplaatst</h2>
    <p>Uw bestelling is geregistreerd met ordernummer:</p>
    <h3><?= htmlspecialchars($order_number) ?></h3>
    <p>De klantgegevens zijn alleen zichtbaar voor uw uitvaartdienst en worden niet gedeeld met ons.</p>

    <div style="margin: 2rem 0;">
        <a href="/pages/account/orders/pdf/pakbon_<?= $order_id ?>.pdf" class="btn" target="_blank" download>
            📄 Pakbon downloaden
        </a>
    </div>

    <a href="/pages/account/dashboard.php" class="btn">Terug naar dashboard</a>
</main>
<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/footer.php'; ?>
