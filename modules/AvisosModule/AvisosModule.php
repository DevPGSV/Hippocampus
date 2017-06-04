<?php

class AvisosModule extends HC_Module {
    public function __construct($hc) {
        parent::__construct($hc);

        $this->registerWindowCallback('notifications', 'AvisosWindowCallback');
    }


    public function onCreatingSidebar(&$sidebar) {
        $newEntry = [
      'icon' => 'notifications',
      'text' => 'Avisos',
      'id' => 'notifications',
    ];
        array_unshift($sidebar, $newEntry); // To prepend the entry
    }

    /*
    public function onCreatingNotifications(&$notifications)  {
        $newEntry = [
          'notificationCounter' => 2,
          'text' => 'Tienes {COUNTER} mensajes nuevos',
          'cb' => 'AvisosNotificationCallback',
          'cbData' => [],
        ];
        array_unshift($notifications, $newEntry); // To prepend the entry
    }
    */

    public function AvisosWindowCallback() {
        return [
      'html' => '
        <svg class="notification-icon gmail"><use xlink:href="#gmail"></use></svg> Tienes 1 mensaje nuevo. </a>
        <br>
        <br>
        <svg class="notification-icon bolotweet"><use xlink:href="#bolotweet"></use></svg> Tienes 2 mensajes nuevo. </a>
        <br>
        <br>
        <svg class="notification-icon ucm"><use xlink:href="#ucm"></use></svg> Tienes 1 mensaje nuevo. </a>
        ',
      'title' => '<svg class="icon notifications windowicon">
        <use xlink:href="#notifications">
        </use>
      </svg>Avisos',
    ];
    }

    public function AvisosNotificationCallback($cbData) {
        return '<p>Module dummy data for notification: <em>Cafetería</em></p><br><pre>'.print_r($cbData, true).'</pre>';
    }
}
