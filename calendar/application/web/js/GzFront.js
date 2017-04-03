var globalVars = [];
function setGlobalVars(vars)
{
    globalVars = vars;
}
function convertCurrency() {
    $(".title-rate").each(function() {
        var element_rate = $(this);
        var villa_node_id = element_rate.find(".villa-node-id").val();
        var rate_type = element_rate.find(".rate-type").val();
        var default_currency = element_rate.find(".default-currency").val();
        var default_rate = element_rate.find(".default-rate").val();
        var param = '';
        if (villa_node_id !== '' && rate_type !== '') {
            param = "&vnid=" + villa_node_id + "&type=" + rate_type;
        } else if (default_currency !== '' && default_rate !== '') {
            param = "&defaultcurrency=" + default_currency
                    + "&defaultrate=" + default_rate;
        }
        $.ajax({
            type: "POST",
            url: globalVars.server
                    + "index.php?controller=GzFront&action=convertCurrency"
                    + param,
            success: function (res) {
                var title_rate = '';
                var country_code = res.country_code;
                var currency_symbol = res.currency_symbol;
                var rate = res.rate;
                if (country_code !== '') {
                    title_rate = country_code + ' ';
                }
                title_rate += currency_symbol + ' ' + rate;
                element_rate.find("span").html(title_rate);
            }
        });
    });
}
$(".ddl-currencies-selector").unbind('change').bind('change', function()
{
    if ($(this).is(":visible") && $(".gzABCalendarTable").length === 0) {
        var currency = $(this).val();
        $.ajax({
            type: "POST",
            data: {
                currencies: currency
            },
            url: globalVars.server + "load.php?controller=GzFront&action=changeCurrancy",
            success: function () {
                convertCurrency();
            }
        });
    }
});