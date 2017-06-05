<?php

class MoodleModule extends HC_Module {
  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('moodle', 'MoodleWindowCallback');
  }

  public static function setup($hc) {
    $sql = "CREATE TABLE IF NOT EXISTS hc_m_MoodleModule_data(
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
      'icon' => 'ucm',
      'text' => 'CV',
      'id' => 'moodle',
    ];
    array_unshift($sidebar, $newEntry); // To prepend the entry
  }

  public function onCreatingNotifications(&$notifications) {
    $newEntry = [
      'notificationCounter' => 4,
      'text' => 'Tienes {COUNTER} mensajes nuevos',
      'cb' => 'MoodleNotificationCallback',
      'cbData' => [],
    ];
    array_unshift($notifications, $newEntry); // To prepend the entry
  }

  // Para mostrar la plataforma Moodle desde Hippocampus, la opción allowembbedframe debe estar activada.
  public function MoodleWindowCallback() {
    return [
      'html' => '<iframe src="http://localhost/moodle" width="100%" height="100%"></iframe>',
      'title' => '<svg class="icon ucm windowicon">
        <use xlink:href="#ucm">
        </use>
      </svg>
      Campus Virtual',
    ];
  }

}
