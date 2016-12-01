/**
 * Created by srq on 2016/11/29.
 */
$(document).ready(function () {
    $(".form_save").click(function () {
        var data = {};
        data.platform_id = $(".platform").val();
        data.bank_account_id = $(".bank_account").val();
        data.balance = $.trim($(".balance").val());
        data.returned_date = $.trim($(".returned_date").val());
        data.recharge_amount = $.trim($(".recharge_amount").val());
        data.recharge_date = $.trim($(".recharge_date").val());
        data.cashback_name = $.trim($(".cashback_name").val());
        data.cashback_amount = $.trim($(".cashback_amount").val());
        data.cashback_date = $.trim($(".cashback_date").val());
        if (data.balance == "") {
            alert("请完成必填");
            return false;
        }

        $.ajax ({
            url: "/mobile/account/create-ajax",
            type: "post",
            dataType: "json",
            data: {data: data},
            success: function (responseData) {
                if (responseData.status == 1) {
                    alert("创建成功");
                    window.location.href = "/mobile/account/view?id="+responseData.data.id;
                } else {
                    alert(responseData.message);
                }
            }
        });
    });

    $(".search_btn").click(function () {
        var keyword = $.trim($(".search_text").val());
        if (keyword == "") {
            alert("请输入关键字");
            return false;
        }
        $.ajax ({
            url: "/mobile/account/get-account-list",
            type: "get",
            dataType: "json",
            data: {
                keyword: keyword
            },
            success: function (responseData) {
                var data = responseData.data;
                var str = '';
                if (data.length == 0) {
                    str = '<div class="search_none">没有找到相关平台</div>';
                } else {
                    var i;
                    for (i in data) {
                        str += '<a href="/mobile/account/view?id=' + data[i].id + '"><li class="list_row">' + data[i].name + '-' + data[i].mobile + '</li></a>';
                    }
                }
                $(".list_display").html(str);
            }
        });
    });
});
