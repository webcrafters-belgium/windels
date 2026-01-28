<?php include $_SERVER['DOCUMENT_ROOT'] . '/header.php'; ?>

<div style="padding: 2rem;">
    <h2>Afbeeldingen converteren naar .webp</h2>
    <div id="progress-wrapper" style="margin-top: 1rem; width: 100%; background: #eee; border-radius: 8px;">
        <div id="progress-bar" style="width: 0%; height: 30px; background: green; border-radius: 8px; color: white; text-align: center; line-height: 30px;">0%</div>
    </div>
    <div id="status" style="margin-top: 1rem;">⏳ Starten...</div>
</div>

<script>
    function runBatch() {
        fetch('convert_images_to_webp.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('progress-bar').style.width = data.progress + '%';
                document.getElementById('progress-bar').textContent = data.progress + '%';
                document.getElementById('status').textContent = `✅ Batch verwerkt (${data.converted} geconverteerd).`;

                if (data.next) {
                    setTimeout(runBatch, 300); // volgende batch
                } else {
                    document.getElementById('status').textContent = "🎉 Alle afbeeldingen geconverteerd!";
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('status').textContent = "❌ Fout bij het verwerken.";
            });
    }

    runBatch();
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
