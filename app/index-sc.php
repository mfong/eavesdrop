<!DOCTYPE html>
<html lang="en">
<head>
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="css/font-awesome.min.css">
<!--[if IE 7]>
<link rel="stylesheet" href="css/font-awesome-ie7.min.css">
<![endif]-->
<link href="css/drop.css" rel="stylesheet" media="screen">
<link rel="shortcut icon" href="/img/favicon.ico" />
</head>
<body>

<div class="container">
	<div class="econtainer">
		<img src="img/mixedtweets.png"/>
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

	<footer>
		<em>Made at <a href="http://sf.musichackday.org/2013/" target="_blank">Music Hack Day 2013 San Francisco</a></em><br/>
		<em>by <a href="https://twitter.com/matthewfong" target="_blank">@matthewfong</a> and <a href="https://twitter.com/abehjat" target="_blank">@abehjat</a></em>
	</footer>
</div>

<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="http://connect.soundcloud.com/sdk.js"></script>
<script src="https://w.soundcloud.com/player/api.js"></script>
<script src="js/drop.js"></script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-38565673-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>