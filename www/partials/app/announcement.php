<style>
    #newsletter-form input,
    #newsletter-form select {
        border-radius: 8px;
        padding: 15px;
        font-size: 1.1rem;
    }

    #newsletter-form .form-check-input {
        transform: scale(1.2);
    }

    #newsletter-form .form-check-label {
        font-size: 1.1rem;
    }

    #newsletter-form .d-grid {
        margin-top: 15px;
    }

    #response-message {
        font-size: 1.2rem;
        color: green;
        font-weight: bold;
    }

    .bg-pattern-2 {
        background: url('/images/bg-pattern-2.png') no-repeat;
        background-size: contain;
    }

</style>

<section class="py-5 my-5">
    <div class="container-fluid">
        <div class="bg-warning py-5 rounded-5 bg-pattern-2">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <img src="/images/phone.png" alt="phone" class="image-float img-fluid">
                    </div>
                    <div class="col-md-8">
                        <h2 class="my-5">Windels Green & Deco Resin App</h2>
                        <p>Met de Windels Green & Deco Resin-app krijg je toegang tot exclusieve resin- en decorproducten, met gemakkelijke bestelopties en updates over nieuwe collecties. Of je nu een amateur of professional bent, de app maakt het eenvoudiger dan ooit om de perfecte producten voor je projecten te vinden.</p>
                        <p>Blijf op de hoogte van onze nieuwste resin-ontwerpen, bestel direct vanuit de app, en krijg toegang tot speciale aanbiedingen en kortingen. Maak je creativiteit vrij met Windels!</p>
                        <strong class="mb-3">Schrijf je in op onze nieuwsbrief om op de hoogte te blijven van de releasedatum</strong>
                        <div class="d-flex gap-2 flex-wrap mb-3">
                            <form id="newsletter-form" class="d-flex flex-row mb-3">
                                <div class="mb-3">
                                    <label for="email" class="form-label hidden">Email</label>
                                    <input type="email" class="form-control form-control-lg" name="email" id="email" placeholder="jouw@e-mailadres.be" required>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-dark btn-lg">Verzenden</button>
                                </div>
                            </form>
                            <div id="response-message" class="mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>

    $(document).ready(function() {
        $('#newsletter-form').on('submit', function(e) {
            e.preventDefault();

            var email = $('#email').val();
            var inschrijven = $('#inschrijven').prop('checked');

            // Controleer of de checkbox is aangevinkt
            if (!inschrijven) {
                $('#response-message').html('<p style="color:red;">Je moet je inschrijven voor de nieuwsbrief.</p>');
                return;
            }

            // Verzenden via AJAX
            $.ajax({
                url: '/functions/newsletter/subscribe.php', // Dit moet de serverzijde zijn die de data verwerkt
                type: 'POST',
                dataType: 'json',
                data: {
                    name: name,
                    email: email,
                    inschrijven: inschrijven
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#response-message').html('<p style="color:green;">Bedankt voor je inschrijving!</p>');
                    } else {
                        $('#response-message').html('<p style="color:red;">Er is iets mis gegaan, probeer het later opnieuw.</p>');
                    }
                },
                error: function(xhr, status, error) {
                    $('#response-message').html('<p style="color:red;">Er is een fout opgetreden bij het verzenden van je formulier.</p>');
                }
            });
        });
    });
</script>
