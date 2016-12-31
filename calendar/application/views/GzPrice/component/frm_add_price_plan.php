<form name="add_price_plan" id="add_price_plan_id" method="post" action=""> 
    <input type="hidden" id="hidden_calendar_id" name="calendar_id" value="<?php echo $tpl['default_calendar']['id']; ?>" />
    <fieldset>
        <!--modified: add new-->
        <div class="form-group">
            <label class="control-label" for="calendar_name">
                <?php echo 'Calendar'; ?>:
            </label>
            <input class="form-control" value="<?php echo $tpl['default_calendar']['title']; ?>" disabled="disabled">
        </div>
        <!--modified: moved position to up-->
        <div class="form-group">
            <label class="control-label" for="title">
                <!--modified: title-->
                <?php echo 'Plan ' . __('title'); ?>:
<!--            <?php //echo __('title'); ?>:-->
            </label>
            <!--modified: remove placeholder-->
            <input data-rule-required="true" data-rule-required="true" class="form-control" type="text" name="title">
<!--        <input data-rule-required="true" data-rule-required="true" class="form-control" type="text" placeholder="Enter ..." name="title">-->
            <div class="control-group"></div>
        </div>
        <div class="form-group">
            <label class="control-label" for="date_range"><?php echo __('date_range'); ?>:</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
                <input data-rule-required="true" date-format="<?php echo $tpl['iso_format']; ?>" id="daterange-btn" name="date_range" class="form-control pull-right" type="text" value="">
            </div>
        </div>
        <!-- modified: added -->
        <div class="form-group">
            <label class="radio control-label" for="rate">Rate Monday to Sunday:</label>
            <select data-rule-required="true" id="rate" name="rate"  class="form-control input-sm" >
                <option value="">---</option>
                <?php foreach ($tpl['rate_prices'] as $rate_price => $rate_name) {
                    ?>
                    <option value="<?php echo $rate_price; ?>">
                        <?php
                        echo "$rate_name - $$rate_price";
                        ?>
                    </option>
                    <?php
                }
                ?>
            </select>
        </div>
        <?php /*modified: hide
        <div class="form-group">
            <label class="control-label" for="monday">
                <?php echo __('Monday'); ?>:
            </label>
            <div class="input-group">
                <span class="input-group-addon"><?php echo Util::getCurrensySimbol($tpl['option_arr_values']['currency']); ?></span>
                <input data-rule-required="true" class="form-control" type="text" name="monday">
            </div>
            <div class="control-group"></div>
        </div>
        <div class="form-group">
            <label class="control-label" for="tuesday">
                <?php echo __('Tuesday'); ?>:
            </label>
            <div class="input-group">
                <span class="input-group-addon"><?php echo Util::getCurrensySimbol($tpl['option_arr_values']['currency']); ?></span>
                <input data-rule-required="true" class="form-control" type="text" name="tuesday">
            </div>
            <div class="control-group"></div>
        </div>
        <div class="form-group">
            <label class="control-label" for="wednesday">
                <?php echo __('Wednesday'); ?>:
            </label>
            <div class="input-group">
                <span class="input-group-addon"><?php echo Util::getCurrensySimbol($tpl['option_arr_values']['currency']); ?></span>
                <input data-rule-required="true" class="form-control" type="text" name="wednesday">
            </div>
            <div class="control-group"></div>
        </div>
        <div class="form-group">
            <label class="control-label" for="thursday">
                <?php echo __('Thursday'); ?>:
            </label>
            <div class="input-group">
                <span class="input-group-addon"><?php echo Util::getCurrensySimbol($tpl['option_arr_values']['currency']); ?></span>
                <input data-rule-required="true" class="form-control" type="text" name="thursday">
            </div>
            <div class="control-group"></div>
        </div>
        <div class="form-group">
            <label class="control-label" for="friday">
                <?php echo __('Friday'); ?>:
            </label>
            <div class="input-group">
                <span class="input-group-addon"><?php echo Util::getCurrensySimbol($tpl['option_arr_values']['currency']); ?></span>
                <input data-rule-required="true" class="form-control" type="text" name="friday">
            </div>
            <div class="control-group"></div>
        </div>
        <div class="form-group">
            <label class="control-label" for="saturday">
                <?php echo __('Saturday'); ?>:
            </label>
            <div class="input-group">
                <span class="input-group-addon"><?php echo Util::getCurrensySimbol($tpl['option_arr_values']['currency']); ?></span>
                <input data-rule-required="true" class="form-control" type="text" name="saturday">
            </div>
            <div class="control-group"></div>
        </div>
        <div class="form-group">
            <label class="control-label" for="sunday">
                <?php echo __('Sunday'); ?>:
            </label>
            <div class="input-group">
                <span class="input-group-addon"><?php echo Util::getCurrensySimbol($tpl['option_arr_values']['currency']); ?></span>
                <input data-rule-required="true" class="form-control" type="text" name="sunday">
            </div>
            <div class="control-group"></div>
        </div>
         * 
         */
        ?>
        <input type="hidden" name="is_default" value="T" />
        <?php /*
        <div class="form-group">
            <label class="control-label" for="adults">
                <?php echo __('adults'); ?>:
            </label>
            <select name="adults"  class="form-control input-sm mini" id="adults">
                <?php for ($i = 0; $i <= 20; $i++) {
                    ?>
                    <option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                    <?php
                }
                ?>
            </select>
            <div class="control-group"></div>
        </div>
        <div class="form-group">
            <label class="control-label" for="children">
                <?php echo __('children'); ?>:
            </label>
            <select name="children"  class="form-control input-sm mini" id="children">
                <?php for ($i = 0; $i <= 20; $i++) {
                    ?>
                    <option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                    <?php
                }
                ?>
            </select>
            <div class="control-group"></div>
        </div>
         * 
         */  ?>       
    </fieldset>
</form>