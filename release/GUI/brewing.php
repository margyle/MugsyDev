<?php
$recipeId = $_GET["recipeId"];
include 'inc/inc.db.php';	
include 'inc/inc.getRecipeSteps.php';	
include 'inc/inc.getMachineStatus.php';

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
        <div class="row" id="header">
            <div class="col-md-8">
                <!--keep empty for header-->
            </div>
            <div class="col-md-2">

            </div>
            <div class="col-md-2 text-right">
                <!--TODO: link to system status-->
                <i class="fas fa-circle text-success"></i>
                <i class="fas fa-circle text-success"></i>
                <i class="fas fa-circle text-success"></i>
                <i class="fas fa-circle text-success"></i>
            </div>
        </div>
        <div class="row" id="progressContainer">
            <div class="col-xs-12 col-sm-12">

                <span style="font-family: bebas;font-size: 30pt;color: #707070">Brew Progress:</span><span style="font-family: Avenir;font-size: 25pt;color: #707070"><?=$recipeName;?></span>
                <div class="progress shadow">
                    <div class="progress-bar progress-bar-striped bg-danger progress-bar-animated" id="brewProgressBar" role="progressbar" style="width: 0%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"> </div>
                </div>
                <br>
                <!--end col-->
            </div>
            <!--end progressContainer-->
        </div>

        <div class="row" id="heading">
            <div class="col-xs-12 col-sm-12">
                <ul id="activeList" class="list-group shadow" data-index="-1">
                    <?php 
                    //create array for timing display
                    $stepTimes = array();
                    foreach ($results as $row) {
                        $step = $row['step'];
                        $waterWeight = $row['waterWeight'];
                        $pourPattern = $row['pourPattern'];
                        $stepTimes[] = $row['stepTime'];
                        $displayTime = $row['stepTime'];
                        $notes = $row['notes'];
                        $totalSteps = $row['totalSteps'];
                        $idOrder = ($step - 1);
                        //build list
                        echo '<li id="' . $idOrder . '" class="list-group-item list-group-item-light" style="font-family: bebas;font-size: 12pt;">
                        Step ' . $step . ':</span><span style="font-family: avenir;font-size: 12pt;"> '
                            . $notes . ' ' . $waterWeight . ' of Water for ' . $displayTime . ' seconds.</span></li>';
                    }
                    $js_array = json_encode($stepTimes);
                    ?>
                </ul>
            </div>

        </div>
        <!--end heading-->
        <br>
    </div>
    <!--end container-->






    <script src="js/vendor/modernizr-3.7.1.min.js"></script>

    <script src="js/vendor/jquery-3.4.1.min.js"></script>

    <script src="js/vendor/popper.min.js"> </script> <script src="js/bootstrap.min.js"></script>

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <script src="js/bootstrap.bundle.js.map"></script>

    <script type="text/javascript" src="js/vendor/Sortable.js"></script>

    <link href="css/fa/css/all.css" rel="stylesheet">
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>


<script>
    //steps -1 = allsteps
    var allSteps = <?=$totalSteps?>;
    var currentStep = 0;
    var percentComplete = 5
    //populate times array with step times
    <?php echo "var times = " . $js_array . ";\n"; ?>

    activeStepTimer();

    function activeStepTimer() {
        if (currentStep < allSteps) {
            //debug
            console.log(currentStep);
            console.log(allSteps);
            console.log(times[currentStep]);
            step2 = currentStep - 1;
            document.getElementById(currentStep).style.backgroundColor = "#f26d7d";
            document.getElementById(currentStep).style.color = "white";
            //update progress bar
            percentComplete = 100/(allSteps - currentStep);
            document.getElementById("brewProgressBar").style.width = percentComplete + "%";
            //reverse
            if (step2 >= 0) {
                    document.getElementById(step2).style.backgroundColor = "";
                    document.getElementById(step2).style.color = "";
                }
            setTimeout(activeStepTimer, times[currentStep] * 1000);
            currentStep++;
        } else {
            window.location.href = 'brewingComplete.php';
        }
    }
</script>

</body>

</html>
