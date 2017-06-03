<?php

class InsertCodeModule extends HC_Module {
  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('software', 'ExampleWindowCallback');
    $this->registerWindowCallback('example2', 'ExampleWindowCallback2');
  }

  public static function setup($hc) {
    $sql = "CREATE TABLE IF NOT EXISTS hc_m_InsertCodeModule_data(
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
      'icon' => 'software',
      'text' => 'Software',
      'id' => 'software',
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

  public function ExampleWindowCallback() {
    return [
      'html' => '<p><div class="lesson-middle js-lesson-editor">
  <div class="waiting-overlay"></div>
  <div class="lesson-file-tabs js-lesson-file-tabs"><div class="lesson-file-tabs__wrapper">
  <div>
  <div class="lesson-file-tabs__tab js-file-tab tab cf active" data-type="script.js" data-state="active">
  <div class="new-icon--small--center new-icon--lesson-file fl"></div>
  <div class="fl">script.js</div>
</div>  </div>
  <div id="composer-view-project-button-for-narrow-screen">
  </div>
</div></p>',
      'title' => 'Insert code',
    ];
  }

  public function ExampleWindowCallback2() {
    return [
      'html' => '<p>Second callback!</p><p data-updatewindowboxservice="example">Back....</p>',
      'title' => 'Example Second Title',
    ];
  }

  public function ExampleNotificationCallback($cbData) {
    return '<p>Module dummy data for notification: <em>Example</em></p><br><pre>'.print_r($cbData, true).'</pre>';
  }

}
