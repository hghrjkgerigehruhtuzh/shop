<?php
// Adatbázis kapcsolat - Figyelj, hogy a keresőnél használt adatbázis nevet írd ide (pl. katalogus_db)!
$host = "localhost"; $user = "root"; $pass = ""; $db = "katalogus_db";
$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8mb4");

if (isset($_POST['mentes'])) {
    $nev = $_POST['nev'];
    $ar = $_POST['ar']; // Ez az alapár
    $leiras = $_POST['leiras'];
    $tipus = $_POST['tipus'];
    $ritkasag = $_POST['ritkasag'];
    
    // Mappa ellenőrzése (kepek vagy uploads - használd azt, ami az index.php-ban van!)
    $mappa = "uploads/"; 
    if (!file_exists($mappa)) { mkdir($mappa, 0777, true); }

    $fajlNev = time() . "_" . $_FILES['kep']['name']; 
    $cel = $mappa . $fajlNev;

    if (move_uploaded_file($_FILES['kep']['tmp_name'], $cel)) {
        // Bővített INSERT az új oszlopokkal
        $stmt = $conn->prepare("INSERT INTO termekek (nev, ar, kep_utvonal, leiras, tipus, ritkasag) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissss", $nev, $ar, $fajlNev, $leiras, $tipus, $ritkasag);
        $stmt->execute();
    }
}

$termekek = $conn->query("SELECT * FROM termekek ORDER BY id DESC");

// Szorzó függvény a megjelenítéshez
function arKalkulator($alapAr, $ritkasag) {
    switch($ritkasag) {
        case 'A': return $alapAr * 15;
        case 'B': return $alapAr * 10;
        case 'C': return $alapAr * 5;
        default:  return $alapAr;
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feltöltés - Gyűjtői Katalógus</title>
    <link rel="stylesheet" href="main.css"> <!-- Ide jöhet a korábbi stílusod -->
    <style>
        .form-container { max-width: 500px; margin: 20px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-container input, .form-container select, .form-container textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .form-container button { width: 100%; padding: 10px; background: #3498db; color: white; border: none; cursor: pointer; border-radius: 4px; font-weight: bold; }
        .form-container button:hover { background: #2980b9; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; padding: 20px; }
        .card { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center; }
        .card img { max-width: 100%; height: 150px; object-fit: cover; }
        .tier-badge { display: inline-block; padding: 2px 8px; background: #eee; border-radius: 4px; font-size: 0.8rem; margin-bottom: 5px; }
    
        :root { --primary: #2c3e50; --accent: #3498db; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        
        /* Navigáció */
        .navbar { background: var(--primary); padding: 1rem; margin-bottom: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        .nav-container { display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; }
        .nav-links { list-style: none; display: flex; gap: 20px; margin: 0; padding: 0; }
        .nav-links a { color: white; text-decoration: none; font-weight: bold; }
        .logo { color: white; text-decoration: none; font-size: 1.5rem; font-weight: bold; }
        .logo span { color: var(--accent); }

        /* Kereső és Szűrő Szekció */
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

        /* Kártya Grid */
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; max-width: 1200px; margin: 0 auto; }
        
        /* Kártya Stílusok */
        .card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); position: relative; transition: 0.3s; }
        .card:hover { transform: translateY(-8px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
        .card img { width: 100%; height: 220px; object-fit: cover; border-bottom: 1px solid #eee; }
        .card-content { padding: 20px; }
        .card h3 { margin-top: 0; color: var(--primary); }
        
        /* Ritkaság Jelvények és Színek */
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
            <li><a href="admin.php">Kereső felület</a></li>
        </ul>
    </div>
</nav>

<div class="form-container">
    <h3>Új elem hozzáadása</h3>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="nev" placeholder="Termék neve" required>
        
        <label>Típus:</label>
        <select name="tipus">
            <option value="Kártya">Kártya</option>
            <option value="Könyv">Könyv</option>
        </select>

        <label>Ritkaság (Tier):</label>
        <select name="ritkasag">
            <option value="D">Tier D (Alapár)</option>
            <option value="C">Tier C (5x szorzó)</option>
            <option value="B">Tier B (10x szorzó)</option>
            <option value="A">Tier A (15x szorzó)</option>
        </select>

        <input type="number" name="ar" placeholder="Alapár (Ft)" required>
        <textarea name="leiras" placeholder="Rövid leírás"></textarea>
        <input type="file" name="kep" accept="image/*" required>
        <button type="submit" name="mentes">Hozzáadás</button>
    </form>
</div>

<div class="grid">
    <?php while($row = $termekek->fetch_assoc()): 
        $ertek = arKalkulator($row['ar'], $row['ritkasag']);
    ?>
        <div class="card">
            <span class="tier-badge">Tier <?php echo $row['ritkasag']; ?></span>
            <img src="uploads/<?php echo $row['kep_utvonal']; ?>" alt="kep">
            <h4><?php echo htmlspecialchars($row['nev']); ?></h4>
            <p><strong>Érték: <?php echo number_format($ertek, 0, ',', ' '); ?> Ft</strong></p>
            <small><?php echo htmlspecialchars($row['tipus']); ?></small>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
