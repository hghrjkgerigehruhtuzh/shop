<?php
$host = "localhost"; $user = "root"; $pass = ""; $db = "shop_db";
$conn = new mysqli($host, $user, $pass, $db);


if (isset($_POST['mentes'])) {
    $nev = $_POST['nev'];
    $ar = $_POST['ar'];
    $leiras = $_POST['leiras'];
    
    $fajlNev = time() . "_" . $_FILES['kep']['name']; 
    $cel = "kepek/" . $fajlNev;

    if (move_uploaded_file($_FILES['kep']['tmp_name'], $cel)) {
        $sql = "INSERT INTO termekek (nev, ar, kep_utvonal, leiras) VALUES ('$nev', '$ar', '$fajlNev', '$leiras')";
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
            <textarea name="leiras" placeholder="Rövid leírás"></textarea>
            <input type="file" name="kep" accept="image/*" required>
            <button type="submit" name="mentes">Hozzáadás</button>
        </form>
    </div>

    <div class="grid">
        <?php while($row = $termekek->fetch_assoc()): ?>
            <div class="card">
                <img src="kepek/<?php echo $row['kep_utvonal']; ?>" alt="kep">
                <h4><?php echo $row['nev']; ?></h4>
                <p><strong><?php echo $row['ar']; ?> Ft</strong></p>
                <small><?php echo $row['leiras']; ?></small>
            </div>
        <?php endwhile; ?>
    </div>

</body>
</html>
