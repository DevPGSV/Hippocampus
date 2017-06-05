<?php

class ThemeManagerModule extends HC_Module {
  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('thememanager', 'ThemeManagerWindowCallback');
    $this->registerApiCallback('thememanagermodule_enabletheme', 'ThemeManagerEnableApiCallback');
  }

  public function onCreatingSidebar(&$sidebar) {
    $cu = $this->hc->getUserManager()->getLoggedInUser();
    $newEntry = [
      'icon' => 'themes',
      'text' => 'Themes',
      'id' => 'thememanager',
    ];
    if ($cu !== false && $cu->isAdmin()) {
      array_push($sidebar, $newEntry); // To apend the entry
    }
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


/*  Puedes obtenerlo con:

  $this->hc->getDB()->getConfigValue("site.theme");

  y cambiarlo con:

  $this->hc->getDB()->setConfigValue("site.theme", 'nuevo valor');
*/


  public function ThemeManagerWindowCallback() {
    $themePath = __DIR__ . '/../../themes/';
    $db = $this->hc->getDB();
    $actualtheme = $db->getConfigValue("site.theme");

    $html = "<table class='table table-responsive modulemanager_modulestable'>";
    $html .= "<th>Temas</th><th>Activado</th>";

    foreach (scandir($themePath, true) as $t) {
        if (is_dir($themePath.$t)) {
            if ($t != '.' && $t != '..') {
              $html .= "<tr>";
              if($t == $actualtheme){
                $enabled = true;
              }
              else{
                $enabled = false;
              }
              $html .= "<td>{$t}</td><td><label class='switch'><input class='checkedtheme' type='checkbox' onchange='thememanagermodule_setThemeEnable(\"{$t}\", this.checked, this)' ".($enabled ? 'checked':'')." ><div class='slider round'></div></label></td>";

              $html .= "</tr>";

            }
        }
    }
    $html .= '</table>';

    return [
      'html' => $html,
      'title' => '
      <svg class="icon themes windowicon">
         <use xlink:href="#themes">
         </use>
      </svg>
      Themes Manager',
    ];
  }

  public function ThemeManagerEnableApiCallback($identifier, $data, $cbdata) {
    if ($this->hc->getDB()->setConfigValue("site.theme", $data['theme'])) {
      return [
        'status' => 'ok',
        'msg' => 'theme_updated',
      ];
    }
    return [
      'status' => 'error',
      'msg' => 'Tema desconocido',
    ];
  }


  public function onCreatingMetacode(&$metacode) {
    $metacode[] = '<script>
  function thememanagermodule_setThemeEnable(theme, doEnable, uiObject) {
    if(!doEnable){
      setTimeout(function(){
        uiObject.checked = true;
      }, 100);
      return;
    }
    $.ajax({
      type: "POST",
      url: "api.php?action=thememanagermodule_enabletheme",
      dataType: "json",
      data: {
        "theme": theme,
        "doEnable": doEnable,
      },
      success: function(data) {
        if (data["status"] == "ok") {
          $(".checkedtheme").each(function(i, element) {
            if(element != uiObject){
              element.checked = false;
            }
          });
          setTimeout(function(){
            location.reload();
          }, 500);

        } else {
          setTimeout(function(){
            uiObject.checked = false;
          }, 200);
        }
      },
    });
  }
  </script>';
    $metacode[] = '<link rel="stylesheet" href="modules/ModuleManagerModule/style.css">';
  }

}
