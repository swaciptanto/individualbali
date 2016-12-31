<?php
if (!empty($_POST['payment_method']) && in_array($_POST['payment_method'], array('paypal', '2checkout', 'authorize'))) {
    ?>
    <div class="alert alert-warning  in">
        <i class="fa-fw fa fa-warning"></i>
        <strong><?php echo __('warning'); ?></strong>
        <?php echo __('payment_redirect_wait'); ?>
    </div>
    <?php
} else {
    ?>
    <div class="alert alert-success  in">
        <i class="fa-fw fa fa-check"></i>
        <strong><?php echo __('success'); ?></strong>
        <?php echo __('booking_save'); ?>
    </div>
    <?php
}
?>
<?php
if (!empty($_REQUEST['payment_method']) && $_REQUEST['payment_method'] == 'paypal') {
    require 'component/payment/paypal.php';
} elseif (!empty($_REQUEST['payment_method']) && $_REQUEST['payment_method'] == '2checkout') {
    require 'component/payment/2checkout.php';
} elseif (!empty($_REQUEST['payment_method']) && $_REQUEST['payment_method'] == 'authorize') {
    require 'component/payment/authorize.php';
}
?>