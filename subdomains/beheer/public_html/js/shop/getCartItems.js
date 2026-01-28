// getCartItems.js

document.addEventListener("DOMContentLoaded", function () {

    function updateCartCount() {
        fetch('/functions/shop/cart/count.php')
            .then(response => response.json())
            .then(data => {
                let cartCountElement = document.querySelector("#cart-count");
                let cartTotalElement = document.querySelector("#cart-total");

                if (cartCountElement) {
                    let newCount = data.count ?? 0;
                    if (cartCountElement.textContent !== newCount.toString()) {
                        cartCountElement.textContent = newCount;
                    }
                } else {
                    console.error("❌ Fout: #cart-count element niet gevonden.");
                }

                if (cartTotalElement) {
                    let newTotal = parseFloat(data.total ?? 0).toFixed(2).replace('.', ',');
                    if (cartTotalElement.textContent !== newTotal) {
                        cartTotalElement.textContent = newTotal;
                    }
                } else {
                    console.error("❌ Fout: #cart-total element niet gevonden.");
                }
            })
            .catch(error => console.error("❌ Fout bij ophalen van winkelmandtelling:", error));
    }
    function updateCartInfo() {
        updateCartCount();

        fetch("/functions/shop/cart/getCartItems.php")
            .then(response => response.json())
            .then(data => {
                let cartItemCount = document.querySelector("#cart-item-count");
                let cartTotal = document.querySelector("#cart-total");
                let cartItemsList = document.querySelector("#cart-items-list");

                if (!cartItemCount || !cartTotal || !cartItemsList) {
                    console.error("❌ Fout: Een of meer winkelwagen-elementen ontbreken!");
                    return;
                }

                // ✅ Controleer of er items zijn in het winkelmandje
                if (!data.success || !data.items || data.items.length === 0) {
                    console.log("🛒 Geen items in winkelmandje. Totaal wordt 0,00.");
                    cartItemCount.textContent = "0";
                    cartTotal.textContent = "0,00";
                    cartItemsList.innerHTML = '<li class="list-group-item text-center text-muted">Je winkelmandje is leeg</li>';
                    return;
                }

                let totalCost = 0;
                let totalItems = 0;

                // Winkelwagenlijst legen
                cartItemsList.innerHTML = '';

                // Voeg products toe aan de lijst
                data.items.forEach(item => {
                    totalItems += item.quantity;
                    totalCost += parseFloat(item.price.replace(',', '.')) * item.quantity;

                    let listItem = document.createElement("li");
                    listItem.className = "list-group-item d-flex justify-content-between align-items-center";
                    listItem.innerHTML = `
                        <div>
                            <h6 class="my-0">${item.name}</h6>
                            <small class="text-muted">Aantal: ${item.quantity}</small>
                        </div>
                        <span class="text-muted">€${item.price}</span>
                        <button class="btn btn-danger btn-sm remove-from-cart" data-id="${item.product_id}">
                            <i class="bi bi-trash"></i>
                        </button>
                    `;
                    cartItemsList.appendChild(listItem);
                });

                // ✅ Update het totaal aantal items en de prijs
                cartItemCount.textContent = totalItems.toString();
                cartTotal.textContent = totalCost.toFixed(2).replace('.', ',');

                console.log("✅ Winkelmandje geüpdatet.");
            })
            .catch(error => console.error("❌ Fout bij ophalen van winkelmandje:", error));
    }

    // **Event delegation: Verwijderen van product uit winkelmand**
    document.body.addEventListener("click", function (event) {
        if (event.target.closest(".remove-from-cart")) {
            let button = event.target.closest(".remove-from-cart");
            let productId = button.getAttribute("data-id");

            if (!productId) {
                console.error("❌ Fout: Geen product ID gevonden voor verwijderen.");
                return;
            }

            fetch("/functions/shop/cart/remove.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `product_id=${productId}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log(`🗑️ Product met ID ${productId} verwijderd uit winkelmand.`);
                        updateCartInfo(); // ✅ Winkelmand opnieuw laden
                    } else {
                        alert("❌ Kan product niet verwijderen.");
                    }
                })
                .catch(error => console.error("❌ Fout bij verwijderen product:", error));
        }
    });

    updateCartInfo(); // ✅ Winkelmand laden bij pagina-opstart
});
