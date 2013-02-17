<?
require_once('twitteroauth/twitteroauth.php');

$consumer_key = "83MHIvLeQr2Zt3QqO3eXLw";
$consumer_secret = "30h0qGW6RhUpjTDayKeCSTjAmOJgLqfk6DR9hv6Bes";
$access_key = "15512007-IBBbYoNX3wu8DTeSqS1IxYgQXEvZv27bsejNsohpE";
$access_secret = "rwPBADZWflr9zJNQWB1L4F6ANOFX1OaQBI4FIxlX6qY";
  
$connection = new TwitterOAuth ($consumer_key ,$consumer_secret , $access_key , $access_secret );

/*$parameters['screen_name'] = 'diplo';
//$parameters['count'] = 2;

$result = $connection->get('statuses/user_timeline', $parameters);*/

//$p['q'] = 'soundcloud.com from:diplo filter:links';
//$p['q'] = 'snd.sc from:' . $_GET['q'] . ' filter:links';
$p['q'] = $_GET['q'] . ' filter:links';
$result = $connection->get('https://api.twitter.com/1.1/search/tweets.json', $p);

echo json_encode($result);


//$connection->post('statuses/update', array('status' => $message));

?>