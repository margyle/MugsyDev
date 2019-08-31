<?php

session_start();
include 'inc/inc.db.php';
include 'inc/inc.getMachineStatus.php';
include 'inc/inc.getCoffeeNowSettings.php';

//debug
//$_SESSION['machineId'] = "";


?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <title>Mugsy</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="manifest" href="site.webmanifest">
    <link rel="apple-touch-icon" href="icon.png">
    <!-- Place favicon.ico in the root directory -->

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
                <!--TODO: link to system status-->
                <?= $statusDisplay ?>
            </div>
        </div>
        <!--start interface cards-->
        <div class="row" id="cardsRow">
            <div class="col-xs-2 col-sm-2">
                <!--this stays empty-->
            </div>
            <!-- coffeenow card -->
            <div class="col-sm-4">
                <div class="card h-100 shadow border-muted" id="coffeeNowCard" onclick="location.href='coffeeNow.php';">
                    <div class="card-body shadow">
                        <img id=coffeeNowTextImg" src="img/blocks/main/mainCoffeeNow.png" style="padding-top: 60px;" />
                    </div>
                    <ul class="list-group list-group-flush ">
                        <li class="list-group-item shadow" id="cardBar">
                        </li>
                    </ul>
                    <div class="card-footer">
                        <span id="usualText">The Usual</span>
                        <p class="text-left" id="coffeeNowCardSettings"><?= $coffeeNowRecipeName ?><br><?= $coffeeNowSettingsType ?></p>

                    </div>
                </div>

            </div>
            <!-- everything else card -->
            <div class="col-sm-4">
                <div class="card  h-100 shadow border-muted" id="everythingElseCard" onclick="location.href='everyThingElse.php';">
                    <div class="card-body shadow">
                        <img id="everythingElseTextImg" src="img/blocks/main/mainEverythingElse.png" style="padding-top: 60px;" />
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item shadow" id="cardBar"">
                                
                            </li>
                        </ul>
                        <div class=" card-footer text-center">
                            <img id="homeCardMugsyLogo" src="img/blocks/main/mugsyLogo.png" />
                </div>
            </div>

        </div>
        <div class="col-xs-2 col-sm-2">
            <!--this stays empty-->
        </div>

    </div>
    <div class="row">
        <hr>
    </div>
    </div>


    <script src="js/vendor/modernizr-3.7.1.min.js"></script>

    <script src="js/vendor/jquery-3.4.1.min.js"></script>

    <script src="js/vendor/popper.min.js" </script> <script src="js/bootstrap.min.js"></script>

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <script src="js/bootstrap.bundle.js.map"></script>

    <script type="text/javascript" src="js/vendor/Sortable.js"></script>

    <link href="css/fa/css/all.css" rel="stylesheet">
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

</body>

</html>
