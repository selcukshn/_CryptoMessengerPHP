<?php
require_once("head.php");

use session\session;
use security\hash;
use ui\write;

if (!session::have_session("User")) {
    header("location:giris-yap");
    die;
} else {
    $sessionIsCorrect = $db->select("Email", "user")->where("Email=?", [hash::decrypt(session::get_session("User"))])->get_one();
    if (!$sessionIsCorrect) {
        header("location:cikis-yap");
        die;
    }
}
$User = $db->select("Name,Surname,Email,Token", "user")->where("Email=?", [hash::decrypt(session::get_session("User"))])->get_one();
?>
<style>
    .dropdown-toggle::after {
        content: none;
    }
</style>
<div>
    <header class="bg-white border-bottom">
        <div class="container-fluid">
            <div class="row align-items-center border-bottom">
                <div class="col">
                    <span>Crypto Messenger</span>
                </div>
                <div class="col text-right p-2">
                    <small class="text-muted"><?php echo $User["Name"] . " " . $User["Surname"] ?></small>
                    <div class="d-inline-block">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-info dropdown-toggle ml-2" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-bell"></i>
                                <!-- <span class="bg-danger badge-custom"></span> -->
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                <div class="dropdown-item">

                                </div>
                            </div>
                        </div>
                        <a href="cikis-yap" class="btn btn-outline-danger ml-2">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row g-0 py-2">
                <?php
                if ($User["Token"] == null) {
                ?>
                    <div class="col-12 p-2 token-require text-white">
                        <span class="mr-2" style="font-size:0.9rem ;">Mesajlaşmaya başlamadan önce token oluşturmanız gerekmektedir</span>
                        <form action="backend/direct.php" method="post" class="d-inline-block">
                            <input name="user" type="hidden" value="<?php echo hash::encrypt($User["Email"]) ?>">
                            <button name="generatetoken" type="submit" class="btn btn-outline-light btn-sm">hemen oluştur</button>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="col-12 col-xl-6 d-flex align-items-center mb-2 mb-xl-0">
                        <div>
                            <small class="mr-2">Token</small>
                            <small id="usertoken" class="text-muted mr-2 text-break"><?php echo $User["Token"] ?></small>
                            <button id="tokencopy" type="button" class="btn btn-outline-secondary btn-sm custom-tooltip mr-1">
                                <i class="fa-solid fa-copy"></i>
                                <span class="tooltiptext">Kopyala</span>
                            </button>
                            <!-- Token reset -->
                            <button id="tokenreset" type="button" class="btn btn-outline-danger btn-sm custom-tooltip" data-toggle="modal" data-target="#TokenResetModal">
                                <i class="fa-solid fa-rotate"></i>
                                <span class="tooltiptext">Token yenile</span>
                            </button>
                            <!-- /Token reset -->
                        </div>
                    </div>
                    <div class="col-12 col-xl-6 d-flex align-items-center">
                        <form action="backend/direct.php" method="post" class="w-100">
                            <div class="input-group ">
                                <input name="from" type="hidden" value="<?php echo $User["Token"] ?>">
                                <input name="to" type="text" class="form-control form-control-sm " placeholder="Mesajlaşma isteği gönder">
                                <div class="input-group-append">
                                    <button name="sendrequest" type="submit" class="btn btn-success btn-sm">Gönder</button>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>
    </header>

    <div class="row overflow-hidden shadow m-0">
        <!-- Users box-->
        <div class="col-3 px-0 bg-white">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item " role="presentation">
                    <a class="nav-link text-dark rounded-0 active" id="message-tab" data-toggle="tab" href="#message" role="tab" aria-controls="message" aria-selected="true">Mesajlar</a>
                </li>
                <?php $requests = $db->select("*", "request")->where("UserTo=?", [$User["Token"]])->get_all(); ?>
                <li class="nav-item " role="presentation">
                    <a class="nav-link text-dark rounded-0 " id="request-tab" data-toggle="tab" href="#request" role="tab" aria-controls="request" aria-selected="false">
                        İstekler
                        <?php if ($requests) {
                        ?>
                            <span class="badge badge-danger"><?php echo count($requests) ?></span>
                        <?php } else { ?>
                            <span class="badge badge-danger">0</span>
                        <?php } ?>
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">

                <!-- Message tab -->
                <div class="tab-pane fade show active" id="message" role="tabpanel" aria-labelledby="message-tab">
                    <div class="messages-box">
                        <div class="list-group rounded-0">
                            <?php
                            $messagerooms = $db->select("*", "privateroom")->where("MessagerA=? or MessagerB=?", [$User["Token"], $User["Token"]])->get_all();
                            if ($messagerooms) {
                                foreach ($messagerooms as $messageroom) {
                                    if ($messageroom["MessagerA"] == $User["Token"]) {
                                        $messager = $db->select("Name,Surname,LastLoginDate", "user")->where("Token=?", [$messageroom["MessagerB"]])->get_one();
                                    } else {
                                        $messager = $db->select("Name,Surname,LastLoginDate", "user")->where("Token=?", [$messageroom["MessagerA"]])->get_one();
                                    } ?>
                                    <a room-id="<?php echo hash::encrypt($messageroom["RoomId"]) ?>" href="#" class="list-group-item list-group-item-action rounded-0 private-room">
                                        <div class="media"><img src="images/user-default.png" alt="user" width="50" class="rounded-circle">
                                            <div class="media-body">
                                                <?php
                                                if ($messager["Name"] == null) { ?>

                                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                                        <p class="font-italic mb-0 text-danger">Silindi</p>
                                                        <!-- Room delete -->
                                                        <button type="button" class="btn btn-outline-danger btn-sm custom-tooltip roomdelete" data-toggle="modal" data-target="#roomdeletemodal">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                        <!-- Room delete -->
                                                    </div>
                                                    <small class="font-italic mb-0 text-muted">
                                                        Son görülme -
                                                    </small>

                                                <?php } else { ?>

                                                    <div class="mb-1">
                                                        <h6 class="mb-0">
                                                            <?php echo $messager["Name"] . " " . $messager["Surname"] ?>
                                                        </h6>
                                                    </div>
                                                    <small class="font-italic mb-0 text-muted">
                                                        Son görülme <?php echo write::calc_elapsed_time(time(), strtotime($messager["LastLoginDate"])) ?>
                                                    </small>

                                                <?php }
                                                ?>

                                            </div>
                                        </div>
                                    </a>
                            <?php }
                            } ?>
                        </div>
                    </div>
                </div>
                <!-- /Message tab -->

                <!-- Request tab -->
                <div class="tab-pane fade " id="request" role="tabpanel" aria-labelledby="request-tab">
                    <?php
                    if ($requests) {
                        foreach ($requests as $request) {
                            $requester = $db->select("Name,Surname", "user")->where("Token=?", [$request["UserFrom"]])->get_one();
                    ?>
                            <div class="list-group-item list-group-item-action list-group-item-light rounded-0  text-dark p-3">
                                <div><?php echo $requester["Name"] . " " . $requester["Surname"] ?></div>
                                <div class="d-flex justify-content-between align-items-end">
                                    <small class="text-muted"><?php echo write::date_to_turkish($request["RequestDate"]) ?></small>
                                    <form action="backend/direct.php" method="post" class="text-right d-inline-block">
                                        <input name="requestid" type="hidden" value="<?php echo hash::encrypt($request["RequestId"]) ?>">
                                        <button name="acceptrequest" type="submit" class="btn btn-primary btn-sm ">Kabul et</button>
                                        <button name="refuserequest" type="submit" class="btn btn-secondary btn-sm ">Reddet</button>
                                    </form>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
                <!-- /Request tab -->

            </div>
        </div>
        <!-- Chat Box-->
        <div class="col-9 px-0">

            <div class="px-4 py-5 chat-box bg-white200">

            </div>

            <form id="sendmessage" method="post" class="bg-light">
                <input name="roomid" type="hidden" value="" disabled>
                <div class="input-group">
                    <input name="message" type="text" placeholder="Mesajını yaz" aria-describedby="button-addon2" class="form-control rounded-0 border-0 py-4 bg-light" disabled>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-link bg-primary" disabled>
                            <i class="fa fa-paper-plane text-white"></i>
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
<?php
require_once("foot.php");
require_once("modals.php");

?>
<script src="js/site.js"></script>
</body>

</html>