<?php
include dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';

// 🔐 Beveiliging: enkel ingelogde partner
session_start();
$partnerId = $_SESSION['partner_id'] ?? 0;

if (!$partnerId) {
    header('Location: login.php');
    exit;
}

// Sociale media velden
$platforms = [
    'facebook_url' => 'Facebook',
    'instagram_url' => 'Instagram',
    'linkedin_url' => 'LinkedIn',
    'website_url' => 'Website',
    'youtube_url' => 'YouTube',
    'twitter_url' => 'Twitter',
    'tiktok_url' => 'TikTok',
    'pinterest_url' => 'Pinterest',
    'whatsapp_url' => 'WhatsApp',
    'telegram_url' => 'Telegram'
];

// Ophalen van bestaande info
$sql = "SELECT * FROM funeral_partner_info WHERE partner_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $partnerId);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc() ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $over_ons = trim($_POST['over_ons_tekst'] ?? '');
    $social_values = [];

    foreach ($platforms as $field => $label) {
        $social_values[$field] = trim($_POST[$field] ?? '');
    }

    $columns = array_keys($social_values);
    $placeholders = implode(', ', array_map(fn($c) => "$c = ?", $columns));
    $params = array_merge([$over_ons], array_values($social_values));
    $types = str_repeat('s', count($params));

    if ($data) {
        $sql = "UPDATE funeral_partner_info SET over_ons_tekst = ?, $placeholders WHERE partner_id = ?";
        $params[] = $partnerId;
        $types .= 'i';
    } else {
        $sql = "INSERT INTO funeral_partner_info (over_ons_tekst, " . implode(', ', $columns) . ", partner_id) VALUES (" . str_repeat("?,", count($params)) . "?)";
        $params[] = $partnerId;
        $types .= 'i';
    }

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    $success = true;
    $data = $_POST;
}

include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/header.php';
?>

<main class="partner-mij-container">
    <h1 class="text-2xl font-semibold text-green-900 mb-6">Over mij & sociale media</h1>

    <?php if (!empty($success)): ?>
        <div class="alert-success">Je gegevens zijn opgeslagen.</div>
    <?php endif; ?>

    <form method="post" class="grid gap-6">
        <div class="card">
            <h2 class="text-lg font-semibold mb-2 text-green-800">Over mij</h2>
            <textarea name="over_ons_tekst" rows="6"><?= htmlspecialchars($data['over_ons_tekst'] ?? '') ?></textarea>
        </div>

        <div class="card">
            <h2 class="text-lg font-semibold mb-2 text-green-800">Sociale media</h2>
            <div id="social-container">
                <!-- dynamische content via JS -->
            </div>
            <button type="button" onclick="addSocialInput()" class="btn-green mt-3">+ Nog een platform toevoegen</button>
        </div>

        <button type="submit" class="btn-green">Opslaan</button>
    </form>

</main>

<style>
body {
    background: url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;
    background-size: cover;
}
.partner-mij-container {
    width: 100%;
    max-width: 800px;
    margin: 2rem auto 2rem auto;
    padding: 3rem 1rem;
    background-color: rgba(255, 255, 255, 0.92);
    border-radius: 24px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}


.partner-mij-container h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #1e4025;
    margin-bottom: 2rem;
    text-align: center;
}

.partner-mij-container form {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.partner-mij-container .card {
    padding: 2rem;
    border-radius: 18px;
    background-color: #ffffff;
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
}

@media (max-width: 768px) {
    .partner-mij-container {
        padding: 2rem 1rem;
    }

    #social-container > div {
        flex-direction: column;
        align-items: flex-start;
    }

    .partner-mij-container h1 {
        font-size: 1.5rem;
    }
}

.card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}
textarea,
input[type="url"],
select {
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 10px;
    width: 100%;
    font-size: 1rem;
}
#social-container > div {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 12px;
    background: #f9f9f9;
    padding: 1rem;
    margin-bottom: 1rem;
    border: 1px solid #ddd;
    border-radius: 12px;
}
.alert-success {
    background-color: #e0f4e5;
    padding: 1rem;
    border-radius: 10px;
    border: 1px solid #bde5ce;
    color: #155724;
    margin-bottom: 1.5rem;
}
.btn-green {
    background-color: #1e4025;
    color: white;
    padding: 0.6rem 1.4rem;
    border-radius: 30px;
    border: none;
    font-weight: 600;
    cursor: pointer;
}
.btn-green:hover {
    background-color: #2e6a3f;
}
.btn-remove {
    background-color: #8b1c1c;
    color: #fff;
    padding: 0.5rem 1.2rem;
    border: none;
    border-radius: 30px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.btn-remove:hover {
    background-color: #a62626;
}

</style>

<script>
const availablePlatforms = <?= json_encode($platforms) ?>;

function addSocialInput(selectedKey = '', url = '') {
    const container = document.getElementById('social-container');
    const div = document.createElement('div');

    let options = '';
    for (const key in availablePlatforms) {
        const selected = key === selectedKey ? 'selected' : '';
        options += `<option value="${key}" ${selected}>${availablePlatforms[key]}</option>`;
    }

    const currentName = selectedKey || 'facebook_url';

    div.innerHTML = `
        <select onchange="this.nextElementSibling.name = this.value">
            ${options}
        </select>
        <input type="url" name="${currentName}" value="${url}" placeholder="https://..." class="p-2 border rounded w-full max-w-[300px]">
        <button type="button" onclick="this.parentElement.remove()" class="btn-remove">Verwijder</button>
    `;

    container.appendChild(div);
}

// Laad bestaande data
<?php foreach ($platforms as $field => $label): ?>
<?php if (!empty($data[$field])): ?>
addSocialInput("<?= $field ?>", "<?= htmlspecialchars($data[$field], ENT_QUOTES) ?>");
<?php endif; ?>
<?php endforeach; ?>
</script>

<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/footer.php'; ?>
