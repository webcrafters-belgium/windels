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

  // 🛒 Klik op "Bekijk winkelmandje" opent de winkelmand-pagina
  const openCartBtn = document.querySelector("#openCartBtn");

  if (openCartBtn) {
    openCartBtn.addEventListener("click", () => {
      window.location.href = "/pages/shop/cart/";
    });
  } else {
    console.warn("⚠️ openCartBtn niet gevonden in de DOM.");
  }

    function updateCartCount() {
        const cartCount = document.getElementById("cart-count");
        const cartTotal = document.getElementById("cart-total");
        const cartItemCount = document.getElementById("cart-item-count");

        fetch("/functions/shop/cart/count.php")
            .then(res => res.json())
            .then(data => {
                if (!data.success) return;

                if (cartCount) cartCount.textContent = data.count;
                if (cartTotal) cartTotal.textContent = data.total.toFixed(2).replace(".", ",");
                if (cartItemCount) cartItemCount.textContent = data.count;
            })
            .catch(err => console.error("Cart count error:", err));
    }


  // **Initieer Alle Functionaliteiten**
  initPreloader();
  initProductQty();
  initJarallax();
  initChocolat();
  initDropdownHover();
  initBrandSwiper();
});
