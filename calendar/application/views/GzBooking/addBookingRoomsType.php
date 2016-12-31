<div class="form-group">
    <?php if (!empty($tpl['calendars']) && count($tpl['calendars']) > 0) { ?>
        <?php
        foreach ($tpl['calendars'] as $k => $v) {
            ?>
            <p style="height: 30px;">
                <strong style="float: left; width: 100px;" ><?php echo $v['i18n'][$this->controller->tpl['default_language']['id']]['title']; ?>: </strong>
                <?php
                $max = (($v['count'] - $v['booked_count']) > 0) ? ($v['count'] - $v['booked_count']) : 0;
                if ($max > 0) {
                    ?>
                    <select style="float: left;" class="choose-room-count form-control input-sm mini" name="room_id[<?php echo $v['id']; ?>]"  data-type-id="<?php echo $v['id']; ?>">
                        <option value="0">--</option>
                        <?php
                        for ($i = 1; $i <= $max; $i++) {
                            ?>
                            <option <?php echo (!empty($_POST["room_id"][$v['id']]) && $_POST["room_id"][$v['id']] == $i) ? "selected='selected'" : ""; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                <?php } else {
                    ?>
                    <strong><?php echo __('full_booked'); ?></strong>
                    <?php
                }
                ?>
            </p>
            <?php
        }
        ?>
        <div class="control-group"></div>
        <fieldset id="add-room-type-frm"></fieldset>
        <?php
    } else {
        ?>
        <p>
            <?php echo __('no_availability_calendars'); ?>
        </p>
        <?php
    }
    ?>
</div>
<fieldset id="select-rooms-id"></fieldset>