<!-- Left side column. contains the logo and sidebar -->
<aside class="left-side sidebar-offcanvas">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
<!--    modified: hide
        <div class="user-panel">
            <div class="pull-left image">
                <?php if (is_file(INSTALL_PATH . UPLOAD_PATH . 'avatar/thumb/' . $user['avatar'])) { ?>
                    <img src="<?php echo INSTALL_URL . UPLOAD_PATH . 'avatar/thumb/' . $user['avatar']; ?>" />
                    <?php
                } else {
                    ?>
                    <img src="<?php echo INSTALL_URL . IMG_PATH . 'user.png'; ?>" />
                    <?php
                }
                ?>
            </div>
            <div class="pull-left info">
                <?php
                if (!empty($user['first']) && !empty($user['last'])) {
                    ?>
                    <p><?php echo $user['first'] . ' ' . $user['last']; ?></p>
                    <?php
                } else {
                    ?>
                    <p><?php echo $user['email']; ?></p>
                    <?php
                }
                ?>
            </div>
        </div>-->

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
<!--        modified: hide
            <li class="<?php echo (@$_REQUEST['controller'] == 'GzAdmin') ? "active" : ""; ?>">
                <a href="<?php echo INSTALL_URL; ?>GzAdmin/dashboard">
                    <i class="fa fa-dashboard"></i> <span><?php echo __('dashboard'); ?></span>
                </a>
            </li>-->
            <?php if ($this->controller->isAdmin()) { ?>
                <li class="treeview <?php echo (@$_REQUEST['controller'] == 'GzCalendar') ? "active" : ""; ?>">
                    <a href="#">
                        <i class="fa fa-fw fa-building-o"></i>
                        <span><?php echo __('calendars'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php echo (@$_REQUEST['controller'] == 'GzCalendar' && @$_REQUEST['action'] == 'index') ? "active" : ""; ?>"><a href="<?php echo INSTALL_URL; ?>GzCalendar/index"><i class="fa    fa-caret-right"></i><?php echo __('all_calendars'); ?></a></li>
                        <li class="<?php echo (@$_REQUEST['controller'] == 'GzCalendar' && @$_REQUEST['action'] == 'create') ? "active" : ""; ?>"><a href="<?php echo INSTALL_URL; ?>GzCalendar/create"><i class="fa    fa-caret-right"></i><?php echo __('add_calendars'); ?></a></li>
                        <li class="<?php echo (@$_REQUEST['controller'] == 'GzCalendar' && @$_REQUEST['action'] == 'block') ? "active" : ""; ?>"><a href="<?php echo INSTALL_URL; ?>GzCalendar/block"><i class="fa    fa-caret-right"></i><?php echo __('calendar_blocking'); ?></a></li>
                    </ul>
                </li>
            <?php } ?>
<!--        modified: hide
            <li class="treeview <?php echo (@$_REQUEST['controller'] == 'GzBooking') ? "active" : ""; ?>">
                <a href="#">
                    <i class="fa fa-fw fa-calendar-o"></i>
                    <span><?php echo __('bookings'); ?></span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php echo (@$_REQUEST['controller'] == 'GzBooking' && @$_REQUEST['action'] == 'index') ? "active" : ""; ?>"><a href="<?php echo INSTALL_URL; ?>GzBooking/index"><i class="fa    fa-caret-right"></i><?php echo __('all_bookings'); ?></a></li>
                    <li class="<?php echo (@$_REQUEST['controller'] == 'GzBooking' && @$_REQUEST['action'] == 'create') ? "active" : ""; ?>"><a href="<?php echo INSTALL_URL; ?>GzBooking/create"><i class="fa    fa-caret-right"></i><?php echo __('add_bookings'); ?></a></li>
                    <li class="<?php echo (@$_REQUEST['controller'] == 'GzBooking' && @$_REQUEST['action'] == 'calendar') ? "active" : ""; ?>"><a href="<?php echo INSTALL_URL; ?>GzBooking/availability"><i class="fa    fa-caret-right"></i><?php echo __('availability'); ?></a></li>
                </ul>
            </li>
            <li class="treeview <?php echo (@$_REQUEST['controller'] == 'GzInvoice') ? "active" : ""; ?>">
                <a href="#">
                    <i class="fa fa-fw fa-credit-card"></i>
                    <span><?php echo __('invoices'); ?></span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php echo (@$_REQUEST['controller'] == 'GzInvoice' && @$_REQUEST['action'] == 'index') ? "active" : ""; ?>"><a href="<?php echo INSTALL_URL; ?>GzInvoice/index"><i class="fa    fa-caret-right"></i><?php echo __('all_invoice'); ?></a></li>
                    <li class="<?php echo (@$_REQUEST['controller'] == 'GzInvoice' && @$_REQUEST['action'] == 'create') ? "active" : ""; ?>"><a href="<?php echo INSTALL_URL; ?>GzInvoice/create"><i class="fa    fa-caret-right"></i><?php echo __('add_invoice'); ?></a></li>
                </ul>
            </li>-->
            <?php if ($this->controller->isAdmin()) { ?>
                <li class="treeview <?php echo (in_array($_REQUEST['controller'], array('GzPrice', 'GzDiscount', 'GzExtra'))) ? "active" : ""; ?>">
                    <a href="#">
                        <i class="fa fa-fw fa-money"></i>
                        <span><?php echo __('price_manager'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu <?php echo (in_array($_REQUEST['controller'], array('GzPrice', 'GzDiscount', 'GzExtra'))) ? "active" : ""; ?>">
                        <li class="<?php echo (@$_REQUEST['controller'] == 'GzPrice' && @$_REQUEST['action'] == 'price') ? "active" : ""; ?>"><a href="<?php echo INSTALL_URL; ?>GzPrice/price"><i class="fa    fa-caret-right"></i><?php echo __('price_plan'); ?></a></li>
<!--                    modified: hide                        
                        <li class="<?php echo (@$_REQUEST['controller'] == 'GzDiscount' && @$_REQUEST['action'] == 'discount') ? "active" : ""; ?>"><a href="<?php echo INSTALL_URL; ?>GzDiscount/discount"><i class="fa    fa-caret-right"></i><?php echo __('discount'); ?></a></li>
                        <li class="<?php echo (@$_REQUEST['controller'] == 'GzExtra' && @$_REQUEST['action'] == 'extra') ? "active" : ""; ?>"><a href="<?php echo INSTALL_URL; ?>GzExtra/extra"><i class="fa    fa-caret-right"></i><?php echo __('extras'); ?></a></li>
-->
                    </ul>
                </li>
            <?php } ?>
<!--        modified: hide
            <li class="treeview <?php echo (in_array($_REQUEST['controller'], array('GzStatistic'))) ? "active" : ""; ?>">
                <a href="#">
                    <i class="fa fa-fw fa-bar-chart-o"></i>
                    <span><?php echo __('statistics'); ?></span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu <?php echo (@$_REQUEST['controller'] == 'GzStatistic') ? "active" : ""; ?>">
                    <li class="<?php echo (@$_REQUEST['controller'] == 'GzStatistic' && @$_REQUEST['action'] == 'booking') ? "active" : ""; ?>"><a href="<?php echo INSTALL_URL; ?>GzStatistic/booking"><i class="fa    fa-caret-right"></i><?php echo __('booking_statistics'); ?></a></li>
                    <li class="<?php echo (@$_REQUEST['controller'] == 'GzStatistic' && @$_REQUEST['action'] == 'amount') ? "active" : ""; ?>"><a href="<?php echo INSTALL_URL; ?>GzStatistic/amount"><i class="fa    fa-caret-right"></i><?php echo __('amount_statistics'); ?></a></li>
                    <li class="<?php echo (@$_REQUEST['controller'] == 'GzStatistic' && @$_REQUEST['action'] == 'people') ? "active" : ""; ?>"><a href="<?php echo INSTALL_URL; ?>GzStatistic/people"><i class="fa    fa-caret-right"></i><?php echo __('people_statistics'); ?></a></li>
                </ul>
            </li>
            <?php if ($this->controller->isAdmin()) { ?>
                <li class="treeview <?php echo (@$_REQUEST['controller'] == 'GzUser') ? "active" : ""; ?>">
                    <a href="#">
                        <i class="fa fa-fw fa-user"></i>
                        <span><?php echo __('users'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php echo (@$_REQUEST['controller'] == 'GzUser' && @$_REQUEST['action'] == 'index') ? "active" : ""; ?>"><a href="<?php echo INSTALL_URL; ?>GzUser/index"><i class="fa    fa-caret-right"></i><?php echo __('all_users'); ?></a></li>
                        <li class="<?php echo (@$_REQUEST['controller'] == 'GzUser' && @$_REQUEST['action'] == 'create') ? "active" : ""; ?>"><a href="<?php echo INSTALL_URL; ?>GzUser/create"><i class="fa    fa-caret-right"></i><?php echo __('add_users'); ?></a></li>
                    </ul>
                </li>
            <?php } ?>
            <?php if ($this->controller->isAdmin()) { ?>
                <li class="<?php echo (@$_REQUEST['controller'] == 'GzSettings' && @$_REQUEST['action'] == 'languages') ? "active" : ""; ?>">
                    <a href="<?php echo INSTALL_URL; ?>GzSettings/languages">
                        <i class="fa fa-fw fa-book"></i> <span><?php echo __('Languages'); ?></span>
                    </a>
                </li>
                <li class="<?php echo (in_array($_REQUEST['controller'], array('GzInstall'))) ? "active" : ""; ?>">
                    <a href="<?php echo INSTALL_URL; ?>GzInstall/index">
                        <i class="fa fa-fw fa-download"></i> <span><?php echo __('menu_install'); ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo INSTALL_URL; ?>?controller=GzPreview&action=index" target="_blank">
                        <i class="fa fa-fw fa-laptop"></i> <span><?php echo __('menu_preview'); ?></span>
                    </a>
                </li>-->
            <?php } ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>