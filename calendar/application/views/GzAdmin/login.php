<div id="menu-container" style="width: 53%;">
    <div class="grid">
        <header class="header bordered" role="banner">
            <h1>Availability Booking Calendar PHP</h1>
        </header>
        <div class="breadcrumb">
            <div class="sub-breadcrumb">
                <span class="time">It is currently <?php echo date('l jS \of F Y h:i:s'); ?></span>
            </div>
        </div>
        <div id="page-body">
            <main role="main">
                <?php
                require_once VIEWS_PATH . 'Layouts/admin/error_notice.php';
                ?>
                <h1 class="bordered title"><span class="green">L</span>OG IN</h1>
                <form id="login" class="form-horizontal" method="post" action="" role="form">
                    <input type="hidden" name="login_user" value="1" />
                    <fieldset>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input id="email" class="form-control input-sm" type="text" placeholder="Email" value="" name="email" tabindex="1">
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input id="password" class="form-control input-sm" type="password" placeholder="Password" name="password" tabindex="2">
                        </div>
                        <div class="form-group">
                            <button id="load" class="btn btn-success" data-loading-text="Logging-in... <i class='fa-spin fa fa-spinner fa-lg'></i>" value="Login" tabindex="6" name="login" type="submit"><i class="fa fa-fw fa-key"></i>&nbsp;Login</button>
                        </div>
                    </fieldset>
                </form>
            </main>
        </div>
    </div>
</div>

