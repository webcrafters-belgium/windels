<?php 
require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc'; 
 
require $_SERVER["DOCUMENT_ROOT"] . '/header.php';
require $_SERVER["DOCUMENT_ROOT"] . '/voedselproblemen/templates/header.php'; 
?>
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Overzicht van Productie Registraties</h2>
        </div>
        <div class="card-body">
            <!-- Zoekformulier -->
            <form id="search-form" class="form-inline mb-4">
                <input type="text" id="search" name="search" class="form-control mr-sm-2" placeholder="Zoeken op lotnummer of datum" value="">
                <button type="submit" class="btn btn-primary">Zoeken</button>
            </form>

            <!-- Tabel voor resultaten -->
            <div id="result" class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Lotnummer</th>
                            <th>Product</th>
                            <th>Aantal Gemaakt</th>
                            <th>Productie Datum</th>
                            <th>Vervaldatum</th>
                            <th>Externe Producten</th> <!-- Nieuwe kolom toegevoegd -->
                        </tr>
                    </thead>
                    <tbody id="result-body">
                        <!-- De resultaten zullen hier worden geladen -->
                    </tbody>
                </table>
            </div>

            <a href="add_productie.php" class="btn btn-primary mt-3">Nieuwe Productie Registreren</a>
        </div>
    </div>
</div>

<script>
// JavaScript voor het uitvoeren van de AJAX-zoekopdracht
document.getElementById('search-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Voorkom het herladen van de pagina
    fetchResults();
});

function fetchResults() {
    const searchTerm = document.getElementById('search').value;
    const url = `search_productie.php?search=${encodeURIComponent(searchTerm)}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            const resultBody = document.getElementById('result-body');
            resultBody.innerHTML = ''; // Maak de tabel leeg

            if (data.length === 0) {
                resultBody.innerHTML = '<tr><td colspan="6" class="text-center">Geen resultaten gevonden</td></tr>';
            } else {
                data.forEach(row => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${row.lotnummer}</td>
                        <td>${row.title}</td>
                        <td>${row.aantal_gemaakt}</td>
                        <td>${row.productie_datum}</td>
                        <td>${row.vervaldatum}</td>
                        <td>
                            <a href="registratie_extern_producten.php?lotnummer=${row.lotnummer}" class="btn btn-info btn-sm">Bekijk Externe Producten</a>
                        </td>
                    `;
                    resultBody.appendChild(tr);
                });
            }
        })
        .catch(error => console.error('Error fetching data:', error));
}

// Voer de zoekopdracht automatisch uit bij het laden van de pagina
fetchResults();
</script>
<?php require $_SERVER["DOCUMENT_ROOT"] . '/footer.php'; ?>