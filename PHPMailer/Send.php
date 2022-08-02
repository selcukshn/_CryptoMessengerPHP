<?php
require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SendMail
{
    private $Host;
    private $SmtpSecure;
    private $Username;
    private $Password;

    function __construct(string $Host, string $Secure, string $Username, string $Password)
    {
        $this->Host = $Host;
        $this->SmtpSecure = $Secure;
        $this->Username = $Username;
        $this->Password = $Password;
    }

    function send($to, $reply = false, $subject, $message, $ifsuccessmsg, $iferrormsg)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->SMTPSecure = $this->SmtpSecure;
            $mail->SMTPAuth = true;
            $mail->Host = $this->Host;
            if ($this->SmtpSecure == "TLS") { // TLS => port:587 | SSL => port:465
                $mail->Port =  587;
            } elseif ($this->SmtpSecure == "SSL") {
                $mail->Port =  465;
            }
            $mail->Username = $this->Username;
            $mail->Password =  $this->Password;

            $mail->setFrom($this->Username, 'Crypto Messenger'); // Gönderen
            $mail->addAddress($to); // Gönderilen
            if ($reply) {
                $mail->addReplyTo($this->Username, "Yanıtla");
            }

            $mail->isHTML(true);
            $mail->Subject = $subject; // Konu
            $mail->msgHTML($message); // Mesaj içeriği

            $mail->CharSet = "UTF-8";
            $mail->send();
            echo ($ifsuccessmsg);
        } catch (Exception $e) {
            echo ($iferrormsg . $mail->ErrorInfo);
        }
    }
}
