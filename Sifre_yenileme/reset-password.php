<?php
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Veritabanı bağlantısını yapın
    $conn = new mysqli("localhost", "root", "", "chatapp");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $verificationCode = mysqli_real_escape_string($conn, $_POST["verificationCode"]);
    $newPassword = mysqli_real_escape_string($conn, $_POST["newPassword"]);

    // Doğrulama kodunu kontrol et
    $user_query = mysqli_query($conn, "SELECT * FROM users WHERE reset_token = '$verificationCode' LIMIT 1");

    if (mysqli_num_rows($user_query) > 0) {
        $user = mysqli_fetch_assoc($user_query);

        // Yeni şifreyi MD5 ile şifreleyerek veritabanına kaydet
        $hashedPassword = md5($newPassword);
        mysqli_query($conn, "UPDATE users SET password = '$hashedPassword', reset_token = NULL WHERE unique_id= {$user['unique_id']}");

        $message = "<div>Şifreniz başarıyla güncellendi.</div>";
    } else {
        $message = "<div>Geçersiz doğrulama kodu.</div>";
    }

    // Veritabanı bağlantısını kapat
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifre Sıfırlama</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

.container {
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

label {
    margin-bottom: 10px;
}

input {
    padding: 10px;
    margin-bottom: 15px;
    width: 100%;
    box-sizing: border-box;
}

button {
    background-color: #3498db;
    color: #fff;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #2980b9;
}
</style>
<body>
    <div class="container">
        <?php echo $message; ?>

        <form action="" method="post">
            <label for="verificationCode">Doğrulama Kodu:</label>
            <input type="text" name="verificationCode" required>

            <label for="newPassword">Yeni Şifre:</label>
            <input type="password" name="newPassword" required>

            <button type="submit">Şifreyi Değiştir</button>
        </form>
    </div>
</body>
</html>
