<!DOCTYPE html>
<html lang="en">
    <head>
        <title>DRIMS - Community Portal</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/jquery.dataTables.min.css">
        <script src="js/jquery-1.11.1.min.js"></script>
        <script src="js/_myLib.js"></script>
        
        <style>
            .dataTables_wrapper .dataTables_length {
                float: right;
            }
            .dataTables_wrapper .dataTables_filter {
                float: left;
                text-align: left;
            }
            
            #loading-img {
                position: fixed;
                background: url(images/image.gif) center center no-repeat;
                display:block;
                height: 100%;
                width:100%;
                z-index: 20;
            }
            .overlay {
                background: #a9a9a9;  
                display: none;       
                position: fixed;   
                top: 0;                
                right: 0;                
                bottom: 0;
                left: 0;
                opacity: 0.5;
            }
        </style>
    </head>
    <body>

        <!-- Fixed navbar -->
        <div class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a href="index"><img src="images/DRIMS_logo_dark_bg.png" style="max-width:100px; padding-top:5px"></a>
                    <!--<a class="navbar-brand" href="index">BARR PUBLIC VIEW</a>-->
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="index">Home <span class="glyphicon glyphicon-home" aria-hidden="true"></span></a></li>
                        <li><a href="agency_list">Agency List <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span></a></li>
                        <li><a href="volunteer">Volunteer <span class="glyphicon glyphicon-check" aria-hidden="true"></span></a></li>
                        <li><a href="mailto:info@barr4bayous.org">Contact Us <span class="glyphicon glyphicon-check" aria-hidden="true"></span></a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">

                        </li>
                        <li><a href="signin">Sign In <span class="glyphicon glyphicon-log-in" aria-hidden="true"></span></a></li>
                    </ul>
                </div>
            </div>
        </div>