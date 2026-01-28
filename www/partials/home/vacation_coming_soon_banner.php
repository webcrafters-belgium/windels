<div class="alert alert-info text-center mb-0 rounded-0 py-3" style="background-color: #cce5ff; color: #004085;">
    <strong>📦 Belangrijk:</strong> We sluiten binnenkort wegens <strong><?= htmlspecialchars($vacation['title']) ?></strong>.
    Houd rekening met vertragingen van maximaal <strong>14 dagen</strong> bij verzendingen.
    <br>
    <small>
        Sluiting: <?= date('d/m/Y', strtotime($vacation['start_date'])) ?> t/m <?= date('d/m/Y', strtotime($vacation['end_date'])) ?>
    </small>
</div>
