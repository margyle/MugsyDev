<?php require 'inc/mugsy.inc.php';?>
<!-- This page is just a scratch pad for accessing some 
links and demos, not meant for prodiction -->
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
			background-image: url("img/ux/backgroundBlueRadial.png");
			background-repeat: no-repeat;
			background-attachment: fixed;
			color: #707070;
			overflow-x: hidden;
		}


		.slidecontainer {
			width: 100%;
			/* Width of the outside container */
		}

		/* The slider itself */
		.slider {
			-webkit-appearance: none;
			width: 100%;
			height: 15px;
			border-radius: 5px;
			background: #707070;
			outline: none;
			opacity: 0.7;
			-webkit-transition: .2s;
			transition: opacity .2s;
		}

		.slider::-webkit-slider-thumb {
			-webkit-appearance: none;
			appearance: none;
			width: 25px;
			height: 25px;
			border-radius: 50%;
			background: white;
			cursor: pointer;
		}

		.slider::-moz-range-thumb {
			width: 25px;
			height: 25px;
			border-radius: 50%;
			background: #4CAF50;
			cursor: pointer;
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
			<!--current status-->
			<div class="col-xs-4 col-sm-4" align="center">
				<img src="img/statusBlock.png" alt="">
			</div>
			<div class="col-xs-6 col-sm-6">
				<h3>
					<div class="alert alert-danger" role="alert" id="mug">
						Unable to Brew: No Cup
					</div>
				</h3>
			</div>

		</div>
		<hr>
		<div class="row">

			<div class="col-xs-4 col-sm-4" align="center">
				<a href="demos/rfid/rfidDemo.php"><img src="img/currentBlock.png" alt=""></a>
			</div>
			<div class="col-xs-4 col-sm-4">
				<img src="img/levelsBlock.png" alt="">
				<!--                         	 		<div id="mugScan">
                           	 		<a href="demos/rfid/rfidDemo.php">Mug Scan </a>	
                           	 		</div>	
                           	 		<div id="integrationsLink">
                           	 		<a href="demos/integrationsDemo.php">Integrations </a>	
                           	 		</div>	 -->
			</div>




		</div>


		<hr>

		<div class="row">

			<div class="col-xs-4 col-sm-4" align="center">
				<a href="demos/rfid/rfidDemo.php"><img src="img/grinderBlock.png" alt=""></a>
			</div>
			<div class="col-xs-4 col-sm-4">
				<form method="get" action="grinderAPI.php" role="form" id="seconds" name="seconds">

					<div class="form-group">
						<label>Seconds</label>

						<select class="form-control" id="seconds" name="seconds">
							<option>1</option>
							<option>2</option>
							<option>3</option>
							<option>4</option>
							<option>5</option>
							<option>10</option>
						</select>
					</div>
					<button type="submit" class="btn btn-success mb-2">Start Grind</button>
				</form>
				<!--                         	 		<div id="mugScan">
                           	 		<a href="demos/rfid/rfidDemo.php">Mug Scan </a>	
                           	 		</div>	
                           	 		<div id="integrationsLink">
                           	 		<a href="demos/integrationsDemo.php">Integrations </a>	
                           	 		</div>	 -->
			</div>




		</div>


		<hr>
		<div class="row">

			<div class="col-xs-4 col-sm-4" align="center">
				<a href="demos/rfid/rfidDemo.php"><img src="img/heaterBlock.png" alt=""></a>
			</div>
			<div class="col-xs-8 col-sm-8">
				<form action="grinderAPI.php" method="POST" role="form">

					<div class="form-group">
						<label>Temperature</label>
						<div class="slidecontainer">
							<input type="range" min="100" max="220" value="65" class="slider" id="myRange">

						</div>

						<!--this is the value display for slider-->
						<h3>
							<div id="demo"></div>
						</h3>Degrees
					</div>
					<button type="submit" class="btn btn-success mb-2">Start Heater</button> <button type="submit" class="btn btn-danger mb-2">Stop Heater</button>
				</form>

			</div>

		</div>


		<hr>
		<div class="row">

			<div class="col-xs-4 col-sm-4" align="center">
				<img src="img/ux/pourPattern.png" alt="">
			</div>
			<div name="temp" class="col-xs-8 col-sm-8">
				<div class="card">
					<div class="card-block">
						<img src="img/ux/standardPattern.png" alt="">
					</div>
				</div>

				<script type="text/javascript">
					setInterval(function() {

						function populatePre(url) {
							var xhr = new XMLHttpRequest();
							xhr.onload = function() {
								document.getElementById('waterTemp').textContent = this.responseText;
							};
							xhr.open('GET', url);
							xhr.send();
						}
						populatePre('helpers/dataFile.txt');
					}, 500);
				</script>

			</div>

		</div>


	</div>
	<hr>
	<div class="row">

		<div class="col-xs-4 col-sm-4" align="center">
			<img src="img/ux/waterTemp.png" alt="">
		</div>
		<div name="temp" class="col-xs-8 col-sm-8">
			<div class="card">
				<div class="card-block">
					<div class="alert alert-danger">
						<strong>
							<h1 class="card-title">
								<div id="waterTemp"></div>
						</strong></div>
					</h1>
					<h6 class="card-subtitle mb-2 text-muted">Current Water Temp</h6>
					<p class="card-text">Water brew temperature is set for 198 degrees fahrenheit.
						<!-- <a href="#" class="card-link">Heat to 195</a>
										    <a href="#" class="card-link">Adjust Temperature</a> -->
				</div>
			</div>

			<script type="text/javascript">
				setInterval(function() {

					function populatePre(url) {
						var xhr = new XMLHttpRequest();
						xhr.onload = function() {
							document.getElementById('mug').textContent = this.responseText;
						};
						xhr.open('GET', url);
						xhr.send();
					}
					populatePre('helpers/dataFile.txt');
				}, 500);
			</script>

		</div>

	</div>

	<hr>
	<div class="row">

		<div class="col-xs-4 col-sm-4" align="center">
			<img src="img/ux/waterTemp.png" alt="">
		</div>
		<div name="color" class="col-xs-8 col-sm-8">
			<div class="card">
				<div class="card-block">
					<a href="http://192.168.43.49/">Enclosure Color</a>
					</br>
					<a href="brewDemo.php">Brew Demo</a>
					</br>
					<a href="demos/rfid/rfidDemo.php">RFID Demo</a>
					</br>
					<a href="demos/integrations.php">Integrations Demo</a>
					</br>
					<a href="https://open.spotify.com/browse/featured">Spotify</a>
					</br>
					<a href="http://192.168.1.183/mugsy/interface/newRecipe.html">Keyboard Test</a>
					</br>
					<a href="range/index.html">range Test</a>
					</br>
					<a href="alexa/interfaceAlexa.html">Assistants</a>
					</br>
					<a href="community/dashboard-home.html">Community</a>
					</br>
					<a href="vis.html">Vis</a>
					</br>
					<a href="admin/recipeForm.php">Recipe Form</a>
					</br>
					<br>
				</div>
			</div>

		</div>

	</div>

	<!-- jQuery -->
	<script src="//code.jquery.com/jquery.js"></script>





	<!-- Bootstrap JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

	<script type="text/javascript">
		var slider = document.getElementById("myRange");
		var output = document.getElementById("demo");
		output.innerHTML = slider.value; // Display the default slider value

		// Update the current slider value (each time you drag the slider handle)
		slider.oninput = function() {
			output.innerHTML = this.value;
		}
	</script>
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->

</body>

</html>
