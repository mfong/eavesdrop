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

//Define Variables
$url_depo = array();
$mentions = array();
$get_query = $_GET['q'];

//Captures the latest Tweet based on the conditions
function search_tweets ($search_tweets, $get_query, $connection)
{
	//Define Variables
	$array = array();

	//Capture URL
	$p['q'] = 'soundcloud from:' . $get_query . ' filter:links';
	$p['count'] = 5;
	$result = $connection->get($search_tweets, $p);

	foreach($result->statuses as $s) 
	{
		foreach($s->entities->urls as $url) 
		{
			$urlLocation = get_location_header($url->expanded_url);
			if ((stristr($urlLocation, 'soundcloud.com') !== FALSE) || (stristr($urlLocation, 'snd.sc') !== FALSE)) 
			{
				$value = $url->expanded_url;
				array_push($array, $value);
			} // End of IF
		} //End of Foreach
	} // End of Foreach

	return $array;

}//End of function

//Read the user's timeline
function read_timeline($read_timeline, $get_query, $connection)
{
	//Define Variables
	$array = array();

	$p['screen_name'] = $get_query;

	$result = $connection->get($read_timeline, $p);

	foreach($result as $twitter_post)
	{
		foreach($twitter_post->entities->user_mentions as $shout_out)
		{
			$value = $shout_out->screen_name;
				if (!in_array($value, $array)) 
				{
					array_push($array, $value);
				} //End of IF Condition

		} //End of foreach

	} //End of forreach

	return $array;

}//End of function

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

//Capture the data from the functions
$url_depo = search_tweets($search_tweets, $get_query, $connection);
$mention  = read_timeline($read_timeline, $get_query, $connection);

$return = new ArrayObject();
$return['urls'] = $url_depo;
$return['mentions'] = $mention;

echo json_encode($return);

?>