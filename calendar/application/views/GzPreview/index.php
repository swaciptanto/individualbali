<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Availability Booking Calendar PHP</title>
        <style>
            .cal-div{
                width: 30%; 
                margin: 0 auto; 
                height: 400px;
            }
            @media (max-width: 479px) {
                .cal-div{
                    width: 100% !important; 
                }
            }
        </style>
    </head>
    <body>
        <div class="cal-div">
            <script type="text/javascript" src="<?php echo INSTALL_URL; ?>index.php?controller=GzFront&action=load&cid[]=<?php echo (empty($_REQUEST['calendars_id'])) ? $tpl['arr'][0]['id'] : implode('&cid[]=', $_POST['calendars_id']); ?>&view_month=<?php echo (empty($_POST['months'])) ? '1' : $_POST['months']; ?>"></script>
        </div>
    </body>
</html>

