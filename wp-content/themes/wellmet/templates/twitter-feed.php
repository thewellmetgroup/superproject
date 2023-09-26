<?php
//DEPRICATED
/*
$handle=$twitter_handle;
$num_tweets = get_field('tweets_num');
$twitter_oauth_access_token = get_field('twitter_oauth_access_token');
$twitter_oauth_access_token_secret = get_field('twitter_oauth_access_token_secret');
$twitter_consumer_key = get_field('twitter_consumer_key');
$twitter_consumer_secret = get_field('twitter_consumer_secret');

if($twitter_oauth_access_token && $twitter_oauth_access_token_secret && $twitter_consumer_key && $twitter_consumer_secret ) {
	require_once(get_theme_file_path().'/lib/TwitterAPIExchange.php');
	 
	$settings = array(
	    'oauth_access_token' => $twitter_oauth_access_token,
	    'oauth_access_token_secret' => $twitter_oauth_access_token_secret,
	    'consumer_key' => $twitter_consumer_key,
	    'consumer_secret' => $twitter_consumer_secret
	);

	$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
	$requestMethod = "GET";
	if (isset($_GET['user']))  {$user = preg_replace("/[^A-Za-z0-9_]/", '', $_GET['user']);}  else {$user  = $handle;}
	if (isset($_GET['count']) && is_numeric($_GET['count'])) {
		$count = $_GET['count'];
	} else {
		$count = $num_tweets;
	}

	$getfield = "?screen_name=$user&count=$count&tweet_mode=extended";
	$twitter = new TwitterAPIExchange($settings);
	$string = json_decode($twitter->setGetfield($getfield)
	->buildOauth($url, $requestMethod)
	->performRequest(),$assoc = TRUE);

	
	if(array_key_exists("errors", $string)) {echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string[errors][0]["message"]."</em></p>";exit();}

	foreach($string as $items)
	    {
	        echo '<div class="tweet">';
		        $date = new DateTime($items['created_at']);
				$date->setTimezone(new DateTimeZone('America/New_York'));
				$formatted_date = $date->format('F d, Y');
				echo '<p class="small date">';
		        	echo $formatted_date;
		        echo '</p>';

		        $tweet = $items['full_text'];
		        $tweet = preg_replace('/#(\w+)/', 
		                    '<a href="https://twitter.com/hashtag/$1" target="_blank">#$1</a>', 
		                    $tweet);
		        $tweet = preg_replace('/@(\w+)/',  
		                    '<a href="https://twitter.com/$1" target="_blank">@$1</a>', 
		                    $tweet);
		        echo $tweet;
	        echo '</div>';
	        
	        //echo "Tweeted by: ". $items['user']['name']."<br />";
	        //echo "Screen name: ". $items['user']['screen_name']."<br />";
	        //echo "Followers: ". $items['user']['followers_count']."<br />";
	        //echo "Friends: ". $items['user']['friends_count']."<br />";
	        //echo "Listed: ". $items['user']['listed_count']."<br /><hr />";
	    }
} else {
	echo __('Please check twitter oAuth settings', 'sage');
}
*/
?>