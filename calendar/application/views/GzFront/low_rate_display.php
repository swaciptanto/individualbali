<span class="title-low-rate">
    <input type="hidden" value="<?php echo $tpl['villa_node_id']; ?>" />
    <span>
        <?php echo $tpl['country_code']
                . ($tpl['country_code'] !== '' ? ' ' : '')
                . "$tpl[currency_symbol] $tpl[rate_low]"; ?>
    </span>
</span>