<?php
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
?>

<div class="myparcel-dashboard-container">
    <h1>📦 MyParcel Dashboard</h1>

    <div class="myparcel-controls">
        <button id="fetch-shipments">📦 Ophalen zendingen</button>
        <button id="create-shipment">➕ Nieuwe zending aanmaken</button>
    </div>

    <div class="myparcel-shipping-calc">
        <h2>📍 Verzendkost berekenen</h2>
        <input type="text" id="postcode" placeholder="Postcode (bv. 9000)">
        <button id="calculate-shipping">💰 Bereken</button>
        <div id="shipping-cost-result"></div>
    </div>

    <div id="shipment-list">
        <!-- Zendingen worden hier geladen -->
    </div>
</div>

<script>
    document.getElementById('fetch-shipments').addEventListener('click', () => {
        fetch('/API/myparcel/get_shipments.php')
            .then(r => r.json())
            .then(data => {
                const list = document.getElementById('shipment-list');
                list.innerHTML = '';
                data.forEach(item => {
                    list.innerHTML += `
                      <div class="shipment">
                        <div><strong>Track & Trace:</strong> ${item.track_trace}</div>
                        <div class="status">Status: ${item.status}</div>
                        <button class="download-label" data-id="${item.id}">📄 Download label</button>
                      </div>`;
                });
            });
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('download-label')) {
            const id = e.target.dataset.id;
            window.open(`/API/myparcel/download_label.php?id=${id}`, '_blank');
        }
    });

    document.getElementById('create-shipment').addEventListener('click', () => {
        fetch('/API/myparcel/create_shipment.php', {
            method: 'POST',
            body: JSON.stringify({ order_id: 123 }), // Dynamisch maken indien nodig
            headers: { 'Content-Type': 'application/json' }
        })
            .then(res => res.json())
            .then(data => {
                alert(data.success ? '✅ Zending aangemaakt!' : '❌ Fout bij aanmaken');
            });
    });

    document.getElementById('calculate-shipping').addEventListener('click', () => {
        const pc = document.getElementById('postcode').value.trim();
        fetch('/API/myparcel/calculate_shipping.php?postcode=' + pc)
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    document.getElementById('shipping-cost-result').innerText = `⚠️ ${data.error}`;
                } else if (data.price) {
                    document.getElementById('shipping-cost-result').innerText = `Geschatte kost: €${(data.price / 100).toFixed(2)}`;
                } else {
                    document.getElementById('shipping-cost-result').innerText = 'Geen gegevens beschikbaar.';
                }
            });

    });
</script>


<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
