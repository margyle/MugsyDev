<?php
session_start();
// Require composer autoloader
//include 'vendor/autoload.php'; #TODO prep local oAuth 
include 'inc/inc.db.php';
include 'inc/inc.getRecipes.php';
include 'inc/inc.createRecipe.php';

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
            background-image: url("img/themes/purp/background.png");
            background-repeat: repeat-x;
            background-attachment: fixed;
            color: #707070;
            overflow-x: hidden;
        }

        @font-face {
            font-family: bebas;

            src: url(inc/webfonts/Bebas/BEBAS.ttf);
        }

        @font-face {
            font-family: avenir;

            src: url(inc/webfonts/avenir/AvenirLTStd-Roman.woff);
        }

        @font-face {
            font-family: avenirOblique;

            src: url(inc/webfonts/avenir/AvenirLTStd-Oblique.woff);
        }
    </style>
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

                <ul class="list-group list-group-horizontal shadow" id="drag1" style="width:100%;">

                    <li class="list-group-item list-group-item-danger text-center" style="background-color:#f28c8f" id="CW">


                        <span style="font-size: 1em;color: white;">grind
                            <i class="fas fa-sun" style="size: 4em;color: white;"></i>

                        </span>

                    </li>

                    <li class="list-group-item list-group-item-secondary text-center" style="background-color: #7c75b2;color:white;" id="CW">


                        <span style="font-size: 1em;">cone
                            <i class="fas fa-circle-notch" style="size: 4em"></i>

                        </span>

                    </li>
                    <li class="list-group-item list-group-item-primary text-center" style="background-color: #00b7ee;color:white;">

                        <span style="font-size: 1em; ">water
                            <i class="fas fa-tint" style="size: 4em"></i>

                        </span>
                    </li>

                    <li class="list-group-item list-group-item-success text-center" style="background-color: #2ab071;color: white;">

                        <span style="font-size: 1em; ">spout
                            <i class="fas fa-grip-lines" style="size: 4em"></i>

                        </span>
                    </li>

                    <li class="list-group-item list-group-item-dark text-center">

                        <span style="font-size: 1em; ">time
                            <i class="fas fa-clock" style="size: 4em"></i>

                        </span>
                    </li>
                    <li class="list-group-item list-group-item-dark text-center"">

                        <span style=" font-size: 1em; ">repeat
                            <i class=" fas fa-redo-alt" style="size: 4em"></i>

                        </span>
                    </li>
                    <li class="list-group-item list-group-item-dark text-center">

                        <span style="font-size: 1em; ">stop
                            <i class="fas fa-stop-circle" style="size: 4em"></i>

                        </span>
                    </li>

                </ul>

            </div>

        </div>

    </div>
    </div>


</body>


<script src="js/vendor/jquery-3.4.1.min.js"></script>

<script src="js/vendor/popper.min.js"></script>

<script src="js/bootstrap.min.js"></script>

<link href="css/bootstrap.min.css" rel="stylesheet">

<script src="js/bootstrap.bundle.js.map"></script>

<script type="text/javascript" src="js/vendor/Sortable.js"></script>

<link href="css/fa/css/all.css" rel="stylesheet">

<script src="js/pourBuilder.js"></script>


<script>
    $(document).ready(function() {
        Sortable.create(drag1, {
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
            //chosenClass: 'active',


        });

        Sortable.create(deleteID, {
            group: 'list-1',
            handle: '.list-group-item',
            onAdd(evt) {

                mainDrag();
                setting++

            }
        });
    });
</script>


<script>
    function deleteItem() {
        var count = document.getElementById('deleteID').getElementsByTagName("li");

        var i = count.length
        var ulElem = document.getElementById('deleteID')

        ulElem.removeChild(ulElem.childNodes[i])
        setting--;
        mainDrag();
    }
</script>


</html>
