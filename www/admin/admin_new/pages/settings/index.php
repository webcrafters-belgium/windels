<?php
require_once '../../includes/header.php';
?>

<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-4xl font-bold mb-2" style="color: var(--text-primary);">
        <i class="bi bi-gear accent-primary mr-3"></i>Instellingen
    </h1>
    <p class="text-lg" style="color: var(--text-muted);">Configureer je admin panel</p>
</div>

<!-- Settings Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="card-glass p-8">
        <h2 class="text-2xl font-bold mb-4" style="color: var(--text-primary);">
            <i class="bi bi-palette accent-primary mr-2"></i>Weergave
        </h2>
        <p style="color: var(--text-muted);">Gebruik de knoppen in de header om:</p>
        <ul class="mt-4 space-y-2" style="color: var(--text-secondary);">
            <li>• Licht/Donker modus te wisselen</li>
            <li>• Groot lettertype in te schakelen</li>
            <li>• Hoog contrast te activeren</li>
        </ul>
    </div>
    
    <div class="card-glass p-8">
        <h2 class="text-2xl font-bold mb-4" style="color: var(--text-primary);">
            <i class="bi bi-database accent-primary mr-2"></i>Database
        </h2>
        <p style="color: var(--text-muted);">Database verbinding is actief</p>
        <div class="mt-4">
            <span class="px-4 py-2 rounded-lg bg-green-500/20 text-green-600 border border-green-500 font-semibold">
                <i class="bi bi-check-circle mr-2"></i>Verbonden
            </span>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>