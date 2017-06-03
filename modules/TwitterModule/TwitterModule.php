<?php

require_once(__DIR__ . '/TwitterAPIExchange.php');

class TwitterModule extends HC_Module {

  private $twitterApiData;
  private $userData;
  private $twitterApi;
  private $loggedIn;
  private $tokensSetup;
  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('twitter', 'TwitterWindowCallback');
    $this->registerWindowCallback('twitter_hometimeline', 'TwitterHomeTimelineWindowCallback');
    $this->registerWindowCallback('twitter_usertimeline', 'TwitterUserTimelineWindowCallback');
    $this->registerWindowCallback('twitter_userprofile', 'TwitterUserProfileWindowCallback');
    $this->registerWindowCallback('twitter_hashtag', 'TwitterHashtagWindowCallback');
    $this->registerWindowCallback('twitter_oauth', 'TwitterOauthWindowCallback');
    $this->registerWindowCallback('twitter_admin', 'TwitterAdminWindowCallback');

    $this->logInUser();

    $c_consumer_key = $hc->getDB()->getConfigValue('module.TwitterModule.consumer_key');
    $c_consumer_secret = $hc->getDB()->getConfigValue('module.TwitterModule.consumer_secret');

    if ($c_consumer_key !== false && $c_consumer_secret !== false)

    $this->loggedIn = false;
    $this->twitterApi = false;
    $cu = $hc->getUserManager()->getLoggedInUser();
    if ($cu !== false && $c_consumer_key !== false && $c_consumer_secret !== false) {
      $stmt = $hc->getDB()->getDBo()->prepare("SELECT * FROM hc_m_TwitterModule_users WHERE user=:id");
      $stmt->bindValue(':id', $cu->getId(), PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (count($rows) === 1) {
        $this->userData = $rows[0];
        $this->twitterApiData = [
          'oauth_access_token' => $rows[0]['oauth_token'],
          'oauth_access_token_secret' => $rows[0]['oauth_token_secret'],
          'consumer_key' => $c_consumer_key,
          'consumer_secret' => $c_consumer_secret,
        ];
        $this->twitterApi = new TwitterAPIExchange($this->twitterApiData);
        $this->loggedIn = true;
      }
    }

  }

  public function logInUser() {

  }

