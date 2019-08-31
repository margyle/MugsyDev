<?php
session_start();
// Require composer autoloader
//include 'vendor/autoload.php'; #TODO prep local oAuth 
include 'inc/inc.db.php';
include 'inc/inc.getRecipes.php';
include 'inc/inc.getMachineStatus.php';
//debug
// $_SESSION["subID"] = ""; 
// $_SESSION['machineId'] = "";

//convert json response from inc.getRecipes.php to array
$recipes =  json_decode($response, true);
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
   
</head>

<body>
    <!-- start container -->
    <div class="container-fluid">
        <!-- start header -->
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
        </div><!-- end header -->
        <!--start recipe-listing-->
        <?php
        // Loop through recipes array and spit out the list item html
        foreach ($recipes as $key => $value) {
            //if less than 3 digits, pad the recipe id #
            $recipeNumber = $value["recipeListId"];
            if (strlen($recipeNumber) <= 3) {
                $recipeIdPadded = sprintf("%03d", $recipeNumber);
            } else {
                $recipeIdPadded = $recipeNumber;
            }
            echo '<div class="col-md-12" id="recipeContainer">';
                echo '<div class="row" id="heading">';
                    echo '<div class="col-xs-10 col-sm-10">';
                        echo '<span id="recipeListNumberHeading">Recipe # '. $recipeIdPadded. ': </span>';
                        echo '<span id="recipeListName">' . $value["recipeListName"] . '</span>';
                        echo '<p id="recipeListSource">Source: ' . $value["recipeListSource"] . '</p>';
                    echo '</div>';
                echo '</div>';

                echo '<!--start icon blocks-->';
                //start grind weight block
                echo '<div class="row" style="padding-bottom: 30px;">';
                    echo '<div class="col-sm-3" id=recipeBlockContainer">';
                        echo '<div class="card shadow border-muted" id="recipeBlock" style="border-radius: 50;">';
                            echo '<div class="card-body shadow text-center">';
                                echo '<i class="icon_mugsy_coffeebeans"></i>';
                            echo '</div>';
                                echo '<ul class="list-group list-group-flush ">';
                                    echo '<li class="list-group-item shadow" id="recipeblockStripe">';
                                    echo '</li>';
                                echo ' </ul>';
                                    echo '<span class="text-center" id="recipeblockText">' . $value["recipeListGrindWeight"] . ' g</span>';
                        echo '</div>';
                    echo '</div>';
                //start grind waterTemp block
                    echo '<div class="col-sm-3" id=recipeBlockContainer">';
                        echo '<div class="card shadow border-muted" id="recipeBlock">';
                            echo '<div class="card-body shadow text-center">';
                                echo '<i class="icon_mugsy_coffeebeans"></i>';
                            echo '</div>';
                                echo '<ul class="list-group list-group-flush ">';
                                    echo '<li class="list-group-item shadow" id="recipeblockStripe">';
                                    echo '</li>';
                                echo '</ul>';
                                    echo '<span class="text-center" id="recipeblockText">' . $value["recipeListWaterWeight"] . ' ml @ ' . $value["recipeListTemperature"] . '&#176</span>';
                        echo '</div>';
                    echo '</div>';
                //start timer block
                    echo '<div class="col-sm-3" id=recipeBlockContainer">';
                        echo '<div class="card shadow border-muted" id="recipeBlock">';
                            echo '<div class="card-body shadow text-center">';
                                echo '<i class="icon_mugsy_timer"></i>';
                            echo '</div>';
                                echo '<ul class="list-group list-group-flush ">';
                                    echo '<li class="list-group-item shadow" id="recipeblockStripe">';
                                    echo '</li>';
                                echo '</ul>';
                                    echo '<span class="text-center" id="recipeblockText">' . $value["recipeListTime"] . '</span>';
                        echo '</div>';
                    echo '</div>';
                // end icon blocks
                // start button block
                    echo '<div class="col-sm-3" id="buttonsContainer">';
                        echo '<div class="row" id="recipeListFirstButtonRow">';
                            echo '<button class="btn btn-danger btn-block shadow" id="recipeListStartButton">Start</button>';
                        echo '</div>';
                    echo '<div class="row" id="recipeListRemainingButtonRows">';
                        echo '<button class="btn btn-primary btn-block shadow" id="recipeListEditButton">Edit </button>';
                    echo '</div>';
                        echo '<div class="row" id="recipeListRemainingButtonRows">';
                            echo ' <button class="btn btn-primary btn-block shadow" data-toggle="modal"
                            data-target="#looksyModal" id="recipeListLookyButton">Looksy</button>';
                        echo '</div>';
                    echo '</div>';
            //end button block
            echo '</div>';
            echo '</div>';
            echo '<hr>';
            //end recipe container

        }
        ?>

    <div class="row" id="footer">
        </div> <!-- end footer -->
    
    </div> <!--end main container-->

    
</body>

<script src="js/vendor/modernizr-3.7.1.min.js"></script>

    <script src="js/vendor/jquery-3.4.1.min.js"></script>

    <script src="js/vendor/popper.min.js" </script> <script src="js/bootstrap.min.js"></script>

    <link href="css/bootstrap.css" rel="stylesheet">

    <script src="js/bootstrap.bundle.js.map"></script>

    <script type="text/javascript" src="js/vendor/Sortable.js"></script>

    <link href="css/fa/css/all.css" rel="stylesheet">
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
</html>
