<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php';

// Haal subscribers op
$subscribers = $conn->query("SELECT email, created_at FROM subscribers ORDER BY created_at DESC");

// Haal bestaande nieuwsbrieven op
$newsletters = $conn->query("SELECT id, subject, created_at FROM newsletters ORDER BY created_at DESC");
?>

<div class="container py-5">
    <h1 class="mb-4">📰 Nieuwsbriefbeheer</h1>

    <!-- ✅ Gedeelte 1: Overzicht van subscribers -->
    <div class="mb-5">
        <h3>📬 Ingeschreven e-mails (<?= $subscribers->num_rows ?>)</h3>
        <div class="table-responsive border rounded mt-3">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th>Emailadres</th>
                    <th>Ingeschreven op</th>
                </tr>
                </thead>
                <tbody>
                <?php while($row = $subscribers->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ✅ Gedeelte 2: Overzicht van bestaande nieuwsbrieven -->
    <div class="mb-5">
        <h3>📄 Aangemaakte nieuwsbrieven</h3>
        <div class="table-responsive border rounded mt-3">
            <table class="table table-bordered table-sm mb-0">
                <thead class="table-light">
                <tr>
                    <th>Onderwerp</th>
                    <th>Aangemaakt op</th>
                    <th>Acties</th>
                </tr>
                </thead>
                <tbody>
                <?php while($row = $newsletters->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['subject']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                        <td>
                            <a href="/admin/pages/newsletter/view.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">📖 Bekijken</a>
                            <a href="/admin/pages/newsletter/send.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-success" onclick="return confirm('Zeker dat je deze nieuwsbrief wil verzenden?')">✉ Verstuur</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ✅ Gedeelte 3: Nieuwsbrief aanmaken -->
    <div>
        <h3>✍️ Nieuwsbrief opstellen</h3>
        <form id="newsletter-admin-form" class="mt-4">
            <div class="mb-3">
                <label for="template" class="form-label">Kies een template</label>
                <select id="template" class="form-select">
                    <option value="">-- Geen template --</option>
                    <option value="promo.php">Promotie</option>
                    <option value="default.html">Standaard</option>
                    <option value="nieuwe_producten.html">Nieuwe producten</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="subject" class="form-label">Onderwerp</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>

            <div class="mb-3">
                <label for="message" class="form-label">Inhoud (HTML toegestaan)</label>
                <textarea class="form-control" id="message" name="message" rows="10" required></textarea>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                <button type="submit" class="btn btn-success">💾 Opslaan</button>
                <button type="button" class="btn btn-secondary" id="preview-button">👁 Preview</button>
                <div id="newsletter-status" class="ms-3 mt-2 text-success"></div>
            </div>
        </form>

        <div id="newsletter-preview" class="p-4 border rounded bg-light d-none mt-4">
            <h4>📥 Voorbeeldweergave</h4>
            <h5 id="preview-subject" class="fw-bold mt-3"></h5>
            <div id="preview-content" class="mt-2"></div>
        </div>

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

        // ✅ Template laden bij selectie
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

        // ✅ Preview tonen
        previewBtn.addEventListener("click", function () {
            const subject = subjectInput.value.trim();
            const message = messageTextarea.value.trim();

            if (!subject || !message) {
                alert("Vul zowel onderwerp als bericht in.");
                return;
            }

            previewSubject.textContent = subject;
            previewContent.innerHTML = message;
            previewContainer.classList.remove("d-none");
        });
    });
</script>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
