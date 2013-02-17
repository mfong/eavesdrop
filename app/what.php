<?
require_once('twitteroauth/twitteroauth.php');

$consumer_key = "83MHIvLeQr2Zt3QqO3eXLw";
$consumer_secret = "30h0qGW6RhUpjTDayKeCSTjAmOJgLqfk6DR9hv6Bes";
$access_key = "15512007-IBBbYoNX3wu8DTeSqS1IxYgQXEvZv27bsejNsohpE";
$access_secret = "rwPBADZWflr9zJNQWB1L4F6ANOFX1OaQBI4FIxlX6qY";
  
$connection = new TwitterOAuth ($consumer_key ,$consumer_secret , $access_key , $access_secret );

$search_tweets = 'https://api.twitter.com/1.1/search/tweets.json';
$search_users  = 'https://api.twitter.com/1.1/users/search.json';
$read_timeline = 'https://api.twitter.com/1.1/statuses/user_timeline.json';


$p['q'] = 'soundcloud from:' . $_GET['q'] . ' filter:links';
$result = $connection->get($search_tweets, $p);

//Define Variables
$url_depo = array();
$mentions = array();

foreach($result->statuses as $s) {
	foreach($s->entities->urls as $url) {
		//if ((stristr($url->expanded_url, 'soundcloud.com') !== FALSE) || (stristr($url->expanded_url, 'snd.sc') !== FALSE)) {
			$value = $url->expanded_url;
			array_push($url_depo, $value);
		//}
	}
}

//Clear Data sets
unset($p);
unset($result);
unset($value);

$p['screen_name'] = $_GET['q'];

$result = $connection->get($read_timeline, $p);

foreach($result as $twitter_post)
{
	foreach($twitter_post->entities->user_mentions as $shout_out)
	{
		$value = $shout_out->screen_name;
		array_push($mentions, $value);
	}
}

//$connection->post('statuses/update', array('status' => $message));

?>