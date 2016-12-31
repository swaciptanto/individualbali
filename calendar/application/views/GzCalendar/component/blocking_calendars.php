<div class="form-group">
    <label><?php echo __('select_calendar'); ?></label>
    <select class="form-control" name="calendar_id" id="calendar_id">
        <option>---</option>
        <?php
        if (count($tpl['calendars']) > 0) {
            foreach ($tpl['calendars'] as $k => $v) {
                ?>
                <option  <?php echo (!empty($tpl['block']['calendar_id']) && $tpl['block']['calendar_id'] == $v['id']) ? "selected='selected'" : ""; ?> value="<?php echo $v['id'] ?>"><?php echo $v['i18n'][$this->controller->tpl['default_language']['id']]['title'] ?></option>
                <?php
            }
        }
        ?>
    </select>
</div>