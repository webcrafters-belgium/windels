<?php

include dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';
session_start();
// Alleen toegankelijk voor ingelogde uitvaartdiensten
if (!isset($_SESSION['partner_id'])) {
    header("Location: /pages/account/login.php");
    exit;
}

// Functie om klantdata te ontsleutelen
function decryptField($data, $key) {
    if ($data === null || $data === '') {
        return '';
    }
    // Strict decoderen; false bij ongeldige base64
    $raw = base64_decode($data, true);
    if ($raw === false || strlen($raw) < 17) {
        return '';
    }
    $iv = substr($raw, 0, 16);
    $encrypted = substr($raw, 16);

    // Probeer eerst met OPENSSL_RAW_DATA (meest logisch bij vooraf decoderen)
    $plain = openssl_decrypt($encrypted, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    if ($plain === false) {
        // Fallback naar opties=0 voor legacy-data die mogelijk nog base64 verwacht
        $plain = openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
        if ($plain === false) {
            return '';
        }
    }
    return $plain;
}

// Haal alle bestellingen van deze uitvaartdienst op
// PAGINERING instellingen
$per_page = isset($_GET['per_page']) ? max(5, (int)$_GET['per_page']) : 5;
$page     = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset   = ($page - 1) * $per_page;

$search = trim($_GET['q'] ?? '');

$sql_base = "
    FROM orders o
    LEFT JOIN order_private op ON o.id = op.order_id
    WHERE o.funeral_partner_id = ?
";

$params = [$_SESSION['partner_id']];
$types  = "i";

// Zoekfilter
if ($search !== '') {
    $sql_base .= " AND (o.order_number LIKE ? OR o.klantnummer_partner LIKE ?)";
    $like = "%$search%";
    $params[] = $like;
    $params[] = $like;
    $types   .= "ss";
}

// Totaal voor paginering
$stmt_total = $mysqli->prepare("SELECT COUNT(*) ".$sql_base);
$stmt_total->bind_param($types, ...$params);
$stmt_total->execute();
$stmt_total->bind_result($total_results);
$stmt_total->fetch();
$stmt_total->close();

$total_pages = max(1, ceil($total_results / $per_page));

// Bestellingen ophalen
$sql = "
    SELECT o.id AS order_id, o.order_number, o.klantnummer_partner,
           o.created_at, o.status,
           ANY_VALUE(op.klant_naam) AS klant_naam,
           ANY_VALUE(op.klant_email) AS klant_email,
           ANY_VALUE(op.klant_telefoon) AS klant_telefoon,
           ANY_VALUE(op.klant_adres) AS klant_adres
    $sql_base
    GROUP BY o.id
    ORDER BY o.created_at DESC
    LIMIT ?, ?
";

$params[] = $offset;
$params[] = $per_page;
$types   .= "ii";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();

$result = $stmt->get_result();
$orders = [];

while ($row = $result->fetch_assoc()) {
    $row['klant_naam']      = decryptField($row['klant_naam'],      $encryption_key);
    $row['klant_email']     = decryptField($row['klant_email'],     $encryption_key);
    $row['klant_telefoon']  = decryptField($row['klant_telefoon'],  $encryption_key);
    $row['klant_adres']     = decryptField($row['klant_adres'],     $encryption_key);
    $orders[] = $row;
}
$stmt->close();

?>
<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php'; ?>
<style>
  body {
    background: url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;
    background-size: cover;
}

.dashboard-page {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 3rem 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    margin: 3rem auto 2rem auto;
}


.dashboard-page {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 3rem 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    margin: 3rem auto 2rem auto;
}

.dashboard-welcome {
    background-color: #f4f6f4;
    padding: 1.5rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.04);
}

.dashboard-welcome h1 {
    margin-bottom: 0.5rem;
    font-size: 1.4rem;
    color: #2a5934;
}

.dashboard-welcome p {
    font-size: 1rem;
    color: #444;
    margin-bottom: 1rem;
}

/* ✅ Mobiele optimalisatie */
@media (max-width: 768px) {


    .dashboard-page {
        padding: 2rem 1rem;
        margin: 2rem 1rem;
        border-radius: 8px;
    }
}
.btn-home {
    background-color: #1e4025;
    color: white;
    padding: 0.7rem 1.5rem;
    border-radius: 30px;
    border: none;
    cursor: pointer;
    display: block;
    width: fit-content;
    margin: 2rem auto 0 auto;
    font-weight: 600;
    text-align: center;
    transition: background-color 0.2s ease;
    text-decoration: none;
}

