<div class="cal-div">
    <script type="text/javascript" src="<?php echo INSTALL_URL; ?>index.php?controller=GzFront&action=load&cid[]=<?php echo $_GET['id']; ?>&view_month=1"></script>
</div>
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