$(document).ready(function() {
    $('#newsletter-form').on('submit', function(e) {
        e.preventDefault();

        var $form = $(this); // verwijzing naar formulier
        var email = $form.find('#email').val().trim();
        var inschrijven = $form.find('#inschrijven').prop('checked');

        if (!inschrijven) {
            $('#response-message').html('<p style="color:red;">Je moet je inschrijven voor de nieuwsbrief.</p>');
            return;
        }

        if (!email) {
            $('#response-message').html('<p style="color:red;">Vul je e-mailadres in.</p>');
            return;
        }

        console.log("Email:", email, "Inschrijven:", inschrijven);

        $.ajax({
            url: '/functions/newsletter/subscribe.php',
            type: 'POST',
            dataType: 'json',
            data: {
                email: email,
                inschrijven: inschrijven
            },
            success: function(response) {
                if (response.status === 'success') {
                    $('#response-message').html('<p style="color:green;">Bedankt voor je inschrijving!</p>');
                } else {
                    $('#response-message').html('<p style="color:red;">' + response.message + '</p>');
                }
            },
            error: function(xhr, status, error) {
                $('#response-message').html('<p style="color:red;">Er is een fout opgetreden bij het verzenden van je formulier.</p>');
            }
        });
    });
});
