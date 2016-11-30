/**
 * Created by srq on 2016/11/30.
 */
$(document).ready(function () {
    $(".submit_button").click(function() {
        var data = {};
        data.username = $(".login_username input").val();
        data.password = $(".login_password input").val();
        $.ajax ({
            url: "/mobile/site/login-ajax",
            type: "post",
            dataType: "json",
            data: {data: data},
            success: function(responseData) {
                if (responseData.status == 1) {
                    window.location.href = '/mobile/account/index';
                } else {
                    alert(responseData.message);
                }
            }
        });
    });
});
