// FILE: /js/checkout.js
document.addEventListener("DOMContentLoaded", () => {
    "use strict";

    const q = id => document.getElementById(id);
    const euro = n => "€" + (n).toFixed(2).replace(".", ",");
    const VAT_RATE = 1.21; // btw op verzendkosten

    const totalEl = q("total_price");
    let baseSubtotal = parseFloat(totalEl?.dataset.baseSubtotal || 0);
    let couponPercent = parseFloat(totalEl?.dataset.currentCouponPercent || 0);

    const shippingContainer = q("shipping_methods_container");
    const shippingDiv = q("shipping_methods");
    const errBox = q("shipping_error");

    let shippingCost = 0;

    function setError(msg) {
        if (!errBox) return;
        errBox.textContent = msg || "";
        errBox.style.display = msg ? "block" : "none";
    }

    function currentItemsTotal() {
        return baseSubtotal * (1 - (couponPercent / 100));
    }

    function updateTotal() {
        const itemsTotal = currentItemsTotal();
        const total = itemsTotal + shippingCost;
        if (totalEl) totalEl.textContent = euro(total);
    }

    function icon(name) {
        const s = (name || "").toLowerCase();
        if (s.includes("levering")) return '<img src="/images/shipping/car.gif" style="height:24px;margin-right:6px;">';
        if (s.includes("afhalen")) return '<img src="/images/shipping/afhalen.gif" style="height:24px;margin-right:6px;">';
        if (s.includes("dpd")) return '<img src="/images/shipping/dpd.png" style="height:30px;margin-right:6px;">';
        return "";
    }

    function renderMethods(options) {
        if (!shippingDiv) return;
        shippingDiv.innerHTML = "";
        shippingCost = 0;

        options.forEach((opt, i) => {
            const id = "ship_" + i;

            // btw alleen toepassen als het geen pickup en geen local_delivery is
            const isLocalDelivery = opt.method === "local_delivery";
            const isPickup = opt.method === "pickup";
            const costWithVat = (isPickup || isLocalDelivery)
                ? opt.cost
                : (opt.cost * VAT_RATE);

            shippingDiv.innerHTML += `
            <div class="form-check mb-1">
                <input class="form-check-input" type="radio" name="shipping_method"
                    id="${id}" value="${costWithVat.toFixed(2)}" data-method="${opt.method}" ${i === 0 ? "checked" : ""}>
                <label class="form-check-label" for="${id}">
                    ${icon(opt.label)}${opt.label} (€${costWithVat.toFixed(2).replace(".", ",")})
                </label>
            </div>`;
        });

        document.querySelectorAll('input[name="shipping_method"]').forEach(r => {
            r.addEventListener("change", () => {
                shippingCost = parseFloat(r.value) || 0;
                updateTotal();
            });
        });

        if (shippingContainer) shippingContainer.style.display = "block";
        shippingCost = parseFloat(document.querySelector('input[name="shipping_method"]:checked')?.value || "0");
        updateTotal();
    }


    function checkDistance() {
        setError("");
        const address = q("address")?.value.trim();
        const zipcode = q("zipcode")?.value.trim();
        const city    = q("city")?.value.trim();
        const country = q("country")?.value.trim();

        if (!address || !zipcode || !city || !country) {
            return; // niet alles ingevuld → geen shipping tonen
        }

        // standaard: afhalen
        let methods = [{ label: "Afhalen in de winkel", cost: 0, method: "pickup" }];

        // check afstand voor eigen levering
        fetch("/functions/shop/cart/check_distance.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `address=${encodeURIComponent(address)}&zipcode=${encodeURIComponent(zipcode)}&city=${encodeURIComponent(city)}`
        })
            .then(r => r.json())
            .then(d => {
                if (d.success && d.within_15km) {
                    methods.push({ label: "Eigen levering (binnen 15km)", cost: 7, method: "local_delivery" });
                }
            })
            .finally(() => {
                // laad vaste methodes (bv. DPD)
                const formData = new FormData();
                formData.append("country", country);
                fetch("/functions/shop/cart/get_shipping_methods.php", {
                    method: "POST",
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
            method: el?.getAttribute("data-method") || "pickup"
        };
    }

    function validateFields() {
        const required = { name:"Naam", email:"E-mail", address:"Straat", number:"Nummer", zipcode:"Postcode", city:"Stad", country:"Land" };
        const miss = [];
        for (const [id,label] of Object.entries(required)) {
            if (!q(id)?.value.trim()) miss.push(label);
        }
        const em = q("email")?.value.trim();
        if (em && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em)) miss.push("geldig e-mailadres");
        return miss;
    }

    async function startCheckout() {
        setError("");
        const miss = validateFields();
        if (miss.length) {
            setError("Vul alle verplichte velden in: " + miss.join(", "));
            return;
        }

        const ship = selectedShipping();
        const payload = {
            shippingCost: ship.cost.toFixed(2),
            shippingMethod: ship.method,
            name: q("name").value.trim(),
            email: q("email").value.trim(),
            phone: q("phone")?.value?.trim() || "",
            street: q("address").value.trim(),
            number: q("number").value.trim(),
            zipcode: q("zipcode").value.trim(),
            city: q("city").value.trim(),
            country: q("country").value.trim()
        };

        const btn = q("checkoutButton");
        const oldText = btn.textContent;
        btn.disabled = true;
        btn.textContent = "Even geduld…";

        try {
            const res = await fetch("/functions/shop/cart/checkout.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (!res.ok || !data.success) {
                setError(data?.error || "Starten van betaling mislukt.");
                btn.disabled = false;
                btn.textContent = oldText;
                return;
            }
            window.location.href = data.redirect;
        } catch {
            setError("Netwerkfout bij starten van de betaling.");
            btn.disabled = false;
            btn.textContent = oldText;
        }
    }

    // bij veranderen gemeente direct shipping tonen
    const cityInput = q("city");
    if (cityInput) {
        cityInput.addEventListener("change", checkDistance);
    }

    // knop = start checkout
    const checkoutBtn = q("checkoutButton");
    if (checkoutBtn) {
        checkoutBtn.addEventListener("click", e => {
            e.preventDefault();
            startCheckout();
        });
    }

    updateTotal();
});
