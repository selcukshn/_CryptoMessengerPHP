<?php
require("PHPMailer/Exception.php");
require("PHPMailer/PHPMailer.php");
require("PHPMailer/SMTP.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="" method="POST">
        <input name="username" type="text" placeholder="Username">
        <input name="email" type="text" placeholder="Email">
        <input name="password" type="text" placeholder="Password">
        <button name="register" type="submit">Send</button>
    </form>
</body>

</html>


<?php
if (isset($_POST["register"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];


    //! localde çalışmaz
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = "mail.oyunrehber.net";
        $mail->SMTPSecure = "tls";
        $mail->Port = 587; // TLS => port:587 | SSL => port:465
        $mail->SMTPAuth = true;
        $mail->Username = "mail hesabı kullanıcı adı";
        $mail->Password = "mail hesabı şifre";

        //Recipients
        $mail->setFrom("Gönderen adres", "Gönderen isim"); // Gönderen
        $mail->addAddress($email); // Gönderilen
        $mail->addReplyTo("mail@oyunrehber.net", "Yanıtla");

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = "Hesap onay"; // Konu
        $mail->msgHTML("ssl deneme <a href='http://www.selcuksahintest.de'>tıkla</a>"); // Mesaj içeriği
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients'; sunucu html desteklemiyorsa burayı kabul eder
        // $mail->CharSet = "UTF-8";
        // $mail->SMTPOptions = [
        //     "ssl" => [
        //         "verify_peer" => false,
        //         "verify_peer_name" => false,
        //         "allow_self_signed" => true
        //     ]
        // ];

        $mail->send();
        echo ("E-posta gönderildi: {$mail->ErrorInfo}");
    } catch (Exception $e) {
        echo ("E-posta gönderilirken bir sorun oluştu: {$mail->ErrorInfo}");
    }
}
?>