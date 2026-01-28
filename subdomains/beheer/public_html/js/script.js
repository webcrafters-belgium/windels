document.addEventListener("DOMContentLoaded", function () {
  "use strict";

  // **Preloader Functionaliteit**
  function initPreloader() {
    document.body.classList.add("preloader-site");

    window.addEventListener("load", function () {
      var preloader = document.querySelector(".preloader-wrapper");
      if (preloader) {
        preloader.style.display = "none";
        document.body.classList.remove("preloader-site");
      }
    });
  }

  // **Chocolat Lightbox**
  function initChocolat() {
    var imageLinks = document.querySelectorAll(".image-link");
    if (imageLinks.length) {
      Chocolat(imageLinks, {
        imageSize: "contain",
        loop: true,
      });
    }
  }

  // **Product Aantal Verhogen/Verlagen**
  function initProductQty() {
    document.querySelectorAll(".product-qty").forEach(function (productQty) {
      var quantityInput = productQty.querySelector("#quantity");

      productQty.querySelector(".quantity-right-plus")?.addEventListener("click", function (e) {
        e.preventDefault();
        var quantity = parseInt(quantityInput.value || 0);
        quantityInput.value = quantity + 1;
      });

      productQty.querySelector(".quantity-left-minus")?.addEventListener("click", function (e) {
        e.preventDefault();
        var quantity = parseInt(quantityInput.value || 0);
        if (quantity > 0) {
          quantityInput.value = quantity - 1;
        }
      });
    });
  }

  // **Parallax Effect (Jarallax)**
  function initJarallax() {
    var jarallaxElems = document.querySelectorAll(".jarallax");
    if (jarallaxElems.length) {
      jarallax(jarallaxElems);
    }

    var jarallaxKeepImgElems = document.querySelectorAll(".jarallax-keep-img");
    if (jarallaxKeepImgElems.length) {
      jarallax(jarallaxKeepImgElems, { keepImg: true });
    }
  }

  // **Dropdown Hover Effect**
  function initDropdownHover() {
    document.querySelectorAll(".nav-item.dropdown").forEach(function (dropdown) {
      var menu = dropdown.querySelector(".dropdown-menu");

      dropdown.addEventListener("mouseenter", function () {
        menu.classList.add("show");
      });

      dropdown.addEventListener("mouseleave", function () {
        menu.classList.remove("show");
      });
    });
  }

  // **Brand Swiper Initialisatie**
  function initBrandSwiper() {
    if (document.querySelector(".brand-carousel")) {
      new Swiper(".brand-carousel", {
        slidesPerView: 4,
        spaceBetween: 30,
        speed: 500,
        navigation: {
          nextEl: ".brand-carousel-next",
          prevEl: ".brand-carousel-prev",
        },
        breakpoints: {
          0: { slidesPerView: 2 },
          768: { slidesPerView: 2 },
          991: { slidesPerView: 3 },
          1500: { slidesPerView: 4 },
        },
      });
    }
  }

  // **Initieer Alle Functionaliteiten**
  initPreloader();
  initProductQty();
  initJarallax();
  initChocolat();
  initDropdownHover();
  initBrandSwiper();
});

// **Checkout knop functionaliteit**
document.addEventListener("DOMContentLoaded", function() {
  const checkoutButton = document.querySelector("#checkoutButton");

  console.log("🔎 Knop gevonden?", checkoutButton);

  if (checkoutButton) {
    checkoutButton.addEventListener("click", function() {
      console.log("✅ Checkout knop is geklikt!");
      window.location.href = "/pages/shop/shopping-cart/";
    });
  } else {
    console.warn("⚠️ Let op: checkoutButton is niet gevonden in de DOM.");
  }
});

