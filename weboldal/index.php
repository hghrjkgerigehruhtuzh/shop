<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "katalogus_db";

$conn = new mysqli($host, $user, $pass, $db);


if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");


function arKalkulator($alapAr, $ritkasag) {
    switch($ritkasag) {
        case 'A': return $alapAr * 15;
        case 'B': return $alapAr * 10;
        case 'C': return $alapAr * 5;
        default:  return $alapAr; // 'D' szint vagy alap
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gyűjtői Katalógus</title>
    <style>
        :root { --primary: #2c3e50; --accent: #3498db; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        

        .navbar { background: var(--primary); padding: 1rem; margin-bottom: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        .nav-container { display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; }
        .nav-links { list-style: none; display: flex; gap: 20px; margin: 0; padding: 0; }
        .nav-links a { color: white; text-decoration: none; font-weight: bold; }
        .logo { color: white; text-decoration: none; font-size: 1.5rem; font-weight: bold; }
        .logo span { color: var(--accent); }

     
        .search-section { position: relative; text-align: center; margin-bottom: 30px; }
        #searchBar { padding: 12px; width: 50%; border-radius: 5px; border: 1px solid #ccc; font-size: 16px; }
        #filterBtn { padding: 12px 20px; cursor: pointer; background: var(--accent); color: white; border: none; border-radius: 5px; font-weight: bold; }
        
        .filter-panel { 
            display: none; position: absolute; left: 50%; transform: translateX(-50%);
            background: white; border: 1px solid #ddd; padding: 20px; z-index: 100; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.2); border-radius: 8px; width: 280px; text-align: left;
            margin-top: 10px;
        }
        .filter-panel.active { display: block; }
        .filter-panel label { display: block; margin-top: 10px; font-weight: bold; }
        .filter-panel select, .filter-panel input { width: 100%; margin-top: 5px; }

     
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; max-width: 1200px; margin: 0 auto; }
        
       
        .card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); position: relative; transition: 0.3s; }
        .card:hover { transform: translateY(-8px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
        .card img { width: 100%; height: 220px; object-fit: cover; border-bottom: 1px solid #eee; }
        .card-content { padding: 20px; }
        .card h3 { margin-top: 0; color: var(--primary); }
        
       
        .rarity-badge { position: absolute; top: 10px; left: 10px; background: rgba(0,0,0,0.8); color: white; padding: 4px 10px; border-radius: 4px; font-weight: bold; z-index: 2; }
        .tier-A { border: 3px solid #ffd700; } /* Arany */
        .tier-B { border: 3px solid #c0c0c0; } /* Ezüst */
        .tier-C { border: 3px solid #cd7f32; } /* Bronz */
        .tier-D { border: 1px solid #ddd; }    /* Alap */

        .final-price { color: #e74c3c; font-weight: bold; font-size: 1.3rem; margin-top: 10px; display: block; }
        
        @media (max-width: 768px) {
            #searchBar { width: 80%; }
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <a href="index.php" class="logo">Gyűjtői<span>Katalógus</span></a>
        <ul class="nav-links">
            <li><a href="index.php">Kezdőlap</a></li>
            <li><a href="admin.php">Új elem</a></li>
        </ul>
    </div>
</nav>

<div class="search-section">
    <input type="text" id="searchBar" placeholder="Keresés név alapján..." onkeyup="filterItems()">
    <button id="filterBtn" onclick="toggleFilter()">Szűrés ⚙️</button>
    
    <div id="filterPanel" class="filter-panel">
        <h4>Szűrési feltételek</h4>
        
        <label for="filterType">Típus:</label>
        <select id="filterType" onchange="filterItems()">
            <option value="all">Összes kategória</option>
            <option value="Kártya">Kártya</option>
            <option value="Könyv">Könyv</option>
        </select>

        <label>Max ár: <span id="priceVal">100000</span> Ft</label>
        <input type="range" id="filterPrice" min="0" max="100000" step="1000" value="100000" oninput="updatePrice(this.value)">
    </div>
</div>

<div class="grid" id="katalogus">
    <?php

    $sql = "SELECT * FROM termekek ORDER BY datum DESC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0):
        while($row = $result->fetch_assoc()): 
         
            $veglegesAr = arKalkulator($row['ar'], $row['ritkasag']);
    ?>
        <div class="card tier-<?php echo $row['ritkasag']; ?>" 
             data-type="<?php echo htmlspecialchars($row['tipus']); ?>" 
             data-price="<?php echo $veglegesAr; ?>">
            
            <div class="rarity-badge">Tier <?php echo $row['ritkasag']; ?></div>
            
            <img src="uploads/<?php echo !empty($row['kep_utvonal']) ? $row['kep_utvonal'] : 'default.jpg'; ?>" alt="termék">
            
            <div class="card-content">
                <h3><?php echo htmlspecialchars($row['nev']); ?></h3>
                <p><?php echo htmlspecialchars($row['leiras']); ?></p>
                <small>Típus: <?php echo htmlspecialchars($row['tipus']); ?></small><br>
                <small>Alap ár: <?php echo number_format($row['ar'], 0, ',', ' '); ?> Ft</small>
                <span class="final-price"><?php echo number_format($veglegesAr, 0, ',', ' '); ?> Ft</span>
            </div>
        </div>
    <?php 
        endwhile; 
    else:
        echo "<div style='grid-column: 1/-1; text-align: center; padding: 50px;'>
                <h3>Még nincs megjeleníthető elem.</h3>
                <p>Kattints az 'Új elem' gombra a kezdéshez!</p>
              </div>";
    endif; 
    ?>
</div>

<script>
  
    function toggleFilter() {
        document.getElementById('filterPanel').classList.toggle('active');
    }
    
 
    function updatePrice(val) {
        document.getElementById('priceVal').innerText = val;
        filterItems();
    }

  
    function filterItems() {
        let nameInput = document.getElementById('searchBar').value.toLowerCase();
        let typeInput = document.getElementById('filterType').value;
        let priceInput = parseInt(document.getElementById('filterPrice').value);
        
        let cards = document.getElementsByClassName('card');

        for (let card of cards) {
            let name = card.querySelector('h3').innerText.toLowerCase();
            let type = card.getAttribute('data-type');
            let price = parseInt(card.getAttribute('data-price'));

     
            let matchesName = name.includes(nameInput);
            let matchesType = (typeInput === "all" || type === typeInput);
            let matchesPrice = (price <= priceInput);

         
            if (matchesName && matchesType && matchesPrice) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        }
    }
</script>

</body>
</html>
