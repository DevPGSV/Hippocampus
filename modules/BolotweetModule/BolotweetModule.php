<?php

class BolotweetModule extends HC_Module {
  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('bolotweet', 'BolotweetWindowCallback');
    $this->registerWindowCallback('example2', 'ExampleWindowCallback2');
    $this->registerWindowCallback('example3', 'ExampleWindowCallback3');
  }

  public static function setup($hc) {
    $sql = "CREATE TABLE IF NOT EXISTS hc_m_BolotweetModule_data(
      id INT NOT NULL AUTO_INCREMENT,
      user INT NOT NULL,
      token VARCHAR(64) NOT NULL,
      data VARCHAR(32) NOT NULL,
      PRIMARY KEY (`id`)
    )";
    $db = $stmt = $hc->getDB()->getDBo();
    $stmt = $db->prepare($sql);
    return $stmt->execute();
  }

  public function onCreatingSidebar(&$sidebar) {
    $newEntry = [
      'icon' => 'bolotweet',
      'text' => 'Bolotweet',
      'id' => 'bolotweet',
    ];
    array_unshift($sidebar, $newEntry); // To prepend the entry
  }

  public function onCreatingNotifications(&$notifications) {
    $newEntry = [
      'notificationCounter' => 2,
      'text' => 'Tienes {COUNTER} mensajes nuevos',
      'cb' => 'ExampleNotificationCallback',
      'cbData' => [],
    ];
    array_unshift($notifications, $newEntry); // To prepend the entry
  }

  public function BolotweetWindowCallback() {
    return [
      'title' => '<svg class="icon bolotweet windowicon">
        <use xlink:href="#bolotweet">
        </use>
      </svg>Bolotweet',
      'html' => ' <div id="content">
        <div id="content_inner">
         <form method="post" id="form_login" class="form_settings" action="">
          <fieldset>
           <legend>Login to Bolotweet</legend>
             <label for="nickname">Username or email address</label>
             <input id="nickname" name="nickname" type="text">

             <label for="password">Password</label>
             <input name="password" class="password" id="password" type="password">

             <input name="rememberme" class="checkbox" id="rememberme" value="true" type="checkbox">
              <label class="checkbox" for="rememberme">Remember me</label>
           <button id="submit" name="submit" title="Login" class="loginbutton" type="button" data-updatewindowboxservice="example2">Login</button>
          </fieldset><br>
          <p data-updatewindowboxservice="example3">Lost or forgotten password?</p>
         </form>
        </div>
       </div>',
    ];
  }

  public function ExampleWindowCallback2() {
    return [
      'html' => '<p>Second callback!</p><p data-updatewindowboxservice="bolotweet">Back....</p>',
      'title' => 'Example Second Title',
    ];
  }

  public function ExampleWindowCallback3() {
    return [
      'html' => '<p>Third callback!</p><p data-updatewindowboxservice="bolotweet">Back....</p>',
      'title' => 'Example Third Title',
    ];
  }

  public function ExampleNotificationCallback($cbData) {
    return '<p>Module dummy data for notification: <em>Example</em></p><br><pre>'.print_r($cbData, true).'</pre>';
  }

}
