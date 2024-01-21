<?php
include_once dirname(__DIR__) . "/php/config.php"; // Veritabanı bağlantısı için config dosyasını ekleyin

// Veritabanına bağlanma
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Bağlantıyı kontrol et
if (!$conn) {
    die("Veritabanı bağlantısı başarısız: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["token"])) {
    $token = mysqli_real_escape_string($conn, $_GET["token"]);

    $user_query = mysqli_query($conn, "SELECT * FROM users WHERE reset_token = '$token' LIMIT 1");

    if (mysqli_num_rows($user_query) > 0) {
        // Kullanıcı doğrulandı, yeni şifre girme formunu göster
        $user = mysqli_fetch_assoc($user_query);  // Kullanıcı verilerini al

        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Yeni Şifre Oluştur</title>
        </head>
        <body>
            <form action="reset-password.php" method="post">
                <label for="new_password">Yeni Şifreniz:</label>
                <input type="password" name="new_password" required>

                <label for="confirm_password">Şifrenizi Tekrar Girin:</label>
                <input type="password" name="confirm_password" required>

                <input type="hidden" name="token" value="<?= $token ?>">
                <input type="hidden" name="user_id" value="<?= $user['unique_id'] ?>">
                <input type="submit" value="Şifreyi Güncelle">
            </form>
        </body>
        </html>
        <?php
    } else {
        echo "Geçersiz veya süresi dolmuş bağlantı.";
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_password"], $_POST["token"], $_POST["confirm_password"])) {
    $new_password = mysqli_real_escape_string($conn, $_POST["new_password"]);
    $confirm_password = mysqli_real_escape_string($conn, $_POST["confirm_password"]);
    $token = mysqli_real_escape_string($conn, $_POST["token"]);
    $user_id = mysqli_real_escape_string($conn, $_POST["user_id"]);

    if ($new_password === $confirm_password) {
        // Şifreler eşleşiyorsa devam et
        // Şifre sıfırlama bağlantısını geçersiz kıl
        mysqli_query($conn, "UPDATE users SET reset_token = NULL WHERE reset_token = '$token'");

        // Yeni şifreyi güvenli bir şekilde veritabanına kaydet
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE users SET password = '$hashed_password' WHERE unique_id = '$user_id'");

        echo "Şifreniz başarıyla güncellendi. Yeni şifrenizle giriş yapabilirsiniz.";
    } else {
        echo "Şifreler eşleşmiyor. Lütfen aynı şifreyi girin.";
    }
}

// Veritabanı bağlantısını kapat
mysqli_close($conn);
?>
