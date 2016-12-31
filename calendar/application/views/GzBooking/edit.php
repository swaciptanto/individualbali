<section class="content-header">
    <h1>
        <?php echo __('booking_header'); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo INSTALL_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo __('home'); ?></a></li>
        <li><a href="<?php echo INSTALL_URL; ?>GzBooking/index"><?php echo __('booking'); ?></a></li>
        <li class="active"><?php echo __('edit_booking'); ?></li>
    </ol>
</section>
<?php
require_once VIEWS_PATH . 'Layouts/admin/error_notice.php';
?>
<form id="new_booking" class="frm-class booking-frm-class" action="<?php echo INSTALL_URL; ?>GzBooking/edit" method="post" name="create">
    <div class="padding-19">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a data-toggle="tab" href="#tab_1"><?php echo __('pay_details'); ?></a>
                </li>
                <li class="">
                    <a data-toggle="tab" href="#tab_2"><?php echo __('client_details'); ?></a>
                </li>

            </ul>
            <div class="tab-content">
                <div id="tab_1" class="tab-pane active">
                    <fieldset>
                        <section class="col-lg-7 connectedSortable">
                            <div class="box box-solid box-primary">
                                <div class="box-header">
                                    <h3 class="box-title"><?php echo __('booking_details'); ?></h3>
                                    <div class="box-tools pull-right">
                                        <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        <label class="control-label" for="calendars_price"><?php echo __('calendars_price'); ?>:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo Util::getCurrensySimbol($tpl['option_arr_values']['currency']); ?></span>
                                            <input data-rule-required="true" id="calendars_price" class="form-control input-sm" type="text" name="calendars_price" size="25" value="<?php echo $tpl['booking']['calendars_price']; ?>" title="<?php echo __('calendars_price'); ?>" placeholder="calendars_price">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="extra_price"><?php echo __('extra_price'); ?>:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo Util::getCurrensySimbol($tpl['option_arr_values']['currency']); ?></span>
                                            <input data-rule-required="true" id="extra_price" class="form-control input-sm" type="text" name="extra_price" size="25" value="<?php echo $tpl['booking']['extra_price']; ?>" title="<?php echo __('extra_price'); ?>" placeholder="<?php echo __('extra_price'); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="tax"><?php echo __('tax'); ?>:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo Util::getCurrensySimbol($tpl['option_arr_values']['currency']); ?></span>
                                            <input data-rule-required="true" id="tax" class="form-control input-sm" type="text" name="tax" size="25" value="<?php echo $tpl['booking']['tax']; ?>" title="tax" placeholder="tax">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="deposit"><?php echo __('deposit'); ?>:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo Util::getCurrensySimbol($tpl['option_arr_values']['currency']); ?></span>
                                            <input data-rule-required="true" id="deposit" class="form-control input-sm" type="text" name="deposit" size="25" value="<?php echo $tpl['booking']['deposit']; ?>" title="deposit" placeholder="deposit">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="promo_code"><?php echo __('promo_code'); ?>:</label>
                                        <input id="promo_code" class="form-control input-sm" type="text" name="promo_code" size="25" value="<?php echo $tpl['booking']['promo_code']; ?>" title="<?php echo __('promo_code'); ?>" placeholder="<?php echo __('promo_code'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="discount"><?php echo __('discount'); ?>:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo Util::getCurrensySimbol($tpl['option_arr_values']['currency']); ?></span>
                                            <input data-rule-required="true" id="discount" class="form-control input-sm" type="text" name="discount" size="25" value="<?php echo $tpl['booking']['discount']; ?>" title="discount" placeholder="discount">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="total"><?php echo __('total'); ?>:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo Util::getCurrensySimbol($tpl['option_arr_values']['currency']); ?></span>
                                            <input data-rule-required="true" id="total" class="form-control input-sm" type="text" name="total" size="25" value="<?php echo $tpl['booking']['total']; ?>" title="total" placeholder="total">
                                        </div>
                                    </div>
                                </div>
                                <fieldset class="form-actions">
                                    <input type="hidden" name="edit_booking" value="1" /> 
                                    <input type="hidden" name="id" value="<?php echo $tpl['booking']['id']; ?>" /> 
                                    <button id="submit-1" class="btn btn-primary" autocomplete="off" value="<?php echo __('save'); ?>" name="<?php echo __('save'); ?>" tabindex="9" type="submit"><i class="fa fa-fw fa-save"></i>&nbsp;&nbsp;<?php echo __('save'); ?></button>
                                </fieldset>
                            </div>
                            <div class="box box-solid box-primary">
                                <div class="box-header">
                                    <h3 class="box-title"><?php echo __('payment_details'); ?></h3>
                                    <div class="box-tools pull-right">
                                        <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button class="btn btn-primary btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        <label class="control-label" for="payment_method"><?php echo __('payment_method'); ?>:</label>
                                        <select data-rule-required="true" name="payment_method" id="payment_method" class="form-control input-sm" >
                                            <option value="">---</option>
                                            <?php
                                            $payment_method_arr = __('payment_method_arr');
                                            foreach ($payment_method_arr as $k => $v) {
                                                ?>
                                                <option <?php echo ($tpl['booking']['payment_method'] == $k) ? "selected='selected'" : ""; ?>  value="<?php echo $k; ?>" ><?php echo $v; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>

                                </div>
                                <fieldset class="form-actions">
                                    <input type="hidden" name="edit_booking" value="1" /> 
                                    <input type="hidden" name="id" value="<?php echo $tpl['booking']['id']; ?>" /> 
                                    <button id="submit" class="btn btn-primary" autocomplete="off" value="Submit" name="submit" tabindex="9" type="submit"><i class="fa fa-fw fa-save"></i>&nbsp;&nbsp;<?php echo __('save'); ?></button>
                                </fieldset>
                            </div>
                            <div class="box box-solid box-primary" id="credit_card_details" style="<?php echo ($tpl['booking']['payment_method'] != "credit_card") ? "display: none;" : ""; ?>">
                                <div class="box-header">
                                    <h3 class="box-title"><strong><?php echo __('credit_card_details'); ?></strong></h3>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        <label class="control-label" for="cc_type"><?php echo __('label_cc_type'); ?>:</label>
                                        <select title="<?php echo __('cc_type'); ?>" data-rule-required='true' name="cc_type" id="cc_type" class="form-control input-sm" >
                                            <option value="">---</option>
                                            <?php
                                            $cc_type = __('cc_type');
                                            foreach ($cc_type as $k => $v) {
                                                ?>
                                                <option <?php echo ($tpl['booking']['cc_type'] == $k) ? "selected='selected'" : ""; ?> value="<?php echo $k; ?>" ><?php echo $v; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="cc_num"><?php echo __('cc_num'); ?>:</label>
                                        <input data-rule-required='true' id="cc_num" class="form-control input-sm" type="text" name="cc_num" size="25" value="<?php echo $tpl['booking']['cc_num']; ?>" title="<?php echo __('cc_num'); ?>" placeholder="<?php echo __('cc_num'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="cc_code"><?php echo __('cc_code'); ?>:</label>
                                        <input data-rule-required='true' id="fax" class="form-control input-sm" type="text" name="cc_code" size="25" value="<?php echo $tpl['booking']['cc_code']; ?>" title="<?php echo __('cc_code'); ?>" placeholder="<?php echo __('cc_code'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="cc_exp_month"><?php echo __('cc_exp_date'); ?>:</label>
                                        <div class="input-group left width_100">
                                            <select title="<?php echo __('cc_exp_date'); ?>" data-rule-required='true' name="cc_exp_month" id="cc_exp_month" class="form-control input-sm medium left margin-right-5" >
                                                <option value="">---</option>
                                                <?php
                                                $month_arr = __('month_arr');
                                                foreach ($month_arr as $k => $v) {
                                                    ?>
                                                    <option <?php echo ($tpl['booking']['cc_exp_month'] == $k) ? "selected='selected'" : ""; ?> value="<?php echo $k; ?>" ><?php echo $v; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <select title="<?php echo __('cc_exp_date'); ?>" data-rule-required='true' name="cc_exp_year" id="cc_exp_year" class="form-control input-sm medium left" >
                                                <option value="">---</option>
                                                <?php
                                                for ($v = date('Y'); $v <= date('Y') + 10; $v++) {
                                                    ?>
                                                    <option <?php echo ($tpl['booking']['cc_exp_year'] == $v) ? "selected='selected'" : ""; ?> value="<?php echo $v; ?>" ><?php echo $v; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <br />
                                    <br />
                                    <fieldset class="form-actions">
                                        <input type="hidden" name="edit_booking" value="1" /> 
                                        <input type="hidden" name="id" value="<?php echo $tpl['booking']['id']; ?>" /> 
                                        <button id="submit" class="btn btn-primary" autocomplete="off" value="Submit" name="submit" tabindex="9" type="submit"><i class="fa fa-fw fa-save"></i>&nbsp;&nbsp;<?php echo __('save'); ?></button>
                                    </fieldset>
                                </div>
                            </div>
                        </section>
                        <section class="col-lg-5 connectedSortable ui-sortable">
                            <div class="box box-solid box-primary">
                                <div class="box-header">
                                    <h3 class="box-title"><?php echo __('booking_details'); ?></h3>
                                    <div class="box-tools pull-right">
                                        <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="form-group" id="calendars-container-id">
                                        <label class="control-label" for="calendar_id"><?php echo __('calendars'); ?>:</label>
                                        <select data-rule-required="true" name="calendar_id" id="calendar_id" class="form-control input-sm" >
                                            <option value="">---</option>
                                            <?php
                                            foreach ($tpl['calendars'] as $k => $v) {
                                                ?>
                                                <option <?php echo ($tpl['booking']['calendar_id'] == $v['id']) ? "selected='selected'" : ""; ?> value="<?php echo $v['id']; ?>" ><?php echo $v['i18n'][$this->controller->tpl['default_language']['id']]['title']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="booking_range"><?php echo __('booking_range'); ?>:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input data-rule-required="true" date-format="<?php echo $tpl['iso_format']; ?>" id="reservationtime" name="date_range" class="form-control pull-right" type="text" value="<?php echo date($tpl['option_arr_values']['date_format'], $tpl['booking']['date_from']) . ' - ' . date($tpl['option_arr_values']['date_format'], $tpl['booking']['date_to']); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="adults"><?php echo __('adults'); ?>:</label>
                                        <input id="adults" class="mini input-sm" type="text" name="adults" size="25" value="<?php echo $tpl['booking']['adults']; ?>" title="adults" placeholder="0">
                                    </div> 
                                    <div class="form-group">
                                        <label class="control-label" for="children"><?php echo __('children'); ?>:</label>
                                        <input id="children" class="mini input-sm" type="text" name="children" size="25" value="<?php echo $tpl['booking']['children']; ?>" title="children" placeholder="0">
                                    </div>
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label class="control-label" for="status"><?php echo __('booking_status'); ?>:</label>
                                            <select data-rule-required="true" name="status" id="status" class="form-control input-sm" >
                                                <option value="">---</option>
                                                <?php
                                                $status_arr = __('status_arr');
                                                foreach ($status_arr as $k => $v) {
                                                    ?>
                                                    <option <?php echo ($tpl['booking']['status'] == $k) ? "selected='selected'" : ""; ?> value="<?php echo $k; ?>" ><?php echo $v; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <fieldset class="form-actions">
                                    <input type="hidden" name="edit_booking" value="1" /> 
                                    <input type="hidden" name="id" value="<?php echo $tpl['booking']['id']; ?>" /> 
                                    <button id="calculate-price-id-1" class="btn btn-default calculate-price-class" autocomplete="off" value="<?php echo __('calculate'); ?>" name="calculate" tabindex="9" type="submit"><i class="fa fa-fw fa-rotate-right"></i>&nbsp;&nbsp;<?php echo __('calculate'); ?></button>
                                    <button id="submit-2" class="btn btn-primary" autocomplete="off" value="Submit" name="submit" tabindex="9" type="submit"><i class="fa fa-fw fa-save"></i>&nbsp;&nbsp;<?php echo __('save'); ?></button>
                                </fieldset>
                            </div>
                            <div class="box box-solid box-primary">
                                <div class="box-header">
                                    <h3 class="box-title"><?php echo __('extras'); ?></h3>
                                    <div class="box-tools pull-right">
                                        <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button class="btn btn-primary btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <?php
                                    if (!empty($tpl['extras']) && count($tpl['extras']) > 0) {
                                        foreach ($tpl['extras'] as $extra) {
                                            ?>
                                            <div class="form-group">
                                                <input type="checkbox" name="extra_id[]" value="<?php echo $extra['id']; ?>" <?php echo (array_key_exists($extra['id'], $tpl['booked_extras'])) ? "checked='checked'" : ""; ?>/>
                                                <label class="control-label" for="">
                                                    <?php
                                                    echo $extra['price'];
                                                    echo ($extra['type'] == 'price') ? "$" : "%";
                                                    ?> ,  <?php echo $extra['title']; ?> 
                                                </label>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        echo "<p>" . __('no_extra_found') . "</p>";
                                    }
                                    ?>
                                </div>
                                <fieldset class="form-actions">
                                    <button id="calculate-price-id-3" class="btn btn-default calculate-price-class" autocomplete="off" value="<?php echo __('calculate'); ?>" name="calculate" tabindex="9" type="submit"><i class="fa fa-fw fa-rotate-right"></i>&nbsp;&nbsp;<?php echo __('calculate'); ?></button>
                                    <button id="submit" class="btn btn-primary" autocomplete="off" value="Submit" name="submit" tabindex="9" type="submit"><i class="fa fa-fw fa-save"></i>&nbsp;&nbsp;<?php echo __('save'); ?></button>
                                </fieldset>
                            </div>
                        </section>
                    </fieldset>
                </div>
                <div id="tab_2" class="tab-pane">
                    <fieldset>
                        <div class="form-group">
                            <label class="control-label" for="title"><?php echo __('booking_title'); ?>:</label>
                            <select name="title" id="title" class="form-control input-sm width_150" >
                                <option value="">---</option>
                                <?php
                                $title_arr = __('title_arr');
                                foreach ($title_arr as $k => $v) {
                                    ?>
                                    <option <?php echo ($tpl['booking']['title'] == $k) ? "selected='selected'" : ""; ?> value="<?php echo $k; ?>" ><?php echo $v; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="first_name"><?php echo __('first_name'); ?>:</label>
                            <input id="first_name" class="form-control input-sm" type="text" name="first_name" size="25" value="<?php echo $tpl['booking']['first_name']; ?>" title="first_name" placeholder="">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="second_name"><?php echo __('second_name'); ?>:</label>
                            <input id="second_name" class="form-control input-sm" type="text" name="second_name" size="25" value="<?php echo $tpl['booking']['second_name']; ?>" title="second_name" placeholder="">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="phone"><?php echo __('phone'); ?>:</label>
                            <input id="phone" class="form-control input-sm" type="text" name="phone" size="25" value="<?php echo $tpl['booking']['phone']; ?>" title="phone" placeholder="">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="email"><?php echo __('email'); ?>:</label>
                            <input id="phone" class="form-control input-sm" type="text" name="email" size="25" value="<?php echo $tpl['booking']['email']; ?>" title="email" placeholder="">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="company"><?php echo __('company'); ?>:</label>
                            <input id="phone" class="form-control input-sm" type="text" name="company" size="25" value="<?php echo $tpl['booking']['company']; ?>" title="company" placeholder="">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="address_1"><?php echo __('address_1'); ?>:</label>
                            <input id="phone" class="form-control input-sm" type="text" name="address_1" size="25" value="<?php echo $tpl['booking']['address_1']; ?>" title="address_1" placeholder="">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="address_2"><?php echo __('address_2'); ?>:</label>
                            <input id="phone" class="form-control input-sm" type="text" name="address_2" size="25" value="<?php echo $tpl['booking']['address_2']; ?>" title="address_2" placeholder="">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="city"><?php echo __('city'); ?>:</label>
                            <input id="phone" class="form-control input-sm" type="text" name="city" size="25" value="<?php echo $tpl['booking']['city']; ?>" title="city" placeholder="">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="state"><?php echo __('state'); ?>:</label>
                            <input id="phone" class="form-control input-sm" type="text" name="state" size="25" value="<?php echo $tpl['booking']['state']; ?>" title="<?php echo $tpl['booking']['state']; ?>" placeholder="">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="zip"><?php echo __('zip'); ?>:</label>
                            <input id="phone" class="form-control input-sm" type="text" name="zip" size="25" value="<?php echo $tpl['booking']['zip']; ?>" title="<?php echo $tpl['booking']['zip']; ?>" placeholder="">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="country"><?php echo __('country'); ?>:</label>
                            <input id="phone" class="form-control input-sm" type="text" name="country" size="25" value="<?php echo $tpl['booking']['country']; ?>" title="<?php echo $tpl['booking']['country']; ?>" placeholder="">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="fax"><?php echo __('fax'); ?>:</label>
                            <input id="phone" class="form-control input-sm" type="text" name="fax" size="25" value="<?php echo $tpl['booking']['fax']; ?>" title="<?php echo $tpl['booking']['fax']; ?>" placeholder="">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="male"><?php echo __('male'); ?>:</label>
                            <select name="male" id="male" class="form-control input-sm width_150" >
                                <option value="">---</option>
                                <?php
                                $male_arr = __('male_arr');
                                foreach ($male_arr as $k => $v) {
                                    ?>
                                    <option <?php echo ($tpl['booking']['male'] == $k) ? "selected='selected'" : ""; ?> value="<?php echo $k; ?>" ><?php echo $v; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="additional"><?php echo __('additional'); ?>:</label>
                            <textarea name="additional" class="form-control" ><?php echo $tpl['booking']['additional']; ?></textarea>
                        </div>
                    </fieldset>
                    <fieldset class="form-actions">
                        <input type="hidden" name="edit_booking" value="1" /> 
                        <input type="hidden" name="id" value="<?php echo $tpl['booking']['id']; ?>" /> 
                        <button id="submit" class="btn btn-primary" autocomplete="off" value="Submit" name="submit" tabindex="9" type="submit"><i class="fa fa-fw fa-save"></i>&nbsp;&nbsp;<?php echo __('save'); ?></button>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</form>
<div id="dialogAddBookingRoom" title="<?php echo htmlspecialchars(__('add_booking_calendar_title')); ?>" style="display:none">
    <form name="dialogAddBookingRoomFrm" id="dialogAddBookingRoomFrmId">
    </form>
</div>
<div id="dialogRoomDelete" title="<?php echo htmlspecialchars(__('booking_calendar_del_title')); ?>" style="display:none">
    <p><?php echo __('calendar_del_body'); ?></p>
</div>
<div id="div_room_id" style="display:none"></div>