<div class="overlay"></div>
<div class="loading-img"></div>
<table id="gzhotel-booking-price-plan-id" class="gzblog-table left width_100" cellpadding="0" cellspacing="0" >
    <thead>
        <tr>
            <th><?php echo __('is_default'); ?></th>
            <th><?php echo __('title'); ?></th>
            <th><?php echo __('from_date'); ?></th>
            <th><?php echo __('to_date'); ?></th>
            <th><?php echo __('Monday'); ?></th>
            <th><?php echo __('Tuesday'); ?></th>
            <th><?php echo __('Wednesday'); ?></th>
            <th><?php echo __('Thursday'); ?></th>
            <th><?php echo __('Friday'); ?></th>
            <th><?php echo __('Saturday'); ?></th>
            <th><?php echo __('Sunday'); ?></th>
            <?php /*
              <th><?php echo __('adults'); ?></th>
              <th><?php echo __('children'); ?></th>
             * 
             */ ?>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($tpl['price_plan'][0]) && count($tpl['price_plan'][0]) > 0) {
            $boolen_arr = __('boolen_arr');
            foreach ($tpl['price_plan'] as $k => $v) {
                ?>
                <tr>
                    <td><?php echo $boolen_arr[$v['is_default']]; ?></td>
                    <td><?php echo $v['title']; ?></td>
                    <td><?php echo date($tpl['option_arr_values']['date_format'], $v['from_date']); ?></td>
                    <td><?php echo date($tpl['option_arr_values']['date_format'], $v['to_date']); ?></td>
                    <td><?php echo Util::currenctFormat($tpl['option_arr_values']['currency'], $v['monday']); ?></td>
                    <td><?php echo Util::currenctFormat($tpl['option_arr_values']['currency'], $v['tuesday']); ?></td>
                    <td><?php echo Util::currenctFormat($tpl['option_arr_values']['currency'], $v['wednesday']); ?></td>
                    <td><?php echo Util::currenctFormat($tpl['option_arr_values']['currency'], $v['thursday']); ?></td>
                    <td><?php echo Util::currenctFormat($tpl['option_arr_values']['currency'], $v['friday']); ?></td>
                    <td><?php echo Util::currenctFormat($tpl['option_arr_values']['currency'], $v['saturday']); ?></td>
                    <td><?php echo Util::currenctFormat($tpl['option_arr_values']['currency'], $v['sunday']); ?></td>
                    <?php /*
                      <td><?php echo $v['adults']; ?></td>
                      <td><?php echo $v['children']; ?></td>
                     * 
                     */ ?>
                    <td><a class="btn btn-success btn-sm icon-edit" href="<?php echo INSTALL_URL; ?>GzPrice/edit/<?php echo $v['id']; ?>" rev="<?php echo $v['id']; ?>" rel="<?php echo $v['calendar_id']; ?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
                    <td><a class="btn btn-danger btn-sm icon-delete" rev="<?php echo $v['id']; ?>" href="<?php echo INSTALL_URL; ?>GzPrice/delete/<?php echo $v['id']; ?>" rel="<?php echo $v['calendar_id']; ?>"><span class="glyphicon glyphicon-remove"></span></a></td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">
                <?php if (!empty($tpl['calendars'])) { ?>
                    <?php echo __('calendars'); ?>:
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-flat"><?php echo @$tpl['default_calendar']['i18n'][$this->controller->tpl['default_language']['id']]['title']; ?></button>
                        <button type="button" class="btn btn-primary btn-flat dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                            <span class="sr-only"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <?php
                            foreach ($tpl['calendars'] as $k => $v) {
                                ?>
                                <li><a class="room_type_drop_down" href="#" rel="<?php echo $v['id']; ?>"><?php echo $v['i18n'][$this->controller->tpl['default_language']['id']]['title']; ?></a></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                <?php } ?>
            </td>
            <td colspan="12">
                <button id="add_price_plan_id" class="btn btn-primary btn-flat"><?php echo __('Add_Price_Plan'); ?></button>
            </td>
        </tr>
    </tfoot>
</table>