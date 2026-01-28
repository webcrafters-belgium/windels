<?php
// FILE: /pages/shop/cart/index.php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/mollie/vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$session_id = session_id();

$sql = "
  SELECT 
    ci.product_id, p.name,
    ci.price,                -- prijs in cart_items (incl. eventuele korting)
    p.price AS regular_price,
    p.sku, p.weight_grams,
    ci.quantity,
    (SELECT image_path FROM product_images WHERE product_id = p.id AND is_main = 1 LIMIT 1) AS main_image
  FROM cart_items ci
  JOIN products p ON p.id = ci.product_id
  WHERE ci.session_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $session_id);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
$totalPrice = 0.0;
$totalWeightG = 0;

while ($row = $result->fetch_assoc()) {
    $qty         = (int)$row['quantity'];
    $cartPrice   = (float)$row['price'];
    $regular     = (float)$row['regular_price'];
    $weightG     = max(0, (int)($row['weight_grams'] ?? 0));

    if (empty($row['main_image']) || !file_exists($_SERVER['DOCUMENT_ROOT'] . $row['main_image'])) {
        $row['main_image'] = "/images/products/placeholder.png";
    }

    $row['original_price'] = $regular;
    $row['discount']       = ($regular > $cartPrice) ? round(100 - ($cartPrice / $regular * 100)) : 0;
    $row['price']          = $cartPrice;

    $cartItems[]  = $row;
    $totalPrice  += $cartPrice * $qty;
    $totalWeightG += $weightG * $qty;
}

if ($totalWeightG <= 0) $totalWeightG = 1000;

$discountPercent  = (float)($_SESSION['applied_coupon']['discount'] ?? 0);
$discountAmount   = ($totalPrice * $discountPercent / 100);
$discountedTotal  = $totalPrice - $discountAmount;
$cartEmpty        = empty($cartItems);

