const url = "https://selcuksahintest.de/cryptomessenger";
var prevroomid = "";

$(function () {
    $("#tokencopy").click(function () {
        var copyText = $("#usertoken").text();
        navigator.clipboard.writeText(copyText);

        $("#tokencopy .tooltiptext").text("Kopyalandı")
    })
    $("#tokencopy").on("mouseleave", function () {
        $("#tokencopy .tooltiptext").text("Kopyala")
    })
    $("#tokenresetconfirm").click(function () {
        if ($(this).prop("checked")) {
            $("#tokenresetcontinue").attr("disabled", false);
        } else {
            $("#tokenresetcontinue").attr("disabled", true);
        }
    })

    $(".private-room").click(function () {
        const roomid = $(this).attr("room-id")
        const viewer = $("#usertoken").text();
        if (prevroomid != roomid) {
            prevroomid = roomid;
            $.ajax({
                method: "post",
                url: url + "/backend/ajax.php?operation=getroom",
                data: { "RoomId": roomid, "Viewer": viewer },
                dataType: "json",
                success: function (data) {
                    $(".chat-box").html(data)
                    $("#sendmessage").html(`
                        <input name="roomid" type="hidden" value="${roomid}">
                        <div class="input-group">
                            <input name="message" type="text" placeholder="Mesajını yaz" aria-describedby="button-addon2" class="form-control rounded-0 border-0 py-4 bg-light">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-link bg-primary">
                                    <i class="fa fa-paper-plane text-white"></i>
                                </button>
                            </div>
                        </div>`);
                }
            })
        }
    })
    $("#sendmessage").on("submit", function (e) {
        e.preventDefault();
        const sender = $("#usertoken").text();
        const formdata = $(this).serializeArray();
        formdata.push({ name: "sender", value: sender });

        $.ajax({
            method: "post",
            url: url + "/backend/ajax.php?operation=sendmessage",
            data: formdata,
            dataType: "json",
            success: function (data) {
                if (data.error) {
                    iziToast.show({
                        icon: data.icon,
                        title: data.title,
                        message: data.message,
                        position: 'topRight', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter, center
                        theme: 'light', // dark,light
                        color: data.color,
                    });
                } else {
                    $(".chat-box #nomessage").remove();
                    $(".chat-box").append(`
                    <div class="media w-50 ml-auto mb-3">
                        <div class="media-body">
                            <div class="bg-primary rounded py-2 px-3 mb-2">
                                <p class="text-small mb-0 text-white">${data.content}</p>
                            </div>
                        </div>
                    </div>
                    `);
                    $("input[name='message']").val("");
                }
            }
        })
    })
})