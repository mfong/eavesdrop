$(function() {
SC.initialize({
  client_id: '7343345bd999dfb70c462490f4dace1a'
});

/*var track_url = "https://soundcloud.com/phatdeuce/bunji-garlin-differentology";
		SC.oEmbed(track_url, { auto_play: true }, function(oEmbed) {
			$('#scCont').html(oEmbed['html']);
			console.log(oEmbed);
		});*/

$('.pause').click(function (e) {
	e.preventDefault();

	var widget = SC.Widget(document.querySelector('#scCont iframe'));
	widget.play();
});

$('.pause').click(function (e) {
	e.preventDefault();

	var widget = SC.Widget(document.querySelector('#scCont iframe'));
	widget.pause();
});

$('.econtainer').on({
	click: function(e) {
		e.preventDefault();

		playNextTrack();
	}
}, '.next');

$('.econtainer').on({
	click: function(e) {
		e.preventDefault();

		nextTrack($(this).attr('username'));
	}
}, '.nextTrack');

$('#twitterform').submit(function(e) {
	e.preventDefault();

	/*lookWhosTalking('https://soundcloud.com/phatdeuce/bunji-garlin-differentology');

	/*var track_url = "https://soundcloud.com/phatdeuce/bunji-garlin-differentology";
		SC.oEmbed(track_url, { auto_play: true }, function(oEmbed) {
			$('#scCont').html(oEmbed['html']);
			console.log(oEmbed);
		});

	var next_track = 'https://soundcloud.com/wgcssecretstash/masquerade-demo-02-16-13';
	var next_artist = 'WolfgangCarter';

	var track = [];
	track['artwork_url'] = 'http://i1.sndcdn.com/artworks-000040612203-ue3knt-large.jpg?de05208';

	var scNext = '<img src="' + track['artwork_url'] + '" class="pull-left album_thumb">';
			scNext += 'Since <strong>@you</strong> mentioned <strong>@me</strong> on <strong>Twitter</strong>';
			scNext += '<br/>Your Next Track is <a href="#" class="next">this better work</a> from <strong>SoundCloud</strong>';
			scNext += '<small>If you don\'t want to listen to this, pick another user that\'s also tweeting about the song your currently listening to!';


			$('#scNext').html(scNext);
			$('.next').attr('trackUrl', next_track);
			$('.next').attr('trackArtist', next_artist);*/



	$('#scCont').html('<i class="icon-spinner icon-spin icon-2x"></i> Finding Songs...');

	var currentTwitterName = $('#twittername').val();
	var url = "mixedtweets.php?do=getTrack&q=" + currentTwitterName;
	$.getJSON(url, function(data) {
		//console.log(data);

		if (data['urls'][0]) {

			if (data['urls'][0]['src'] == 'soundcloud') {

		var track_url = data['urls'][0]['track_url'];
		SC.oEmbed(track_url, { auto_play: true }, function(oEmbed) {
			$('#scCont').html(oEmbed['html']);
			console.log(oEmbed);

			var widget = SC.Widget(document.querySelector('#scCont iframe'));
			widget.bind(SC.Widget.Events.FINISH, playNextTrack);

		});

	} else if (data['urls'][0]['src'] == 'rdio') {
		$('#scCont').html(data['urls'][0]['curl']['html']);
	}

		/*var next_track = data['next_song'][0]['track_url'];
		var next_artist = data['next_song'][0]['user'];
		console.log(next_track);

		SC.get('/resolve', { url: next_track }, function(track) {
			console.log(track);

			var scNext = '<img src="' + track['artwork_url'] + '" class="pull-left album_thumb">';
			scNext += 'Since <strong>@' + currentTwitterName + '</strong> mentioned <strong>@' + next_artist + '</strong> on <strong>Twitter</strong>';
			scNext += '<br/>Your Next Track is <a href="#" class="next">' + track['title'] + '</a> from <strong>SoundCloud</strong>';
			scNext += '<small>or pick another user that\'s also tweeting about the song your currently listening to!';


			$('#scNext').html(scNext);
			$('.next').attr('trackUrl', next_track);
			$('.next').attr('trackArtist', next_artist);
		});*/

		nextTrack(currentTwitterName);

		lookWhosTalking(track_url);

		/*var url = "what2.php?do=getEmbed&q=" + data['urls'][0]['id'];
		$.getJSON(url, function(data) {
			console.log(data);
			$('#tCont').html(data['html']);
		});*/

	} else {

		$('#scCont').html('<div class="alert alert-box alert-error"><i class="icon-warning-sign icon-2x"></i> No Songs Found! Please Choose Someone Else :(</div>');

	}

	});
});

function playNextTrack() {

	var track_url = $('.next').attr('trackurl');
	var track_artist = $('.next').attr('trackartist');
	var track_src = $('.next').attr('tracksrc');

	if (track_src == 'soundcloud') {
		SC.oEmbed(track_url, { auto_play: true }, function(oEmbed) {
			$('#scCont').html(oEmbed['html']);
			console.log(oEmbed);

			var widget = SC.Widget(document.querySelector('#scCont iframe'));
			widget.bind(SC.Widget.Events.FINISH, playNextTrack);

		});
	} else if (track_src == 'rdio') {
		var url = 'mixedtweets.php?do=getRdioCurl&q=' + track_url;
		$.getJSON(url, function(data) {
			console.log(data);
			$('#scCont').html(data['html']);
		});
	}
	nextTrack(track_artist);
	lookWhosTalking(track_url);
}


function nextTrack(currentTwitterName) {
	$('#scNext').html('<i class="icon-spinner icon-spin icon-2x"></i> Finding Your Next Track...');

	var url = "mixedtweets.php?do=getNextTrack&q=" + currentTwitterName;
	$.getJSON(url, function(data) {
		//console.log(data);

		if (data['next_song']) {

		var next_track = data['next_song'][0]['track_url'];
		var next_artist = data['next_song'][0]['user'];
		var next_src = data['next_song'][0]['src'];
		//console.log(next_src);

		if (next_src == 'soundcloud') {

		SC.get('/resolve', { url: next_track }, function(track) {
			console.log('sc here');

			// todo: null artwork

			var scNext = '<img src="' + track['artwork_url'] + '" class="pull-left album_thumb">';
			scNext += 'Since <strong>@' + currentTwitterName + '</strong> mentioned <strong>@' + next_artist + '</strong> on <strong>Twitter</strong>';
			scNext += '<br/>Your Next Track is <a href="#" class="next">' + track['title'] + '</a> from <strong>SoundCloud</strong>';
			scNext += '<small>or pick another user that\'s also tweeting about the song your currently listening to!';


			$('#scNext').html(scNext);
			$('.next').attr('trackurl', next_track);
			$('.next').attr('trackartist', next_artist);
			$('.next').attr('tracksrc', next_src);
		});

		} else if (next_src == 'rdio') {
			console.log('rdio here');

			var artwork_url = data['next_song'][0]['curl']['thumbnail_url'];
			var track_title = data['next_song'][0]['curl']['title'];
			
			var scNext = '<img src="' + artwork_url + '" class="pull-left album_thumb">';
			scNext += 'Since <strong>@' + currentTwitterName + '</strong> mentioned <strong>@' + next_artist + '</strong> on <strong>Twitter</strong>';
			scNext += '<br/>Your Next Track is <a href="#" class="next">' + track_title + '</a> from <strong>Rdio</strong>';
			scNext += '<small>or pick another user that\'s also tweeting about the song your currently listening to!';


			$('#scNext').html(scNext);
			$('.next').attr('trackurl', next_track);
			$('.next').attr('trackartist', next_artist);
			$('.next').attr('tracksrc', next_src);
		}

	} else {

		$('#scNext').html('<div class="alert alert-box alert-error"><i class="icon-warning-sign icon-2x"></i> No Songs Found! Please Choose Someone Else :(</div>');

	}

	});
}

function lookWhosTalking(trackUrl) {
	$('#twCont').html('');
	var url = "mixedtweets.php?do=searchTrack&q=" + encodeURIComponent(trackUrl);
	$.getJSON(url, function(data) {
		console.log(data);

		var tw = '<table class="table table-condensed table-striped table-hover"><thead><tr><th colspan="3">Who Else is Talking About This Track</td></tr></thead><tbody>';
		$.each(data['statuses'], function(i, v) {
			tw += '<tr class="nextTrack" username="' + v['user']['screen_name'] + '"><td><img src="' + v['user']['profile_image_url'] + '"></td><td>@' + v['user']['screen_name'] + '</td><td>' + v['text'] + '</td></tr>'
		});
		tw += '</tbody></table>';

		$('#twCont').html(tw);
	});
}

});