<section class="content-header">
    <h1>
        <?php echo __('import'); ?>
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="<?php echo INSTALL_URL; ?>"><i class="fa fa-dashboard"></i> 
                <?php
                echo __('home');
                ?>
            </a>
        </li>
        <li><a href="<?php echo INSTALL_URL; ?>GzBooking/index"><?php echo __('booking'); ?></a></li>
        <li class="active"><?php echo __('import'); ?></li>
    </ol>
</section>
<?php
require_once VIEWS_PATH . 'Layouts/admin/error_notice.php';
?>
<section class="content">
    <div class="row">
        <div class="col-md-12 ui-sortable">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title"><?php echo __('import'); ?></h4>
                </div>
                <div class="panel-body">
                    <form id="import-frm-id" class="frm-class import-frm-class" action="<?php echo INSTALL_URL; ?>GzBooking/import" method="post" name="import-frm" enctype="multipart/form-data">
                        <div class="callout callout-info" >
                            <h4><?php echo __('import_data') . ' ' . __('booking'); ?></h4>
                            <p><?php echo __('import_info'); ?></p>
                        </div>
                        <div class="padding-19 nav-tabs-custom left width_100">
                            <div class="overlay"></div>
                            <div class="loading-img"></div>
                            <div id="import_table">
                                <div style="overflow-x: auto; overflow-y: hidden;">
                                    <table class="table table-striped table-bordered dataTable no-footer" cellpadding="0" cellspacing="0" >
                                        <thead>
                                            <tr>
                                                <th><?php echo __('id'); ?></th>
                                                <th><?php echo __('calendar_id'); ?></th>
                                                <th><?php echo __('booking_number'); ?></th>
                                                <th><?php echo __('title'); ?></th>
                                                <th><?php echo __('first_name'); ?></th>
                                                <th><?php echo __('second_name'); ?></th>
                                                <th><?php echo __('phone'); ?></th>
                                                <th><?php echo __('email'); ?></th>
                                                <th><?php echo __('company'); ?></th>
                                                <th><?php echo __('address_1'); ?></th>
                                                <th><?php echo __('address_2'); ?></th>
                                                <th><?php echo __('state'); ?></th>
                                                <th><?php echo __('city'); ?></th>
                                                <th><?php echo __('zip'); ?></th>
                                                <th><?php echo __('country'); ?></th>
                                                <th><?php echo __('fax'); ?></th>
                                                <th><?php echo __('male'); ?></th>
                                                <th><?php echo __('additional'); ?></th>
                                                <th><?php echo __('date_from'); ?></th>
                                                <th><?php echo __('date_to'); ?></th>
                                                <th><?php echo __('booking_status'); ?></th>
                                                <th><?php echo __('promo_code'); ?></th>
                                                <th><?php echo __('amount'); ?></th>
                                                <th><?php echo __('calendars_price'); ?></th>   
                                                <th><?php echo __('extra_price'); ?></th>
                                                <th><?php echo __('discount'); ?></th>
                                                <th><?php echo __('total'); ?></th>
                                                <th><?php echo __('tax'); ?></th>
                                                <th><?php echo __('security'); ?></th>
                                                <th><?php echo __('deposit'); ?></th>
                                                <th><?php echo __('payment_method'); ?></th>
                                                <th><?php echo __('adults'); ?></th>
                                                <th><?php echo __('children'); ?></th>
                                                <th><?php echo __('date'); ?></th>
                                                <th><?php echo __('label_cc_type'); ?></th>
                                                <th><?php echo __('cc_num'); ?></th>
                                                <th><?php echo __('cc_code'); ?></th>
                                                <th><?php echo __('cc_exp_month'); ?></th>
                                                <th><?php echo __('cc_exp_year'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($_POST['import'])) {
                                                if (!empty($tpl['booking_arr'])) {
                                                    foreach ($tpl['booking_arr'] as $key => $value) {
                                                        ?>
                                                        <tr>
                                                            <td><input class="mini" type="text" name="id[]" value="<?php echo $value[0]; ?>"></td>
                                                            <td><input class="mini" type="text" name="calendar_id[]" value="<?php echo $value[1]; ?>"></td>
                                                            <td><input class="mini" type="text" name="booking_number[]" value="<?php echo $value[2]; ?>"></td>
                                                            <td><input class="mini" type="text" name="title[]" value="<?php echo $value[3]; ?>"></td>
                                                            <td><input class="mini" type="text" name="first_name[]" value="<?php echo $value[4]; ?>"></td>
                                                            <td><input class="mini" type="text" name="second_name[]" value="<?php echo $value[5]; ?>"></td>
                                                            <td><input class="mini" type="text" name="phone[]" value="<?php echo $value[6]; ?>"></td>
                                                            <td><input class="mini" type="text" name="email[]" value="<?php echo $value[7]; ?>"></td>
                                                            <td><input class="mini" type="text" name="company[]" value="<?php echo $value[8]; ?>"></td>
                                                            <td><input type="text" name="address_1[]" value="<?php echo $value[9]; ?>"></td>
                                                            <td><input type="text" name="address_2[]" value="<?php echo $value[10]; ?>"></td>
                                                            <td><input class="mini" type="text" name="city[]" value="<?php echo $value[12]; ?>"></td>
                                                            <td><input class="mini" type="text" name="state[]" value="<?php echo $value[11]; ?>"></td>
                                                            <td><input class="mini" type="text" name="zip[]" value="<?php echo $value[13]; ?>"></td>
                                                            <td><input class="mini" type="text" name="country[]" value="<?php echo $value[14]; ?>"></td>
                                                            <td><input class="mini" type="text" name="fax[]" value="<?php echo $value[15]; ?>"></td>
                                                            <td><input class="mini" type="text" name="male[]" value="<?php echo $value[16]; ?>"></td>
                                                            <td><input class="mini" type="text" name="additional[]" value="<?php echo $value[17]; ?>"></td>
                                                            <td><input class="mini" type="text" name="date_from[]" value="<?php echo $value[18]; ?>"></td>
                                                            <td><input class="mini" type="text" name="date_to[]" value="<?php echo $value[19]; ?>"></td>
                                                            <td><input class="mini" type="text" name="status[]" value="<?php echo $value[20]; ?>"></td>
                                                            <td><input class="mini" type="text" name="promo_code[]" value="<?php echo $value[21]; ?>"></td>
                                                            <td><input class="mini" type="text" name="amount[]" value="<?php echo $value[22]; ?>"></td>
                                                            <td><input class="mini" type="text" name="calendars_price[]" value="<?php echo $value[23]; ?>"></td>
                                                            <td><input class="mini" type="text" name="extra_price[]" value="<?php echo $value[24]; ?>"></td>
                                                            <td><input class="mini" type="text" name="discount[]" value="<?php echo $value[25]; ?>"></td>
                                                            <td><input class="mini" type="text" name="total[]" value="<?php echo $value[26]; ?>"></td>
                                                            <td><input class="mini" type="text" name="tax[]" value="<?php echo $value[27]; ?>"></td>
                                                            <td><input class="mini" type="text" name="security[]" value="<?php echo $value[28]; ?>"></td>
                                                            <td><input class="mini" type="text" name="deposit[]" value="<?php echo $value[29]; ?>"></td>
                                                            <td><input class="mini" type="text" name="payment_method[]" value="<?php echo $value[30]; ?>"></td>
                                                            <td><input class="mini" type="text" name="adults[]" value="<?php echo $value[31]; ?>"></td>
                                                            <td><input class="mini" type="text" name="children[]" value="<?php echo $value[32]; ?>"></td>
                                                            <td><input class="mini" type="text" name="date[]" value="<?php echo $value[33]; ?>"></td>
                                                            <td><input class="mini" type="text" name="cc_type[]" value="<?php echo $value[34]; ?>"></td>
                                                            <td><input class="mini" type="text" name="cc_num[]" value="<?php echo $value[35]; ?>"></td>
                                                            <td><input class="mini" type="text" name="cc_code[]" value="<?php echo $value[36]; ?>"></td>
                                                            <td><input class="mini" type="text" name="cc_exp_month[]" value="<?php echo $value[37]; ?>"></td>
                                                            <td><input class="mini" type="text" name="cc_exp_year[]" value="<?php echo $value[38]; ?>"></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td><strong style="float: right"><?php echo __('total_row'); ?>:</strong></td>
                                                        <td colspan="37"><?php echo $tpl['row_count'] ?></td>
                                                    </tr>
                                                </tfoot>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td>99</td>
                                                <td>1</td>
                                                <td>1275362525</td>
                                                <td>mr</td>
                                                <td>first</td>
                                                <td>first second</td>
                                                <td>1234567891</td>
                                                <td>admin@admin.com</td>
                                                <td>company</td>
                                                <td>street 1</td>
                                                <td>street 2</td>
                                                <td>city</td>
                                                <td>state</td>
                                                <td>9000</td>
                                                <td>USA</td>
                                                <td>fax</td>
                                                <td>male</td>
                                                <td>additional</td>
                                                <td>1424822400</td>
                                                <td>1424995200</td>
                                                <td>pending</td>
                                                <td>123</td>
                                                <td>1000</td>
                                                <td>300</td>
                                                <td>0</td>
                                                <td>30</td>
                                                <td>1000</td>
                                                <td>700</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>bank_acount</td>
                                                <td>2</td>
                                                <td>3</td>
                                                <td>2015-02-24 12:51:17</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>100</td>
                                                <td>1</td>
                                                <td>1275362525</td>
                                                <td>mr</td>
                                                <td>first</td>
                                                <td>first second</td>
                                                <td>1234567891</td>
                                                <td>admin@admin.com</td>
                                                <td>company</td>
                                                <td>street 1</td>
                                                <td>street 2</td>
                                                <td>city</td>
                                                <td>state</td>
                                                <td>9000</td>
                                                <td>USA</td>
                                                <td>fax</td>
                                                <td>male</td>
                                                <td>additional</td>
                                                <td>1424822400</td>
                                                <td>1424995200</td>
                                                <td>pending</td>
                                                <td>123</td>
                                                <td>1000</td>
                                                <td>300</td>
                                                <td>0</td>
                                                <td>30</td>
                                                <td>1000</td>
                                                <td>700</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>bank_acount</td>
                                                <td>2</td>
                                                <td>3</td>
                                                <td>2015-02-24 12:51:17</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="39">
                                                    <?php echo __('etc'); ?>
                                                </td>
                                            </tr>
                                            </tbody>
                                        <?php } ?>

                                    </table>
                                </div>
                            </div>
                            <br /><br />
                            <?php
                            if (!empty($tpl['booking_arr'])) {
                                ?>
                                <fieldset class="form-actions">
                                    <input type="hidden" name="save" value="1" /> 
                                    <button id="save-submit-id" class="btn btn-default" autocomplete="off" value="<?php echo __('save'); ?>" name="submit" tabindex="9" type="submit"><?php echo __('save'); ?></button>
                                </fieldset>
                                <?php
                            } else {
                                ?>
                                <fieldset class="scheduler-border bg-light-orange">
                                    <legend class="scheduler-border"><?php echo __('import'); ?></legend>
                                    <br />
                                    <div class="form-group">
                                        <label class="control-label" for="csv_file">
                                            <?php echo __('label_csv_file'); ?>:
                                        </label>
                                        <input class="form-control" type="file" name="csv_file">
                                    </div>
                                </fieldset>
                                <fieldset class="form-actions">
                                    <input type="hidden" name="import" value="1" /> 
                                    <button id="import-submit-id" class="btn btn-default" autocomplete="off" value="<?php echo __('import'); ?>" name="submit" tabindex="9" type="submit"><?php echo __('import'); ?></button>
                                </fieldset>
                            <?php }
                            ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>