.btn-home:hover {
    background-color: #2e6a3f;
}
.pagination-controls {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin:1rem 0 1.2rem;
}

.per-page-form select {
    padding:.35rem .6rem;
    border-radius:6px;
}

.pagination-bar {
    display:flex;
    gap:.4rem;
    margin-top:1.2rem;
    flex-wrap:wrap;
}

.page-btn {
    padding:.45rem .75rem;
    background:#fff;
    border:1px solid #ccc;
    border-radius:6px;
    text-decoration:none;
    color:#2a5934;
    font-weight:600;
}

.page-btn.active {
    background:#2a5934;
    color:white;
    border-color:#2a5934;
}

.page-btn:hover {
    background:#e9f3ea;
}

</style>
<main class="dashboard-page container">
    <div class="dashboard-welcome">
        <h2>Mijn bestellingen</h2>
    </div>
    <div class="dashboard-action">
        <!-- Zoekbalk -->
        <form method="get" class="search-bar">
            <input type="text" name="q" placeholder="Zoek op ordernr. of klantnr."
                value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            <button type="submit" class="btn btn-primary">Zoeken</button>
            <?php if (!empty($_GET['q'])): ?>
                <a href="mijn_bestellingen.php" class="btn btn-secondary">Reset</a>
            <?php endif; ?>
        </form>
        <?php if (empty($orders)): ?>
            <?php if (!empty($_GET['q'])): ?>
                <p><strong>Geen bestellingen gevonden voor deze zoekopdracht.</strong></p>
            <?php else: ?>
                <p>Er zijn nog geen bestellingen geplaatst.</p>
            <?php endif; ?>
        <?php else: ?>
            <div class="pagination-controls">
                <form method="get" class="per-page-form">
                    <input type="hidden" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    <label>Toon:</label>
                    <select name="per_page" onchange="this.form.submit()">
                        <option value="5"  <?= $per_page == 5  ? 'selected' : '' ?>>5</option>
                        <option value="10" <?= $per_page == 10 ? 'selected' : '' ?>>10</option>
                        <option value="25" <?= $per_page == 25 ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= $per_page == 50 ? 'selected' : '' ?>>50</option>
                    </select>
                    <span>per pagina</span>
                </form>

                <div class="pagination-info">
                    Totaal gevonden: <strong><?= $total_results ?></strong>
                </div>
            </div>

            <table>
                <thead style="text-align:left">
                    <tr>
                        <th>Ordernummer</th>
                        <th>Datum</th>
                        <th>Klantnr.</th>
                        <th>Klant</th>
                        <th>Status</th>
                        <th>Actie</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): 
                        $status_class = 'status-' . $order['status']; 
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($order['order_number']) ?></td>
                            <td><?= htmlspecialchars($order['created_at']) ?></td>
                            <td><?= htmlspecialchars($order['klantnummer_partner'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($order['klant_naam']) ?></td>
                           <td>
                                <span class="status-label <?= $status_class ?>">
                                    <?= ucfirst(str_replace('_',' ',htmlspecialchars($order['status']))) ?>
                                </span>
                            </td>
                            <td>
                                <a href="view_order.php?id=<?= (int)$order['order_id'] ?>" class="btn">Bekijken</a><br>
                                <a href="/pages/account/orders/pdf/pakbon_<?= (int)$order['order_id'] ?>.pdf" target="_blank" class="btn" download>📄 Pakbon downloaden</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($total_pages > 1): ?>
                <div class="pagination-bar">
                    <!-- Vorige knop -->
                    <?php if ($page > 1): ?>
                        <a class="page-btn" href="?page=<?= $page-1 ?>&per_page=<?= $per_page ?>&q=<?= urlencode($search) ?>">« Vorige</a>
                    <?php endif; ?>

                    <!-- Paginanummers -->
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a class="page-btn <?= $i == $page ? 'active' : '' ?>"
                        href="?page=<?= $i ?>&per_page=<?= $per_page ?>&q=<?= urlencode($search) ?>">
                        <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <!-- Volgende knop -->
                    <?php if ($page < $total_pages): ?>
                        <a class="page-btn" href="?page=<?= $page+1 ?>&per_page=<?= $per_page ?>&q=<?= urlencode($search) ?>">Volgende »</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <a href="/pages/account/dashboard.php" class="btn-home">← Terug naar dashboard</a>  
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
