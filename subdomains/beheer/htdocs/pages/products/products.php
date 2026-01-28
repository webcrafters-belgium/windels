<?php
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<div id="product-container">
    <!-- Productlijst wordt hier geladen -->
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const productsContainer = document.getElementById('product-container');

        fetch('/functions/products/fetch_products.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    productsContainer.innerHTML = data.html;
                } else {
                    productsContainer.innerHTML = `<p>Fout: ${data.message}</p>`;
                }
            })
            .catch(error => {
                console.error('Fout bij ophalen van producten:', error);
                productsContainer.innerHTML = '<p>Er is een fout opgetreden bij het laden van de producten.</p>';
            });
    });
</script>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
