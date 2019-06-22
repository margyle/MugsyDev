<?php
session_start();
$_SESSION["UUID"] = $_GET['UUID'];
include '../inc/inc.db.php';
include  '../inc/inc.decaf.getRecipe.php';
include '../inc/inc.brewTools.php';
?>
?>
<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mugsy</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="inc/css/bootstrap.min.css">
    <!--todo : move all inline css over to inc-->
    <style>
        html {
            overflow: scroll;
            overflow-x: hidden;
        }

        ::-webkit-scrollbar {
            width: 0px;
            /* remove scrollbar space */
            background: transparent;
            /* optional: just make scrollbar invisible */
        }

        /* optional: show position indicator in red */
        ::-webkit-scrollbar-thumb {
            background: #FF0000;
        }

        body {
            background-image: url("img/ux/backGroundPink.png");
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #707070;
            overflow-x: hidden;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div id="recipeTable" class="col-md-8">
                <?php echo $recipe; ?>
            </div>
        </div>
        <div class="row">
            <div id="brewButtons" class="col-md-8">
                <?php echo $brewButtons; ?>
            </div>
        </div>
</body>
<!-- jQuery -->
<script src="../inc/js/jquery.js"></script>
<!-- Bootstrap JavaScript -->
<script src="../inc/js/bootstrap.min.js"></script>

</html>
