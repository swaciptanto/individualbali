<div class="overlay"></div>
<div class="loading-img"></div>
<table id="gzhotel-booking-extra-id" class="gzblog-table" cellpadding="0" cellspacing="0" >
    <thead>
        <tr>
            <th><?php echo __('image'); ?></th>
            <th><?php echo __('name'); ?></th>
            <th><?php echo __('price'); ?></th>
            <th><?php echo __('label_extra_type'); ?></th>
            <th><?php echo __('label_extra_per'); ?></th>
            <th style="width: 35px"></th>
            <th style="width: 35px"></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (count($tpl['extras'][0]) > 0) {
            foreach ($tpl['extras'] as $k => $v) {
                ?>
                <tr>
                    <td>
                        <?php if (is_file(INSTALL_PATH . UPLOAD_PATH . 'extra/thumb/' . $v['img'])) { ?>
                            <div class="view view-tenth">   
                                <img src="<?php echo INSTALL_URL . UPLOAD_PATH . 'extra/thumb/' . $v['img']; ?>" />
                                <div class="mask">
                                    <a href="<?php echo INSTALL_URL; ?>GzExtra/cropImage/<?php echo $v['id']; ?>" class="info btn btn-app btn-primary gallery-crop" ><i class="fa fa-crop"></i><?php echo __('crop'); ?></a>
                                    <a rev="<?php echo $v['id']; ?>" class="info btn btn-app btn-danger gallery-delete" href="<?php echo INSTALL_URL; ?>GzExtra/deleteImage/<?php echo $v['id']; ?>"><i class="fa fa-times"></i><?php echo __('remove'); ?></a>
                                </div>
                            </div>
                        <?php } ?>
                    </td>
                    <?php
                    $extra_per = __('extra_per');
                    $extra_type = __('extra_type');
                    ?>
                    <td><?php echo $v['title']; ?></td>
                    <td><?php echo ($v['type'] == 'price') ? Util::currenctFormat($tpl['option_arr_values']['currency'], $v['price']) : $v['price'] . ' %'; ?></td>
                    <td><?php echo $extra_per[$v['per']]; ?></td>
                    <td><?php echo $extra_type[$v['type']]; ?></td>
                    <td><a class="btn btn-success btn-sm icon-edit" href="<?php echo INSTALL_URL; ?>GzExtra/edit/<?php echo $v['id']; ?>" rev="<?php echo $v['id']; ?>" ><span class="glyphicon glyphicon-pencil"></span></a></td>
                    <td><a class="btn btn-danger btn-sm icon-delete" rev="<?php echo $v['id']; ?>" href="<?php echo INSTALL_URL; ?>GzExtra/delete/<?php echo $v['id']; ?>" ><span class="glyphicon glyphicon-remove"></span></a></td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="14">
                <button id="add_extra_id" class="btn btn-primary btn-flat"><?php echo __('Add_Extra'); ?></button>
            </td>
        </tr>
    </tfoot>
</table>