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

