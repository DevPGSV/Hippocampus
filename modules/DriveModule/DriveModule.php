<?php

class DriveModule extends HC_Module {
  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('drive', 'DriveWindowCallback');
  }

  public static function setup($hc) {
    $sql = "CREATE TABLE IF NOT EXISTS hc_m_DriveModule_data(
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
      'icon' => 'drive',
      'text' => 'Drive',
      'id' => 'drive',
    ];
    array_unshift($sidebar, $newEntry); // To prepend the entry
  }

  // Para mostrar la plataforma Moodle desde Hippocampus, la opción allowembbedframe debe estar activada.
  public function DriveWindowCallback() {
    return [
      'html' => '<iframe src="https://drive.google.com/embeddedfolderview?id=0B6xNlYS7GPDXbkFCblVDVXphMlk#list" width="100%" height="100%" class="iframe-embedded"></iframe>',
      'title' => '<svg class="icon drive windowicon">
        <use xlink:href="#drive">
        </use>
      </svg>
      Google Drive',
    ];
  }

  public function DriveNotificationCallback($cbData) {
    return '<p>Module dummy data for notification: <em>Drive Callback</em></p><br><pre>'.print_r($cbData, true).'</pre>';
  }

  public function onCreatingMetacode(&$metacode) {

    $metacode[] = '<link rel="stylesheet" href="modules/DriveModule/style.css">';
  }

}
