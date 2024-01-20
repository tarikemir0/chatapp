<?php
session_start();
include("php/config.php");

// Oturum kontrolü
if (!isset($_SESSION['unique_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['unique_id'];

// Kullanıcı bilgilerini çek
$sql = "SELECT * FROM users WHERE unique_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fname = $row['fname'];
    $lname = $row['lname'];
    $email = $row['email'];
    $img = $row['img'];
    $status = $row['status'];
    $seed = $row['seed'];
    // Diğer kullanıcı bilgilerini de çekebilirsiniz
} else {
    echo "Kullanıcı bulunamadı!";
    exit;
}

// Profil güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_fname = $_POST['fname'];
    $new_lname = $_POST['lname'];
    $new_email = $_POST['email'];
    $new_img = $_POST['img'];
 


    // Güncelleme SQL sorgusu
    $update_sql = "UPDATE users SET fname = '$new_fname', lname = '$new_lname', email = '$new_email', img = '$new_img' WHERE unique_id = '$user_id'";
    $update_result = $conn->query($update_sql);

    if ($update_result) {
        echo "Profil güncellendi!";
    } else {
        echo "Güncelleme hatası: " . $conn->error;
    }
}

// Profil silme işlemi
if (isset($_POST['delete'])) {
    $delete_sql = "DELETE FROM users WHERE unique_id = '$user_id'";
    $delete_result = $conn->query($delete_sql);

    if ($delete_result) {
        session_destroy(); // Kullanıcının oturumunu sonlandır
        header("Location: login.php");
        exit();
    } else {
        echo "Silme hatası: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profil.css">
    <title>Kullanıcı Profil Ayarları</title>
</head>
<body>

<h1>Kullanıcı Profil Ayarları</h1>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="fname">Ad:</label>
    <input type="text" id="fname" name="fname" value="<?php echo $fname; ?>" required>

    <label for="lname">Soyad:</label>
    <input type="text" id="lname" name="lname" value="<?php echo $lname; ?>" required>

    <label for="email">E-posta:</label>
    <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>

    <label for="img">Profil Fotoğrafı:</label>
    <input type="file" id="img" name="img" value="<?php echo $img; ?>" accept="image/*" >
    <button type="submit">Güncelle</button>
</form>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <button type="submit" name="delete" onclick="return confirm('Profilinizi silmek istediğinize emin misiniz?')">Profil Sil</button>
</form>

<a href="users.php">Çıkış</a>

</body>
</html>
