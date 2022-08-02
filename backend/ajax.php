<?php
define("95495036eb814b2fb6b1bc61dee0884c5570b46c0b1166c3cd7c6b24922fb3ce", "");

require_once("../init.php");

if (
    @$_SERVER["HTTP_ORIGIN"] != "http://selcuksahintest.de" &&
    @$_SERVER["HTTP_ORIGIN"] != "https://selcuksahintest.de" &&
    @$_SERVER["HTTP_ORIGIN"] != "http://www.selcuksahintest.de" &&
    @$_SERVER["HTTP_ORIGIN"] != "https://www.selcuksahintest.de"
) {
    header("location:" . URL);
    die;
}

if (empty($_SERVER["HTTP_X_REQUESTED_WITH"]) or strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) != "xmlhttprequest") {
    header("location:" . URL);
    die;
}

use security\form_control;
use security\hash;
use session\session;
use session\token;
use ui\write;
use ui\create;

$now = date("Y-m-d H:i:s", time());

$operation = "";
if (isset($_GET["operation"])) {
    $operationControl = form_control::request_get($_GET["operation"]);
    if ($operationControl[0]) {
        header("location:" . URL);
        die;
    }
    $operation = $operationControl[1];
    switch ($operation) {
        case "getroom":
            if (hash::decrypt($_POST["RoomId"])) {

                $RoomId = hash::decrypt($_POST["RoomId"]);

                $ViewerControl = form_control::alphanumeric($_POST["Viewer"], false, 64, 64);
                if ($ViewerControl[0]) {
                    header("location:" . URL);
                    die;
                }
                $Viewer = $ViewerControl[1];

                $Messages = $db->select("*", "privatemessage")->where("RoomId=?", [$RoomId])->get_all();
                $SendData = "";
                if ($Messages) {
                    foreach ($Messages as $Message) {
                        if ($Viewer == $Message["Messager"]) {
                            $SendData .= '<div class="media w-50 ml-auto mb-3">
                        <div class="media-body">
                            <div class="bg-primary shadow-sm rounded py-2 px-3 mb-2">
                                <p class="media-message text-small mb-0 text-white">' . hash::decrypt($Message["Message"]) . '</p>
                            </div>
                            <p class="media-date small">' . write::calc_elapsed_time(time(), strtotime($Message["MessageDate"])) . '</p>
                        </div>
                    </div>';
                        } else {
                            if ($Message["Messager"] == null) {
                                $SendData .= '
                                <div class="media w-50 mb-3">
                                    <div class="media-body">
                                        <div class="bg-light shadow-sm rounded py-2 px-3 mb-2">
                                            <p class="font-italic text-decoration- media-message text-small mb-0 text-danger">Silindi</p>
                                        </div>
                                        <p class="media-date small">-</p>
                                    </div>
                                </div>';
                            } else {
                                $SendData .= '
                                <div class="media w-50 mb-3">
                                    <div class="media-body">
                                        <div class="bg-light shadow-sm rounded py-2 px-3 mb-2">
                                            <p class="media-message text-small mb-0 text-muted">' . hash::decrypt($Message["Message"]) . '</p>
                                        </div>
                                        <p class="media-date small">' . write::calc_elapsed_time(time(), strtotime($Message["MessageDate"])) . '</p>
                                    </div>
                                </div>';
                            }
                        }
                        //     $SendData .= '<div class="media w-50 mb-3">
                        //     <div class="media-body">
                        //         <div class="bg-light shadow-sm rounded py-2 px-3 mb-2">
                        //             <p class="text-small mb-0 text-muted">' . hash::decrypt($Message["Message"]) . '</p>
                        //         </div>
                        //         <p class="small text-muted">' . write::calc_elapsed_time(time(), strtotime($Message["MessageDate"])) . '</p>
                        //     </div>
                        // </div>';
                    }
                } else {
                    $SendData = '<h5 id="nomessage" class="text-center">Bu kişi ile mesajlaşmanız bulunmamaktadır.</h5>';
                }
                exit(json_encode($SendData));
            } else {
                header("location:" . URL);
                die;
            }
            break;
        case "sendmessage":
            if (hash::decrypt($_POST["roomid"])) {
                $RoomId = hash::decrypt($_POST["roomid"]);

                $SenderControl = form_control::alphanumeric($_POST["sender"], false, 64, 64);
                if ($SenderControl[0]) {
                    exit(json_encode(create::izitoast_warning("Hata", "Mesaj gönderen kişinin " . $SenderControl[1])));
                    die;
                }
                $Sender = $SenderControl[1];

                $MessageControl = form_control::long_text($_POST["message"], 1000);
                if ($MessageControl[0]) {
                    exit(json_encode(create::izitoast_warning("Hata", "Mesaj " . $MessageControl[1], true)));
                    die;
                }
                $Message = hash::encrypt($MessageControl[1]);

                $ReceiverControl = $db->select("MessagerA,MessagerB", "privateroom")->where("RoomId=?", [$RoomId])->get_one();
                if ($ReceiverControl["MessagerA"] == null || $ReceiverControl["MessagerB"] == null) {
                    exit(json_encode(create::izitoast_danger("Hata", "Bu odada yalnızca bir kişi bulunuyor.Bir kişinin bulunduğu odaya mesaj gönderemezsiniz.")));
                    die;
                }

                $result = $db->insert(
                    "privatemessage",
                    "RoomId,Messager,Message",
                    "?,?,?",
                    [$RoomId, $Sender, $Message]
                );

                if ($result) {
                    $lastAdded = $db->select("Message,MessageDate", "privatemessage")->where("MessageId=?", [$result])->get_one();
                    exit(json_encode(
                        [
                            "error" => false,
                            "content" => hash::decrypt($lastAdded["Message"])
                        ]
                    ));
                    die;
                } else {
                    exit(json_encode(create::izitoast_danger("Hata", "Bilinmeyen bir hata oluştu lütfen sayfayı yenileyip tekrar deneyin")));
                    die;
                }

                exit(json_encode($SendData));
            } else {
                header("location:" . URL);
                die;
            }
            break;
        default:
            header("location:" . URL);
            die;
            break;
    }
}
