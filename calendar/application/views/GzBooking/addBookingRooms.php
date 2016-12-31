<div class="form-group">
    <?php
    if (!empty($tpl['calendars']) && count($tpl['calendars']) > 0) {
        ?>
        <label><?php echo __('select_room'); ?></label>
        <select class="form-control" name="calendar_id" id="calendar_id">
            <option>---</option>
            <?php
            foreach ($tpl['calendars'] as $k => $v) {
                ?>
                <option value="<?php echo $v['id'] ?>"><?php echo $v['i18n'][$this->controller->tpl['default_language']['id']]['title'] ?></option>
                <?php
            }
            ?>
        </select>
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