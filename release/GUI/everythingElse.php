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
	<!--todo kill these breaks and set proper padding-->1
	<br>
	<br>
	<br>
	<div class="container-fluid">
		<!-- If Needed Left and Right Padding in 'md' and 'lg' screen means use container class -->
		<div class="row">

			<div class="col-xs-4 col-sm-4">
				<a href="coffeeSettings.php"><img src="img/blocks/coffeeSettings.png" alt=""></a>
			</div>
			<div class="col-xs-4 col-sm-4">
				<a href="mugsySettings.php"><img src="img/blocks/mugsySettings.png" alt=""></a>
			</div>
			<div class="col-xs-4 col-sm-4">
				<a href="userSettings.php.php"><img src="img/blocks/userSettings.png" alt=""></a>
			</div>

		</div>
	</div>

	<!--end container-->
	<!-- jQuery -->
	<script src="inc/js/jquery.js"></script>
	<!-- Bootstrap JavaScript -->
	<script src="inc/js/bootstrap.min.js"></script>

</body>

</html>
