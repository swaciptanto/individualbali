<form name="ABCBookingForm" id="gz-abc-form-id">
    <?php if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) { ?>
        <fieldset>
            <div class="box box-solid box-primary">
                <div class="box-header">
                    <h3 class="box-title"><strong><?php echo __('booking_details'); ?></strong></h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label" for=""><?php echo __('check_in_date'); ?>:</label>
                        <?php echo date($tpl['option_arr_values']['date_format'], $_POST['start_date']); ?>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="">
                            <?php echo __('check_out_date'); ?>:
                        </label>
                        <?php echo date($tpl['option_arr_values']['date_format'], $_POST['end_date']); ?>
                    </div>
                    <?php /*
                    <div class="form-group">
                        <label class="control-label" for="">
                            <?php echo __('adults'); ?>:
                        </label>
                        <?php echo $_POST['adults']; ?>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="">
                            <?php echo __('children'); ?>:
                        </label>
                        <?php echo $_POST['children']; ?>
                    </div> */ ?>
                    <div class="form-group">
                        <label class="control-label" for="">
                            <?php echo ($tpl['option_arr_values']['based_on'] == 'night') ? __('total_nights') : __('total_days'); ?>:</label>
                        <?php echo $tpl['nights']; ?>
                    </div>    
                    <div class="form-group">
                        <label class="control-label" for="">
                            <?php echo __('calendar'); ?>:
                        </label>
                        <?php echo $tpl['calendar']['i18n'][$tpl['select_language']['id']]['title']; ?>
                    </div>   
                </div>
            </div>
            <?php if (count($tpl['extras']) > 0) { ?>
                <div class="box box-solid box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><strong><?php echo __('extras'); ?></strong></h3>
                    </div>
                    <div class="box-body">
                        <?php
                        foreach ($tpl['extras'] as $k => $v) {
                            ?>
                            <div class="form-group">
                                <label class="control-label" for="">
                                    <?php echo $v['title']; ?>
                                </label>
                                <br />
                                <?php echo $v['description']; ?> 
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            <?php } ?>
            <div class="box box-solid box-primary">
                <div class="box-header">
                    <h3 class="box-title"><strong><?php echo __('prices_details'); ?></strong></h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label" for=""><?php echo __('calendar_price'); ?>:</label>
                        <span><?php echo $tpl['prices']['formated_calendars_price']; ?></span>
                        <div class="control-group"></div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for=""><?php echo __('extra_price'); ?>:</label>
                        <span><?php echo $tpl['prices']['formated_extra_price']; ?></span>
                        <div class="control-group"></div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for=""><?php echo __('tax'); ?>:</label>
                        <span><?php echo $tpl['prices']['formated_tax']; ?></span>
                        <div class="control-group"></div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for=""><?php echo __('discount'); ?>:</label>
                        <span><?php echo $tpl['prices']['formated_discount']; ?></span>
                        <div class="control-group"></div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for=""><?php echo __('deposit_price'); ?>:</label>
                        <span><?php echo $tpl['prices']['formated_deposit']; ?></span>
                        <div class="control-group"></div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for=""><?php echo __('total_price'); ?>:</label>
                        <span><?php echo $tpl['prices']['formated_total']; ?></span>
                        <div class="control-group"></div>
                    </div>
                </div>
            </div>
            <?php if ($tpl['option_arr_values']['title'] != 1 || $tpl['option_arr_values']['male'] != 1 || $tpl['option_arr_values']['first_name'] != 1 || $tpl['option_arr_values']['second_name'] != 1 || $tpl['option_arr_values']['phone'] != 1 || $tpl['option_arr_values']['email'] != 1) { ?>
                <div class="box box-solid box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><strong><?php echo __('personal_details'); ?></strong></h3>
                    </div>
                    <div class="box-body">
                        <?php if ($tpl['option_arr_values']['title'] != 1) { ?>
                            <div class="form-group">
                                <label class="control-label" for="title"><?php echo __('booking_title'); ?>:</label>
                                <span><?php
                                    $title_arr = __('title_arr');
                                    echo $title_arr[$_POST['title']];
                                    ?></span>
                            </div>
                        <?php } ?>
                        <?php if ($tpl['option_arr_values']['male'] != 1) { ?>
                            <div class="form-group">
                                <label class="control-label" for="male"><?php echo __('male'); ?>:</label>
                                <span><?php
                                    $male_arr = __('male_arr');
                                    echo $male_arr[$_POST['male']];
                                    ?></span>
                                <div class="control-group"></div>
                            </div>
                        <?php } ?>
                        <?php if ($tpl['option_arr_values']['first_name'] != 1) { ?>
                            <div class="control-group"></div>
                            <div class="form-group">
                                <label class="control-label" for="first_name"><?php echo __('first_name'); ?>:</label>
                                <span><?php echo $_POST['first_name']; ?></span>
                                <div class="control-group"></div>
                            </div>
                        <?php } ?>
                        <?php if ($tpl['option_arr_values']['second_name'] != 1) { ?>
                            <div class="form-group">
                                <label class="control-label" for="second_name"><?php echo __('second_name'); ?>:</label>
                                <span><?php echo $_POST['second_name']; ?></span>
                                <div class="control-group"></div>
                            </div>
                        <?php } ?>
                        <?php if ($tpl['option_arr_values']['phone'] != 1) { ?>
                            <div class="form-group">
                                <label class="control-label" for="phone"><?php echo __('phone'); ?>:</label>
                                <span><?php echo $_POST['phone']; ?></span>
                                <div class="control-group"></div>
                            </div>
                        <?php } ?>
                        <?php if ($tpl['option_arr_values']['email'] != 1) { ?>
                            <div class="form-group">
                                <label class="control-label" for="email"><?php echo __('email'); ?>:</label>
                                <span><?php echo $_POST['email']; ?></span>
                                <div class="control-group"></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <?php if ($tpl['option_arr_values']['company'] != 1 || $tpl['option_arr_values']['address_1'] != 1 || $tpl['option_arr_values']['address_2'] != 1 || $tpl['option_arr_values']['state'] != 1 || $tpl['option_arr_values']['city'] != 1 || $tpl['option_arr_values']['zip'] != 1 || $tpl['option_arr_values']['country'] != 1 || $tpl['option_arr_values']['fax'] != 1) { ?>
                <div class="box box-solid box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><strong><?php echo __('billing_address'); ?></strong></h3>
                    </div>
                    <div class="box-body">
                        <?php if ($tpl['option_arr_values']['company'] != 1) { ?>
                            <div class="form-group">
                                <label class="control-label" for="company"><?php echo __('company'); ?>:</label>
                                <span><?php echo $_POST['company']; ?></span>
                                <div class="control-group"></div>
                            </div>
                        <?php } ?>
                        <?php if ($tpl['option_arr_values']['address_1'] != 1) { ?>
                            <div class="form-group">
                                <label class="control-label" for="address_1"><?php echo __('address_1'); ?>:</label>
                                <span><?php echo $_POST['address_1']; ?></span>
                                <div class="control-group"></div>
                            </div>
                        <?php } ?>
                        <?php if ($tpl['option_arr_values']['address_2'] != 1) { ?>
                            <div class="form-group">
                                <label class="control-label" for="address_2"><?php echo __('address_2'); ?>:</label>
                                <span><?php echo $_POST['address_2']; ?></span>
                                <div class="control-group"></div>
                            </div>
                        <?php } ?>
                        <?php if ($tpl['option_arr_values']['city'] != 1) { ?>
                            <div class="form-group">
                                <label class="control-label" for="city"><?php echo __('city'); ?>:</label>
                                <span><?php echo $_POST['city']; ?></span>
                                <div class="control-group"></div>
                            </div>
                        <?php } ?>
                        <?php if ($tpl['option_arr_values']['state'] != 1) { ?>
                            <div class="form-group">
                                <label class="control-label" for="state"><?php echo __('state'); ?>:</label>
                                <span><?php echo $_POST['state']; ?></span>
                                <div class="control-group"></div>
                            </div>
                        <?php } ?>
                        <?php if ($tpl['option_arr_values']['zip'] != 1) { ?>
                            <div class="form-group">
                                <label class="control-label" for="zip"><?php echo __('zip'); ?>:</label>
                                <span><?php echo $_POST['zip']; ?></span>
                                <div class="control-group"></div>
                            </div>
                        <?php } ?>
                        <?php if ($tpl['option_arr_values']['country'] != 1) { ?>
                            <div class="form-group">
                                <label class="control-label" for="country"><?php echo __('country'); ?>:</label>
                                <span><?php echo $_POST['country']; ?></span>
                                <div class="control-group"></div>
                            </div>
                        <?php } ?>
                        <?php if ($tpl['option_arr_values']['fax'] != 1) { ?>
                            <div class="form-group">
                                <label class="control-label" for="fax"><?php echo __('fax'); ?>:</label>
                                <span><?php echo $_POST['fax']; ?></span>
                                <div class="control-group"></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <?php if ($tpl['option_arr_values']['enable_payment'] == 1) { ?>
                <div class="box box-solid box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><strong><?php echo __('payment_details'); ?></strong></h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label class="control-label" for="payment_method"><?php echo __('payment_method'); ?>:</label>
                            <span>
                                <?php
                                $payment_method_arr = __('payment_method_arr');
                                echo $payment_method_arr[$_POST['payment_method']];
                                ?></span>
                            <div class="control-group"></div>
                        </div>
                    </div>
                </div>
                <?php
                if ($_POST['payment_method'] == 'credit_card') {
                    ?>
                    <div class="box box-solid box-primary" id="credit_card_details">
                        <div class="box-header">
                            <h3 class="box-title"><strong><?php echo __('credit_card_details'); ?></strong></h3>
                        </div>
                        <div class="box-body">
                            <div >
                                <div class="form-group">
                                    <label class="control-label" for="cc_type"><?php echo __('label_cc_type'); ?>:</label>
                                    <span><?php
                                        $cc_type = __('cc_type');
                                        echo $cc_type[$_POST['cc_type']];
                                        ?></span>
                                    <div class="control-group"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="cc_num"><?php echo __('cc_num'); ?>:</label>
                                    <span><?php echo $_POST['cc_num']; ?></span>
                                    <div class="control-group"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="cc_code"><?php echo __('cc_code'); ?>:</label>
                                    <span><?php echo $_POST['cc_code']; ?></span>
                                    <div class="control-group"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label left" for="cc_exp_year"><?php echo __('cc_exp_date'); ?>:</label>
                                    <span class="medium  margin-right-5"><?php
                                        $month_arr = __('month_arr');
                                        echo $month_arr[$_POST['cc_exp_month']];
                                        ?></span>
                                    <span class="mini "><?php echo $_POST['cc_exp_year']; ?></span>
                                    <div class="control-group"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                } elseif ($_POST['payment_method'] == 'bank_acount') {
                    ?>
                    <div class="box box-solid box-primary" id="bank_acount_details">
                        <div class="box-header">
                            <h3 class="box-title"><strong><?php echo __('bank_acount_details'); ?></strong></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label class="control-label" for=""><?php echo __('bank_info'); ?>:</label>
                                <span><?php echo $tpl['option_arr_values']['bank_account_info']; ?></span>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            <?php } ?>
            <?php if ($tpl['option_arr_values']['additional'] != 1) { ?>
                <div class="box box-solid box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><strong><?php echo __('additional'); ?></strong></h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label class="control-label" for="additional"><?php echo __('additional'); ?>:</label>
                            <span><?php echo $_POST['additional']; ?></span>
                            <div class="control-group"></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </fieldset>

        <fieldset>
            <div class="box box-solid box-primary margin-0">
                <div class="box-body  text-center">
                    <?php
                    foreach ($_POST as $name => $value) {
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
                    ?>                
                    <input type="hidden" name="create_booking" value="1" /> 
                    <button data-style="expand-left" class="btn btn-default btn-danger ladda-button" id="back_booking_frm_btn_id" autocomplete="off" value="<?php echo __('back'); ?>" name="back" tabindex="9" type="submit">
                        <span class="ladda-label">               
                            <?php echo __('back'); ?>
                        </span>        
                        <span class="ladda-spinner"></span>
                    </button>
                    <button data-style="expand-left" class="btn btn-warning btn-warning ladda-button" id="checkout_frm_btn_id" autocomplete="off" value="<?php echo __('booking'); ?>" name="submit" tabindex="9" type="submit">
                        <span class="ladda-label"><i class="fa fa-gavel"></i>&nbsp;&nbsp;&nbsp;<?php echo __('booking'); ?></span>
                        <span class="ladda-spinner"></span>
                    </button>
                </div>
            </div>
        </fieldset>
    <?php } else {
        ?>
        <div class="alert alert-warning  in">
            <i class="fa-fw fa fa-warning"></i>
            <strong><?php echo __('warning'); ?></strong>
            <?php echo __('rooms_error_message'); ?>
        </div>
        <fieldset>
            <div class="box box-solid box-primary margin-0">
                <div class="box-body">
                    <button style="float: left;" class="btn btn-default" id="back_booking_frm_btn_id" autocomplete="off" value="<?php echo __('back'); ?>" name="back" tabindex="9" type="submit"><?php echo __('back'); ?></button>
                </div>
            </div>
        </fieldset>
        <?php
    }
    ?>
</form>