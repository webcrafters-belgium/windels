<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

// Controleer of de gebruiker is ingelogd en rol heeft
if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = 'admin';
}

$admin_role = $_SESSION['role'];
$current_date = date('Y-m-d');
$category = 'schaplabel';

// Fetch all buttons for the user's role
$stmt = $conn->prepare("
    SELECT b.* 
    FROM buttons b 
    JOIN button_roles br 
        ON b.id = br.button_id 
    WHERE 
        (br.role = ? OR br.role = 'All') 
        AND b.visible = 1 
        AND (b.display_date IS NULL OR b.display_date <= ?) 
        AND b.category = ? 
    ORDER BY b.position ASC
");

$stmt->execute([$admin_role, $current_date, $category]);

$result = $stmt->get_result();
$buttons = $result->fetch_all(MYSQLI_ASSOC);
?>

<div class="container mt-5">
    <div class="card shadow-lg p-4 main-content">
        <!-- Knoppenweergave op basis van rol -->
        <?php if (empty($buttons)): ?>
            <div class="alert alert-warning text-center">
                <strong>Momenteel zijn er geen opties beschikbaar voor uw rol.</strong>
                <p>Neem contact op met uw beheerder voor verdere assistentie.</p>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($buttons as $button): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="<?php echo htmlspecialchars($button['icon']); ?>"></i> 
                                    <?php echo htmlspecialchars($button['name']); ?>
                                </h5>
                                <a href="<?php echo htmlspecialchars($button['url']); ?>" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar <?php echo htmlspecialchars($button['name']); ?>
                                </a>
                            </div>
                        </div>
                    </div> 
                <?php endforeach; ?>           
            </div>
        <?php endif; ?> 
    </div>

</div>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>