<?php

class MoodleModule extends HC_Module {
  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('moodle', 'MoodleWindowLogin');
    $this->registerWindowCallback('moodle2', 'MoodleWindowCourses');
    // Puedo poner tantos callbacks como necesite
  }

  public static function setup($hc) {
    $sql = "CREATE TABLE hc_m_MoodleModule_data(
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

  public function MoodleWindowLogin() {
    //Llamo a la API para autenticar al usuario.
    return [
      'html' => '<p>Module dummy data for service: <em>Moodle</em></p><p data-updatewindowboxservice="moodle2">More....</p>',
      'title' => '<svg class="icon ucm windowicon">
        <use xlink:href="#ucm">
        </use>
      </svg>
      Campus Virtual',
    ];
  }

  public function MoodleWindowCourses() {
    //Llamo a la API para sacar la información de todos los cursos del usuario y mostrarlos.
    //La opción data-updatewindowboxservice actualiza la id al llamarse esta función.
    return [
      'html' => '<p>Second callback!</p><p data-updatewindowboxservice="moodle">Back....</p>',
      'title' => 'Mis cursos',
    ];
  }

  public function MoodleNotificationCallback($cbData) {
    return '<p>Module dummy data for notification: <em>Moodle Callback</em></p><br><pre>'.print_r($cbData, true).'</pre>';
  }

}
