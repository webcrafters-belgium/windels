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