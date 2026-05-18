<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gyűjtői Katalógus</title>
    <link rel="stylesheet" href="main.css">
    <link rel="icon" type="image/png" href="favicon/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/svg+xml" href="favicon/favicon.svg" />
<link rel="shortcut icon" href="favicon/favicon.ico" />
<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png" />
<link rel="manifest" href="favicon/site.webmanifest" />
    <style>
       
        #filterPanel { display: none; margin: 10px 0; padding: 10px; border: 1px solid #ccc; }
    </style>
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

<div class="search-section">
    <input type="text" id="searchBar" placeholder="Keresés..." onkeyup="filterItems()">
    <button id="filterBtn" onclick="toggleFilter()">Szűrés ⚙️</button>
    
    <div id="filterPanel" class="filter-panel">
        <h4>Szűrési feltételek</h4>
        
        <label>Típus:</label>
        <select id="filterType" onchange="filterItems()">
            <option value="all">Összes típus</option>
            <option value="Kártya">Kártya</option>
            <option value="Könyv">Könyv</option>
            <option value="Képregény">Képregény</option>
            <option value="Figura">Figura</option>
        </select>

        <label>Max ár: <span id="priceVal">50000</span> Ft</label>
        <input type="range" id="filterPrice" min="0" max="50000" step="500" value="50000" oninput="updatePrice(this.value)">
    </div>
</div>
   
<div class="grid2">
    <?php
    $conn = new mysqli("localhost", "root", "", "shop_db");
    if ($conn->connect_error) { die("Hiba: " . $conn->connect_error); }
    
    $result = $conn->query("SELECT * FROM termekek");
    while($row = $result->fetch_assoc()): ?>
       
        <div class="card" data-type="<?php echo $row['tipus']; ?>" data-price="<?php echo $row['ar']; ?>">
            <img src="kepek/<?php echo $row['kep_utvonal']; ?>" alt="tárgy">
            <div class="card-content">
                <h3><?php echo $row['nev']; ?></h3>
                <p><?php echo $row['leiras']; ?></p>
                <p><strong><?php echo number_format($row['ar'], 0, ',', ' '); ?> Ft</strong></p>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<script>
function toggleFilter() {
    const panel = document.getElementById('filterPanel');
    if (panel) {
        panel.style.display = (panel.style.display === 'none' || panel.style.display === '') ? 'block' : 'none';
    }
}

function updatePrice(val) {
    document.getElementById('priceVal').innerText = val;
    filterItems();
}

function filterItems() {
    let nameSearch = document.getElementById('searchBar').value.toLowerCase();
    let typeSearch = document.getElementById('filterType').value;
    let priceSearch = parseInt(document.getElementById('filterPrice').value);
    
    let cards = document.getElementsByClassName('card');

    for (let card of cards) {
        let name = card.querySelector('h3').innerText.toLowerCase();
        let type = card.getAttribute('data-type');
        let price = parseInt(card.getAttribute('data-price'));

        let nameMatch = name.includes(nameSearch);
        let typeMatch = (typeSearch === "all" || type === typeSearch);
        let priceMatch = (price <= priceSearch);

        if (nameMatch && typeMatch && priceMatch) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    }
}
</script>
</body>
</html>

