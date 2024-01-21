<?php
include_once "config.php"; // Veritabanı bağlantısı için config dosyasını ekleyin

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["token"])) {
    $token = mysqli_real_escape_string($conn, $_GET["token"]);

    $user_query = mysqli_query($conn, "SELECT * FROM users WHERE reset_token = '$token' LIMIT 1");

    if (mysqli_num_rows($user_query) > 0) {
        // Kullanıcı doğrulandı, yeni şifre girme formunu göster
        echo '<form action="reset-password.php" method="post">
                <label for="new_password">Yeni Şifreniz:</label>
                <input type="password" name="new_password" required>
                <input type="hidden" name="token" value="' . $token . '">
                <input type="submit" value="Şifreyi Güncelle">
              </form>';
    } else {
        echo "Geçersiz veya süresi dolmuş bağlantı.";
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_password"], $_POST["token"])) {
    $new_password = mysqli_real_escape_string($conn, $_POST["new_password"]);
    $token = mysqli_real_escape_string($conn, $_POST["token"]);

    // Şifre sıfırlama bağlantısını geçersiz kıl
    mysqli_query($conn, "UPDATE users SET reset_token = NULL WHERE reset_token = '$token'");

    // Yeni şifreyi veritabanına kaydet (bu kısmı güvenlik önlemleri ile geliştirmeniz önerilir)
    mysqli_query($conn, "UPDATE users SET password = '$new_password' WHERE reset_token = '$token'");

    echo "Şifreniz başarıyla güncellendi. Yeni şifrenizle giriş yapabilirsiniz.";
}
?>
