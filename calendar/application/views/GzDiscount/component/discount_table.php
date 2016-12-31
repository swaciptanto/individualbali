<div class="overlay"></div>
<div class="loading-img"></div>
<table id="gzhotel-booking-discount-id" class="gzblog-table" cellpadding="0" cellspacing="0" >
    <thead>
        <tr>
            <th><?php echo __('title'); ?></th>
            <th><?php echo __('from_date'); ?></th>
            <th><?php echo __('to_date'); ?></th>
            <th><?php echo __('promo_code'); ?></th>
            <th><?php echo __('discount'); ?></th>
            <th><?php echo __('type'); ?></th>
            <th style="width: 30px;"></th>
            <th style="width: 30px;"></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (count($tpl['discounts']) > 0) {
            foreach ($tpl['discounts'] as $k => $v) {
                ?>
                <tr>
                    <td><?php echo $v['title']; ?></td>
                    <td><?php echo date($tpl['option_arr_values']['date_format'], $v['from_date']); ?></td>
                    <td><?php echo date($tpl['option_arr_values']['date_format'], $v['to_date']); ?></td>
                    <td><?php echo $v['promo_code']; ?></td>
                    <td><?php echo ($v['type'] == 'price') ? Util::currenctFormat($tpl['option_arr_values']['currency'], $v['discount']) : $v['discount'] . ' %'; ?></td>
                    <td><?php echo $v['type']; ?></td>
                    <td><a class="btn btn-success btn-sm icon-edit" href="<?php echo INSTALL_URL; ?>GzDiscount/edit/<?php echo $v['id']; ?>" rev="<?php echo $v['id']; ?>" rel="<?php echo $v['room_type_id']; ?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
                    <td><a class="btn btn-danger btn-sm icon-delete" rev="<?php echo $v['id']; ?>" href="<?php echo INSTALL_URL; ?>?GzDiscount/delete/<?php echo $v['id']; ?>" rel="<?php echo $v['room_type_id']; ?>"><span class="glyphicon glyphicon-remove"></span></a></td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="8">
                <button id="add_discount_id" class="btn btn-primary btn-flat"><?php echo __('Add_Discount'); ?></button>
            </td>
        </tr>
    </tfoot>
</table>