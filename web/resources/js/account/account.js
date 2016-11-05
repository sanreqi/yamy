/**
 * Created by srq on 2016/11/5.
 */
if (typeof(yamy) == "undefined") {
    var yamy = {};
}

yamy.account = (function() {
    //隐藏金额
    var _hideSum = function() {
        $(".view_sum").click(function() {
            $(".view_sum").toggle();
            $(".display_sum").toggle();
        });
    }

    return {
        init: function() {
            _hideSum();
        }
    }
})();

