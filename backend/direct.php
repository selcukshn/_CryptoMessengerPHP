<?php
define("95495036eb814b2fb6b1bc61dee0884c5570b46c0b1166c3cd7c6b24922fb3ce", "");

require_once("../init.php");
require_once("../PHPMailer/Send.php");

if (
    @$_SERVER["HTTP_ORIGIN"] != "http://selcuksahintest.de" &&
    @$_SERVER["HTTP_ORIGIN"] != "https://selcuksahintest.de" &&
    @$_SERVER["HTTP_ORIGIN"] != "http://www.selcuksahintest.de" &&
    @$_SERVER["HTTP_ORIGIN"] != "https://www.selcuksahintest.de"
) {
    header("location:" . URL);
    die;
}

use \security\form_control;
use \security\hash;
use \session\session;
use \session\token;
use \ui\write;
use \ui\create;

$now = date("Y-m-d H:i:s", time());
$SendMail = new SendMail("mail.selcuksahintest.de", "TLS", "selcuksahin@selcuksahintest.de", "64666198sst.");


if (isset($_POST["register"])) {
    // if (!token::token_control("register-token", $_POST["register-token"])) {
    //     header("location:" . URL . "/kayit-ol");
    // }
    $NameControl = form_control::alphabetic($_POST["name"]);
    if ($NameControl[0]) {
        exit("Adınız " . $NameControl[1]);
    }
    $Name = $NameControl[1];

    $SurnameControl = form_control::alphabetic($_POST["surname"]);
    if ($SurnameControl[0]) {
        exit("Soyadınız " . $SurnameControl[1]);
    }
    $Surname = $SurnameControl[1];

    $EmailControl = form_control::email($_POST["email"]);
    if ($EmailControl[0]) {
        exit("E-posta adresiniz " . $EmailControl[1]);
    }
    $Email = $EmailControl[1];
    $HaveEmail = $db->select("Email", "user")->where("Email=?", [$Email])->get_column();
    if ($HaveEmail) {
        exit("Bu e-posta adresi ile daha önce hesap oluşturulmuş");
    }

    $PasswordControl = form_control::password($_POST["password"], $_POST["passwordRe"]);
    if ($PasswordControl[0]) {
        exit("Şifreniz " . $PasswordControl[1]);
    }
    $Password = $PasswordControl[1];

    if ($_POST["agreement"] == "on") {
        $Agreement = true;
    } elseif (is_null($_POST["agreement"])) {
        exit("Kullanıcı sözleşmesinin onaylanması gerekiyor");
    } else {
        exit("Kullanıcı sözleşmesinin geçersiz değer içeriyor");
    }

    $result = $db->insert(
        "user",
        "Name,Surname,Email,Password",
        "?,?,?,?",
        [$Name, $Surname, $Email, $Password]
    );
    if ($result) {
        header("Location:../giris-yap?s=ok");
    } else {
        header("Location:../giris-yap?s=no");
    }
}
if (isset($_POST["login"])) {
    if (!token::token_control("login-token", $_POST["login-token"])) {
        header("location:" . URL . "/giris-yap");
    }
    $EmailControl = form_control::email($_POST["email"]);
    if ($EmailControl[0]) {
        exit("E-posta adresiniz " . $EmailControl[1]);
    }
    $Email = $EmailControl[1];

    $PasswordControl = form_control::password($_POST["password"]);
    if ($PasswordControl[0]) {
        exit("Şifreniz " . $PasswordControl[1]);
    }
    $Password = $PasswordControl[1];

    $result = $db->select("Email,Password", "user")->where("Email=? and Password=?", [$Email, $Password])->get_one();

    if ($result) {
        session::create_session("User", hash::encrypt($Email));
        $db->update("user", "LastLoginDate=?", [$now])->where("Email=?", [$Email])->set();
        header("Location:" . URL);
    } else {
        exit("E-posta adresiniz veya şifreniz yanlış");
    }
}
if (isset($_POST["forgotpassword"])) {
    // if (!token::token_control("forgotpassword-token", $_POST["forgotpassword-token"])) {
    //     header("location:" . URL);
    // }
    $EmailControl = form_control::email($_POST["email"]);
    if ($EmailControl[0]) {
        exit("E-posta adresiniz " . $EmailControl[1]);
    }
    $Email = $EmailControl[1];
    $User = $db->select("*", "user")->where("Email=?", [$Email])->get_one();
    if (!$User) {
        exit("E-posta adresi bulunamadı");
    }
    if ($User["PasswordResetToken"] != null) {
        exit("E-posta adresinize zaten bağlantı gönderilmiş.");
    }
    $PasswordResetToken = hash::encrypt(uniqid());
    $result = $db->update("user", "PasswordResetToken=?", [$PasswordResetToken])->where("Email=?", [$Email])->set();

    if ($result) {
        $SendMail->send(
            $Email,
            true,
            "Şifrenizi sıfırlayın",
            "Şifrenizi sıfırlamak için <a href='" . URL . "/sifre-sifirla?user=" . hash::encrypt($User["UserId"]) . "&token=" . hash::encrypt($PasswordResetToken) . "'>tıkla</a>",
            "Şifrenizi yenilemeniz için gereken bağlantı e-posta adresinize gönderildi",
            "Şifrenizi yenilemeniz için gereken bağlantı gönderilirken bir sorun oluştu sayfayı yenileyip tekrar deneyiniz"
        );
    } else {
        exit("Şifre yenileme linki gönderilemedi");
    }
}
if (isset($_POST["resetpassword"])) {
    // if (!token::token_control("resetpassword-token", $_POST["resetpassword-token"])) {
    //     header("location:" . URL);
    // }
    if (!hash::decrypt($_POST["user"]) || !hash::decrypt($_POST["token"])) {
        header("location:" . URL);
    }
    $User = hash::decrypt($_POST["user"]);
    $Token = hash::decrypt($_POST["token"]);

    $User = $db->select("*", "user")->where("UserId=? and PasswordResetToken=?", [$User, $Token])->get_one();
    if (!$User) {
        header("location:" . URL);
    }

    $PasswordControl = form_control::password($_POST["password"], $_POST["passwordre"]);
    if ($PasswordControl[0]) {
        exit("Şifreniz " . $PasswordControl[1]);
    }
    $Password = $PasswordControl[1];

    $result = $db->update("user", "Password=?,PasswordResetToken=?", [$Password, null])->where("UserId=?", [$User["UserId"]])->set();

    if ($result) {
        header("Location:../giris-yap?s=ok");
    } else {
        header("Location:../giris-yap?s=no");
    }
}
if (isset($_POST["generatetoken"])) {
    if (hash::decrypt($_POST["user"])) {
        $UserControl = form_control::email(hash::decrypt($_POST["user"]));
        if ($UserControl[0]) {
            die("Geçersiz istek " . $UserControl[1]);
        }
        $User = $UserControl[1];

        $result = $db->update("user", "Token=?", [hash::sha256(uniqid())])->where("Email=?", [$User])->set();

        if ($result) {
            $db->update("user", "TokenDate=?", [$now])->where("Email=?", [$User])->set();
            header("Location:" . URL);
            die;
        } else {
            header("Location:" . URL . "?s=error");
            die;
        }
    } else {
        header("Location:../logout.php");
        die;
    }
}
if (isset($_POST["sendrequest"])) {
    $FromControl = form_control::alphanumeric($_POST["from"], false, 64, 64);
    if ($FromControl[0]) {
        die("Gönderen kişinin token bilgisi " . $FromControl[1]);
    }
    $From = $FromControl[1];

    $ToControl = form_control::alphanumeric($_POST["to"], false, 64, 64);
    if ($ToControl[0]) {
        die("Gönderilen kişinin token bilgisi " . $ToControl[1]);
    }
    $To = $ToControl[1];

    if ($From == $To) {
        die("Gönderen kişi ile gönderilen kişinin token bilgileri aynı olamaz");
    }

    $HaveFrom = $db->select("Token", "user")->where("Token=?", [$From])->get_one();
    if (!$HaveFrom) {
        die("Gönderen kişinin token bilgisi geçersiz");
    }
    $HaveTo = $db->select("Token", "user")->where("Token=?", [$To])->get_one();
    if (!$HaveTo) {
        die("Gönderilen kişinin token bilgisi geçersiz");
    }

    $RequestedBefore = $db->select("UserFrom,UserTo", "request")->where("UserFrom=? and UserTo=?", [$From, $To])->get_one();
    if ($RequestedBefore) {
        die("Bu kişiye daha önce istek gönderilmiş");
    }

    $AlreadyHaveRoom = $db->select("*", "privateroom")->where("MessagerA=? and MessagerB=?", [$From, $To])->get_one();
    if ($AlreadyHaveRoom) {
        die("Bu kişiyle zaten sohbet başlatılmış");
    }
    $AlreadyHaveRoom = $db->select("*", "privateroom")->where("MessagerA=? and MessagerB=?", [$To, $From])->get_one();
    if ($AlreadyHaveRoom) {
        die("Bu kişiyle zaten sohbet başlatılmış");
    }

    $result = $db->insert("request", "UserFrom,UserTo", "?,?", [$From, $To]);

    if ($result) {
        header("Location:" . URL);
        die;
    } else {
        header("Location:" . URL . "?s=error");
        die;
    }
}
if (isset($_POST["acceptrequest"])) {
    if (hash::decrypt($_POST["requestid"])) {
        $RequestId = hash::decrypt($_POST["requestid"]);

        $FindRequest = $db->select("UserFrom,UserTo", "request")->where("RequestId=?", [$RequestId])->get_one();
        if (!$FindRequest) {
            die("istek bulunamadı lütfen sayfayı yenileyiniz");
        }

        $result = $db->insert("privateroom", "MessagerA,MessagerB", "?,?", [$FindRequest["UserFrom"], $FindRequest["UserTo"]]);
        if ($result) {
            $db->delete("request")->where("UserFrom=? and UserTo=?", [$FindRequest["UserFrom"], $FindRequest["UserTo"]])->set();
            $db->delete("request")->where("UserFrom=? and UserTo=?", [$FindRequest["UserTo"], $FindRequest["UserFrom"]])->set();

            header("Location:" . URL);
            die;
        }
    } else {
        header("Location:../logout.php");
        die;
    }
}
if (isset($_POST["refuserequest"])) {
    if (hash::decrypt($_POST["requestid"])) {
        $RequestId = hash::decrypt($_POST["requestid"]);

        $FindRequest = $db->select("UserFrom,UserTo", "request")->where("RequestId=?", [$RequestId])->get_one();
        if (!$FindRequest) {
            die("istek bulunamadı lütfen sayfayı yenileyiniz");
        }

        $db->delete("request")->where("UserFrom=? and UserTo=?", [$FindRequest["UserFrom"], $FindRequest["UserTo"]])->set();
        $db->delete("request")->where("UserFrom=? and UserTo=?", [$FindRequest["UserTo"], $FindRequest["UserFrom"]])->set();

        header("Location:" . URL);
        die;
    } else {
        header("Location:../logout.php");
        die;
    }
}
if (isset($_POST["tokenresetcontinue"])) {
    $UserTokenControl = form_control::alphanumeric($_POST["usertoken"], false, 64, 64);
    if ($UserTokenControl[0]) {
        echo $UserTokenControl[1];
        die;
    }
    $UserToken = $UserTokenControl[1];

    $DeleteFromRequest = $db->delete("request")->where("UserFrom=? or UserTo=?", [$UserToken, $UserToken])->set();

    $CheckOtherMessagerIsNullA = $db->select("*", "privateroom")->where("MessagerA=?", [$UserToken])->get_all();
    if (count($CheckOtherMessagerIsNullA) > 0) {
        foreach ($CheckOtherMessagerIsNullA as $A) {
            if ($A["MessagerB"] == null) {
                $db->delete("privateroom")->where("RoomId=?", [$A["RoomId"]])->set();
                $db->delete("privatemessage")->where("RoomId=?", [$A["RoomId"]])->set();
            }
        }
    }
    $CheckOtherMessagerIsNullB = $db->select("*", "privateroom")->where("MessagerB=?", [$UserToken])->get_all();
    if (count($CheckOtherMessagerIsNullB) > 0) {
        foreach ($CheckOtherMessagerIsNullB as $B) {
            if ($B["MessagerA"] == null) {
                $db->delete("privateroom")->where("RoomId=?", [$B["RoomId"]])->set();
                $db->delete("privatemessage")->where("RoomId=?", [$B["RoomId"]])->set();
            }
        }
    }

    $RemoveFromPrivateRoomA = $db->update("privateroom", "MessagerA=?", [null])->where("MessagerA=?", [$UserToken])->set();
    $RemoveFromPrivateRoomB = $db->update("privateroom", "MessagerB=?", [null])->where("MessagerB=?", [$UserToken])->set();
    $RemoveFromPrivateMessage = $db->update("privatemessage", "Messager=?,Message=?", [null, null])->where("Messager=?", [$UserToken])->set();

    $UserGenerateNewToken = $db->update("user", "Token=?", [hash::sha256(uniqid())])->where("Token=?", [$UserToken])->set();

    if ($UserGenerateNewToken) {
        header("Location:" . URL);
        die;
    } else {
        header("Location:" . URL . "?s=error");
        die;
    }
}
if (isset($_POST["roomdeletecontinue"])) {
    if (hash::decrypt($_POST["roomid"])) {
        $RoomId = hash::decrypt($_POST["roomid"]);

        $DeletePrivateRoom = $db->delete("privateroom")->where("RoomId=?", [$RoomId])->set();
        $DeletePrivateMessage = $db->delete("privatemessage")->where("RoomId=?", [$RoomId])->set();

        header("Location:" . URL);
        die;
    }
}
