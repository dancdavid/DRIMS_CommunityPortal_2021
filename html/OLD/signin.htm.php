<div class="container" style="padding-top:130px;">
    <div class="jumbotron">
        <h2 class="text-primary text-center"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Agency Login </h2><br>

        <?php
        if (isset($_GET['e'])) {
            echo '<div class="col-sm-offset-4"><h5 class="text-danger">' . $_GET['e'] . '</h5></div>';
        }
        ?>

        <form class="form-horizontal" role="form" method="post" action="_lib/action.php?action=login">
            <div class="form-group">
                <label for="email" class="col-sm-offset-2 col-sm-2 control-label">User ID</label>
                <div class="col-sm-5">
                    <input type="email" class="form-control" id="email" name="email" placeholder="email" required>
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-sm-offset-2 col-sm-2 control-label">Password</label>
                <div class="col-sm-5">
                    <input type="password" class="form-control" id="password" name="password" placeholder="password" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8">
                    <button type="submit" class="btn btn-primary">Sign In</button>
                </div>
            </div>
        </form>
        
        <div class="col-sm-12 col-xs-12">
            <a href="forgot_password" class="pull-right">Forgot Password</a>
        </div>

    </div>    
</div>