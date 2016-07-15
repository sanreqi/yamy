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
});


