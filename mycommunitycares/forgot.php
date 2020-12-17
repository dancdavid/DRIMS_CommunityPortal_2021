<?php
//if ($_SERVER['SERVER_NAME'] === 'drims.tst') {
//    define('ROOT_DOMAIN', '.drims.tst');
//} else {
//    define('ROOT_DOMAIN', '.drims.org');
//}
//
//session_start();
//setcookie('v', "", time() - 3600, '/', ROOT_DOMAIN);
//setcookie('vcms', "", time() - 3600, '/', ROOT_DOMAIN);
//setcookie('vcp', "", time() - 3600, '/', ROOT_DOMAIN);
//
//if (session_name() !== null) {
//    $_SESSION = array();
//    setcookie(session_name(), '', time() - 42000, '/', ROOT_DOMAIN);
//    session_destroy();
//}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Community Cares</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="icon" href="../drims_icon.ico" />
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>

<!-- Fixed navbar -->
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="index"><img src="images/My-Community-Cares-Logo.gif" style="max-width:100px; padding-top:5px"></a>
            <!--<a class="navbar-brand" href="index">BARR PUBLIC VIEW</a>-->
        </div>
    </div>
</div>

<div class="container-fluid" style="margin-top:50px;">
    <div class="row">
        <div class="col">
            <a href="https://drims.org" target="_blank" alt="Click Here To Learn More About DRIMS"></a><img src="../images/DRIMS_powered_by_LOGO_2_transparent_bg.gif" style="max-width:100px; padding-left:15px;">
        </div>
    </div>
</div>

<div class="container" style="border: 1px solid darkgrey; border-radius:10px; margin-top:50px">
    <div class="row" style="margin-top:20px;">
        <div class="col text-center">
            <img src="images/My-Community-Cares-Logo.gif" class="img-fluid">
            <h3 class="text-danger">You forgot your password?</h3>
            Just enter your email you used to register with us
            <br>
            and we will send you an email with instructions.
            <?php
            if (isset($_GET['e'])) {
                $err = ($_GET['e'] === 'contactsupport') ? 'Your account is not active <a href="mailto:support@drims.atlassian.net?subject=Problem Logging in" target="_blank">Contact Support</a>.' : $_GET['e'];
                echo '<h5 class="text-danger">** ' . $err . ' **</h5>';
            }
            ?>
        </div>
    </div>

    <form class="form-horizontal" role="form" method="post" action="../_lib/action.php?action=forgot_password">
        <div class="form-group" style="text-align:center;">
            <div class="col-sm-offset-4 col-sm-4" style="text-align:center;">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8" style="text-align:center;">
                <button type="submit" class="btn btn-primary btn-lg">Sign In</button>
            </div>
        </div>
    </form>

    <div class="col-sm-12 col-xs-12">
        <a href="forgot.php" class="pull-right">Forgot Password</a>
    </div>

</div>

<script src="js/bootstrap.min.js"></script>

</body>
</html>