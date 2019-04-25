<?php
//todo: convert php form handler to DECAF API endpoint
require '../inc/mugsy.inc.php';
?>
<!DOCTYPE html>
<html lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mugsy</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <script src="//code.jquery.com/jquery.js"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<!-- #todo: move all inline css over to inc-->
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
      background-image: url("../img/ux/backGroundPink.png");
      background-repeat: no-repeat;
      background-attachment: fixed;
      color: #707070;
      overflow-x: hidden;
    }
  </style>

</head>
<!--#todo: kill these breaks and set proper padding-->
<body>
  <br>
  <br>
  <br>
  <!--headerlogo-->
  <div class="container-fluid">
    <!-- If Needed Left and Right Padding in 'md' and 'lg' screen means use container class -->

    <div class="row">
      <!--current status-->

      <!--gutter-->
      <div class="col-xs-2 col-sm-2">
      </div>

      <div class="col-xs-8 col-sm-8">
        <img src="img/recipeBuilder.png">
      </div>



      <!--gutter-->
      <div class="col-xs-2 col-sm-2">
      </div>
    </div>
    <hr>
    <div class="container-fluid">
      <!-- If Needed Left and Right Padding in 'md' and 'lg' screen means use container class -->

      <div class="row">
        <!--current status-->

        <!--gutter-->
        <div class="col-xs-2 col-sm-2">
        </div>

        <div class="col-xs-8 col-sm-8">
          <form action="include/inc/insertRecipe.inc.php" method="get">
            <div class="form-group row">
              <label for="recipeName" class="col-4 col-form-label">Recipe Name</label>
              <div class="col-8">
                <input id="recipeName" name="recipeName" placeholder="Mugsy Blend " type="text" class="form-control" required="required">
              </div>
            </div>

            <div class="form-group row">
              <label for="recipeName" class="col-4 col-form-label">Cone Type</label>
              <div class="col-8">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                  <label class="form-check-label" for="inlineRadio1">Standard</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                  <label class="form-check-label" for="inlineRadio2">Wave</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3" disabled>
                  <label class="form-check-label" for="inlineRadio3">V60 (disabled)</label>
                </div>
              </div>
            </div>


            <div class="form-group row">
              <label for="recipeTemp" class="col-4 col-form-label">Temp</label>
              <div class="col-8">
                <input id="recipeTemp" name="recipeTemp" placeholder="198" type="text" class="form-control" required="required">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-4">Spin Direction</label>
              <div class="col-8">
                <div class="custom-control custom-radio custom-control-inline">
                  <input name="recipeSpinDirection" id="recipeSpinDirection_0" type="radio" required="required" class="custom-control-input" value="CW">
                  <label for="recipeSpinDirection_0" class="custom-control-label">Forward</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input name="recipeSpinDirection" id="recipeSpinDirection_1" type="radio" required="required" class="custom-control-input" value="CCW">
                  <label for="recipeSpinDirection_1" class="custom-control-label">Backward</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input name="recipeSpinDirection" id="recipeSpinDirection_2" type="radio" required="required" class="custom-control-input" value="CW,CCW">
                  <label for="recipeSpinDirection_2" class="custom-control-label">Split</label>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <label for="recipeGrindTime" class="col-4 col-form-label">Grind Time (Scale Offline)</label>
              <div class="col-8">
                <select id="recipeGrindTime" name="recipeGrindTime" class="custom-select" required="required">
                  <option value="10">10</option>
                  <option value="15">15</option>
                  <option value="20">20</option>
                  <option value="35">25</option>
                  <option value="30">30</option>
                  <option value=""></option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="recipeStep" class="col-4 col-form-label">Recipe Steps</label>
              <div class="col-8">
                <textarea id="recipeStep" name="recipeStep" cols="40" rows="5" class="form-control" aria-describedby="recipeStepHelpBlock" required="required"></textarea>
                <span id="recipeStepHelpBlock" class="form-text text-muted">Step #, Seconds, Milliliters</span>
              </div>
            </div>
            <div class="form-group row">
              <div class="offset-4 col-8">
                <button name="submit" type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
          </form>
          <div id="passKey"></div>
        </div>

        <!--gutter-->
        <div class="col-xs-2 col-sm-2">
        </div>





      </div>

      <!-- jQuery -->
      <script src="//code.jquery.com/jquery.js"></script>





      <!-- Bootstrap JavaScript -->
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>


      <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->

</body>

</html>