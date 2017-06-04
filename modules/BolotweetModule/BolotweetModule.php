<?php

class BolotweetModule extends HC_Module {
  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('bolotweet', 'BolotweetWindowCallback');
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
      'html' => '<div id="content">
        <div id="content_inner">
         <form method="POST" id="form_login" class="form_settings" action="">
          <fieldset>
             <label for="nickname" class="logintext">Nombre de usuario o email </label>
             <input id="nickname" name="nickname" type="text">

             <label for="password"  class="logintext">Contraseña</label>
             <input name="password" class="password" id="password" type="password">
             <br>
           <button id="submit" name="submit" title="Login" class="loginbutton" type="button" data-updatewindowboxservice="example2">Login</button>
          </fieldset><br>
         </form>
        </div>
       </div>',
    ];
  }

  public function ExampleNotificationCallback($cbData) {
    return '<p>Module dummy data for notification: <em>Example</em></p><br><pre>'.print_r($cbData, true).'</pre>';
  }

}
