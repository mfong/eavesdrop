<!DOCTYPE html>
<html lang="en">
<head>
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="css/font-awesome.min.css">
<!--[if IE 7]>
<link rel="stylesheet" href="css/font-awesome-ie7.min.css">
<![endif]-->
<link href="css/drop.css" rel="stylesheet" media="screen">
</head>
<body>

<div class="container">
	<div class="econtainer">
		<form id="twitterform">
			<div class="input-prepend bigassinput">
				<span class="add-on">@</span>
				<input type="text" id="twittername"/>
			</div>
		</form>
		
		<div id="scCont"></div>
		<div class="controls">
			<a href="#" class="play">play</a>
			<a href="#" class="pause">pause</a>
			<a href="#" class="next">next</a>
		</div>
		<div id="scNext" class="clearfix"></div>
		<div id="tCont"></div>
		<div id="twCont"></div>
	</div>
</div>

<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="http://connect.soundcloud.com/sdk.js"></script>
<script src="https://w.soundcloud.com/player/api.js"></script>
<script src="js/drop.js"></script>
</body>
</html>