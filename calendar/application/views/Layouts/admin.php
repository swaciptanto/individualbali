<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Availability Booking Calendar PHP</title>
        <?php
        $user = $this->controller->getUser();

        foreach ($this->controller->css as $css) {
            echo '<link type="text/css" rel="stylesheet" href="' . (isset($css['remote']) && $css['remote'] ? NULL : INSTALL_URL) . $css['path'] . $css['file'] . '" />';
        }
        ?>

        <?php
        foreach ($this->controller->js as $js) {
            echo '<script type="text/javascript" src="' . (isset($js['remote']) && $js['remote'] ? NULL : INSTALL_URL) . $js['path'] . $js['file'] . '"></script>';
        }
        ?>
    </head>
    <body class="skin-blue">
        <header class="header">
<!--        modified: hide
            <a href="http://www.gzscripts.com/" class="logo" title="GZ Scripts|Booking System">
                 Availability Booking Calendar
            </a>-->
            <?php
            require_once VIEWS_PATH . 'Layouts/admin/menu/navbar_static_top.php';
            ?>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left" id="gz-abc-container-id">
            <?php
            require_once VIEWS_PATH . 'Layouts/admin/menu/sidebar.php';
            ?>
            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <?php
                require $content_tpl;
                ?>
            </aside><!-- /.right-side -->
            <a class="btn btn-icon btn-info btn-scroll-to-top fade" data-click="scroll-top" href="javascript:;">
                <i class="fa fa-angle-up"></i>
            </a>
            <?php
            require_once VIEWS_PATH . 'Layouts/admin/footer.php';
            ?>
        </div>
        <div id="container-abc-url-id" style="display: none;"><?php echo INSTALL_URL; ?></div>
    </div>

