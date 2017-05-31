<?php

class GEAmodule extends HC_Module {
  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('gea', 'MiGeaUcm');
  }

  public function onCreatingSidebar(&$sidebar) {
    $newEntry = [
      'icon' => 'ucm',
      'text' => 'GEA',
      'id' => 'gea',
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

  public function MiGeaUcm() {
   
    return [
      'html' => '<div class="geadiv"><object data="https://geaportal.ucm.es"></object></div>',
      'title' => '<svg class="icon ucm windowicon">
        <use xlink:href="#ucm">
        </use>
      </svg>
      GEA UCM',
    ];
  }


  public function ExampleNotificationCallback($cbData) {
    return '<p>Module dummy data for notification: <em>Example</em></p><br><pre>'.print_r($cbData, true).'</pre>';
  }

}
