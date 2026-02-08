<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user']['id'])) {
    header("Location: /pages/account/login");
    exit;
}

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

$user_id   = $_SESSION['user']['id'];
$user_name = $_SESSION['user']['name'] ?? 'Gebruiker';
$user_mail = $_SESSION['user']['email'] ?? '';

$stmt = $conn->prepare(
    "SELECT image_path FROM user_images WHERE user_id = ? ORDER BY uploaded_at DESC LIMIT 1"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$profile_image = $res->num_rows
        ? $res->fetch_assoc()['image_path']
        : "/images/profile/default.svg";
$stmt->close();

$q = $conn->prepare("SELECT COUNT(*) c FROM orders WHERE user_id=?");
$q->bind_param("i", $user_id);
$q->execute();
$order_count = (int) $q->get_result()->fetch_assoc()['c'];
$q->close();

$q = $conn->prepare("SELECT id, created_at, status FROM orders WHERE user_id=? ORDER BY created_at DESC LIMIT 5");
$q->bind_param("i", $user_id);
$q->execute();
$r = $q->get_result();
$order_rows = [];
while ($order = $r->fetch_assoc()) {
    $order_rows[] = $order;
}
$q->close();
?>

<style>
:root {
    --page-bg: #f7f8fb;
    --card-bg: #ffffff;
    --border: #e2e2ea;
    --text-dark: #0f172a;
    --text-muted: #475569;
    --accent: #14b8a6;
}

.account-page {
    min-height: calc(100vh - 70px);
    padding: 2.5rem 1rem 3rem;
    background: var(--page-bg);
    color: var(--text-dark);
    font-family: 'Inter', system-ui, sans-serif;
}

.account-page__shell {
    max-width: 1200px;
    margin: 0 auto;
}

.account-header {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.75rem 2rem;
    border-radius: 1.25rem;
    background: var(--card-bg);
    border: 1px solid var(--border);
}

.account-avatar {
    width: 96px;
    height: 96px;
    border-radius: 999px;
    object-fit: cover;
    border: 2px solid var(--border);
}

.account-header h1 {
    font-size: 2rem;
    margin-bottom: 0.25rem;
}

.account-header p {
    color: var(--text-muted);
    margin: 0;
}

.account-actions {
    margin-top: 1.25rem;
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.account-actions a {
    padding: 0.5rem 1rem;
    border-radius: 0.85rem;
    font-size: 0.9rem;
    text-decoration: none;
    color: var(--text-dark);
    background: #e2e8f0;
    border: 1px solid transparent;
    transition: border 0.2s ease, background 0.2s ease;
}

.account-actions a:hover {
    background: #d1d7e8;
    border-color: var(--border);
}

.account-layout {
    margin-top: 2rem;
    display: grid;
    grid-template-columns: minmax(210px, 250px) 1fr;
    gap: 1.5rem;
}

.account-sidebar {
    background: var(--card-bg);
    border-radius: 1rem;
    border: 1px solid var(--border);
    padding: 1.25rem;
    position: sticky;
    top: 1.25rem;
    height: fit-content;
}

.account-sidebar nav {
    display: flex;
    flex-direction: column;
    gap: 0.45rem;
}

.account-sidebar nav a {
    color: var(--text-muted);
    text-decoration: none;
    padding: 0.65rem 0.85rem;
    border-radius: 0.75rem;
    transition: background 0.2s ease, color 0.2s ease;
}

.account-sidebar nav a.active,
.account-sidebar nav a:hover {
    background: #eef2ff;
    color: var(--text-dark);
}

.account-sidebar nav a.logout {
    margin-top: 0.5rem;
    border: 1px solid #fecdd3;
    color: #b91c1c;
    text-align: center;
}

.account-content {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.account-card {
    background: var(--card-bg);
    border-radius: 1rem;
    border: 1px solid var(--border);
    padding: 1.5rem;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
}

.account-card h2 {
    margin-bottom: 0.5rem;
}

.stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1rem;
}

.stat {
    background: #f8fafc;
    border-radius: 0.85rem;
    padding: 1rem;
    border: 1px solid #eef2ff;
    text-align: center;
}

.stat .label {
    font-size: 0.85rem;
    color: var(--text-muted);
}

.stat .value {
    font-size: 1.6rem;
    font-weight: 600;
}

.stat .value.active {
    color: var(--accent);
}

.orders-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
}

.order-card {
    border-radius: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.order-card__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.order-id {
    font-weight: 600;
}

.order-date {
    color: var(--text-muted);
    font-size: 0.9rem;
}

.order-status {
    border-radius: 999px;
    padding: 0.3rem 0.85rem;
    border: 1px solid rgba(15, 23, 42, 0.1);
    font-size: 0.75rem;
    text-transform: capitalize;
    color: var(--text-dark);
}

.order-card a {
    margin-top: auto;
    align-self: flex-start;
}

.profile-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
    align-items: end;
}

.profile-form label {
    font-size: 0.85rem;
    color: var(--text-muted);
}

.profile-form input {
    width: 100%;
    padding: 0.75rem 1rem;
    border-radius: 0.9rem;
    border: 1px solid var(--border);
    background: #f8fafc;
    color: var(--text-dark);
}

.btn-primary,
.btn-outline {
    border-radius: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
}

.btn-primary {
    padding: 0.85rem 1.5rem;
    background: var(--accent);
    color: #fff;
}

.btn-outline {
    padding: 0.7rem 1.35rem;
    border: 1px solid #cbd5f5;
    background: #fff;
    color: var(--text-dark);
}

#addresses .address-tags {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin: 0.5rem 0 1rem;
}

