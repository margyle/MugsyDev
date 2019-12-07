<?php
session_start();
// Require composer autoloader
//include 'vendor/autoload.php'; #TODO prep local oAuth 
include 'inc/inc.db.php';
include 'inc/inc.getRecipes.php';
include 'inc/inc.getMachineStatus.php';
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
            <div class="col-md-12" style="padding-top: 18px">
                <span style="font-family: bebas;font-size: 17pt;color: #707070;">Pour Builder</span>
            </div>
            <div class="col-md-12" style="padding-top: 8px">
                <div class="progress shadow" id="prog" style="height: 20px;">
                    <div class="progress-bar progress-bar-striped bg-dark" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">Step 1
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12" style="padding-top: 24px;">

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
                                        <button type="button" class="btn list-group-item-success shadow" onClick="addStep()" style="background-color: #2ab071;color: white;">Next
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

<!-- <script type="text/javascript" src="js/pourBuilder.js"></script> -->


<script>
    //this code will live in the js/pourBuilder.js file
    //set up sliders

    //make available step options draggable
    var setting = 1;
    var draggedItem = "grind";
    var currentOption = "waiting";
    var waterStatus = "unset";
    var settingsObject = {};
    updateEditor();
    $(document).ready(function() {
        
        Sortable.create(availableOptions, {
            animation: 100,
            group: {
                name: "list-1",
                pull: "clone",
                revertClone: false,
                put: "false"
            },
            draggable: '.list-group-item',
            handle: '.list-group-item',
            sort: false,
            filter: '.sortable-disabled',
            onMove: function(evt) {
                //getInputs();
                // if (evt.item > 0) {
                //     return false;
                // }
            }
            //chosenClass: 'active',
        });

        //make sticky list to recieve step options
        Sortable.create(stepStickyList, {
            group: 'list-1',
            handle: '.list-group-item',
            onAdd(evt) {
                draggedItem = evt.item;
                //console.log(draggedItem.id);
                updateEditor();
                setting++

            }
        });
    });
    //function to delete last step option
    function deleteItem() {
        var count = document.getElementById('stepStickyList').getElementsByTagName("li");
        var i = count.length
        var ulElem = document.getElementById('stepStickyList')
        ulElem.removeChild(ulElem.childNodes[i])
        setting--;
        //mainDrag();
    }

    //update editor for dragged option type
    function updateEditor() {
        console.log("Editor Type: " + draggedItem.id);

        if (draggedItem.id == "water") {
            //console.log(setting == 1);
            var el1 = document.querySelector('#editorActionable');
            var water = document.createElement('div');
            water.innerHTML = '<div id="editorActionable"><b>Water Settings</b><hr>' +
            '<div class="form-group row" style="padding-left:0px; padding-top: 0px">' +
            '<label class="col-sm-4 col-form-label">Total Milliliters:</label>' +
            '<input type="number" class="form-control col-sm-4" step="5.00" value="50" id="waterWeight" name="waterWeight">'+
            '</div>' +
            '<div class="form-group row" style="padding-left:0px; padding-top: 0px">' +
            '<label class="col-sm-4 col-form-label">Flow Rate:</label>' +
            '<input type="number" class="form-control col-sm-4" step="0.10" value="4.00" id="flowRate" name="flowrate">'+
            '</div>' +
            '<hr>' +
            '</div>';
            // replace el with newEL
            el1.parentNode.replaceChild(water, el1);
            waterStatus = "set";
            el1 = null;

        }
        if (draggedItem.id == "cone") {
            //console.log(setting == 1);
            var el1 = document.querySelector('#editorActionable');
            var cone = document.createElement('div');
            cone.innerHTML = '<div id="editorActionable">Direction:' +
                '<div class="form-group row" style="padding-left:15px; padding-top: 10px">' +
                '<div class="form-check form-check-inline">' +
                '<input class="form-check-input" type="radio" name="coneDirection" id="coneDirection" value="CW">' +
                '<label class="form-check-label" for="coneDirectionCW">Clockwise</label>' +
                '</div>' +
                '<div class="form-check form-check-inline">' +
                '<input class="form-check-input" type="radio" name="coneDirection" id="coneDirection" value="CC">' +
                '<label class="form-check-label" for="coneDirectionCC">Counter Clockwise</label>' +
                '</div>' +
                '</div>' +
                '<div class="form-group row" style="padding-left:0px; padding-top: 0px">' +
                '<label class="col-sm-4 col-form-label">Distance:</label>' +
                '<select class="form-control col-sm-4" id="coneDistance" name="coneDistance">' +
                '<option value="45">45° </option>' +
                '<option value="90">90° Quarter Turn</option>' +
                '<option value="135">135° </option>' +
                '<option value="180">180° Half Turn</option>' +
                '<option value="225">225°</option>' +
                '<option value="270">270°</option>' +
                '<option value="315">315°</option>' +
                '<option value="360">360° Complete Turn</option>' +
                '</select>' +
                '</div>' +
                '<hr></div>';


            // replace el with newEL
            el1.parentNode.replaceChild(cone, el1);
            el1 = null;

        }
        if (draggedItem.id == "spout") {
            //console.log(setting == 1);
            var el1 = document.querySelector('#editorActionable');
            var spout = document.createElement('div');
            spout.innerHTML = '<div id="editorActionable">Spout Movement:' +
                '<div class="form-group row col-sm-8" style="padding-left:15px; padding-top: 10px">' +
                //'<label for="range">Start Position</label>'+
                '<label for="startPos">Start Position:</label>'+
                '<input type="range" class="custom-range" min="1" max="180" id="startPos">'+
                '<br><hr>'+
                '<label for="endPos">End Position:</label>'+
                '<input type="range" class="custom-range" min="1" max="180" id="endPos">'+
                '</div>';  
                // '<div class="col-sm-8">' +            
                // 'Range Slider' +
                // '</div>';
            // replace el with newEL
            el1.parentNode.replaceChild(spout, el1);
            el1 = null;

        }
        if (draggedItem.id == "time") {
            //console.log(setting == 1);
            var el1 = document.querySelector('#editorActionable');
            var time = document.createElement('div');
            time.innerHTML = '<div id="editorActionable">Updated form for time settings</div>';
            // replace el with newEL
            el1.parentNode.replaceChild(time, el1);
            el1 = null;

        }
        if (draggedItem.id == "repeat") {
            //console.log(setting == 1);
            var el1 = document.querySelector('#editorActionable');
            var repeat = document.createElement('div');
            repeat.innerHTML = '<div id="editorActionable">Updated form for repeat settings</div>';
            // replace el with newEL
            el1.parentNode.replaceChild(repeat, el1);
            el1 = null;

        }
        if (draggedItem.id == "stop") {
            //console.log(setting == 1);
            var el1 = document.querySelector('#editorActionable');
            var stop = document.createElement('div');
            stop.innerHTML = '<div id="editorActionable">Updated form for stop settings</div>';
            // replace el with newEL
            el1.parentNode.replaceChild(stop, el1);
            el1 = null;

        }
        if (draggedItem == "grind") {
            //console.log(setting == 1);
            var el1 = document.querySelector('#editorActionable');
            var grind = document.createElement('div');
            grind.innerHTML = '<div id="editorActionable"><b>Grind Settings</b><hr>' +
            '<div class="form-group row" style="padding-left:0px; padding-top: 0px">' +
            '<label class="col-sm-4 col-form-label">Total Milligrams:</label>' +
            '<input type="number" class="form-control col-sm-4" step="1.00" value="30" id="coffeeWeight" name="coffeeWeight">'+
            '</div>' +
            '<div class="form-group row" style="padding-left:0px; padding-top: 0px">' +
            '<label class="col-sm-4 col-form-label">Grind Size:</label>' +
            '<input type="number" class="form-control col-sm-4" step="1" value="7.00" id="grindSize" min ="1" max="10" name="grindSize">'+
            '</div>' +
            '<hr>' +
            '</div>';
            // replace el with newEL
            el1.parentNode.replaceChild(grind, el1);
            el1 = null;

        }
    }
    //getInputs(): grab entered values and add to the settingsObject, then display the values
    function getInputs(){
        var inputs = document.querySelectorAll('input,select');    
        for (var i = 0; i < inputs.length; i++) {
        settingsObject[inputs[i].id] = inputs[i].value;
        }
        console.log(settingsObject)
        console.log(draggedItem.id)
        document.getElementById("json").innerHTML = JSON.stringify(settingsObject, undefined, 2);
    }
</script>

</html>
