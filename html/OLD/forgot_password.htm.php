<div class="container" style="padding-top:130px;">
    <div class="jumbotron">
        <h2 class="text-danger text-center"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> Forgot Password </h2><br>
        
        <div class="col-sm-offset-4 col-xs-offset-4 col-xs-8 col-sm-8">
            It happens. Provide us with the email you use to sign in. <br>
        </div>
        

        <?php
        if (isset($_GET['e']) && $_GET['e'] === 'fail') {
            echo '<div class="col-sm-offset-4 col-xs-offset-4 col-xs-8 col-sm-8">
                <h5 class="text-danger">Sorry we could not find your email address. <br> Please contact your Agency administrator to have you added to our system.
                </h5>
                </div>';
        }
        
        if (isset($_GET['e']) && $_GET['e'] === 'pass') {
            echo '<div class="col-sm-offset-4 col-xs-offset-4 col-xs-8 col-sm-8">
                <h5 class="text-danger">
                We have sent you an email with instructions to reset your password.<br>
                <a href="signin" class="text-primary">Click here to Sign In <span class="glyphicon glyphicon-log-in" aria-hidden="true"></span></a><br>
                </h5>
                </div>';
        }
        ?>

        <form class="form-horizontal" role="form" method="post" action="_lib/action.php?action=forgot_password">
            <div class="form-group">
                <label for="email" class="col-sm-offset-2 col-sm-2 control-label">Email</label>
                <div class="col-sm-5">
                    <input type="email" class="form-control" id="email" name="email" placeholder="email" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>

    </div>    
</div>