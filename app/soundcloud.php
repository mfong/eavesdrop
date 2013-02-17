<!DOCTYPE html>
<html lang="en">
<head>
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>

<form id="twitterform">
<input type="text" class="xx-large" id="twittername"/>
</form>

<script src="http://code.jquery.com/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="http://connect.soundcloud.com/sdk.js"></script>
<script>
SC.initialize({
  client_id: '7343345bd999dfb70c462490f4dace1a'
});

$('#twitterform').submit(function(e) {
	e.preventDefault();


	var url = "what.php?q=" + $('#twittername').val();
	$.getJSON(url, function(data) {
		console.log(data);
		var track_url = data;
		SC.oEmbed(track_url, { auto_play: true }, function(oEmbed) {
			$('body').append(oEmbed['html']);
			console.log(oEmbed);
		});
	});
});
</script>
</body>
</html>