  public static function setup($hc) {
    $sql = "CREATE TABLE IF NOT EXISTS hc_m_TwitterModule_users(
      user INT NOT NULL,
      oauth_token VARCHAR(256) NOT NULL,
      oauth_token_secret VARCHAR(256) NOT NULL,
      user_id VARCHAR(64) NOT NULL,
      screen_name VARCHAR(64) NOT NULL,

      PRIMARY KEY (`user`)
    )";
    $db = $stmt = $hc->getDB()->getDBo();
    $stmt = $db->prepare($sql);
    return $stmt->execute();
  }

  public function onCreatingSidebar(&$sidebar) {
    $newEntry = [
      'icon' => 'twitter',
      'text' => 'Twitter',
      'id' => 'twitter',
    ];
    array_unshift($sidebar, $newEntry); // To prepend the entry
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

  /**
  *  Modified from: http://www.webtipblog.com/add-links-to-twitter-mentions-hashtags-and-urls-with-php-and-the-twitter-1-1-oauth-api/
  *
  * addTweetEntityLinks
  *
  * adds a link around any entities in a twitter feed
  * twitter entities include urls, user mentions, and hashtags
  *
  * @author     Joe Sexton <joe@webtipblog.com>
  * @param      object $tweet a JSON tweet object v1.1 API
  * @return     string tweet
  */
  function addTweetEntityLinks( $tweet )
  {
    // actual tweet as a string
    $tweetText = $tweet['text'];

    // create an array to hold urls
    $tweetEntites = array();

    // add each url to the array
    foreach( $tweet['entities']['urls'] as $url ) {
      $tweetEntites[] = array (
        'type'    => 'url',
        'curText' => substr( $tweetText, $url['indices'][0], ( $url['indices'][1] - $url['indices'][0] ) ),
        'newText' => "<a href='".$url['expanded_url']."' target='_blank'>".$url['display_url']."</a>"
      );
    }  // end foreach

    // add each user mention to the array
    foreach ( $tweet['entities']['user_mentions'] as $mention ) {
      $string = substr( $tweetText, $mention['indices'][0], ( $mention['indices'][1] - $mention['indices'][0] ) );
      $tweetEntites[] = array (
        'type'    => 'mention',
        'curText' => substr( $tweetText, $mention['indices'][0], ( $mention['indices'][1] - $mention['indices'][0] ) ),
        //'newText' => "<a href='http://twitter.com/".$mention['screen_name']."' target='_blank'>".$string."</a>"
        'newText' => "<p data-updatewindowboxservice='twitter_userprofile' data-cbdata-Userprofile='{$mention['screen_name']}'>".$string."</p>"
      );
    }  // end foreach

    // add each hashtag to the array
    foreach ( $tweet['entities']['hashtags'] as $tag ) {
      $string = substr( $tweetText, $tag['indices'][0], ( $tag['indices'][1] - $tag['indices'][0] ) );
      $tweetEntites[] = array (
        'type'    => 'hashtag',
        'curText' => substr( $tweetText, $tag['indices'][0], ( $tag['indices'][1] - $tag['indices'][0] ) ),
        //'newText' => "<a href='http://twitter.com/search?q=%23".$tag['text']."&src=hash' target='_blank'>".$string."</a>"
        'newText' => "<p data-updatewindowboxservice='twitter_hashtag' data-cbdata-Hashtag='{$tag['text']}'>".$string."</p>"
      );
    }  // end foreach

    // replace the old text with the new text for each entity
    foreach ( $tweetEntites as $entity ) {
      $tweetText = str_replace( $entity['curText'], $entity['newText'], $tweetText );
    } // end foreach

    return $tweetText;

  } // end addTweetEntityLinks()

  private function tweetsToHtmlTable($tweets) {
    $tweetsHtmlFormatted = "<table class='table table-responsive' style='color:white;'>";
    //$tweetsHtmlFormatted .= '<tr><td></td></tr>';
    foreach ($tweets as $tweet) {
      $tweetText = $this->addTweetEntityLinks($tweet);
      $tweetsHtmlFormatted .= "<tr><td><p data-updatewindowboxservice='twitter_userprofile' data-cbdata-Userprofile='{$tweet['user']['screen_name']}'>{$tweet['user']['screen_name']}</p>{$tweetText}<br>{$tweet['created_at']}</td></tr>";
    }
    $tweetsHtmlFormatted .= "</table>";
    return $tweetsHtmlFormatted;
  }

  public function TwitterWindowCallback() {
    if (!$this->loggedIn) return $this->TwitterOauthWindowCallback();
    return [
      'html' => "<p data-updatewindowboxservice='twitter_hometimeline'>Home timeline</p><p data-updatewindowboxservice='twitter_usertimeline'>User timeline</p><p data-updatewindowboxservice='twitter_userprofile'>User profile</p>",
      'title' => '
      <svg class="icon twitter windowicon">
         <use xlink:href="#twitter">
         </use>
      </svg> <span class="glyphicon glyphicon-menu-hamburger navbar-element"></span>
      Twitter',
    ];
  }

  public function TwitterHomeTimelineWindowCallback() {
    if (!$this->loggedIn) return $this->TwitterOauthWindowCallback();

    $url = 'https://api.twitter.com/1.1/statuses/home_timeline.json';
    $getfield = '?count=5';
    $tweets = json_decode($this->twitterApi->setGetfield($getfield)->buildOauth($url, 'GET')->performRequest(), true);
    $tweetsHtmlFormatted = $this->tweetsToHtmlTable($tweets);
    return [
      'html' => "<p data-updatewindowboxservice='twitter'>Back!....</p>\n\n$tweetsHtmlFormatted",
      'title' => '
      <svg class="icon twitter windowicon">
         <use xlink:href="#twitter">
         </use>
      </svg> <span class="glyphicon glyphicon-time navbar-element"></span>
      Twitter Home Timeline',
    ];
  }

  public function TwitterUserTimelineWindowCallback($fields = []) {
    if (!$this->loggedIn) return $this->TwitterOauthWindowCallback();
    if (empty($fields['Usertimeline'])) return $this->TwitterWindowCallback();

    $userTimeline = $fields['Usertimeline'];
    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    $getfield = '?screen_name='.$userTimeline.'&count=5';
    $tweets = json_decode($this->twitterApi->setGetfield($getfield)
      ->buildOauth($url, 'GET')
      ->performRequest(), true);
    $tweetsHtmlFormatted = $this->tweetsToHtmlTable($tweets);
    return [
      'html' => "<p data-updatewindowboxservice='twitter'>Back!....</p>\n\n$tweetsHtmlFormatted",
      'title' => '
      <svg class="icon twitter windowicon">
         <use xlink:href="#twitter">
         </use>
      </svg> <span class="glyphicon glyphicon-time navbar-element"></span>
      Twitter User Timeline',
    ];
  }

  public function TwitterUserProfileWindowCallback($fields = []) {
    if (!$this->loggedIn) return $this->TwitterOauthWindowCallback();

    $profile = $this->userData['screen_name'];
    if (!empty($fields['Userprofile'])) $profile = $fields['Userprofile'];

    $html = "<p>Profile</p>";
    $html .= "<p>@{$profile}</p><p data-updatewindowboxservice='twitter_usertimeline' data-cbdata-Usertimeline='{$profile}'>timeline</p>";
    $html .= "<p data-updatewindowboxservice='twitter'>Back!....</p>";
    return [
      'html' => $html,
      'title' => '
      <svg class="icon twitter windowicon">
         <use xlink:href="#twitter">
         </use>
      </svg> <span class="glyphicon glyphicon-user navbar-element"></span>
      Twitter User Profile',
    ];
  }

  public function TwitterHashtagWindowCallback($fields = []) {
    if (!$this->loggedIn) return $this->TwitterOauthWindowCallback();

    if (empty($fields['Hashtag'])) return $this->TwitterWindowCallback();
    $html = "<p>Hashtag</p>";
    if (!empty($fields['Hashtag'])) $html .= "#{$fields['Hashtag']}";
    $html .= "<p data-updatewindowboxservice='twitter'>Back!....</p>";
    return [
      'html' => $html,
      'title' => '
      <svg class="icon twitter windowicon">
         <use xlink:href="#twitter">
         </use>
      </svg> <span class="glyphicon glyphicon-filter navbar-element"></span>
      Twitter Hashtag',
    ];
  }

  public function TwitterOauthWindowCallback($fields = []) {
    if ($this->loggedIn) return $this->TwitterWindowCallback();
    $html = '
    <script>
    var popupwindow;
    function startLogin() {
      console.log("Start login");
      popupwindow = window.open("modules/TwitterModule/TwitterOauth.php","TwitterOauth","width=550,height=650,0,status=0,");
    }

    function endLogin() {
      console.log("Endlogin");
      popupwindow.close();
    }
    </script>
    <a onclick="">
      <input type="submit" value="LOGIN EN TWITTER" onclick="startLogin()" />
    </a>';
    return [
      'html' => $html,
      'title' => '
      <svg class="icon twitter windowicon">
         <use xlink:href="#twitter">
         </use>
      </svg> <span class="glyphicon glyphicon-log-in navbar-element"></span>
      Twitter Login',
    ];
  }

  public function TwitterMentionNotificationCallback($cbData) {
    return '<p>Twitter Mentions</p><br><pre>'.print_r($cbData, true).'</pre>';
  }

}
