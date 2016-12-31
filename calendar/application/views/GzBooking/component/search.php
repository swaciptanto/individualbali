<form style="display: none;" id="search-booking-frm-id" class="booking-frm-class" action="<?php echo INSTALL_URL; ?>GzBooking/index" method="post" name="booking-frm-search">
    <br />
    <fieldset class="scheduler-border bg-light-green">
        <row class="row">
            <div class="col-sm-6">
                <row class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label class="control-label" for="from_start_time"><?php echo __('start_between'); ?>:</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input id="from_start_time" class="form-control input-sm medium" type="text" name="from_start_time" value="<?php echo @$_POST['from_start_time']; ?>" data-date-format="<?php echo $tpl['js_format']; ?>" first-day="<?php echo $tpl['option_arr_values']['week_first_day']; ?>" data-rule-required="true" >
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label class="control-label" for="to_start_time">&nbsp;</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input id="to_start_time" class="form-control input-sm medium" type="text" name="to_start_time" value="<?php echo @$_POST['to_start_time']; ?>" data-date-format="<?php echo $tpl['js_format']; ?>" first-day="<?php echo $tpl['option_arr_values']['week_first_day']; ?>" data-rule-required="true" >
                            </div>
                        </div>
                    </div>
                </row>
            </div>
            <div class="col-sm-6">
                <row class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label class="control-label" for="from_end_time"><?php echo __('end_between'); ?>:</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input id="from_end_time" class="form-control input-sm medium" type="text" name="from_end_time" value="<?php echo @$_POST['from_end_time']; ?>"  data-date-format="<?php echo $tpl['js_format']; ?>" first-day="<?php echo $tpl['option_arr_values']['week_first_day']; ?>" data-rule-required="true" >
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label class="control-label" for="to_end_time">&nbsp;</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input id="to_end_time" class="form-control input-sm medium" type="text" name="to_end_time" value="<?php echo @$_POST['to_end_time']; ?>" data-date-format="<?php echo $tpl['js_format']; ?>" first-day="<?php echo $tpl['option_arr_values']['week_first_day']; ?>" data-rule-required="true" >
                            </div>
                        </div>
                    </div>
                </row>
            </div>
        </row>
        <row class="row">
            <div class="col-sm-4">
                <div class="form-group" id="calendars-container-id">
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
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label class="control-label" for="adults"><?php echo __('adults'); ?>:</label>
                    <input id="adults" class="mini input-sm mini" type="text" name="adults" size="25" value="<?php echo @$_POST['adults']; ?>" title="<?php echo __('adults'); ?>" placeholder="0">
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label class="control-label" for="children">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('children'); ?>:</label>
                    <input id="children" class="mini input-sm mini" type="text" name="children" size="25" value="<?php echo @$_POST['children']; ?>" title="<?php echo __('children'); ?>" placeholder="0">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label" for="status">
                        <?php echo __('booking_status'); ?>:
                    </label>
                    <select data-rule-required="true" name="status" id="status" class="form-control input-sm" >
                        <option value="">---</option>
                        <?php
                        $status_arr = __('status_arr');
                        foreach ($status_arr as $k => $v) {
                            ?>
                            <option <?php echo (@$_POST['status'] == $k)?"selected='selected'":""; ?> value="<?php echo $k; ?>" ><?php echo $v; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
        </row>
        <row class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label" for="first_name"><?php echo __('first_name'); ?>:</label>
                    <input id="first_name" class="form-control input-sm" type="text" name="first_name" size="25" value="<?php echo @$_POST['first_name']; ?>" title="<?php echo __('first_name'); ?>" placeholder="">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label" for="second_name"><?php echo __('second_name'); ?>:</label>
                    <input id="second_name" class="form-control input-sm" type="text" name="second_name" size="25" value="<?php echo @$_POST['second_name']; ?>" title="<?php echo __('second_name'); ?>" placeholder="">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label" for="email"><?php echo __('email'); ?>:</label>
                    <input id="email" class="form-control input-sm" type="text" name="email" size="25" value="" title="<?php echo __('email'); ?>" placeholder="">
                </div>
            </div>
        </row>
        <row>
            <div class="col-sm-6">
                <br />
                <button id="search-project-id" class="btn btn-success" autocomplete="off" value="<?php echo __('search'); ?>" name="submit" tabindex="9" type="submit"><i class="fa fa-fw fa-search"></i>&nbsp;<?php echo __('search'); ?></button>
            </div>
        </row>
    </fieldset>
</form>