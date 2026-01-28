</div>
<footer>
        <p class="mr-auto">&copy; <?php echo date("Y"); ?> Windels Green & Deco Resin. Alle rechten voorbehouden.</p>
        <p class="ml-auto">Versie: <?php echo file_exists('/version.txt') ? file_get_contents('/version.txt') : '0.0.1'; ?></p>
        
</footer>
<script>
        function updateDateTime() {
            const now = new Date();
            const dateTimeString = now.toLocaleDateString('nl-NL', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('datetime').textContent = dateTimeString;
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>
     

     <?php
        if(isset($_SESSION['status']) && $_SESSION['status'] !='')
        {
            ?> 
                <script>
                    swal({
                      title: "<?php echo $_SESSION['status']; ?>",
                      //Text: "You clicked the button",
                      icon: "<?php echo $_SESSION['status_code']; ?>",
                      button: "Oké !",
                    });
                </script>
            <?php 
             unset($_SESSION['status']);
        }
    ?> 
    
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/voedselproblemen/bootstrap/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/js/bootstrap.min.js"></script>
</body>
</html>
