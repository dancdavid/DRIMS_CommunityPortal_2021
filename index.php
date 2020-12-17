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

if (!defined('ROOT_URL')) {
    // If ROOT URL is not defiled then load the config file
    require_once 'config/config.php';
}

// get the landing page name
$segments = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$landing_page = count($segments) ? $segments[0] : 'mycommunitycares';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Community Cares</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="icon" href="drims_icon.ico" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<!-- Fixed navbar -->
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="index"><img src="mycommunitycares/images/My-Community-Cares-Logo.gif" style="max-width:100px; padding-top:5px"></a>
            <!--<a class="navbar-brand" href="index">BARR PUBLIC VIEW</a>-->
        </div>
    </div>
</div>

<div class="container-fluid" style="margin-top:50px;">
    <div class="row">
        <div class="col">
            <a href="https://drims.org" target="_blank" alt="Click Here To Learn More About DRIMS"><img src="../images/DRIMS_powered_by_LOGO_2_transparent_bg.gif" style="max-width:100px; padding-left:15px;"></a>
        </div>
    </div>
</div>

<div class="container" style="border: 1px solid darkgrey; border-radius:10px; margin-top:50px">
    <div class="row" style="margin-top:20px;">
        <div class="col text-center">
            <img src="mycommunitycares/images/My-Community-Cares-Logo.gif" class="img-fluid">
            <h3>Organization Sign In</h3>
            <?php
            if (isset($_GET['e'])) {
                if($_GET['e'] === 'contactsupport'){
                    $err = 'Your account is not active <a href="mailto:support@drims.atlassian.net?subject=Problem Logging in" target="_blank">Contact Support</a>.';
                }else if($_GET['e'] === 'accessdenied'){
                    $err = 'You do not appear to have access to this instance of DRIMS Community Portal. Try to login again or contact DRIMS Customer Support for additional help: <a href="mailto:support@drims.atlassian.net?subject=Problem Logging in" target="_blank">Contact Support</a>.';
                }else if($_GET['e'] === 'incorrecturl'){
                    $err = 'The DRIMS Community Portal instance you are looking for does not exist or is no longer available. If you feel there is a technical issue please contact DRIMS Support <a href="mailto:support@drims.atlassian.net?subject=Problem Logging in" target="_blank">Contact Support</a>.';
                }else{
                    $err = $_GET['e'];
                }
                echo '<h5 class="col-md-offset-3 col-md-6 col-md-offset-3 text-danger text-center">** ' . $err . ' **</h5>';
            }
            ?>
        </div>
    </div>

        <form class="form-horizontal" role="form" method="post" action="_lib/action.php?action=login">
            <input type="hidden" name="landing_page" id="landing_page"  value="<?= $landing_page ?>">
            <div class="form-group" style="text-align:center;">
                <div class="col-sm-offset-4 col-sm-4" style="text-align:center;">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-4" style="text-align:center;">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8" style="text-align:center;">
                    <button type="submit" class="btn btn-primary btn-lg">Sign In</button>
                </div>
            </div>
        </form>

<!--        <div class="col-sm-12 col-xs-12">-->
<!--            <a href="forgot_password.php" class="pull-right">Forgot Password</a>-->
<!--        </div>-->

</div>

<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/initial.js"></script>

</body>
</html>