<?php

class BibliotecaModule extends HC_Module {
  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('library', 'functionCallBack');
  }

  public function onCreatingSidebar(&$sidebar) {
    $newEntry = [
      'icon' => 'library',
      'text' => 'Biblioteca UCM',
      'id' => 'library',
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

  public function functionCallBack() {
   // $currentuser = $hc->getUserManager()->getLoggedInUser();
    return [
      'html' => '<div class="geadiv"><object data="http://biblioteca.ucm.es/"></object></div>',
      'title' => '<svg class="icon asociations windowicon">
        <use xlink:href="#library">
        </use>
      </svg>
      Biblioteca UCM',
    ];
  }

  public function ExampleNotificationCallback($cbData) {
    return '<p>Module dummy data for notification: <em>Example</em></p><br><pre>'.print_r($cbData, true).'</pre>';
  }

}
