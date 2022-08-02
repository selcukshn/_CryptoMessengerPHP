<?php
$title = "Şifremi unuttum";
require_once("head.php");

use session\token;
?>
<div class="container-lg">
    <div class="bg-light my-5 mx-auto p-4 front-side-form" style=" border-radius: 10px;">
        <h1 class="h5 text-center" style="margin:4rem 0 ;">Şifremi unuttum</h1>
        <form action="backend/direct.php" method="post">
            <input name="forgotpassword-token" value="<?php echo token::create_token("forgotpassword-token") ?>" type="hidden">

            <input name="email" type="email" class="form-control mb-3" placeholder="E-posta">

            <button name="forgotpassword" type="submit" class="btn btn-success w-100 py-3 mb-3" style="border-radius:100px ;">Gönder</button>
            <div class="text-muted text-right">
                <a href="giris-yap" class="" style="border-radius:100px ;">Giriş yap</a>
            </div>
        </form>
    </div>
</div>
<?php
require_once("foot.php");
?>