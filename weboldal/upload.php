

<?php
$conn = new mysqli("localhost", "root", "", "katalogus_db");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nev = $_POST['nev'];
    $leiras = $_POST['leiras'];
    
  
    $target_dir = "uploads/";
    $file_name = time() . "_" . basename($_FILES["kep"]["name"]);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["kep"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO termekek (nev, leiras, kep_utvonal) VALUES ('$nev', '$leiras', '$file_name')";
        if ($conn->query($sql)) {
            header("Location: index.php?success=1");
        }
    }
}
?>

<div class="card" 
     data-type="<?php echo $row['tipus']; ?>" 
     data-price="<?php echo $row['ar']; ?>">

    <p>Ár: <?php echo $row['ar']; ?> Ft</p>
</div>