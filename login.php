<?php
$title = "Giriş yap";
require_once("head.php");

use session\token;
?>
<div class="container-lg">
    <div class="bg-light my-5 mx-auto p-4 front-side-form" style="border-radius: 10px;">
        <h1 class="h5 text-center" style="margin:4rem 0 ;">Giriş yap</h1>
        <form action="backend/direct.php" method="post">
            <input name="login-token" value="<?php echo token::create_token("login-token") ?>" type="hidden">
            <input name="email" type="email" class="form-control mb-3" placeholder="E-posta">
            <input name="password" type="password" class="form-control mb-3" placeholder="Şifre">

            <div class="mb-3 d-flex justify-content-between">
                <div class="form-check d-inline-block">
                    <input name="rememberme" class="form-check-input" type="checkbox" id="rememberme">
                    <label class="form-check-label" for="rememberme" style="font-size: 0.9rem;">
                        Beni hatırla
                    </label>
                </div>
                <span><a href="sifremi-unuttum">Şifremi unuttum?</a></span>
            </div>
            <button name="login" type="submit" class="btn btn-success w-100 py-3 mb-3" style="border-radius:100px ;">Giriş yap</button>
            <div class="text-muted text-right">
                <span>Hesabın yok mu?</span>
                <a href="kayit-ol" class="" style="border-radius:100px ;">Kayıt ol</a>
            </div>
        </form>
    </div>
</div>
<?php
require_once("foot.php");
?>