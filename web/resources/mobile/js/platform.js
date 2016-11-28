/**
 * Created by srq on 2016/11/28.
 */
$(document).ready(function() {
    $(".submit_button").click(function() {
        var data = {};
        data.name = $.trim($(".platform_input_name").val());
        if (data.name == "") {
            alert("请输入平台名称");
        }
        $.ajax ({
            url: "/mobile/platform/save-ajax",
            type: "post",
            dataType: "json",
            data: {data: data},
            success: function(data) {
                if (data.status == 1) {
                    alert("保存成功");
                    window.location.href = "/mobile/platform/create";
                } else {
                    alert(data.message);
                }
            }
        });
    });
});
