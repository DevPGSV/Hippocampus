<?php

require_once(__DIR__ . '/TwitterAPIExchange.php');

class TwitterModule extends HC_Module {


  private $twitterApi;
  public function __construct($hc) {
    parent::__construct($hc);
    /*
    $this->twitterApi = new TwitterAPIExchange($this->twitterApiData);

    $this->registerWindowCallback('twitter', 'TwitterWindowCallback');
    $this->registerWindowCallback('twitter_hometimeline', 'TwitterHomeTimelineWindowCallback');
    $this->registerWindowCallback('twitter_usertimeline', 'TwitterUserTimelineWindowCallback');
    $this->registerWindowCallback('twitter_userprofile', 'TwitterUserProfileWindowCallback');
    */
  }

  public static function setup($hc) {
    /*
    $sql = "CREATE TABLE hc_m_TwitterModule_users(
      id INT NOT NULL AUTO_INCREMENT,
      user INT NOT NULL,
      token VARCHAR(64) NOT NULL,
      data VARCHAR(32) NOT NULL,
      PRIMARY KEY (`id`)
    )";
    $db = $stmt = $hc->getDB()->getDBo();
    $stmt = $db->prepare($sql);
    return $stmt->execute();
    */
    return false;
  }

  public function onCreatingSidebar(&$sidebar) {
    $newEntry = [
      'icon' => 'twitter',
      'text' => 'Twitter',
      'id' => 'twitter',
    ];
    //array_unshift($sidebar, $newEntry); // To prepend the entry
  }

  public function onCreatingNotifications(&$notifications) {
    $newEntry = [
      'notificationCounter' => 2,
      'text' => 'Tienes {COUNTER} menciones',
      'cb' => 'TwitterMentionNotificationCallback',
      'cbData' => [],
    ];
    array_unshift($notifications, $newEntry); // To prepend the entry
  }

  private function tweetsToHtmlTable($tweets) {
    $tweetsHtmlFormatted = "<table class='table table-responsive' style='color:white;'>";
    //$tweetsHtmlFormatted .= '<tr><td></td></tr>';
    foreach ($tweets as $tweet) {
      $tweetsHtmlFormatted .= "<tr><td>{$tweet['user']['screen_name']}<br>{$tweet['text']}<br>{$tweet['created_at']}</td></tr>";
    }
    $tweetsHtmlFormatted .= "</table>";
    return $tweetsHtmlFormatted;
  }

  public function TwitterWindowCallback() {
    return [
      'html' => "<p data-updatewindowboxservice='twitter_hometimeline'>Home timeline</p><p data-updatewindowboxservice='twitter_usertimeline'>User timeline</p><p data-updatewindowboxservice='twitter_userprofile'>User profile</p>",
      'title' => 'Twitter',
    ];
  }

  public function TwitterHomeTimelineWindowCallback() {
    //$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    $url = 'https://api.twitter.com/1.1/statuses/home_timeline.json';
    //$getfield = '?screen_name=DevPGSV&count=2';
    $getfield = '?count=2';
    //$tweets = json_decode($this->twitterApi->setGetfield($getfield)->buildOauth($url, 'GET')->performRequest(), true);
    
    //print_r($tweets);
    $tweetsHtmlFormatted = $this->tweetsToHtmlTable($tweets);
    return [
      'html' => "<p data-updatewindowboxservice='twitter'>Back!....</p>\n\n$tweetsHtmlFormatted",
      'title' => 'Twitter Home Timeline',
    ];
  }

  public function TwitterUserTimelineWindowCallback() {
    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    $getfield = '?screen_name=DevPGSV&count=2';
    $tweets = json_decode($this->twitterApi->setGetfield($getfield)
      ->buildOauth($url, 'GET')
      ->performRequest(), true);
    $tweetsHtmlFormatted = $this->tweetsToHtmlTable($tweets);
    return [
      'html' => "<p data-updatewindowboxservice='twitter'>Back!....</p>\n\n$tweetsHtmlFormatted",
      'title' => 'Twitter User Timeline',
    ];
  }

  public function TwitterUserProfileWindowCallback() {
    $userProfileHtmlFormatted = "<p>Profile</p>";
    return [
      'html' => "<p data-updatewindowboxservice='twitter'>Back!....</p>\n\n$userProfileHtmlFormatted",
      'title' => 'Twitter User Profile',
    ];
  }

  public function TwitterMentionNotificationCallback($cbData) {
    return '<p>Twitter Mentions</p><br><pre>'.print_r($cbData, true).'</pre>';
  }

}
