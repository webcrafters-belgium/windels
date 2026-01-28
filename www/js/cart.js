document.addEventListener("DOMContentLoaded", () => {
    updateCartCount();
    loadCartItems();
    initAddToCartButtons();
});

function updateCartCount() {
    fetch("/functions/shop/cart/count.php")
        .then(res => res.json())
        .then(data => {
            if (!data.success) return;
            const countEl = document.getElementById("cart-count");
            const totalEl = document.getElementById("cart-total");

            if (countEl) countEl.textContent = data.count;
            if (totalEl) totalEl.textContent = data.total.toFixed(2).replace(".", ",");
            document.querySelectorAll(".cart-count-badge").forEach(badge => {
                badge.textContent = data.count;
                badge.style.display = data.count > 0 ? "inline-flex" : "none";
            });
        });
}

function showAddedToCart() {
    const popup = document.getElementById("addedToCart");
    if (!popup) return;
    popup.innerHTML = `<i class="bi bi-check-circle-fill"></i> In winkelmandje`;
    popup.classList.add("show");
    setTimeout(() => {
        popup.classList.remove("show");
        popup.style.opacity = "1";
    }, 2000);
}

function loadCartItems() {
    fetch("/functions/shop/cart/getCartItems.php")
        .then(res => res.json())
        .then(data => {
            const list = document.getElementById("cart-items-list");
            if (!list) return;
            list.innerHTML = "";

            if (!data.success || data.items.length === 0) {
                list.innerHTML = `<li class="list-group-item">Je winkelmandje is leeg</li>`;
                return;
            }

            data.items.forEach(item => {
                const li = document.createElement("li");
                li.className = "list-group-item d-flex justify-content-between align-items-center";
                li.innerHTML = `
                    <div class="d-flex align-items-center gap-2">
                        <img src="${item.image}" alt="${item.name}"
                             style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                        <div>
                          <h6 class="my-0">${item.name}</h6>
                          <small class="text-muted">${item.quantity} × €${item.price.toFixed(2).replace(".", ",")}</small>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="text-muted">€${item.subtotal.toFixed(2).replace(".", ",")}</span>
                        <button class="btn btn-sm btn-outline-danger mt-1 remove-from-cart" data-id="${item.id}">
                          <i class="bi bi-trash"></i>
                        </button>
                    </div>`;
                list.appendChild(li);
            });

            list.querySelectorAll(".remove-from-cart").forEach(btn => {
                btn.addEventListener("click", e => {
                    e.preventDefault();
                    removeFromCart(btn.dataset.id);
                });
            });
        });
}

function removeFromCart(productId) {
    const formData = new FormData();
    formData.append("product_id", productId);
    fetch("/functions/shop/cart/remove.php", {
        method: "POST",
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateCartCount();
                loadCartItems();
            } else {
                console.error(data.message || "Kon product niet verwijderen");
            }
        })
        .catch(err => console.error("Error:", err));
}

function initAddToCartButtons() {
    document.querySelectorAll(".add-to-cart").forEach(btn => {
        btn.addEventListener("click", e => {
            e.preventDefault();
            const productId = btn.dataset.id;
            if (!productId) return;

            const qty   = btn.dataset.qty   || 1;
            const price = btn.dataset.price || null;
            const name  = btn.dataset.name  || null;

            const formData = new FormData();
            formData.append("product_id", productId);
            formData.append("quantity", qty);
            if (price) formData.append("price", price);
            if (name)  formData.append("name", name);

            fetch("/functions/shop/cart/add.php", {
                method: "POST",
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        updateCartCount();
                        loadCartItems();
                        showAddedToCart();
                    } else {
                        console.error(data.message || "Kon product niet toevoegen");
                    }
                })
                .catch(err => console.error("Error:", err));
        });
    });
}
