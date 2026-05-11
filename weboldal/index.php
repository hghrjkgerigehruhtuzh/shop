<?php
$host = "localhost"; $user = "root"; $pass = ""; $db = "shop_db";
$conn = new mysqli($host, $user, $pass, $db);

if (isset($_POST['mentes'])) {
    $nev = $_POST['nev'];
    $ar = $_POST['ar'];
    $leiras = $_POST['leiras'];
    $tipus = $_POST['tipus']; 
    
    $fajlNev = time() . "_" . $_FILES['kep']['name']; 
    $cel = "kepek/" . $fajlNev;

    if (move_uploaded_file($_FILES['kep']['tmp_name'], $cel)) {
        $sql = "INSERT INTO termekek (nev, ar, kep_utvonal, leiras, tipus) VALUES ('$nev', '$ar', '$fajlNev', '$leiras', '$tipus')";
        $conn->query($sql);
    }
}

$termekek = $conn->query("SELECT * FROM termekek ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Katalógus</title>
    <link rel="stylesheet" href="main.css">
    <link rel="icon" type="image/png" href="favicon/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/svg+xml" href="favicon/favicon.svg" />
<link rel="shortcut icon" href="favicon/favicon.ico" />
<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png" />
<link rel="manifest" href="favicon/site.webmanifest" />
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

    <h1>Gyűjtői Katalógus</h1>

    <div class="form-container">
        <h3>Új elem hozzáadása</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="nev" placeholder="Termék neve" required>
            <input type="number" name="ar" placeholder="Ár (Ft)" required>
            
        
            <select name="tipus" required style="width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #ccc;">
                <option value="Kártya">Kártya</option>
                <option value="Könyv">Könyv</option>
                <option value="Könyv">Képregény</option>
                <option value="Könyv">Figura</option>
            </select>

            <textarea name="leiras" placeholder="Rövid leírás"></textarea>
            <input type="file" name="kep" accept="image/*" required>
            <button type="submit" name="mentes">Hozzáadás</button>
        </form>
    </div>

    <div class="grid">
        <?php while($row = $termekek->fetch_assoc()): ?>
            
            <div class="card" data-type="<?php echo $row['tipus']; ?>" data-price="<?php echo $row['ar']; ?>">
                <img src="kepek/<?php echo $row['kep_utvonal']; ?>" alt="kep">
                <div class="card-content">
                    <h4><?php echo $row['nev']; ?></h4>
                    <p><strong><?php echo number_format($row['ar'], 0, ',', ' '); ?> Ft</strong></p>
                    <small><?php echo $row['tipus']; ?> - <?php echo $row['leiras']; ?></small>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

</body>
</html>
