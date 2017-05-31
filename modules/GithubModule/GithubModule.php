<?php

class GithubModule extends HC_Module {
  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('github', 'GithubWindowCallback');
    $this->registerWindowCallback('github2', 'GithubWindowCallback2');
  }

  public static function setup($hc) {
    $sql = "CREATE TABLE hc_m_GithubModule_data(
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
      'icon' => 'github',
      'text' => 'Github',
      'id' => 'github',
    ];
    array_unshift($sidebar, $newEntry); // To prepend the entry
  }

  public function onCreatingNotifications(&$notifications) {
    $newEntry = [
      'notificationCounter' => 46,
      'text' => 'Tienes {COUNTER} mensajes nuevos',
      'cb' => 'GithubNotificationCallback',
      'cbData' => [],
    ];
    array_unshift($notifications, $newEntry); // To prepend the entry
  }

  public function GithubWindowCallback() {
    return [
      'html' => '<p>Module dummy data for service: <em>Example</em></p><p data-updatewindowboxservice="example2">More....</p>',
      'title' => '<svg class="icon github windowicon">
        <use xlink:href="#github">
        </use>
      </svg>
      Github',
    ];
  }

  public function GithubWindowCallback2() {
    return [
      'html' => '<p>Second callback!</p><p data-updatewindowboxservice="example">Back....</p>',
      'title' => 'Github',
    ];
  }

  public function GithubNotificationCallback($cbData) {
    return '<p>Module dummy data for notification: <em>Example</em></p><br><pre>'.print_r($cbData, true).'</pre>';
  }

}
