<?php
$title = "Kayıt ol";
require_once("head.php");

use session\token;
?>
<div class="container-lg">
    <div class="bg-light my-5 mx-auto p-4 front-side-form" style=" border-radius: 10px;">
        <h1 class="h5 text-center" style="margin:4rem 0 ;">Kayıt ol</h1>
        <form action="backend/direct.php" method="post">
            <input name="register-token" value="<?php echo token::create_token("register-token") ?>" type="hidden">

            <input name="name" type="text" class="form-control mb-3" placeholder="Adınız">
            <input name="surname" type="text" class="form-control mb-3" placeholder="Soyadınız">
            <input name="email" type="email" class="form-control mb-3" placeholder="E-posta adresiniz">
            <input name="password" type="password" class="form-control mb-3" placeholder="Şifreniz">
            <input name="passwordRe" type="password" class="form-control mb-3" placeholder="Şifreniz tekrar">

            <div class="mb-3 d-flex justify-content-between">
                <div class="form-check d-inline-block">
                    <input name="agreement" class="form-check-input" type="checkbox" id="agreement">
                    <label class="form-check-label" for="agreement" style="font-size: 0.9rem;">
                        Kullanıcı sözleşmesini okudum ve kabul ediyorum
                    </label>
                </div>
            </div>
            <button name="register" type="submit" class="btn btn-danger w-100 py-3 mb-3" style="border-radius:100px ;">Kayıt ol</button>
            <div class="text-muted text-right">
                <span>Hesabın var mı?</span>
                <a href="giris-yap" class="" style="border-radius:100px ;">Giriş yap</a>
            </div>
        </form>
    </div>
</div>
<?php
require_once("foot.php");
?>