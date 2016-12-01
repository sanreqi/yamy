/**
 * Created by srq on 2016/12/1.
 */
$(document).ready(function () {
    $(".form_save").click(function () {
        var $this = $(this);
        var data = {};
        data.amount = $.trim($(".detail_amount").val());
        data.charge = $.trim($(".detail_charge").val());
        data.time = $.trim($(".detail_time").val());
        data.balance = $.trim($(".detail_balance").val());
        data.returned = $.trim($(".detail_returned").val());
        data.id = $this.attr("account_id");
        data.type = $this.attr("type");
        if (data.amount == "") {
            alert("请完成必填");
            return false;
        }

        $.ajax ({
            url: "/mobile/account/create-detail-ajax",
            type: "post",
            dataType: "json",
            data: {data: data},
            success: function (responseData) {
                if (responseData.status == 1) {
                    alert("创建成功");
                    window.location.href = "/mobile/account/view?id=" + data.id;
                } else {
                    alert(responseData.message);
                }
            }
        });
    });
});
