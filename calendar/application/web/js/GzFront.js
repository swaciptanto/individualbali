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
        $.ajax({
            type: "POST",
            url: globalVars.server
                    + "index.php?controller=GzFront&action=convertCurrency"
                    + "&vnid=" + villa_node_id
                    + "&type=" + rate_type,
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
    if ($(".btn-inquiry").length === 0 && $(this).is(":visible")) {
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