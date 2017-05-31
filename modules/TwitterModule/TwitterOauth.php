<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/../../core/hippocampus.php');

$hc = new Hippocampus();

//require_once(__DIR__ . '/TwitterAPIExchange.php');
//require(__DIR__ . '/TwitterConfig.php');


$twitterApiData = [
  'oauth_access_token' => $hc->getDB()->getConfigValue('module.TwitterModule.oauth_access_token'),
  'oauth_access_token_secret' => $hc->getDB()->getConfigValue('module.TwitterModule.oauth_access_token_secret'),
  'consumer_key' => $hc->getDB()->getConfigValue('module.TwitterModule.consumer_key'),
  'consumer_secret' => $hc->getDB()->getConfigValue('module.TwitterModule.consumer_secret'),
];

$thisUrl = explode("?", (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]")[0];

$loggedIn = false;

if (!isset($_GET['cb'])) {

  $twitterApi = new TwitterAPIExchange($twitterApiData);

  $url = 'https://api.twitter.com/oauth/request_token';
  $postfields = [
    'oauth_callback' => $thisUrl.'?cb',
  ];
  $response = $twitterApi->setPostfields($postfields)->buildOauth($url, 'POST')->performRequest();
  parse_str($response, $responseDecoded);
  $_SESSION['TwitterModule']['oauth']['oauth_token'] = $responseDecoded['oauth_token'];
  $_SESSION['TwitterModule']['oauth']['oauth_token_secret'] = $responseDecoded['oauth_token_secret'];

  $url = 'https://api.twitter.com/oauth/authorize?oauth_token='.$responseDecoded['oauth_token'];
  header('Location: '. $url);

} else {
  // ?oauth_token=b5K8awAAAAAA04OwAAABXFvp3ao&oauth_verifier=zilDbRRHbZkFA70BYcjGM1qFuosxnGw6
  if (
      empty($_GET['oauth_token']) ||
      empty($_GET['oauth_verifier']) ||
      empty($_SESSION['TwitterModule']['oauth']['oauth_token']) ||
      empty($_SESSION['TwitterModule']['oauth']['oauth_token_secret']))
    {
    header('Location: '. $thisUrl);
  } else {
    $twitterApiData['oauth_access_token'] = $_SESSION['TwitterModule']['oauth']['oauth_token'];
    $twitterApiData['oauth_access_token_secret'] = $_SESSION['TwitterModule']['oauth']['oauth_token_secret'];
    $twitterApi = new TwitterAPIExchange($twitterApiData);
    $url = 'https://api.twitter.com/oauth/access_token';
    $postfields = [
      'oauth_verifier' => $_GET['oauth_verifier'],
    ];
    $response = $twitterApi->setPostfields($postfields)->buildOauth($url, 'POST')->performRequest();
    parse_str($response, $responseDecoded);
    $_SESSION['TwitterModule']['oauth']['oauth_token'] = $responseDecoded['oauth_token'];
    $_SESSION['TwitterModule']['oauth']['oauth_token_secret'] = $responseDecoded['oauth_token_secret'];
    $_SESSION['TwitterModule']['oauth']['user_id'] = $responseDecoded['user_id'];
    $_SESSION['TwitterModule']['oauth']['screen_name'] = $responseDecoded['screen_name'];
    $_SESSION['TwitterModule']['oauth']['x_auth_expires'] = $responseDecoded['x_auth_expires'];

    $cu = $hc->getUserManager()->getLoggedInUser();
    if ($cu !== false) {
      $stmt = $hc->getDB()->getDBo()->prepare("INSERT INTO hc_m_TwitterModule_users
        (user, oauth_token, oauth_token_secret, user_id, screen_name)
        VALUES(:user, :oauth_token, :oauth_token_secret, :user_id, :screen_name)
        ON DUPLICATE KEY UPDATE
          user=:user2, oauth_token=:oauth_token2, oauth_token_secret=:oauth_token_secret2, user_id=:user_id2, screen_name=:screen_name2
      ");
      $stmt->bindValue(':user', $cu->getId(), PDO::PARAM_INT);
      $stmt->bindValue(':oauth_token', $responseDecoded['oauth_token'], PDO::PARAM_STR);
      $stmt->bindValue(':oauth_token_secret', $responseDecoded['oauth_token_secret'], PDO::PARAM_STR);
      $stmt->bindValue(':user_id', $responseDecoded['user_id'], PDO::PARAM_STR);
      $stmt->bindValue(':screen_name', $responseDecoded['screen_name'], PDO::PARAM_STR);

      $stmt->bindValue(':user2', $cu->getId(), PDO::PARAM_INT);
      $stmt->bindValue(':oauth_token2', $responseDecoded['oauth_token'], PDO::PARAM_STR);
      $stmt->bindValue(':oauth_token_secret2', $responseDecoded['oauth_token_secret'], PDO::PARAM_STR);
      $stmt->bindValue(':user_id2', $responseDecoded['user_id'], PDO::PARAM_STR);
      $stmt->bindValue(':screen_name2', $responseDecoded['screen_name'], PDO::PARAM_STR);

      if ($stmt->execute()) {
          $loggedIn = true;
      }
    }
  }
}

if ($loggedIn):
?>
  <html>
  <head>
    <script>
      opener.endLogin();
    </script>
  </head>
  <body>
    <p>You can close this window now</p>
  </body>
  </html>
<?php
endif;
