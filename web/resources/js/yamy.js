$(document).ready(function() { 
    var select1 = $("select[name='Detail[platform_id]']"); 
    var select2 = $("select[name='Detail[account_id]']");
    select1.change(function() {
        var id = $(this).val();
        $.ajax({
            url: '/platform/get-accounts-ajax',
            type: 'get',
            dataType: 'json',
            data: {
                id: id
            },
            success: function(data) {
                select2.empty();
                str = "";
                select2.html(str);
                $.each(data, function(k, v) {
                    str += "<option value="+k+">"+v+"</option>";      
                });    
                select2.html(str);
            }
        });
    });

    /**
     * 时间插件
     */
    $('.datepicker').datetimepicker({
        lang:'ch',
        timepicker:false,
        format:'Y-m-d',
        formatDate:'Y-m-d',
    });

    /**
     * 新增账号页面checkbox
     */
    $(".recharge_chx").click(function() {
        $(".recharge_tr").toggle(300);
    });
    $(".cashback_chx").click(function() {
        $(".cashback_tr").toggle(300);
    });
});


