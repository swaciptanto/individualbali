<form name="edit_blocking" id="edit_blocking_id" method="post" action=""> 
    <input type="hidden" name="id" value="<?php echo @$tpl['block']['id']; ?>" id="edit_price_plan_id" />
    <input type="hidden" name="eidt_blocking" value="1" />
    <fieldset>
        <div class="form-group">
            <label class="control-label" for="date_range"><?php echo __('date_range'); ?>:</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
                <input data-rule-required="true" date-format="<?php echo $tpl['iso_format']; ?>" id="edit-daterange-btn" name="date_range" class="form-control pull-right" type="text" value="<?php echo date($tpl['option_arr_values']['date_format'], $tpl['block']['from_date']) . ' - ' . date($tpl['option_arr_values']['date_format'], @$tpl['block']['to_date']); ?>">
            </div>
        </div>
        <?php
        require_once 'blocking_calendars.php';
        ?>
    </fieldset>
</form>