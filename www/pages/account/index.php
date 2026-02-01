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

$stmt = $conn->prepare("
    SELECT image_path 
    FROM user_images 
    WHERE user_id = ? 
    ORDER BY uploaded_at DESC 
    LIMIT 1
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$profile_image = $res->num_rows
        ? $res->fetch_assoc()['image_path']
        : "/images/profile/default.svg";
$stmt->close();

$q = $conn->prepare("SELECT COUNT(*) c FROM orders WHERE user_id=?");
$q->bind_param("i",$user_id);
$q->execute();
$order_count = $q->get_result()->fetch_assoc()['c'];
$q->close();
?>

<main class="account-page">

    <section class="account-header">
        <img src="<?= htmlspecialchars($profile_image) ?>" class="account-avatar" alt="Profiel">
        <div>
            <h1>Welkom, <?= htmlspecialchars($user_name) ?></h1>
            <p>Beheer je account en bestellingen</p>
        </div>
    </section>

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

            <div id="dashboard" class="account-card">
                <h2>Overzicht</h2>
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
                <h2>Recente bestellingen</h2>
                <table>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Datum</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $q = $conn->prepare("
                        SELECT id, created_at, status 
                        FROM orders 
                        WHERE user_id=? 
                        ORDER BY created_at DESC 
                        LIMIT 5
                    ");
                    $q->bind_param("i",$user_id);
                    $q->execute();
                    $r = $q->get_result();

                    if (!$r->num_rows) {
                        echo "<tr><td colspan='4'>Nog geen bestellingen</td></tr>";
                    }

                    while($o=$r->fetch_assoc()){
                        echo "
                        <tr>
                            <td>#{$o['id']}</td>
                            <td>".date('d-m-Y',strtotime($o['created_at']))."</td>
                            <td>{$o['status']}</td>
                            <td>
                                <a class='btn-outline' href='/pages/account/orders/detail.php?order_id={$o['id']}'>
                                    Bekijken
                                </a>
                            </td>
                        </tr>";
                    }
                    $q->close();
                    ?>
                    </tbody>
                </table>
            </div>

            <div id="profile" class="account-card">
                <h2>Profiel</h2>
                <form class="profile-form">
                    <label>Naam</label>
                    <input type="text" value="<?= htmlspecialchars($user_name) ?>">

                    <label>E-mail</label>
                    <input type="email" value="<?= htmlspecialchars($user_mail) ?>" disabled>

                    <button class="btn-primary" type="submit">Opslaan</button>
                </form>
            </div>

            <div id="security" class="account-card">
                <h2>Beveiliging</h2>
                <p>Beheer je wachtwoord en loginmethodes.</p>
                <a class="btn-outline" href="/pages/account/password/">Wachtwoord wijzigen</a>
            </div>

        </section>
    </div>

</main>

<script>
    if (location.hash === '#_=_') {
        history.replaceState(null, '', location.pathname);
    }
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
