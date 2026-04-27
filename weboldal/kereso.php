<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gyűjtői Katalógus</title>
    <link rel="stylesheet" href="main.css">
    
</head>
<body>
<nav class="navbar">
    <div class="nav-container">
        <a href="index.php" class="logo">Gyűjtői<span>Katalógus</span></a>
        <ul class="nav-links">
            <li><a href="index.php">Kezdőlap</a></li>
            <li><a href="kereso.php">Kereső felület</a></li>
        </ul>
    </div>
</nav>
    <div class="search-container">
        <input type="text" id="searchBar" placeholder="Keresés a gyűjteményben..." onkeyup="search()">
    </div>

    <div class="grid2" >
       
        <?php
        $conn = new mysqli("localhost", "root", "", "shop_db");
        $result = $conn->query("SELECT * FROM termekek");
        while($row = $result->fetch_assoc()): ?>
            <div class="card">
                <img src="kepek/<?php echo $row['kep_utvonal']; ?>" alt="tárgy">
                <div class="card-content">
                    <h3><?php echo $row['nev']; ?></h3>
                    <p><?php echo $row['leiras']; ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <script>
        function search() {
            let input = document.getElementById('searchBar').value.toLowerCase();
            let cards = document.getElementsByClassName('card');

            for (let card of cards) {
                let title = card.querySelector('h3').innerText.toLowerCase();
                card.style.display = title.includes(input) ? "block" : "none";
            }
        }
    </script>
</body>
</html>