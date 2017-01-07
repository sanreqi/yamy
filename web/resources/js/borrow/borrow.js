/**
 * Created by srq on 2017/1/3.
 */
if (typeof(yamy) == "undefined") {
    var yamy = {};
}

yamy.borrow = (function() {
    var _bindDatePicker = function () {
        /**
         * 时间插件
         */
        $('.datepicker').datetimepicker({
            lang: 'ch',
            timepicker: false,
            format: 'Y-m-d',
            formatDate: 'Y-m-d',
        });
    };

    return {
        init: function() {
            _bindDatePicker();
        }
    }
})();

yamy.borrow.detail = (function() {
    var _initCondition = function() {
        data = {};
        data.status = 1;
        ajaxLoad(data);
    }

    //tab点击
    var _bindStatusBtn = function() {
        $(".status").click(function() {
            var $this = $(this);
            $(".status").removeClass("selected");
            $(this).addClass("selected");
            ajaxLoad({
                status: $this.attr("data-id")
            });
        });
    }
    //搜索按钮点击
    var _bindSearchBtn = function() {
        $(".search").click(function() {
            var postData = getCondition();
            ajaxLoad(postData);
        });
    }
    //分页按钮点击
    var _bindPageBtn = function() {
        $(".pagination li a").click(function() {
            var data = getCondition();
            var url = $(this).attr("href");
            alert(url);return;
            ajaxLoad(url, data);
        });
    }
    function ajaxLoad(ajax_url, data) {
        //alert(ajax_url);return;
        var url = "/borrow/get-detail-data";
        if (ajax_url != "undefined") {
            url = ajax_url;
        }
        $.ajax({
            url: url,
            type: "post",
            dataType: "json",
            data: {data: data},
            success: function(responseData) {
                if (responseData.status == 1) {
                    var data = responseData.data.data;
                    var str = '';
                    str += '<tr>' +
                        '<th width="120" align="left">平台名称</th>' +
                        '<th align="left">账号名称</th>' +
                        '<th align="left">借款总额</th>' +
                        '<th align="left">剩余应还金额</th>' +
                        '<th align="left">借款时间</th>' +
                        '<th align="left">应还款时间</th>' +
                        '<th align="left">备注</th>' +
                        '<th width="80" align="center">操作</th></tr>';
                    for (var i in data) {
                        str += '<tr>' +
                            '<td align="left">'+data[i].way.platform+'</td>' +
                            '<td>'+data[i].way.account+'</td>' +
                            '<td>'+data[i].amount+'</td>' +
                            '<td>'+data[i].remain+'</td>' +
                            '<td>'+data[i].borrow_time+'</td>' +
                            '<td>'+data[i].payment_time+'</td>' +
                            '<td>'+data[i].note+'</td>' +
                            '<td align="center"><a href="/borrow/way-update?id=1">编辑</a> | <a class="delete_item" href="/borrow/way-delete?id=1">删除</a></td>' +
                            '</tr>';
                    }
                    var page_str = "第"+(responseData.data.page+1)+"页"+responseData.data.pager;
                    $(".spage").html(page_str);
                    $("table").html(str);
                }
            }
        });
    }
    function getCondition() {
        var data = {};
        data.stauts = $(".status.selected").attr("data-id");
        data.startTime = $(".start_time").val();
        data.endTime = $(".end_time").val();
        return data;
    }

    return {
        init: function() {
            _initCondition();
            _bindStatusBtn();
            _bindSearchBtn();
            _bindPageBtn();
        }
    }
})();

