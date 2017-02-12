<select id="currencies-selector">
    <?php
    foreach ($tpl['currencies'] as $key => $value) {
        ?>
        <option <?php echo ($tpl['currencies_select'] == $key) ? "selected='selected'" : ""; ?> value="<?php echo $key; ?>"><?php echo $key; ?></option>
        <?php
    }
    ?>
</select>