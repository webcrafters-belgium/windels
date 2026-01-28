<section class="py-5">
    <div class="container-fluid">
        <h2 class="my-5">Mensen zoeken ook naar</h2>

        <?php

        // Controleer de verbinding
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Haal de laatste zoekopdrachten op
        $sql = "SELECT query FROM search_queries ORDER BY search_date DESC LIMIT 10";
        $result = $conn->query($sql);

        // Loop door de zoekopdrachten en maak een knop voor elk item
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<a href="#" class="btn btn-warning me-2 mb-2">' . ($row['query']) . '</a>';
            }
        } else {
            echo "Geen zoekopdrachten gevonden.";
        }

        $conn->close();
        ?>
    </div>
</section>
