<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

// Klanten ophalen (gekoppeld op e-mail)
$stmt = $conn->prepare("SELECT u.id, u.first_name, u.last_name, u.email, u.phone, u.created_at,
  (SELECT COUNT(*) FROM orders o WHERE o.email = u.email) as aantal_bestellingen
  FROM users u
  WHERE u.role IN ('customer', 'admin')
  ORDER BY u.created_at DESC");

$stmt->execute();
$result = $stmt->get_result();
?>

<!-- PAGE HEADER -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2 glow-text" style="color: var(--text-primary);">
                <i class="bi bi-people-fill accent-primary mr-3"></i>Klantenoverzicht
            </h1>
            <p class="text-lg" style="color: var(--text-muted);">Beheer al je klantgegevens</p>
        </div>
        <div class="flex gap-3">
            <input type="file" id="csv_file" name="csv_file" accept=".csv" style="display: none;">
            <button id="uploadBtn" class="glass px-5 py-3 rounded-xl font-semibold hover:bg-white/10 transition flex items-center gap-2" style="color: var(--text-secondary);">
                <i class="bi bi-cloud-upload"></i>
                Importeer (.csv)
            </button>
        </div>
    </div>
</div>

<!-- SEARCH BAR -->
<div class="card-glass p-4 mb-8">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-500/20 to-slate-600/20 flex items-center justify-center">
            <i class="bi bi-search text-lg" style="color: var(--text-muted);"></i>
        </div>
        <input type="text" id="zoek-input" placeholder="Zoek op naam of e-mail..." 
               class="flex-1 px-4 py-3 rounded-xl glass border bg-transparent" style="border-color: var(--border-glass);">
    </div>
    <div id="import-feedback" class="mt-4"></div>
</div>

<!-- CUSTOMERS TABLE -->
<div class="card-glass p-8">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500/30 to-purple-500/30 flex items-center justify-center">
                <i class="bi bi-people text-xl text-violet-400"></i>
            </div>
            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Klanten</h2>
        </div>
        <span class="px-4 py-2 rounded-xl glass text-sm font-medium" style="color: var(--text-muted);">
            <?= $result->num_rows ?> klanten
        </span>
    </div>

    <div class="overflow-x-auto rounded-xl">
        <table class="w-full" id="klanten-tabel">
            <thead>
                <tr class="border-b" style="border-color: var(--border-glass); background: var(--bg-glass);">
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Naam</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">E-mail</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Telefoon</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Aangemaakt</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Orders</th>
                    <th class="text-right py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Acties</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="--tw-divide-opacity: 0.1;">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr class="group hover:bg-white/5 transition-colors">
                        <td class="py-4 px-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-violet-500/30 to-purple-500/30 flex items-center justify-center text-sm font-bold text-violet-400">
                                    <?php
                                        $firstInitial = mb_substr((string)($row['first_name'] ?? ''), 0, 1, 'UTF-8');
                                        $lastInitial = mb_substr((string)($row['last_name'] ?? ''), 0, 1, 'UTF-8');
                                        $initials = strtoupper($firstInitial . $lastInitial);
                                        if ($initials === '') {
                                            $initials = '?';
                                        }
                                    ?>
                                    <?= htmlspecialchars($initials) ?>
                                </div>
                                <span class="font-semibold"><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></span>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-sm" style="color: var(--text-muted);"><?= htmlspecialchars($row['email']) ?></td>
                        <td class="py-4 px-4 text-sm" style="color: var(--text-muted);"><?= htmlspecialchars($row['phone'] ?? '-') ?></td>
                        <td class="py-4 px-4 text-sm" style="color: var(--text-muted);"><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                        <td class="py-4 px-4">
                            <span class="px-3 py-1.5 rounded-lg text-xs font-semibold <?= $row['aantal_bestellingen'] > 0 ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-slate-500/20 text-slate-400 border border-slate-500/30' ?>">
                                <?= $row['aantal_bestellingen'] ?> orders
                            </span>
                        </td>
                        <td class="py-4 px-4 text-right">
                            <a href="/admin/pages/customers/detail.php?id=<?= $row['id'] ?>" 
                               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-teal-400 hover:bg-teal-500/20 transition-colors">
                                <i class="bi bi-eye-fill mr-1.5"></i> Bekijk
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="py-16 text-center">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-slate-500/20 to-slate-600/20 flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-people text-4xl" style="color: var(--text-muted);"></i>
                        </div>
                        <p class="text-lg font-semibold mb-1" style="color: var(--text-secondary);">Geen klanten gevonden</p>
                        <p class="text-sm" style="color: var(--text-muted);">Klanten verschijnen hier zodra ze registreren</p>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.getElementById('zoek-input').addEventListener('keyup', function () {
        const zoekTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#klanten-tabel tbody tr');
        rows.forEach(row => {
            const tekst = row.innerText.toLowerCase();
            row.style.display = tekst.includes(zoekTerm) ? '' : 'none';
        });
    });

    document.getElementById('uploadBtn').addEventListener('click', () => {
        document.getElementById('csv_file').click();
    });

    document.getElementById('csv_file').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('csv_file', file);

        fetch('/admin/pages/customers/import_csv.php', {
            method: 'POST',
            body: formData
        })
            .then(res => res.text())
            .then(msg => {
                document.getElementById('import-feedback').innerHTML = '<div class="p-4 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">' + msg + '</div>';
            })
            .catch(err => {
                document.getElementById('import-feedback').innerHTML = '<div class="p-4 rounded-xl bg-rose-500/20 text-rose-400 border border-rose-500/30">Fout bij uploaden.</div>';
            });
    });
</script>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
