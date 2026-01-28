<div class="alert alert-warning text-center mb-0 rounded-0 py-3" style="background-color: #ffecb5; color: #664d03;">
    <strong>📢 Let op:</strong> We zijn momenteel gesloten wegens <strong><?= htmlspecialchars($vacation['title']) ?></strong>
    <?php if (!empty($vacation['note'])): ?>
        – <?= htmlspecialchars($vacation['note']) ?>
    <?php endif; ?>
    <br>
    <small>
        (van <?= date('d/m/Y', strtotime($vacation['start_date'])) ?>
        t/m <?= date('d/m/Y', strtotime($vacation['end_date'])) ?>)
    </small>
</div>
