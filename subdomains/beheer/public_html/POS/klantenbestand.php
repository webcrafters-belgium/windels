<?php
require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';
//require $_SERVER["DOCUMENT_ROOT"] . '/authenticatelogg.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/POS/LoyverseClient.php';

$api_token = '56f733c909bf417f98a0ff88b1f3a983';
$loyverse = new LoyverseClient($api_token);

// Klant toevoegen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $customer_data = [
        "name" => $_POST['name'],
        "email" => $_POST['email'],
        "phone" => $_POST['phone'],
        "address" => $_POST['address'] ?? null,
        "city" => $_POST['city'] ?? null,
        "region" => $_POST['region'] ?? null,
        "postal_code" => $_POST['postal_code'] ?? null,
        "country_code" => $_POST['country_code'] ?? null,
        "customer_code" => $_POST['customer_code'] ?? null,
        "note" => $_POST['note'] ?? null,
        "total_points" => $_POST['total_points'] ?? 100, // Default 100 punten
    ];

    try {
        $result = $loyverse->addCustomer($customer_data);
        echo "<div class='alert alert-success'>Klant succesvol toegevoegd!</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Fout bij het toevoegen van de klant: " . $e->getMessage() . "</div>";
    }
}

// Klant bewerken
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_customer_id'])) {
    $customer_data = [
        "id" => $_POST['edit_customer_id'],
        "name" => $_POST['edit_name'],
        "email" => $_POST['edit_email'],
        "phone_number" => $_POST['edit_phone'],
        "address" => $_POST['edit_address'],
        "address" => $_POST['edit_address'], // Straat en huisnummer
        "city" => $_POST['edit_city'],
        "region" => $_POST['edit_region'],
        "postal_code" => $_POST['edit_postal_code'],
        "country_code" => $_POST['edit_country_code'], 
        "note" => $_POST['edit_note']
    ];

    if (isset($_POST['edit_total_points'])) {
        $customer_data['total_points'] = (int)str_replace(',', '.', $_POST['edit_total_points']);
    }

    if (isset($_POST['edit_customer_code'])) {
        $customer_data['customer_code'] = $_POST['edit_customer_code'];
    }

    try {
        $result = $loyverse->updateCustomer($customer_data);
        echo "<div class='alert alert-success'>Klant succesvol bijgewerkt!</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Fout bij het bijwerken van de klant: " . $e->getMessage() . "</div>";
    }
}

// Klant verwijderen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_customer_id'])) {
    $customer_id = $_POST['delete_customer_id'];

    try {
        $result = $loyverse->deleteCustomer($customer_id);
        echo "<div class='alert alert-success'>Klant succesvol verwijderd!</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Fout bij het verwijderen van de klant: " . $e->getMessage() . "</div>";
    }
}

