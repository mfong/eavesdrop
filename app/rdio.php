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
$next_song = array();
$the_talks = array();
$what = $_GET['do'];
$get_query = $_GET['q'];

//Captures the latest Tweet based on the conditions
function search_tweets ($search_tweets, $get_query, $connection)
{
	//Define Variables
	$array = array();

	//Capture URL
	$p['q'] = 'rd.io/x from:' . $get_query . ' filter:links';
	$p['count'] = 3;
	$result = $connection->get($search_tweets, $p);
	//print_r($result);

	foreach($result->statuses as $s) 
	{
		foreach($s->entities->urls as $url) 
		{
			$urlHeaders = get_headers($url->expanded_url, 1);
			if (is_array($urlHeaders['Location'])) {
				$urlLocation = $urlHeaders['Location'][0];
			} else {
				$urlLocation = $urlHeaders['Location'];
			}
			if ((stristr($urlLocation, 'rd.io') !== FALSE) || (stristr($urlLocation, 'rdio.com') !== FALSE)) 
			{
				$package = array();
				$package['user'] = $get_query;
				$package['track_url'] = $url->expanded_url;
				$package['id'] = $s->id;

    			$ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, 'http://www.rdio.com/api/oembed/?format=json&url=' . $url->expanded_url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $package['curl'] = json_decode(curl_exec($ch)); 

        // close curl resource to free up system resources 
        curl_close($ch); 

				array_push($array, $package);
				break 2;
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
				if (!in_array($value, $array) && strtolower($value) != strtolower($get_query)) 
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
			/*$value['urls'] = $result;
			$value['artist'] = $get_query;*/
			return $result;
			break;
		}

	}
}

//Who's talking about the link that is currently being played
function talked_by($link, $search_tweets, $connection)
{
	//Define Variable
	//$capture = array();
	//$return  = array();

	//Capture Data
	stripslashes($link);
	$p['q'] = $link;// . ' filter:links';
	//$p['count'] = 5;

	return $connection->get($search_tweets, $p);

	/*foreach($result->statuses as $people)
	{
		$capture['screen_name']		  = $people->user->screen_name;
		$capture['profile_image_url'] = $people->user->profile_image_url;
		$capture['text'] 			  = $people->text;
		array_push($return, $capture);
		unset($capture);
	}*/

	//return $return;
}

function get_embed($get_query, $connection) {
	$p['id'] = $get_query;
	return $connection->get('https://api.twitter.com/1/statuses/oembed.json', $p);
}

switch ($what) {
	case 'getTrack':
		//Capture the data from the functions
		$url_depo = search_tweets($search_tweets, $get_query, $connection);
		//$mention  = read_timeline($read_timeline, $get_query, $connection);
		//$next_song = next_song($mention, $search_tweets, $connection);
		//$the_talks = talked_by($url_depo[0]['track_url'], $search_tweets, $connection);

		$return = new ArrayObject();
		$return['urls'] = $url_depo;
		//$return['mentions'] = $mention;
		//$return['next_song'] = $next_song;
		//$return['talked_by'] = $the_talks;

		echo json_encode($return);
		break;
	case 'getNextTrack':
		$mention  = read_timeline($read_timeline, $get_query, $connection);
		$next_song = next_song($mention, $search_tweets, $connection);
		
		$return = new ArrayObject();
		//$return['mentions'] = $mention;
		$return['next_song'] = $next_song;
		
		echo json_encode($return);	
		break;
	case 'searchTrack':
		echo json_encode(talked_by($_GET[q], $search_tweets, $connection));
		break;
	case 'getEmbed':
		echo json_encode(get_embed($get_query, $connection));
		break;
}

?>