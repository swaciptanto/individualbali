<form name="gzExtraForm" id="gz-abc-form-id">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-solid box-primary">
                <div class="box-header">
                    <h3 class="box-title"><strong><?php echo __('calendar'); ?></strong></h3>
                </div>
                <div class="box-body" style="padding: 0;">
                    <table class="table table-hover">
                        <tr>
                            <td>
                                <div class="col-sm-12">
                                    <h2><?php echo $tpl['calendar']['i18n'][$tpl['select_language']['id']]['title']; ?></h2>
                                    <div class="col-sm-12 padding-left-0">
                                        <?php if (!empty($tpl['calendar']['gallery'][0]['thumb']) && is_file(INSTALL_PATH . UPLOAD_PATH . "calendars/thumb/" . $tpl['calendar']['gallery'][0]['thumb'])) { ?>
                                            <a class="gz-gallery-first gz-gallery-<?php echo $tpl['calendar']['id']; ?>" rel="gz-gallery-<?php echo $tpl['calendar']['id']; ?>" href="<?php echo INSTALL_URL . UPLOAD_PATH . "calendars/preview/" . $tpl['calendar']['gallery'][0]['preview']; ?>">        
                                                <img class="gz-hotel-booking-photo" alt="" src="<?php echo INSTALL_URL . UPLOAD_PATH . "calendars/thumb/" . $tpl['calendar']['gallery'][0]['thumb']; ?>">
                                            </a>
                                        <?php } else { ?>
                                            <a class="gz-gallery-first gz-gallery-<?php echo $tpl['calendar']['id']; ?>" rel="gz-gallery-<?php echo $tpl['calendar']['id']; ?>" href="<?php echo INSTALL_URL . "application/web/img/no-image.png"; ?>">        
                                                <img class="gz-hotel-booking-photo" alt="" src="<?php echo INSTALL_URL . "application/web/img/no-image.png"; ?>" />
                                            </a>
                                        <?php } ?> 
                                        <?php for ($i = 1; $i < count($tpl['calendar']['gallery']); $i++) { ?>
                                            <a class="hidden-class gz-gallery-<?php echo $tpl['calendar']['id']; ?>" rel="gz-gallery-<?php echo $tpl['calendar']['id']; ?>" href="<?php echo INSTALL_URL . UPLOAD_PATH . "calendars/preview/" . $tpl['calendar']['gallery'][$i]['preview']; ?>">
                                                <?php if (!empty($tpl['calendar']['gallery'][$i]['thumb']) && is_file(INSTALL_PATH . UPLOAD_PATH . "calendars/thumb/" . $tpl['calendar']['gallery'][$i]['thumb'])) { ?>
                                                    <img class="gz-hotel-booking-photo" alt="" src="<?php echo INSTALL_URL . UPLOAD_PATH . "calendars/thumb/" . $tpl['calendar']['gallery'][$i]['thumb']; ?>">
                                                    <?php
                                                }
                                                ?>
                                            </a>
                                        <?php } ?>

                                        <?php echo $tpl['calendar']['i18n'][$tpl['select_language']['id']]['description']; ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <fieldset>
                <div class="box box-solid box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><strong><?php echo __('booking_details'); ?></strong></h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label style="width: 50%;" class="control-label" ><?php echo __('from_date'); ?>:</label>
                            <?php echo date($tpl['option_arr_values']['date_format'], $_POST['start_date']); ?>
                        </div>
                        <div class="form-group">
                            <label style="width: 50%;" class="control-label" ><?php echo __('to_date'); ?>:</label>
                            <?php echo date($tpl['option_arr_values']['date_format'], $_POST['end_date']); ?>
                        </div>
                        <div class="form-group">
                            <?php echo $tpl['nights']; ?> (<a href="javascript:" id="change-date-id"><?php echo __('click_to_change_dates'); ?></a>)
                        </div>
                        <?php /*
                        <div class="form-group">
                            <label class="control-label" for="adults"><?php echo __('calendar_adults'); ?>:</label>
                            <select id="adults" name="adults" class="form-control input-sm mini people-group" >
                                <option value="">--</option>
                                <?php for ($i = 1; $i <= $tpl['calendar']['adults']; $i++) {
                                    ?>
                                    <option <?php echo (!empty($_POST['adults']) && $_POST['adults'] == $i) ? "selected='selected'" : ""; ?> value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="children"><?php echo __('calendar_children'); ?>:</label>
                            <select id="children" name="children" class="form-control input-sm mini people-group" >
                                <option value="">--</option>
                                <?php for ($i = 1; $i <= $tpl['calendar']['children']; $i++) {
                                    ?>
                                    <option <?php echo (!empty($_POST['children']) && $_POST['children'] == $i) ? "selected='selected'" : ""; ?> value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div> */ ?>
                        <div class="form-group">
                            <label class="control-label" for=""><?php echo __('calendar_price'); ?>:</label>
                            <span id="calendars_price"><?php echo $tpl['prices']['formated_calendars_price']; ?></span>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for=""><?php echo __('extra_price'); ?>:</label>
                            <span id="extra_price"><?php echo $tpl['prices']['formated_extra_price']; ?></span>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for=""><?php echo __('tax'); ?>:</label>
                            <span id="tax"><?php echo $tpl['prices']['formated_tax']; ?></span>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for=""><?php echo __('discount'); ?>:</label>
                            <span id="discount"><?php echo $tpl['prices']['formated_discount']; ?></span>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for=""><?php echo __('deposit_price'); ?>:</label>
                            <span id="deposit"><?php echo $tpl['prices']['formated_deposit']; ?></span>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for=""><?php echo __('total_price'); ?>:</label>
                            <span id="total"><?php echo $tpl['prices']['formated_total']; ?></span>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for=""><?php echo __('promo_code'); ?>:</label>
                            <input type="text" id="promo_code" name="promo_code" value="" class="form-control input-sm" >
                        </div>
                        <?php
                        if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {
                            ?>
                            <?php
                            if (count($tpl['extras']) > 0) {
                                ?>
                                <h3 class="box-title text-center">
                                    <strong><?php echo __('extras'); ?></strong>
                                </h3>
                                <br />     
                                <table class="table table-hover">
                                    <?php
                                    foreach ($tpl['extras'] as $k => $v) {
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="col-sm-12">
                                                    <h2><?php echo $v['title']; ?></h2>
                                                    <?php if (is_file(INSTALL_PATH . UPLOAD_PATH . 'extra/thumb/' . $v['img'])) { ?>
                                                        <span>
                                                            <img style="width: 50%; float: left; margin-right: 10px;" src="<?php echo INSTALL_URL . UPLOAD_PATH . 'extra/thumb/' . $v['img']; ?>" />                            
                                                        </span>
                                                    <?php } ?>  
                                                    <?php echo $v['description']; ?>
                                                </div>
                                                <div class="col-sm-2" style="height: 100%; vertical-align: middle;">
                                                    <input <?php echo (!empty($_POST['extra_id']) && in_array($v['id'], $_POST['extra_id'])) ? "checked='checked'" : ""; ?> type="checkbox" name="extra_id[]" value="<?php echo $v['id']; ?>" />
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </table>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </fieldset>
                <?php
            }
            ?>
        </div>
        <div class="col-sm-12">
            <?php require 'booking_form.php'; ?>
        </div>
        <div class="col-sm-12 text-center">
            <fieldset>
                <div class="box box-solid box-primary">
                    <div class="box-body">
                        <?php
                        foreach ($_POST as $name => $value) {
                            if (!in_array($name, array('extra_id', 'captcha', 'additional', 'payment_method', 'cc_type', 'cc_num', 'cc_code', 'cc_exp_year', 'cc_exp_month', 'fax', 'country', 'zip', 'state', 'address_2', 'address_1', 'company', 'email', 'phone', 'second_name', 'first_name', 'male', 'title', 'adults', 'children'))) {
                                if (is_array($value)) {
                                    foreach ($value as $k => $v) {
                                        ?>
                                        <input type="hidden" name="<?php echo $name; ?>[]" value="<?php echo $v ?>" />
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value ?>" />
                                    <?php
                                }
                            }
                        }
                        ?>
                        <a data-style="expand-left" href="javascript:" class="btn btn-default btn btn-danger ladda-button" id="back_to_calendar_id" autocomplete="off" value="<?php echo __('back'); ?>" name="back" tabindex="9" type="submit">
                            <span class="ladda-label"><?php echo __('back'); ?></span>
                            <span class="ladda-spinner"></span>
                        </a>
                        <a data-style="expand-left" href="javascript:" class="btn btn-warning ladda-button" id="booking_frm_btn_id" autocomplete="off" value="<?php echo __('booking'); ?>" name="submit" tabindex="9" type="submit">
                            <span class="ladda-label"><i class="fa fa-gavel"></i>&nbsp;&nbsp;&nbsp;<?php echo __('booking'); ?></span>
                            <span class="ladda-spinner"></span>
                        </a>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</form>
