<?php
$title = "Şifremi sıfırla";
require_once("head.php");

use session\token;
use security\form_control;
use security\hash;

if (!isset($_GET["user"]) || !isset($_GET["token"])) {
    header("location:" . URL);
    die;
}
if (!hash::decrypt(str_replace(" ", "+", $_GET["user"])) || !hash::decrypt(str_replace(" ", "+", $_GET["token"]))) {
    header("location:" . URL);
    die;
}
$User = $db->select("*", "user")->where("UserId=?", [hash::decrypt(str_replace(" ", "+", $_GET["user"]))])->get_one();
if (!$User["PasswordResetToken"] || $User["PasswordResetToken"] != hash::decrypt(str_replace(" ", "+", $_GET["token"]))) {
    header("location:" . URL);
    die;
}
?>
<div class="container-lg">
    <div class="bg-light my-5 mx-auto p-4 front-side-form" style=" border-radius: 10px;">
        <h1 class="h5 text-center" style="margin:4rem 0 ;">Şifremi sıfırla</h1>
        <form action="backend/direct.php" method="post">
            <input name="resetpassword-token" value="<?php echo token::create_token("resetpassword-token") ?>" type="hidden">
            <input name="user" value="<?php echo str_replace(" ", "+", $_GET["user"]) ?>" type="hidden">
            <input name="token" value="<?php echo str_replace(" ", "+", $_GET["token"]) ?>" type="hidden">

            <input name="password" type="password" class="form-control mb-3" placeholder="Yeni şifreniz">
            <input name="passwordre" type="password" class="form-control mb-3" placeholder="Yeni şifreniz tekrar">

            <button name="resetpassword" type="submit" class="btn btn-success w-100 py-3 mb-3" style="border-radius:100px ;">Gönder</button>
            <div class="text-muted text-right">
                <a href="giris-yap" class="" style="border-radius:100px ;">Giriş yap</a>
            </div>
        </form>
    </div>
</div>
<?php
require_once("foot.php");
?>