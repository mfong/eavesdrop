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
		$urlLocation = get_location_header($url->expanded_url);
		if ((stristr($urlLocation, 'soundcloud.com') !== FALSE) || (stristr($urlLocation, 'snd.sc') !== FALSE)) {
			$value = $url->expanded_url;
			array_push($url_depo, $value);
		}
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
		if (!in_array($value, $mentions)) {
			array_push($mentions, $value);
		}
	}
}

$return = new ArrayObject();
$return['urls'] = $url_depo;
$return['mentions'] = $mentions;

echo json_encode($return);

//$connection->post('statuses/update', array('status' => $message));



// this will return the long url from (most) url shorteners
function get_location_header($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_USERAGENT, 'MHD 2013');
	curl_setopt($ch, CURLOPT_REFERER, 'http://tbd.com');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	$response = curl_exec($ch);
	curl_close($ch);
	
	if ($response) {
	   preg_match_all('#Location:\s?([^\s]+)#i', $response, $match);
	    
	    if (isset($match[1])) {
	        return end($match[1]);
	       }
	}
	
	return false;
}

?>