document.addEventListener("DOMContentLoaded", () => {
  "use strict";

  const cartCount = document.querySelector("#cart-count");
  const cartItemCount = document.querySelector("#cart-item-count");
  const cartTotal = document.querySelector("#cart-total");
  const cartItemsList = document.querySelector("#cart-items-list");
  const swiperWrapper = document.querySelector(".best-selling-swiper-wrapper");

  // **Producten ophalen en weergeven in Swiper**
  fetch('/functions/shop/amount-sold.php')
      .then(response => response.json())
      .then(data => {
        if (!swiperWrapper) return;

        swiperWrapper.innerHTML = data.map(product => `
                <div class="best-selling-item swiper-slide">
                    <span class="badge bg-success position-absolute m-3">Best verkocht</span>
                    <figure>
                        <a href="/product/${product.id}" title="${product.name}">
                            <img loading="lazy" src="/images/products/${product.id}/main.svg" class="tab-image"
                            alt="${product.name}">
                        </a>
                    </figure>
                    <h3>${product.name}</h3>
                    <span class="price">€${product.price}</span>
                    <div class="d-flex align-items-center justify-content-between">
                        <button class="add-to-cart btn btn-primary" data-id="${product.id}">
                            Toevoegen aan winkelmandje <i class="bi bi-shopping-cart"></i>
                        </button>
                    </div>
                </div>
            `).join("");

        // **Swiper Initialisatie**
        new Swiper('.swiper-container', {
          navigation: {
            nextEl: '.best-selling-carousel-next',
            prevEl: '.best-selling-carousel-prev',
          },
          slidesPerView: 4,
          spaceBetween: 20,
          loop: true,
        });

        updateCartInfo();
      })
      .catch(error => console.error("❌ Error fetching top products:", error));

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



  // **Winkelmand ophalen en updaten**
  function updateCartInfo() {
    updateCartCount();
    fetch("/functions/shop/cart/getCartItems.php")
        .then(response => response.json())
        .then(data => {
          if (!cartItemCount || !cartTotal || !cartItemsList) {
            console.error("❌ Fout: Winkelwagen-elementen ontbreken!");
            return;
          }

          if (!data.success || !data.items || data.items.length === 0) {
            cartItemCount.textContent = "0";
            cartTotal.textContent = "0,00";
            cartItemsList.innerHTML = '<li class="list-group-item text-center text-muted">Je winkelmandje is leeg</li>';
            return;
          }

          let totalCost = 0;
          let totalItems = 0;

          cartItemsList.innerHTML = data.items.map(item => {
            totalItems += item.quantity;
            totalCost += parseFloat(item.price.replace(',', '.')) * item.quantity;
            return `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="my-0">${item.name}</h6>
                                <small class="text-muted">Aantal: ${item.quantity}</small>
                            </div>
                            <span class="text-muted">€${item.price}</span>
                            <button class="btn btn-danger btn-sm remove-from-cart" data-id="${item.product_id}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </li>
                    `;
          }).join("");

          cartItemCount.textContent = totalItems.toString();
          cartTotal.textContent = totalCost.toFixed(2).replace('.', ',')
        })
        .catch(error => console.error("❌ Fout bij ophalen van winkelmandje:", error));
  }

  // **Event Delegation voor winkelmand updates (Add & Remove)**
  document.body.addEventListener("click", (event) => {
    let target = event.target;

    // **Product toevoegen aan winkelmandje**
    if (target.classList.contains("add-to-cart")) {
      let productId = target.getAttribute("data-id");
      fetch('/functions/shop/cart/add.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `product_id=${productId}`
      })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              console.log("🛒 Product toegevoegd! Bijwerken na 2 seconden...");
              setTimeout(updateCartInfo, 2000);
            } else {
              alert("❌ Er is een fout opgetreden, probeer opnieuw.");
            }
          })
          .catch(error => console.error("❌ Fout bij toevoegen aan winkelmandje:", error));
    }

    // **Product verwijderen uit winkelmandje**
    if (target.classList.contains("remove-from-cart")) {
      let productId = target.getAttribute("data-id");
      fetch("/functions/shop/cart/remove.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `product_id=${productId}`
      })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              console.log("🗑️ Product verwijderd! Winkelmand updaten...");
              updateCartInfo();
            } else {
              alert("❌ Kan product niet verwijderen.");
            }
          })
          .catch(error => console.error("❌ Fout bij verwijderen product:", error));
    }
  });


  updateCartInfo(); // ✅ Winkelmand laden bij pagina-opstart
});

