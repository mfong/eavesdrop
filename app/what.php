<?
require_once('twitteroauth/twitteroauth.php');

$consumer_key = "83MHIvLeQr2Zt3QqO3eXLw";
$consumer_secret = "30h0qGW6RhUpjTDayKeCSTjAmOJgLqfk6DR9hv6Bes";
$access_key = "15512007-IBBbYoNX3wu8DTeSqS1IxYgQXEvZv27bsejNsohpE";
$access_secret = "rwPBADZWflr9zJNQWB1L4F6ANOFX1OaQBI4FIxlX6qY";
  
$connection = new TwitterOAuth ($consumer_key ,$consumer_secret , $access_key , $access_secret);

$search_tweets = 'https://api.twitter.com/1.1/search/tweets.json';
$search_users  = 'https://api.twitter.com/1.1/users/search.json';
$read_timeline = 'https://api.twitter.com/1.1/statuses/user_timeline.json';

//Define Variables
$url_depo  = array();
$mentions  = array();
$next_song = array();
$the_talks = array();
$get_query = $_GET['q'];


//Captures the latest Tweet based on the conditions
function search_tweets ($search_tweets, $get_query, $connection)
{
	//Define Variables
	$array = array();

	//Capture Data
	$p['q'] = 'soundcloud from:' . $get_query . ' filter:links';
	$p['count'] = 5;
	$result = $connection->get($search_tweets, $p);

	foreach($result->statuses as $s) 
	{
		foreach($s->entities->urls as $url) 
		{
			$urlLocation = get_location_header($url->expanded_url);
			$urlHeaders = get_headers($url->expanded_url,1);
			$urlLocation = $urlHeaders['Location'];
			if ((stristr($urlLocation, 'soundcloud.com') !== FALSE) || (stristr($urlLocation, 'snd.sc') !== FALSE)) 
			{
				$value = $url->expanded_url;
				array_push($array, $value);
			} // End of IF

		} //End of Foreach

	} // End of Foreach

	shuffle($array);

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

//Return the next result based on the user's initial input
function next_song($array, $search_tweets, $connection)
{
	//Randomize Results
	shuffle($array);

	//So far as long as the Array is not empty
	while(!empty($array))
	{
		//Passes the information of the person mentioned as the query item
		$get_query = array_pop($array);

		//Get the results from TWitter
		$result = search_tweets ($search_tweets, $get_query, $connection);
		
		//If we found a link, return it and break the while cycle
		if(!empty($result))
		{
			$value['urls'] = $result;
			$value['artist'] = $get_query;
			return $value;
			break;
		}

	}
}

//Who's talking about the link that is currently being played
function talked_by($link, $search_tweets, $connection)
{
	//Define Variable
	$capture = array();
	$return  = array();

	//Capture Data
	stripslashes($link);
	$p['q'] = $link . ' filter:links';
	$p['count'] = 5;

	$result = $connection->get($search_tweets, $p);

	foreach($result->statuses as $people)
	{
		$capture['screen_name']		  = $people->user->screen_name;
		$capture['profile_image_url'] = $people->user->profile_image_url;
		$capture['text'] 			  = $people->text;
		array_push($return, $capture);
		unset($capture);
	}

	return $return;
}

// this will return the long url from (most) url shorteners
//Not needed, using get_headers()
function get_location_header($url) 
{
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
$url_depo  = search_tweets($search_tweets, $get_query, $connection);
$mention   = read_timeline($read_timeline, $get_query, $connection);
$next_song = next_song($mention, $search_tweets, $connection);

$vid_link  = $url_depo[0];
$the_talks = talked_by($vid_link, $search_tweets, $connection);


$return 			 = new ArrayObject();
$return['urls'] 	 = $url_depo;
$return['mentions']  = $mention;
$return['next_song'] = $next_song;
$return['talked_by'] = $the_talks;

echo "<pre>";
//echo json_encode($return);
print_r($return);
echo "</pre>";

?>