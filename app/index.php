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
	</div>
</div>

<footer>
	<i class="icon-play icon-2x"></i>
</footer>

<script src="http://code.jquery.com/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="http://connect.soundcloud.com/sdk.js"></script>
<script>
SC.initialize({
  client_id: '7343345bd999dfb70c462490f4dace1a'
});

$('#twitterform').submit(function(e) {
	e.preventDefault();

	$('#scCont').html('<i class="icon-spinner icon-spin icon-2x"></i> Finding Songs...');

	var url = "what.php?q=" + $('#twittername').val();
	$.getJSON(url, function(data) {
		console.log(data);
		var track_url = data;
		SC.oEmbed(track_url, { auto_play: true }, function(oEmbed) {
			$('#scCont').html(oEmbed['html']);
			console.log(oEmbed);
		});

		var url = "e.php?q=" + track_url;
			$.getJSON(url, function(data) {
				console.log(data)
			});
	});
});
</script>
</body>
</html>