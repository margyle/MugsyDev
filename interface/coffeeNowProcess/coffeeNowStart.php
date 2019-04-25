<?php
require 'inc/mugsy.inc.php';
include '../include/inc/coffeeNow.inc.php';
//next step in recipe is auto populated in JS with "php echo $nextStep">
//next step url builder is in CoffeeNow.inc.php
?>
<!DOCTYPE html>
<html lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mugsy</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

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
      background-image: url("img/backGroundPink.png");
      background-repeat: no-repeat;
      background-attachment: fixed;
      color: #707070;
      overflow-x: hidden;
    }
  </style>
</head>

<body>
  <!--#todo: kill these breaks and set proper padding-->
  <br>
  <br>
  <br>
  <div class="container-fluid">
    <!-- If Needed Left and Right Padding in 'md' and 'lg' screen means use container class -->
    <div class="row">
      <div class="col-xs-2 col-sm-2">
        <!--this stays empty-->
      </div>
      <!--end col-->
      <div class="col-xs-4 col-sm-4-offset-2">
        <img src="kalitaNow.png" alt="">
      </div>
      <!--start card-->
      <div class="col-xs-4 col-sm-4-offset-2">
        <div class="card">
          <img src="tinyCoffeeNow.png" class="card-img-top" alt="...">
          <h3 class="card-title" style="color:#f26d7d;">Grinding</h3>
          <div id="timerDiv" class="alert alert-danger"></div>
          <p class="card-text">Mugsy is brewing using your last cup's settings. </p>
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><?php echo $recipeName; ?></li>
          <li class="list-group-item"><?php echo $coffeeType; ?></li>
        </ul>
        <div class="card-body">
        </div>
      </div>
    </div>
    <div class="col-xs-2 col-sm-2">
      <!--this stays empty-->
    </div>
  </div>
  </div>
  <!--#todo move css/js includes from cdn to local-->
  <!-- jQuery -->
  <script src="//code.jquery.com/jquery.js"></script>
  <!-- Bootstrap JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->

</body>

</html>

<!--brew step to next brew step-->
<script type="text/javascript">
  $(function() {
    console.log("runningMAOS");
    var current_progress = 0;
    var interval = setInterval(function() {
      current_progress += 100;
      location.replace(<?php echo $nextStep ?>);
      if (current_progress >= 100)
        clearInterval(interval);
      console.log("runningMAOS***");
    }, <?php echo $bloomTime ?>);
    console.log("runningMAOSXXX");
  });
</script>
<!--grinder countdown-->
<script type="text/javascript">
  var timeLeft = <?php echo $grinderCountDown; ?>
  var elem = document.getElementById('timerDiv');
  var timerId = setInterval(countdown, 1000);

  function countdown() {
    if (timeLeft == -1) {
      clearTimeout(timerId);
      doSomething();
    } else {
      elem.innerHTML = timeLeft + ' seconds remaining';
      timeLeft--;
    }
  }

  function doSomething() {
    alert("Remove Me");
  }
</script>