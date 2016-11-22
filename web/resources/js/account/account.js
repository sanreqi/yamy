/**
 * Created by srq on 2016/11/5.
 */
if (typeof(yamy) == "undefined") {
    var yamy = {};
}

yamy.account = (function () {
    //隐藏金额
    var _hideSum = function () {
        $(".view_sum").click(function () {
            $(".view_sum").toggle();
            $(".display_sum").toggle();
        });
    }

    return {
        init: function () {
            _hideSum();
        }
    }
})();

/**
 * 新增账号页面
 */
yamy.account.create = (function () {
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
    var _bindChx = function () {
        $(".recharge_chx").click(function () {
            $(".recharge_tr").toggle(300);
        });
        $(".cashback_chx").click(function () {
            $(".cashback_tr").toggle(300);
        });
    };
    var _bindSelect2 = function () {
        $(".platform_select2").select2()
        $(".bankaccount_select2").select2();
        single_select2_change("platform_select2", "platformid_hidden");
        multi_select2_change("bankaccount_select2", "bankcardids_hidden");
    }
    var _bindDaysInput = function () {
        var reg = new RegExp("^[0-9]*$");
        $(".invest_days").keyup(function () {
            var val = $(this).val();
            if (reg.test(val) && val != "") {
                $.ajax ({
                    url: "/account/change-returned-ajax",
                    type: "post",
                    dataType: "json",
                    data: {
                        date: $(".recharge_date").val(),
                        days: val
                    },
                    success: function(data) {
                        $(".latest_returned").val(data.date);
                    }
                });
            }
        });
    }
    return {
        init: function () {
            _bindChx();
            _bindSelect2();
            _bindDatePicker();
            _bindDaysInput();
        }
    }
})();

/**
 *
 * @param select2_class
 * @param hidden_class
 */
function single_select2_change(select2_class, hidden_class) {
    var id;
    var $select2 = $("." + select2_class);
    $select2.on("select2:select", function (e) {
        id = e.params.data.id;
        $("." + hidden_class).val(id);
    });
}

/**
 *multi select2选择事件
 * @param select2_class
 * @param hidden_class
 */
function multi_select2_change(select2_class, hidden_class) {
    var id;
    var hidden_array;
    var $select2 = $("." + select2_class);
    //是数组
    var hidden_val = $("." + hidden_class).val();
    //alert(hidden_val);
    if (hidden_val == "") {
        hidden_array = [];
    } else {
        hidden_array = hidden_val.split(",");
    }
    $select2.on("select2:select", function (e) {
        id = e.params.data.id;
        hidden_array.push(id);
        $("." + hidden_class).val(hidden_array.join(","));
    });
    $select2.on("select2:unselect", function (e) {
        id = e.params.data.id;
        for (var i in hidden_array) {
            if (hidden_array[i] == id) {
                hidden_array.splice(i, 1);
            }
        }
        $("." + hidden_class).val(hidden_array.join(","));
    });
}