.address-tag {
    padding: 0.35rem 0.9rem;
    border-radius: 999px;
    border: 1px solid #cbd5f5;
    color: var(--text-muted);
    font-size: 0.8rem;
}

.account-card__lead {
    color: var(--text-muted);
    font-size: 0.95rem;
    margin-bottom: 0.75rem;
}

@media (max-width: 1024px) {
    .account-layout {
        grid-template-columns: 1fr;
    }

    .account-sidebar {
        position: relative;
        top: 0;
    }
}
</style>

<main class="account-page">
    <div class="account-page__shell">
        <section class="account-header">
            <img src="<?= htmlspecialchars($profile_image) ?>" class="account-avatar" alt="Profiel">
            <div class="account-header__content">
                <h1>Welkom, <?= htmlspecialchars($user_name) ?></h1>
                <p>Beheer je account, bestellingen en voorkeuren vanuit één overzicht.</p>
            </div>
        </section>

        <div class="account-actions">
            <a href="#orders">Bekijk recente bestellingen</a>
            <a href="#profile">Profiel updaten</a>
            <a href="#security">Beveiliging aanpassen</a>
        </div>

        <div class="account-layout">
            <aside class="account-sidebar">
                <nav>
                    <a class="active" href="#dashboard">Dashboard</a>
                    <a href="#orders">Bestellingen</a>
                    <a href="#profile">Profiel</a>
                    <a href="#addresses">Adressen</a>
                    <a href="#security">Beveiliging</a>
                    <a class="logout" href="/pages/account/logout/">Uitloggen</a>
                </nav>
            </aside>

            <section class="account-content">
                <div id="dashboard" class="account-card account-card--secondary">
                    <h2>Overzicht</h2>
                    <p class="account-card__lead">Je account is actief en klaar om bestellingen te plaatsen.</p>
                    <div class="stats">
                        <div class="stat">
                            <span class="label">Bestellingen</span>
                            <span class="value"><?= $order_count ?></span>
                        </div>
                        <div class="stat">
                            <span class="label">Status</span>
                            <span class="value active">Actief</span>
                        </div>
                    </div>
                </div>

                <div id="orders" class="account-card">
                    <div class="account-card__header">
                        <h2>Recente bestellingen</h2>
                        <span class="account-card__lead">Laatste 5 bestellingen</span>
                    </div>
                    <div class="orders-grid">
                        <?php if (empty($order_rows)): ?>
                            <div class="order-card">
                                <p class="order-date">Nog geen bestellingen geplaatst.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($order_rows as $order): ?>
                                <article class="order-card">
                                    <div class="order-card__header">
                                        <span class="order-id">#<?= htmlspecialchars($order['id']) ?></span>
                                        <span class="order-status"><?= htmlspecialchars($order['status']) ?></span>
                                    </div>
                                    <p class="order-date"><?= date('d-m-Y', strtotime($order['created_at'])) ?></p>
                                    <a class="btn-outline" href="/pages/account/orders/detail.php?order_id=<?= htmlspecialchars($order['id']) ?>">Bestelling bekijken</a>
                                </article>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="profile" class="account-card">
                    <h2>Profiel</h2>
                    <form class="profile-form" action="/pages/account/accountgegevens" method="post">
                        <label>Naam</label>
                        <input type="text" value="<?= htmlspecialchars($user_name) ?>" name="name">

                        <label>E-mail</label>
                        <input type="email" value="<?= htmlspecialchars($user_mail) ?>" name="email" disabled>

                        <div>
                            <button class="btn-primary" type="submit">Opslaan</button>
                        </div>
                    </form>
                </div>

                <div id="addresses" class="account-card">
                    <h2>Adressen</h2>
                    <p class="account-card__lead">Beheer aflever- en factuuradressen zodat bestellingen vlekkeloos aankomen.</p>
                    <div class="address-tags">
                        <span class="address-tag">Afleveradres</span>
                        <span class="address-tag">Factuuradres</span>
                    </div>
                    <a class="btn-outline" href="/pages/account/accountgegevens/">Adresboek bekijken</a>
                </div>

                <div id="security" class="account-card">
                    <h2>Beveiliging</h2>
                    <p class="account-card__lead">Vernieuw je wachtwoord of schakel extra verificatie in voor extra rust.</p>
                    <a class="btn-outline" href="/pages/account/password/">Wachtwoord wijzigen</a>
                </div>
            </section>
        </div>
    </div>
</main>

<script>
    if (location.hash === '#_=_') {
        history.replaceState(null, '', location.pathname);
    }
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
