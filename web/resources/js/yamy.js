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
     * 加载select2
     */
    $(".info_select2").select2();
    var $info_select = $(".info_select2");
    $info_select.on("select2:select", function(e) {
        console.log(e.params.data.id);
        fillInfoData();
    });
    $info_select.on("select2:unselect", function(e) {
        fillInfoData();
    });
    $(".account_select2").select2();
    function fillInfoData() {
        info_data = [];
        var i = 0;
        $(".select2-selection__rendered li.select2-selection__choice").each(function() {
            var $this = $(this);
            info_data[i++] = $this.attr("title");
        });
        $(".info_data").val(info_data);
    }

});


