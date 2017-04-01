<span class="title-rate">
<input class="villa-node-id" type="hidden" value="<?php echo $tpl['villa_node_id']; ?>" />
<input class="rate-type" type="hidden" value="<?php echo $tpl['rate_type']; ?>" />
<input class="default-currency" type="hidden" value="<?php echo $tpl['default_currency']; ?>" />
<input class="default-rate" type="hidden" value="<?php echo $tpl['default_rate']; ?>" />
<span>
<?php echo $tpl['country_code'] . ($tpl['country_code'] !== '' ? ' ' : '')
. "$tpl[currency_symbol] $tpl[rate]"; ?>
</span>
</span>