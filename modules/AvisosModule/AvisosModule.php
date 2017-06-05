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

    public function onCreatingNotifications(&$notifications)  {
        $newEntry = [
            'notificationCounter' => 2,
            'text' => 'Tienes {COUNTER} mensajes nuevos',
            'cb' => 'AvisosNotificationCallback',
            'cbData' => [],
            'icon' => 'notifications',
        ];
        array_unshift($notifications, $newEntry); // To prepend the entry
    }

    public function AvisosWindowCallback() {

        $notifications = $this->hc->getNotifications();
        $notificationsNumber = 0;

        foreach ($notifications as $notification) {
          if (!empty($notification['notificationCounter'])) {
            $notificationsNumber += $notification['notificationCounter'];
          }
        }

        $avisosHtmlFormatted = "Tienes ".$notificationsNumber." notificaciones nuevas.<br><br>";

        foreach ($notifications as $notification) {
          if (!empty($notification['notificationCounter'])) {
            $notification['text'] = str_replace('{COUNTER}', (string)$notification['notificationCounter'], $notification['text']);
          }

          $icon = '';
          if (!empty($notification['icon'])) {
              $icon = $notification['icon'];
          }

          $avisosHtmlFormatted .= "<p class='notification-text-inside'><svg class='notification-icon".$icon." mediumicon'><use xlink:href='#$icon'></use></svg><br>{$notification['text']}</p><br>";
        }

        return [
            'html' => $avisosHtmlFormatted,
            'title' => '<svg class="icon notifications windowicon">
                <use xlink:href="#notifications">
                </use>
                </svg>Avisos',
        ];
    }

    public function AvisosNotificationCallback($cbData) {
        return '<p> </p>';
    }

    public function onCreatingMetacode(&$metacode) {
        $metacode[] = '<link rel="stylesheet" href="modules/AvisosModule/style.css">';
    }
}
