<label class="control-label" for="calendar_id"><?php echo __('calendars'); ?>:</label>
<select data-rule-required="true" name="calendar_id" id="calendar_id" class="form-control input-sm" >
    <option value="">---</option>
    <?php
    foreach ($tpl['calendars'] as $k => $v) {
        ?>
        <option <?php echo (@$_POST['calendar_id'] == $v['id'])?"selected='selected'":""; ?> value="<?php echo $v['id']; ?>" ><?php echo $v['i18n'][$this->controller->tpl['default_language']['id']]['title']; ?></option>
        <?php
    }
    ?>
</select>