// Klanten ophalen
try {
    $customers = $loyverse->getCustomers();
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Fout bij het ophalen van klanten: " . $e->getMessage() . "</div>";
}
require $_SERVER["DOCUMENT_ROOT"] . '/header.php'; // Zorgt voor de bestaande header met Bootstrap
?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2 class="card-title">Klantenbeheer</h2>
        </div>
        <div class="card-body">
            <!-- Formulier om klant toe te voegen -->
            <h4>Nieuwe klant toevoegen</h4>
            
            <button class="btn btn-success" data-toggle="modal" data-target="#addCustomerModal">Nieuwe Klant Toevoegen</button>
            <!-- Zoekveld -->
            <input type="text" id="search" class="form-control mb-4" placeholder="Zoek klant...">

            <!-- Tabel met klanten -->
            <div id="customers_table" class="table-responsive">
                <?php include 'customers_table.php'; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal voor klant toevoegen -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomerModalLabel">Nieuwe Klant Toevoegen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="name">Naam:</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email">E-mail:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="phone">Telefoonnummer:</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="col-md-6">
                                <label for="customer_code">Klantenkaartnummer:</label>
                                <input type="text" class="form-control" id="customer_code" name="customer_code">
                            </div>
                        </div>

                        <h5 class="mt-4">Adresgegevens</h5>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="address">Adres:</label>
                                <input type="text" class="form-control" id="address" name="address">
                            </div>
                            <div class="col-md-6">
                                <label for="city">Plaats:</label>
                                <input type="text" class="form-control" id="city" name="city">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label for="region">Regio:</label>
                                <input type="text" class="form-control" id="region" name="region">
                            </div>
                            <div class="col-md-4">
                                <label for="postal_code">Postcode:</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code">
                            </div>
                            <div class="col-md-4">
                                <label for="country_code">Landcode:</label>
                                <select class="form-control" id="country_code" name="country_code" required>
                                    <option value="">Selecteer land</option>
                                    <option value="NL">Nederland</option>
                                    <option value="BE">België</option>
                                    <option value="DE">Duitsland</option>
                                    <option value="FR">Frankrijk</option>
                                    <option value="US">Verenigde Staten</option>
                                    <option value="GB">Verenigd Koninkrijk</option>
                                </select>
                            </div>

                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="note">Notitie:</label>
                                <textarea class="form-control" id="note" name="note" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Sluiten</button>
                    <button type="submit" class="btn btn-primary">Opslaan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal voor klant bewerken -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Klant Bewerken</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="edit_customer_id" id="edit_customer_id">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="edit_name">Naam:</label>
                            <input type="text" class="form-control" id="edit_name" name="edit_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_email">E-mail:</label>
                            <input type="email" class="form-control" id="edit_email" name="edit_email" required>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="edit_phone">Telefoonnummer:</label>
                            <input type="text" class="form-control" id="edit_phone" name="edit_phone">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_customer_code">Klantenkaartnummer:</label>
                            <input type="text" class="form-control" id="edit_customer_code" name="edit_customer_code">
                        </div>
                    </div>

                    <h5 class="mt-4">Adresgegevens</h5>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="edit_address">Adres:</label>
                            <input type="text" class="form-control" id="edit_address" name="edit_address">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_city">Plaats:</label>
                            <input type="text" class="form-control" id="edit_city" name="edit_city">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label for="edit_region">Regio:</label>
                            <input type="text" class="form-control" id="edit_region" name="edit_region">
                        </div>
                        <div class="col-md-4">
                            <label for="edit_postal_code">Postcode:</label>
                            <input type="text" class="form-control" id="edit_postal_code" name="edit_postal_code">
                        </div>
                        <div class="col-md-4">
                            <label for="edit_country_code">Landcode:</label>
                            <select class="form-control" id="edit_country_code" name="edit_country_code" required>
                                <option value="">Selecteer land</option>
                                <option value="NL">Nederland</option>
                                <option value="BE">België</option>
                                <option value="DE">Duitsland</option>
                                <option value="FR">Frankrijk</option>
                                <option value="US">Verenigde Staten</option>
                                <option value="GB">Verenigd Koninkrijk</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="edit_note">Notitie:</label>
                            <textarea class="form-control" id="edit_note" name="edit_note" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="edit_total_points">Totaal Punten:</label>
                            <input type="text" class="form-control" id="edit_total_points" name="edit_total_points" value="100">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Opslaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Zoekfunctie
    $('#search').on('keyup', function() {
        var searchQuery = $(this).val();
        $.ajax({
            url: 'search_customers.php',
            method: 'GET',
            data: { query: searchQuery },
            success: function(response) {
                $('#customers_table').html(response);
            },
            error: function(xhr, status, error) {
                alert('Fout bij het zoeken naar klanten: ' + error);
            }
        });
    });

    // Vul het bewerkmodaal met de juiste klantgegevens
    $('#editCustomerModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var name = button.data('name');
        var email = button.data('email');
        var phone = button.data('phone');
        var address = button.data('address');
        var city = button.data('city');
        var region = button.data('region');
        var postalCode = button.data('postal_code');
        var countryCode = button.data('country_code');
        var note = button.data('note');
        var points = button.data('points');
        var note = button.data('note');
        var points = button.data('points');
        var customerCode = button.data('customer-code');

        var modal = $(this);
        modal.find('#edit_customer_id').val(id);
        modal.find('#edit_name').val(name);
        modal.find('#edit_email').val(email);
        modal.find('#edit_phone').val(phone);
        modal.find('#edit_address').val(address);
        modal.find('#edit_city').val(city);
        modal.find('#edit_region').val(region);
        modal.find('#edit_postal_code').val(postalCode);
        modal.find('#edit_country_code').val(countryCode);
        modal.find('#edit_note').val(note);
        modal.find('#edit_total_points').val(points);
        modal.find('#edit_customer_code').val(customerCode); 
    });
});

</script>

<?php
require $_SERVER["DOCUMENT_ROOT"] . '/footer.php'; // Zorgt voor de bestaande footer
?>
