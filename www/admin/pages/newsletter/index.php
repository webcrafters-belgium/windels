<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

// Haal subscribers op
$subscribers = $conn->query("SELECT email, created_at FROM subscribers ORDER BY created_at DESC");

// Haal bestaande nieuwsbrieven op
$newsletters = $conn->query("SELECT id, subject, created_at FROM newsletters ORDER BY created_at DESC");
?>

<!-- PAGE HEADER -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2 glow-text" style="color: var(--text-primary);">
                <i class="bi bi-newspaper accent-primary mr-3"></i>Nieuwsbriefbeheer
            </h1>
            <p class="text-lg" style="color: var(--text-muted);">Beheer abonnees en verstuur nieuwsbrieven</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- SUBSCRIBERS CARD -->
    <div class="card-glass p-6">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500/30 to-green-500/30 flex items-center justify-center">
                    <i class="bi bi-envelope-fill text-xl text-emerald-400"></i>
                </div>
                <h2 class="text-xl font-bold" style="color: var(--text-primary);">Abonnees</h2>
            </div>
            <span class="px-3 py-1.5 rounded-lg text-sm font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                <?= $subscribers->num_rows ?> ingeschreven
            </span>
        </div>
        
        <div class="overflow-y-auto max-h-64 rounded-xl">
            <table class="w-full">
                <thead>
                    <tr class="border-b" style="border-color: var(--border-glass);">
                        <th class="text-left py-3 px-4 text-sm font-semibold" style="color: var(--text-muted);">Email</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold" style="color: var(--text-muted);">Datum</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="--tw-divide-opacity: 0.1;">
                    <?php while($row = $subscribers->fetch_assoc()): ?>
                        <tr class="hover:bg-white/5">
                            <td class="py-3 px-4 text-sm"><?= htmlspecialchars($row['email']) ?></td>
                            <td class="py-3 px-4 text-sm" style="color: var(--text-muted);"><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- NEWSLETTERS CARD -->
    <div class="card-glass p-6">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500/30 to-indigo-500/30 flex items-center justify-center">
                    <i class="bi bi-file-earmark-text text-xl text-blue-400"></i>
                </div>
                <h2 class="text-xl font-bold" style="color: var(--text-primary);">Nieuwsbrieven</h2>
            </div>
        </div>
        
        <div class="overflow-y-auto max-h-64 rounded-xl">
            <table class="w-full">
                <thead>
                    <tr class="border-b" style="border-color: var(--border-glass);">
                        <th class="text-left py-3 px-4 text-sm font-semibold" style="color: var(--text-muted);">Onderwerp</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold" style="color: var(--text-muted);">Datum</th>
                        <th class="text-right py-3 px-4 text-sm font-semibold" style="color: var(--text-muted);">Acties</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="--tw-divide-opacity: 0.1;">
                    <?php while($row = $newsletters->fetch_assoc()): ?>
                        <tr class="hover:bg-white/5">
                            <td class="py-3 px-4 text-sm font-medium"><?= htmlspecialchars($row['subject']) ?></td>
                            <td class="py-3 px-4 text-sm" style="color: var(--text-muted);"><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                            <td class="py-3 px-4 text-right">
                                <a href="/admin/pages/newsletter/view.php?id=<?= $row['id'] ?>" class="p-1.5 rounded-lg glass-hover text-blue-400 hover:bg-blue-500/20" title="Bekijk">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="/admin/pages/newsletter/send.php?id=<?= $row['id'] ?>" onclick="return confirm('Zeker dat je deze nieuwsbrief wil verzenden?')" class="p-1.5 rounded-lg glass-hover text-emerald-400 hover:bg-emerald-500/20" title="Verstuur">
                                    <i class="bi bi-send"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- CREATE NEWSLETTER FORM -->
<div class="card-glass p-8">
    <div class="flex items-center space-x-3 mb-6">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500/30 to-purple-500/30 flex items-center justify-center">
            <i class="bi bi-pencil-square text-xl text-violet-400"></i>
        </div>
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Nieuwsbrief opstellen</h2>
    </div>
    
    <form id="newsletter-admin-form" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col">
                <label for="template" class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Kies een template</label>
                <select id="template" class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
                    <option value="">-- Geen template --</option>
                    <option value="promo.php">Promotie</option>
                    <option value="default.html">Standaard</option>
                    <option value="nieuwe_producten.html">Nieuwe producten</option>
                </select>
            </div>

            <div class="flex flex-col">
                <label for="subject" class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Onderwerp</label>
                <input type="text" class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);" id="subject" name="subject" required placeholder="Onderwerp van je nieuwsbrief">
            </div>
        </div>

        <div class="flex flex-col">
            <label for="message" class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Inhoud (HTML toegestaan)</label>
            <textarea class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass); min-height: 200px;" id="message" name="message" required placeholder="Schrijf je nieuwsbrief hier..."></textarea>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="accent-bg text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition flex items-center gap-2">
                <i class="bi bi-save"></i>Opslaan
            </button>
            <button type="button" class="glass px-6 py-3 rounded-xl font-semibold hover:bg-white/10 transition flex items-center gap-2" id="preview-button">
                <i class="bi bi-eye"></i>Preview
            </button>
            <span id="newsletter-status" class="text-emerald-400"></span>
        </div>
    </form>

    <div id="newsletter-preview" class="hidden mt-8 p-6 rounded-xl glass">
        <h4 class="text-lg font-semibold mb-4 flex items-center gap-2" style="color: var(--text-primary);">
            <i class="bi bi-inbox text-teal-400"></i>Voorbeeldweergave
        </h4>
        <h5 id="preview-subject" class="font-bold text-xl mb-4"></h5>
        <div id="preview-content" class="prose" style="color: var(--text-secondary);"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const templateSelect = document.getElementById("template");
        const messageTextarea = document.getElementById("message");
        const previewBtn = document.getElementById("preview-button");
        const previewContainer = document.getElementById("newsletter-preview");
        const previewSubject = document.getElementById("preview-subject");
        const previewContent = document.getElementById("preview-content");
        const subjectInput = document.getElementById("subject");

        templateSelect.addEventListener("change", function () {
            const template = this.value;
            if (template !== "") {
                fetch(`/admin/pages/newsletter/get_template.php?file=${template}`)
                    .then(resp => resp.text())
                    .then(html => {
                        messageTextarea.value = html;
                    })
                    .catch(err => {
                        alert("Fout bij laden van template");
                        console.error(err);
                    });
            }
        });

        previewBtn.addEventListener("click", function () {
            const subject = subjectInput.value.trim();
            const message = messageTextarea.value.trim();

            if (!subject || !message) {
                alert("Vul zowel onderwerp als bericht in.");
                return;
            }

            previewSubject.textContent = subject;
            previewContent.innerHTML = message;
            previewContainer.classList.remove("hidden");
        });
    });
</script>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
