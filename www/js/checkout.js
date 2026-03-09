// FILE: /js/checkout.js
document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const q = id => document.getElementById(id);
    const euro = n => '€' + Number(n).toFixed(2).replace('.', ',');
    const VAT_RATE = 1.21; // btw op verzendkosten

    const totalEl = q('total_price');
    const couponLineEl = q('coupon_line');
    const couponFeedbackEl = q('coupon_feedback');
    const couponFormEl = q('couponForm');
    const couponCodeEl = q('coupon_code');

    const baseSubtotal = parseFloat(totalEl?.dataset.baseSubtotal || '0') || 0;
    let couponValue = parseFloat(totalEl?.dataset.currentCouponValue || '0') || 0;
    let couponType = (totalEl?.dataset.currentCouponType || 'percent').toLowerCase();

    const shippingContainer = q('shipping_methods_container');
    const shippingDiv = q('shipping_methods');
    const errBox = q('shipping_error');

    let shippingCost = 0;

    function normalizedCouponType(type) {
        const t = String(type || '').toLowerCase();
        return t === 'percent' || t === 'percentage' ? 'percent' : 'amount';
    }

    function computeDiscountAmount(subtotal) {
        if (couponValue <= 0) return 0;
        const type = normalizedCouponType(couponType);
        return type === 'amount'
            ? Math.min(couponValue, subtotal)
            : subtotal * (couponValue / 100);
    }

    function couponText() {
        const type = normalizedCouponType(couponType);
        if (couponValue <= 0) return '';
        return type === 'amount' ? `-€${couponValue.toFixed(2).replace('.', ',')}` : `-${couponValue}%`;
    }

    function setError(msg) {
        if (!errBox) return;
        errBox.textContent = msg || '';
        errBox.style.display = msg ? 'block' : 'none';
    }

    function currentItemsTotal() {
        return Math.max(0, baseSubtotal - computeDiscountAmount(baseSubtotal));
    }

    function updateCouponUi() {
        const text = couponText();

        if (couponLineEl) {
            couponLineEl.style.display = text ? 'block' : 'none';
            couponLineEl.textContent = text ? `Korting: ${text}` : '';
        }

        if (couponFeedbackEl) {
            if (!text) {
                couponFeedbackEl.innerHTML = '';
            }
        }
    }

    function updateTotal() {
        const itemsTotal = currentItemsTotal();
        const total = itemsTotal + shippingCost;
        if (totalEl) totalEl.textContent = euro(total);
    }

    function icon(name) {
        const s = (name || '').toLowerCase();
        if (s.includes('levering')) return '<img src="/images/shipping/car.gif" style="height:24px;margin-right:6px;">';
        if (s.includes('afhalen')) return '<img src="/images/shipping/afhalen.gif" style="height:24px;margin-right:6px;">';
        if (s.includes('dpd')) return '<img src="/images/shipping/dpd.png" style="height:30px;margin-right:6px;">';
        return '';
    }

    function renderMethods(options) {
        if (!shippingDiv) return;
        shippingDiv.innerHTML = '';
        shippingCost = 0;

        options.forEach((opt, i) => {
            const id = 'ship_' + i;

            // btw alleen toepassen als het geen pickup en geen local_delivery is
            const isLocalDelivery = opt.method === 'local_delivery';
            const isPickup = opt.method === 'pickup';
            const costWithVat = (isPickup || isLocalDelivery)
                ? opt.cost
                : (opt.cost * VAT_RATE);

            shippingDiv.innerHTML += `
            <div class="form-check mb-1">
                <input class="form-check-input" type="radio" name="shipping_method"
                    id="${id}" value="${costWithVat.toFixed(2)}" data-method="${opt.method}" ${i === 0 ? 'checked' : ''}>
                <label class="form-check-label" for="${id}">
                    ${icon(opt.label)}${opt.label} (€${costWithVat.toFixed(2).replace('.', ',')})
                </label>
            </div>`;
        });

        document.querySelectorAll('input[name="shipping_method"]').forEach(r => {
            r.addEventListener('change', () => {
                shippingCost = parseFloat(r.value) || 0;
                updateTotal();
            });
        });

        if (shippingContainer) shippingContainer.style.display = 'block';
        shippingCost = parseFloat(document.querySelector('input[name="shipping_method"]:checked')?.value || '0');
        updateTotal();
    }

    function checkDistance() {
        setError('');
        const address = q('address')?.value.trim();
        const zipcode = q('zipcode')?.value.trim();
        const city = q('city')?.value.trim();
        const country = q('country')?.value.trim();

        if (!address || !zipcode || !city || !country) {
            return;
        }

        // standaard: afhalen
        let methods = [{ label: 'Afhalen in de winkel', cost: 0, method: 'pickup' }];

        // check afstand voor eigen levering
        fetch('/functions/shop/cart/check_distance.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `address=${encodeURIComponent(address)}&zipcode=${encodeURIComponent(zipcode)}&city=${encodeURIComponent(city)}`
        })
            .then(r => r.json())
            .then(d => {
                if (d.success && d.within_15km) {
                    methods.push({ label: 'Eigen levering (binnen 15km)', cost: 7, method: 'local_delivery' });
                }
            })
            .finally(() => {
                const formData = new FormData();
                formData.append('country', country);
                fetch('/functions/shop/cart/get_shipping_methods.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            data.methods.forEach(m => {
                                methods.push({
                                    label: `${m.name} (${m.duration})`,
                                    cost: parseFloat(m.cost) || 0,
                                    method: m.name.toLowerCase()
                                });
                            });
                        }
                    })
                    .finally(() => renderMethods(methods));
            });
    }

    function selectedShipping() {
        const el = document.querySelector('input[name="shipping_method"]:checked');
        return {
            cost: el ? (parseFloat(el.value) || 0) : 0,
            method: el?.getAttribute('data-method') || 'pickup'
        };
    }

    function validateFields() {
        const required = { name: 'Naam', email: 'E-mail', address: 'Straat', number: 'Nummer', zipcode: 'Postcode', city: 'Stad', country: 'Land' };
        const miss = [];
        for (const [id, label] of Object.entries(required)) {
            if (!q(id)?.value.trim()) miss.push(label);
        }
        const em = q('email')?.value.trim();
        if (em && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em)) miss.push('geldig e-mailadres');
        return miss;
    }

    async function startCheckout() {
        setError('');
        const miss = validateFields();
        if (miss.length) {
            setError('Vul alle verplichte velden in: ' + miss.join(', '));
            return;
        }

        const ship = selectedShipping();
        const payload = {
            shippingCost: ship.cost.toFixed(2),
            shippingMethod: ship.method,
            name: q('name').value.trim(),
            email: q('email').value.trim(),
            phone: q('phone')?.value?.trim() || '',
            street: q('address').value.trim(),
            number: q('number').value.trim(),
            zipcode: q('zipcode').value.trim(),
            city: q('city').value.trim(),
            country: q('country').value.trim()
        };

        const btn = q('checkoutButton');
        const oldText = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Even geduld...';

        try {
            const res = await fetch('/functions/shop/cart/checkout.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (!res.ok || !data.success) {
                setError(data?.error || 'Starten van betaling mislukt.');
                btn.disabled = false;
                btn.textContent = oldText;
                return;
            }
            window.location.href = data.redirect;
        } catch {
            setError('Netwerkfout bij starten van de betaling.');
            btn.disabled = false;
            btn.textContent = oldText;
        }
    }

    async function applyCoupon(event) {
        event.preventDefault();
        if (!couponCodeEl?.value?.trim()) {
            setError('Vul een kortingscode in.');
            return;
        }

        setError('');
        const formData = new FormData(couponFormEl);

        try {
            const res = await fetch('/functions/shop/cart/apply_coupon.php', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (!data.success) {
                setError(data.error || 'Kortingscode kon niet toegepast worden.');
                return;
            }

            couponValue = parseFloat(data.discount_value || 0) || 0;
            couponType = normalizedCouponType(data.discount_type || 'percent');

            if (couponFeedbackEl) {
                const label = couponType === 'amount'
                    ? `€${couponValue.toFixed(2).replace('.', ',')}`
                    : `${couponValue}%`;
                couponFeedbackEl.innerHTML = `
                    <div class="alert alert-success m-0 d-flex justify-content-between align-items-center">
                        <span>Coupon <strong>${data.code}</strong> toegepast (${label})</span>
                        <button id="remove_coupon_btn" class="btn btn-sm btn-outline-danger ms-2">X</button>
                    </div>`;
            }

            updateCouponUi();
            updateTotal();
        } catch {
            setError('Netwerkfout bij toepassen van kortingscode.');
        }
    }

    async function removeCoupon(event) {
        if (!event.target.closest('#remove_coupon_btn')) return;
        event.preventDefault();

        try {
            const res = await fetch('/functions/shop/cart/remove_coupon.php', { method: 'POST' });
            const data = await res.json();
            if (!data.success) {
                setError('Kortingscode kon niet verwijderd worden.');
                return;
            }

            couponValue = 0;
            couponType = 'percent';
            if (couponFeedbackEl) couponFeedbackEl.innerHTML = '';
            updateCouponUi();
            updateTotal();
        } catch {
            setError('Netwerkfout bij verwijderen van kortingscode.');
        }
    }

    const cityInput = q('city');
    if (cityInput) {
        cityInput.addEventListener('change', checkDistance);
    }

    const checkoutBtn = q('checkoutButton');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', e => {
            e.preventDefault();
            startCheckout();
        });
    }

    if (couponFormEl) {
        couponFormEl.addEventListener('submit', applyCoupon);
    }

    if (couponFeedbackEl) {
        couponFeedbackEl.addEventListener('click', removeCoupon);
    }

    updateCouponUi();
    updateTotal();
});
