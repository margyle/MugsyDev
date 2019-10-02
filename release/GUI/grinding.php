<?php
session_start();
// Require composer autoloader
//include 'vendor/autoload.php'; #TODO prep local oAuth 
include 'inc/inc.db.php';
include 'inc/inc.getRecipes.php';
include 'inc/inc.getMachineStatus.php';


?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <title>Mugsy</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">

</head>

<body>

    <div class="container-fluid">
        <div class="row" id="statusHeader" style="padding-top:5px">
            <div class="col-md-8">
                <!--keep empty for header-->
            </div>
            <div class="col-md-2">

            </div>
            <div class="col-md-2 text-right">
                <!--TODO: link to new system status set up
                <?= $statusDisplay ?>
            </div>
            
        </div>
        <div class="row">
        <div class="col-md-2">

</div>
<div class="col-md-8"><img src="img/blocks/grinding/header.png"/></div>
        <div class="col-md-2">

            </div>
        </div>
        <!--start interface cards-->
        <div class="row" id="cardsRow" style="padding-top:20px;">
        
            <div class="col-xs-2 col-sm-2">
                <!--this stays empty-->
            </div>
            <!-- coffeenow card -->
            <div class="col-sm-4" style="padding-bottom:20px">
            
                <div class="card h-100 shadow border-muted" id="grinderCard"">
                    <div class="card-body shadow">
                        <img src="img/blocks/grinding/grinder.png" />
                    </div>
                    <ul class="list-group list-group-flush ">
                        <li class="list-group-item shadow" id="cardBar">
                        </li>
                    </ul>
                    <div class="card-footer text-center">

                        <span class="text-center" id="recipeListNumberHeading"><?= $grindWeight ?></span><span id="recipeblockText">g</span>

                    </div>
                </div>
            </div>


            <!-- everything else card -->
            <div class="col-sm-4">
                <span style="color: #707070;"> Grinder Progress</span>
                <div class="progress shadow">
                    <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" id="grindProgress" role="progressbar" style="width: 0%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <hr>


                <div class="row">
                    <div class="col-sm-12">
                        <div class="card text-white  mb-3 shadow" style="max-width: 18rem;">

                            <div class="card-body">
                                <span id="grams" style="font-family: bebas;font-size:42pt;color: #707070;">0.00</span>
                                <span id="recipeblockText">grams</span>
                            </div>

                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12" style="padding-top:8px;">
                        <div class="card text-white bg-danger mb-3 shadow" style="max-width: 18rem;">

                            <div class="card-body text-center">

                                <a href="emergencyStop.php" class="card-text" style="font-family: bebas;font-size:29pt; color:white;" >E-Stop</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-2 col-sm-2">
            <!--this stays empty-->
        </div>

    </div>



    <script src="js/vendor/modernizr-3.7.1.min.js"></script>

    <script src="js/vendor/jquery-3.4.1.min.js"></script>

    <script src="js/vendor/popper.min.js"></script> <script src="js/bootstrap.min.js"></script>

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <script src="js/bootstrap.bundle.js.map"></script>

    <script type="text/javascript" src="js/vendor/Sortable.js"></script>

    <link href="css/fa/css/all.css" rel="stylesheet">
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>
    <!-- grinder.js gets weight in realtime -->
    <script src="js/grinder.js"></script>

</body>

</html>
