<?php

// Bestand om Packlink te testen.

ini_set('display_errors', 1);
error_reporting(E_ALL);

include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc'; // bevat $packlink_api_key
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Packlink API Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h1 class="mb-4">Packlink API Test</h1>

    <form id="testForm" class="row g-3">
        <div class="col-md-4">
            <label for="to_zip" class="form-label">Postcode bestemming</label>
            <input type="text" class="form-control" id="to_zip" name="to_zip" value="9000">
        </div>
        <div class="col-md-4">
            <label for="to_city" class="form-label">Stad bestemming</label>
            <input type="text" class="form-control" id="to_city" name="to_city" value="Gent">
        </div>
        <div class="col-md-4">
            <label for="to_country" class="form-label">Landcode (ISO)</label>
            <input type="text" class="form-control" id="to_country" name="to_country" value="BE">
        </div>

        <div class="col-md-3">
            <label for="weight" class="form-label">Gewicht (kg)</label>
            <input type="number" class="form-control" id="weight" name="weight" step="0.1" value="1">
        </div>
        <div class="col-md-3">
            <label for="length" class="form-label">Lengte (cm)</label>
            <input type="number" class="form-control" id="length" name="length" value="20">
        </div>
        <div class="col-md-3">
            <label for="width" class="form-label">Breedte (cm)</label>
            <input type="number" class="form-control" id="width" name="width" value="15">
        </div>
        <div class="col-md-3">
            <label for="height" class="form-label">Hoogte (cm)</label>
            <input type="number" class="form-control" id="height" name="height" value="10">
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Verzendkosten berekenen</button>
        </div>
    </form>

    <hr class="my-4">

    <h2>Resultaat</h2>
    <div id="results"></div>
</div>

<script>
    document.getElementById("testForm").addEventListener("submit", async function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const res = await fetch("/functions/shipping/packlink_rates.php", {
            method: "POST",
            body: formData
        });

        const data = await res.json();
        const container = document.getElementById("results");
        container.innerHTML = "";

        if (!data.success) {
            container.innerHTML = `<div class="alert alert-danger">Fout: ${data.error || "Onbekend probleem"}</div>`;
            console.log(data);
            return;
        }

        if (!data.rates || data.rates.length === 0) {
            container.innerHTML = `<div class="alert alert-warning">Geen verzendopties gevonden</div>`;
            return;
        }

        const list = document.createElement("ul");
        list.className = "list-group";

        data.rates.forEach(rate => {
            const price = parseFloat(rate["price"] ?? rate["total_price"] ?? 0).toFixed(2).replace('.', ',');
            const name = rate["name"] ?? rate["courier_name"] ?? "Onbekend";
            const time = rate["delivery_time"] ?? `${rate["min_delivery_time"] ?? "?"}-${rate["max_delivery_time"] ?? "?"} dagen`;

            const li = document.createElement("li");
            li.className = "list-group-item";
            li.innerHTML = `<strong>${name}</strong> — €${price} — ${time}`;
            list.appendChild(li);
        });

        container.appendChild(list);
    });
</script>
</body>
</html>