include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>
    <main id="shopping-cart-page" class="container-fluid w-100 py-5">
        <h1 class="mb-4 text-start">Winkelmandje</h1>

        <?php if ($cartEmpty): ?>
            <p class="alert alert-warning text-center">Je winkelmandje is leeg.</p>
            <div class="text-center"><a href="/pages/shop" class="btn btn-primary">Verder winkelen</a></div>
        <?php else: ?>
            <div class="row g-4 flex-column flex-md-row">
                <!-- Linkerzijde -->
                <div class="col-12 col-md-6">
                    <div class="mb-4 w-100">
                        <h4>Verzendadres</h4>
                        <div id="shipping_error" class="alert alert-warning" style="display:none;"></div>

                        <select id="country" class="form-select border-primary mb-2">
                            <option value="">-- Kies je land --</option>
                            <option value="BE" selected>België</option>
                            <option value="NL">Nederland</option>
                        </select>
                        <input type="text" id="name"         class="form-control mb-2" placeholder="Naam" value="Gielen">
                        <input type="text" id="firstname"    class="form-control mb-2" placeholder="Voornaam" value="Matthias">
                        <input type="email" id="email"       class="form-control mb-2" placeholder="E-mail">
                        <input type="text" id="address" class="form-control mb-2" placeholder="Straat">
                        <input type="text" id="number"  class="form-control mb-2" placeholder="Nummer">
                        <input type="text" id="zipcode" class="form-control mb-2" placeholder="Postcode">
                        <input type="text" id="city"    class="form-control mb-2" placeholder="Stad">
                        <input type="tel"  id="phone"   class="form-control mb-2" placeholder="Telefoonnummer">
                        <input type="hidden" id="cart_weight_g" value="<?= (int)$totalWeightG ?>">
                    </div>

                    <div class="mb-4" id="shipping_methods_container" style="display:none;">
                        <h4>Verzendmethode</h4>
                        <div id="shipping_methods" class="ps-2"></div>
                    </div>

                    <form method="post" action="/functions/shop/cart/apply_coupon.php" class="mb-3 d-flex align-items-center gap-2">
                        <input type="text" name="coupon_code" class="form-control" placeholder="Kortingscode" required>
                        <button type="submit" class="btn btn-outline-primary">Toepassen</button>
                    </form>

                    <?php if (isset($_SESSION['applied_coupon'])): ?>
                        <div class='alert alert-success'>
                            Coupon <strong><?= htmlspecialchars($_SESSION['applied_coupon']['code']) ?></strong> toegepast (<?= (int)$discountPercent ?>%)
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Rechterzijde -->
                <div class="col-12 col-md-6">
                    <div class="mb-4 text-center">
                        <h4 class="mb-1">Totaalprijs</h4>
                        <?php if ($discountPercent): ?>
                            <p class="text-danger">Korting: -€<?= number_format($discountAmount, 2, ',', '.') ?> (<?= $discountPercent ?>%)</p>
                        <?php endif; ?>
                        <p id="total_price" class="fs-4 fw-semibold text-success">€<?= number_format($discountedTotal, 2, ',', '.') ?></p>
                    </div>

                    <ul class="list-group mb-4 shadow-sm rounded">
                        <?php foreach ($cartItems as $item): ?>
                            <li class="list-group-item d-flex align-items-center flex-wrap">
                                <img src="<?= htmlspecialchars($item['main_image']); ?>" alt="<?= htmlspecialchars($item['name']); ?>"
                                     class="me-3 mb-2" style="width: 80px; height: auto; border-radius: 5px;">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="mb-1 fw-bold"><?= htmlspecialchars($item['name']); ?></h5>
                                        <?php if ($item['discount']): ?>
                                            <span class="badge bg-green align-self-start">-<?= $item['discount']; ?>% KORTING</span>
                                        <?php endif; ?>
                                    </div>
                                    <small>
                                        Aantal: <?= (int)$item['quantity']; ?> |
                                        Prijs per stuk:
                                        <?php if ($item['discount']): ?>
                                            <span class="text-muted"><del>€<?= number_format($item['original_price'], 2, ',', '.'); ?></del></span>
                                            <span class="fw-semibold text-success ms-1">€<?= number_format($item['price'], 2, ',', '.'); ?></span>
                                        <?php else: ?>
                                            €<?= number_format($item['price'], 2, ',', '.'); ?>
                                        <?php endif; ?>
                                    </small>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="text-center">
                        <button id="checkoutButton" class="btn btn-success btn-lg px-5">Doorgaan naar afrekenen</button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <script>
        /* ===== Config & helpers ===== */
        const VAT_ON_MY_PARCEL = true, VAT_RATE = 1.21;
        const q = id => document.getElementById(id);
        const euro = n => "€" + (n).toFixed(2).replace('.', ',');
        const requiredLabels = { name:'naam', email:'e‑mail', address:'straat', number:'nummer', zipcode:'postcode', city:'stad', country:'land' };

        const cartTotal = <?= json_encode((float)$discountedTotal) ?>;
        const weightG = parseInt(q("cart_weight_g").value || "1000", 10) || 1000;

        const shippingContainer = q("shipping_methods_container");
        const shippingDiv = q("shipping_methods");
        const totalEl = q("total_price");
        const errBox = q("shipping_error");

        let shippingCost = 0, debounceTimer=null;

        /* ===== UI ===== */
        function setError(msg, fields=[]) {
            if (!errBox) return;
            errBox.textContent = msg ? (fields.length ? `${msg} (velden: ${fields.join(', ')})` : msg) : '';
            errBox.style.display = msg ? 'block' : 'none';
        }
        function updateTotal() {
            let korting = 0;
            if (cartTotal >= 50) korting = Math.min(Math.floor(cartTotal / 50) * 10, 100);
            totalEl.textContent = euro(cartTotal + shippingCost * (100 - korting)/100);
        }
        function icon(name){
            const s=(name||'').toLowerCase();
            if (s.includes('dpd')) return '<img src="/images/shipping/dpd.png" style="height:40px;margin-right:8px;">';
            if (s.includes('levering')) return '<img src="/images/shipping/car.gif" style="height:28px;margin-right:8px;">';
            if (s.includes('afhalen')) return '<img src="/images/shipping/afhalen.gif" style="height:28px;margin-right:8px;">';
            return '';
        }
        function renderBase(localDelivery=null){
            shippingDiv.innerHTML = `
    <div class="form-check">
      <input class="form-check-input" type="radio" name="shipping_method" id="ship_pickup" value="0" data-method="pickup" checked>
      <label class="form-check-label" for="ship_pickup">${icon('afhalen')}Afhalen in de winkel (${euro(0)})</label>
    </div>`;
            shippingCost = 0;

            if (localDelivery){
                const c = Number(localDelivery.cost)||0;
                shippingDiv.innerHTML += `
      <div class="form-check">
        <input class="form-check-input" type="radio" name="shipping_method" id="ship_local_delivery" value="${c.toFixed(2)}" data-method="close-range">
        <label class="form-check-label" for="ship_local_delivery">${icon('levering')}Bezorging aan huis (${euro(c)})</label>
      </div>`;
            }

            document.querySelectorAll('input[name="shipping_method"]').forEach(r=>{
                r.addEventListener('change', ()=>{ shippingCost = parseFloat(r.value)||0; updateTotal(); });
            });
            shippingContainer.style.display = "block";
            updateTotal();
        }

        /* ===== MyParcel prijs ===== */
        async function fetchMyParcelPrice() {
            setError('');
            const country = (q('country')?.value || 'BE').toUpperCase();
            const postcode = q('zipcode')?.value?.trim() || '';
            const street   = q('address')?.value?.trim() || '';
            const number   = q('number')?.value?.trim()  || '';
            const city     = q('city')?.value?.trim()    || '';
            const name     = q('name')?.value?.trim()    || '';
            const email    = q('email')?.value?.trim()   || '';
            const phone    = q('phone')?.value?.trim()   || '';

            if (!postcode || !street || !number) { renderBase(window._localDelivery||null); return; }

            const params = new URLSearchParams({ country, postcode, street, number, city, name, email, phone, weight_g: String(weightG) });
            if (!city || !name || !email) params.set('estimate','1');

            try {
                const res  = await fetch(`/API/myparcel/calculate_shipping.php?${params.toString()}`, { headers:{'Accept':'application/json'} });
                const data = await res.json();

                renderBase(window._localDelivery||null);
                if (!res.ok || data.error){ setError(data.error||'❌ Ophalen verzendkosten mislukt.', data.fields||[]); return; }

                let price = parseFloat(data.shipping_cost);
                if (isNaN(price)) { setError('❌ Ongeldige prijs van MyParcel.'); return; }
                if (VAT_ON_MY_PARCEL) price *= VAT_RATE;

                shippingDiv.innerHTML += `
      <div class="form-check">
        <input class="form-check-input" type="radio" name="shipping_method" id="ship_dpd_myparcel" value="${price.toFixed(2)}" data-method="DPD">
        <label class="form-check-label" for="ship_dpd_myparcel">${icon('dpd')}DPD via MyParcel (${euro(price)})</label>
      </div>`;

                document.querySelectorAll('input[name="shipping_method"]').forEach(r=>{
                    r.addEventListener('change', ()=>{ shippingCost = parseFloat(r.value)||0; updateTotal(); });
                });
                updateTotal();
            } catch { renderBase(window._localDelivery||null); setError('❌ Netwerkfout bij verzendkosten.'); }
        }

        /* ===== Lokale levering (afstand) ===== */
        function debounce(fn, ms=500){ clearTimeout(debounceTimer); debounceTimer = setTimeout(fn, ms); }
        function checkDistance() {
            const address = q("address").value.trim();
            const zipcode = q("zipcode").value.trim();
            const city    = q("city").value.trim();
            if (!address || !zipcode || !city) {
                window._localDelivery = null; renderBase(null); fetchMyParcelPrice(); return;
            }
            fetch("/functions/shop/cart/check_distance.php", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: `address=${encodeURIComponent(address)}&zipcode=${encodeURIComponent(zipcode)}&city=${encodeURIComponent(city)}`
            })
                .then(r=>r.json())
                .then(d=>{
                    window._localDelivery = (d.success && d.within_15km) ? { cost:7, distance:d.distance } : null;
                    renderBase(window._localDelivery);
                    fetchMyParcelPrice();
                })
                .catch(()=>{ window._localDelivery=null; renderBase(null); fetchMyParcelPrice(); });
        }

        /* ===== Checkout klik ===== */
        function validateFields(){
            const miss = [];
            for (const [id,label] of Object.entries(requiredLabels)){
                const v = q(id)?.value?.trim(); if (!v) miss.push(label);
            }
            const em = q('email')?.value?.trim();
            if (em && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em)) miss.push('geldig e‑mailadres');
            return miss;
        }
        function selectedShipping(){
            const el = document.querySelector('input[name="shipping_method"]:checked');
            return { cost: el ? (parseFloat(el.value)||0) : 0, method: el?.getAttribute('data-method') || 'pickup' };
        }
        async function startCheckout(){
            setError('');
            const miss = validateFields();
            if (miss.length){ setError('Vul alle verplichte velden in: ' + miss.join(', ')); return; }

            const ship = selectedShipping();
            const payload = {
                shippingCost: ship.cost.toFixed(2), shippingMethod: ship.method,
                name:q('name').value.trim(), email:q('email').value.trim(), phone:q('phone')?.value?.trim()||'',
                street:q('address').value.trim(), number:q('number').value.trim(),
                zipcode:q('zipcode').value.trim(), city:q('city').value.trim(), country:q('country').value.trim()
            };

            const btn = q('checkoutButton'), oldText = btn.textContent;
            btn.disabled = true; btn.textContent = 'Even geduld…';

            try {
                const res = await fetch('/functions/shop/cart/checkout.php', {
                    method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (!res.ok || !data.success) { setError(data?.error || 'Starten van betaling mislukt.'); btn.disabled=false; btn.textContent=oldText; return; }
                window.location.href = data.redirect; // Mollie
            } catch {
                setError('Netwerkfout bij starten van de betaling.');
                btn.disabled=false; btn.textContent=oldText;
            }
        }

        /* ===== Bindings ===== */
        ['country','address','number','zipcode','city','name','email','phone'].forEach(id=>{
            const el = q(id); if (!el) return;
            el.addEventListener('input', ()=>debounce(checkDistance, 500));
            el.addEventListener('change', ()=>debounce(checkDistance, 200));
        });
        document.addEventListener('DOMContentLoaded', ()=>{
            checkDistance();
            const btn = q('checkoutButton'); if (btn) btn.addEventListener('click', startCheckout);
        });
    </script>

<?php

include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
