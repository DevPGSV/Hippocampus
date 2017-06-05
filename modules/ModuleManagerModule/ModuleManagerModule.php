<?php

class ModuleManagerModule extends HC_Module {
  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('modulemanager', 'ModuleManagerWindowCallback');

    $this->registerApiCallback('modulemanagermodule_enablemodule', 'ModuleManagerEnableApiCallback');
  }

  public function onCreatingSidebar(&$sidebar) {
    $cu = $this->hc->getUserManager()->getLoggedInUser();
    $newEntry = [
      'icon' => 'settings',
      'text' => 'Modules',
      'id' => 'modulemanager',
    ];
    if ($cu !== false && $cu->isAdmin()) {
      array_push($sidebar, $newEntry); // To apend the entry
    }
  }

  public function ModuleManagerWindowCallback() {
    $db = $this->hc->getDB()->getDBo();

    $sql = "SELECT DISTINCT config.value 'module', config_setup.value 'setup', config_enable.value 'enable' FROM config
      JOIN config as config_setup
        ON config_setup.varkey = concat('module.', config.value, '.setup')
      JOIN config as config_enable
        ON config_enable.varkey = concat('module.', config.value, '.enable')
      WHERE
        config.varkey LIKE 'module.%.module'
      ";

    $stmt = $db->prepare($sql);
    $stmt->execute();
    $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = "<table class='table table-responsive modulemanager_modulestable'>";
    $html .= "<tr><th>Module</th><th>Enabled</th></tr>";
    foreach($modules as $module) {
      if ($module['module'] === 'ModuleManagerModule') continue;
      $enabled = ($module['enable'] == 'true');
      $html .= "<tr><td>{$module['module']}</td><td><label class='switch'><input type='checkbox' onchange='modulemanagermodule_setModuleEnable(\"{$module['module']}\", this.checked, this)' ".($enabled ? 'checked':'')."><div class='slider round'></div></label></td></tr>";
    }
    $html .= '</table>';


    return [
      'html' => $html,
      'title' => '
      <svg class="icon settings windowicon">
         <use xlink:href="#settings">
         </use>
      </svg>
      Module Manager',
    ];
  }

  public function ModuleManagerEnableApiCallback($identifier, $data, $cbdata) {
    $module = $this->hc->getDB()->getConfigValue("module.{$data['module']}.module");
    if ($module === $data['module']) {
      if ($module === 'ModuleManagerModule') {
        return [
          'status' => 'error',
          'msg' => 'core_module',
        ];
      }
      $doEable = $data['doEnable'] == 'true' ? 'true' : 'false';
      if ($this->hc->getDB()->setConfigValue("module.{$data['module']}.enable", $doEable)) {
        return [
          'status' => 'ok',
          'msg' => 'module_updated_'.$doEable,
        ];
      }
    }
    return [
      'status' => 'error',
      'msg' => 'unkown_module',
    ];
  }

  public function onCreatingMetacode(&$metacode) {
    $metacode[] = '<script>
  function modulemanagermodule_setModuleEnable(module, doEnable, uiObject) {
    $.ajax({
      type: "POST",
      url: "api.php?action=modulemanagermodule_enablemodule",
      dataType: "json",
      data: {
        "module": module,
        "doEnable": doEnable,
      },
      success: function(data) {
        //$("#twittermodule_config_message").html(data["msg"]);
        if (data["status"] == "ok") {
          console.log("module set enabled to " + doEnable + " correctly");
          console.log(uiObject);

        } else {
          setTimeout(function(){
            uiObject.checked = !uiObject.checked;
          }, 100);
        }
      },
    });
  }
  </script>';
    $metacode[] = '<link rel="stylesheet" href="modules/ModuleManagerModule/style.css">';
  }

}
