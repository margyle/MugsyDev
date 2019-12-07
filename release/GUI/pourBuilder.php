<?php
session_start();
// Require composer autoloader
//include 'vendor/autoload.php'; #TODO prep local oAuth 
include 'inc/inc.db.php';
// include 'inc/inc.getRecipes.php';
// include 'inc/inc.getMachineStatus.php';
?>
<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mugsy</title>

    <!-- Bootstrap CSS -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">


</head>

<body>

    <div class="container">

        <div class="row">
            <div class="col-md-12" style="padding-top: 10px">
                <span style="font-family: bebas;font-size: 17pt;color: #707070;">Pour Builder</span>
                <span class="float-right" >
                <ul class="list group list-group-horizontal-sm" name="stepCount" id="stepCount">
                </ul>       
    </span>

      
            </div>
            <div class="col-md-12" style="padding-top: 0px">

            </div>
        </div>

        <div class="row">
            <div class="col-md-12" style="padding-top: 0px;">

                <ul class="list-group list-group-horizontal shadow" id="availableOptions" style="width:100%;">

                    <li class="list-group-item list-group-item-danger text-center" style="background-color:#f28c8f" id="grind">
                        <span style="font-size: 1em;color: white;">grind
                            <i class="fas fa-sun" style="size: 4em;color: white;"></i>
                        </span>
                    </li>

                    <li class="list-group-item list-group-item-secondary text-center" style="background-color: #7c75b2;color:white;" id="cone">
                        <span style="font-size: 1em;">cone
                            <i class="fas fa-circle-notch" style="size: 4em"></i>
                        </span>
                    </li>

                    <li class="list-group-item list-group-item-primary text-center" style="background-color: #00b7ee;color:white;" id="water">
                        <span style="font-size: 1em; ">water
                            <i class="fas fa-tint" style="size: 4em"></i>
                        </span>
                    </li>

                    <li class="list-group-item list-group-item-success text-center" style="background-color: #2ab071;color: white;" id="spout">
                        <span style="font-size: 1em; ">spout
                            <i class="fas fa-grip-lines" style="size: 4em"></i>
                        </span>
                    </li>

                    <li class="list-group-item list-group-item-dark text-center" id="time">
                        <span style="font-size: 1em; ">time
                            <i class="fas fa-clock" style="size: 4em"></i>
                        </span>
                    </li>

                    <li class="list-group-item list-group-item-dark text-center" id="repeat">
                        <span style=" font-size: 1em; ">repeat
                            <i class=" fas fa-redo-alt" style="size: 4em"></i>
                        </span>
                    </li>

                    <li class="list-group-item list-group-item-dark text-center" id="stop">
                        <span style="font-size: 1em; ">stop
                            <i class="fas fa-stop-circle" style="size: 4em"></i>
                        </span>
                    </li>
                </ul>

            </div>

        </div>
        <!--end available steps -->

        <div class="row">
            <!--start anchor list and editors-->
            <div class="col-md-4" style="padding-top: 24px; padding-bottom:20px;">
                <div class="card shadow">
                    <div class="card-header text-center">
                        Step Builder
                    </div>
                    <!--drag step options to this sticky list-->
                    <ul class="list-group list-group-flush this-list" id="stepStickyList">
                        <li class="list-group-item list-group-item-danger text-center" id="grind" style="background-color: #f28c8f;color: white;">
                            <span style="font-size: 1em;">grind
                                <i class="fas fa-sun" style="size: 4em"></i>
                            </span>
                        </li>
                    </ul>
                    <!--end sticky list-->
                </div>
            </div>
            <div class="col-md-8 " style="padding-top: 24px;padding-bottom: 20px">
                <div id="stepOptionEditor">
                    <div class="col-md-14">
                        <div class="card shadow">
                            <div class="card-header" id="editorHeader" style="background: #00b7ee;color: white;">
                                Step Editor: &nbsp;<span id="json" style="color:black;font-size:14px;"></span>
                            </div>
                            <div class="card-body">
                                <form name="steps" id="steps">
                                    <p class="card-text"><span id="editorActionable">
                                        Start with Cone<br>
                                        </span>
                                        <button type="button" class="btn list-group-item-success shadow" style="background-color: #7c75b2;color: white;" onClick="getInputs()">Save Values</button>
                                        <button type="button" class="btn list-group-item-success shadow" onClick="postStep()" style="background-color: #2ab071;color: white;">Next
                                            Step</button>
                                        <button type="button" class="btn list-group-item-danger shadow" onClick="deleteItem();" style="background-color:#f28c8f;color:white;">Delete
                                            Step</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!--end anchor list and editors-->

    </div>
    <!--end container-->



</body>


<script src="js/vendor/jquery-3.4.1.min.js"></script>

<script src="js/vendor/popper.min.js"></script>

<script src="js/bootstrap.min.js"></script>

<link href="css/bootstrap.min.css" rel="stylesheet">

<script src="js/bootstrap.bundle.js.map"></script>

<script src="js/rangeslider.js/rangeslider.min.js"></script>

<script type="text/javascript" src="js/vendor/Sortable.js"></script>

<link href="css/fa/css/all.css" rel="stylesheet">

<!-- import forms for step settings -->
<script type="text/javascript" src="js/pourBuilderForms.js"></script>
<!-- import the pour builder functions -->
<script type="text/javascript" src="js/pourBuilder.js"></script>


</html>
