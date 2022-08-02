<?php
if (!defined("95495036eb814b2fb6b1bc61dee0884c5570b46c0b1166c3cd7c6b24922fb3ce")) {
    die("...");
}
?>
<!-- Token reset modal -->
<div class="modal fade" id="TokenResetModal" tabindex="-1" aria-labelledby="TokenResetModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TokenResetModalLabel">Token yenile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Token yenilemeden önce bilmeniz gerekenler
                <ul>
                    <li>Tüm mesajlarınız <strong>silinir</strong></li>
                    <li>Gönderdiğiniz veya gelen tüm istekler <strong>silinir</strong></li>
                    <li>Bulunduğunuz tüm özel sohbetlerden <strong>çıkartılırsınız</strong></li>
                </ul>
                <div class="form-check">
                    <input id="tokenresetconfirm" name="tokenresetconfirm" type="checkbox" class="form-check-input">
                    <label for="tokenresetconfirm" class="form-check-label">Kabul ediyorum</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                <form action="backend/direct.php" method="POST">
                    <input type="hidden" name="usertoken" value="<?php echo $User["Token"] ?>">
                    <button id="tokenresetcontinue" name="tokenresetcontinue" type="submit" class="btn btn-danger" disabled>Devam et</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Token reset modal -->

<!-- Room delete modal -->
<div class="modal fade" id="roomdeletemodal" tabindex="-1" aria-labelledby="roomdeletemodal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roomdeletemodallabel">Odayı sil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Karşı taraf token bilgisini değiştirdiği için bu oda kapatıldı
                <br>
                Odayı silmek istiyor musunuz?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                <form action="backend/direct.php" method="POST">
                    <input type="hidden" name="roomid" value="">
                    <button name="roomdeletecontinue" type="submit" class="btn btn-danger">Devam et</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $(".roomdelete").click(function() {
            const roomid = $(this).parents("a").attr("room-id");
            $("#roomdeletemodal form input[name='roomid']").val(roomid);
        })
    })
</script>
<!-- /Room delete modal -->