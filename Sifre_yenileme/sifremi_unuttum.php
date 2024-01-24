<?php
$conn = new mysqli("localhost", "root", "", "chatapp");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\chatapp\vendor\autoload.php'; // Composer autoload dosyası
require 'C:\xampp\htdocs\chatapp\vendor\phpmailer\phpmailer\src\Exception.php';
require 'C:\xampp\htdocs\chatapp\vendor\phpmailer\phpmailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\chatapp\vendor\phpmailer\phpmailer\src\SMTP.php';

require  'C:\xampp\htdocs\CHatapp\vendor\autoload.php';
 

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["email"])) {
        $email = mysqli_real_escape_string($conn, $_POST["email"]);

        $user_query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' LIMIT 1");

        if (mysqli_num_rows($user_query) > 0) {
            $user = mysqli_fetch_assoc($user_query);

            $reset_token = md5(uniqid(rand(), true));

            // Şifre sıfırlama bağlantısını veritabanına kaydet
            mysqli_query($conn, "UPDATE users SET reset_token = '$reset_token' WHERE email = '$email'");

            // Kullanıcıya e-posta ile şifre sıfırlama bağlantısı gönder
            $reset_link = "http://chatapp/reset-password.php?reset_token=$reset_token";
            $to = $user['email']; // Kullanıcının e-posta adresi

            $mail = new PHPMailer(true); // Hata ayıklama modu etkinleştirildi
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';  // E-posta sunucu adresi
                $mail->SMTPAuth   = true;                   // SMTP kimlik doğrulama kullan
                $mail->Username   = 'rastgel05@gmail.com';        // SMTP kullanıcı adı
                $mail->Password   = 'wdnc gjuv cgqk nolw';        // SMTP şifre
                $mail->SMTPSecure = 'tls';                   // Güvenli bağlantı türü: tls veya ssl
                $mail->Port       = 587;                     // SMTP port numarası
                $mail->CharSet    = 'UTF-8';                 // Türkçe karakter desteği ekledik
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

                // Set SMTPAutoTLS to false
                $mail->SMTPAutoTLS = false;

                //Recipients
                $mail->setFrom('rastgel05@gmail.com', 'Ondokuz Mayıs Üniversitesi'); // Gönderen e-posta ve ismi
                $mail->addAddress($to, $user['fname']);    // Alıcı e-posta ve ismi

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Şifre Sıfırlama';
                $mail->Body    = "Merhaba {$user['fname']},<br><br>Şifrenizi sıfırlamak için aşağıdaki bağlantıya tıklayın:<br>$reset_link";

                // Debugging (optional)
                $mail->SMTPDebug = 0; // 0: kapalı, 1: bilgiler, 2: hata ayrıntıları

                $mail->send();
                $message = "<div>Şifre sıfırlama bağlantısı e-posta adresinize gönderildi.</div>";
            } catch (Exception $e) {
                $message = "<div>E-posta gönderme hatası: {$mail->ErrorInfo}</div>";
            }
        } else {
            $message = "<div>Bu e-posta adresi ile kayıtlı bir kullanıcı bulunamadı.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifre Sıfırlama</title>
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
    <div>
        <?php echo $message; ?>
    </div>

    <form method="post" action="">
        <label for="email">E-posta:</label>
        <input type="email" name="email" id="email" required>
        <button type="submit">Şifre Sıfırlama Bağlantısı Gönder</button>
        <a href="reset-password.php">ŞİFRE YENİLEME YERİNE GİT</a>
    </form>
</body>
</html>
