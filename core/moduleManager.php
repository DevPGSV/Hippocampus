<?php


class ModuleManager
{
  private $modulesPath = __DIR__ . '/../modules/';
  private $hc;
  private $modules = [];
  private $windowRegistrations = [];
  private $apiRegistrations = [];

  public function __construct($hc)
  {
      $this->hc = $hc;
      $this->loadAllModules();
  }

  public function loadModule($id)
  {
      if (is_dir($this->modulesPath.$id)) {
          if (is_file($this->modulesPath.$id.'/'.$id.'.php')) {
              require($this->modulesPath.$id.'/'.$id.'.php');
              if ($this->setupModule($id)) {
                $t = new $id($this->hc);
                return $t;
              }
          }
      }
      return false;
  }

  public function setupModule($mid) {
    if ($this->hc->getDB()->getConfigValue('module.'.$mid.'.setup') !== 'true') {
      if ($mid::setup($this->hc)) {
        return $this->hc->getDB()->setConfigValue('module.'.$mid.'.setup', 'true');
      }
      return false;
    }
    return true;
  }

  public function loadAllModules()
  {
      foreach (scandir($this->modulesPath, true) as $t) {
          if (is_dir($this->modulesPath.$t)) {
              if ($t != '.' && $t != '..') {
                  $aux = $this->loadModule($t);
                  if ($aux) {
                      $this->modules[$t] = [
                        'id' => $t,
                        'class' => $aux,
                      ];
                      foreach($aux->getWindowRegistrations() as $wr) {
                        $this->windowRegistrations[$wr['identifier']] = [
                          'module' => $t,
                          'cb' => $wr['cb'],
                        ];
                      }
                      foreach($aux->getApiRegistrations() as $ar) {
                        $this->apiRegistrations[$ar['identifier']] = [
                          'module' => $t,
                          'cb' => $ar['cb'],
                          'cbdata' => $ar['cbdata'],
                        ];
                      }
                  }
              }
          }
      }
  }

  public function apiIdentifierRegistered($identifier) {
    return !empty($this->apiRegistrations[$identifier]);
  }

  public function apiIdentifierProcess($identifier, $data) {
    $apiRegistration = $this->apiRegistrations[$identifier];
    $modulename = $apiRegistration['module'];
    $module = $this->modules[$modulename];
    $cb = $apiRegistration['cb'];
    $cbdata = $apiRegistration['cbdata'];
    return $module['class']->$cb($identifier, $data, $cbdata);
  }

  public function onCreatingSidebar(&$sidebar) {
    foreach ($this->modules as $m) {
      $m['class']->onCreatingSidebar($sidebar);
    }
    return $sidebar;
  }

  public function onWindowContent($windowIdentifier, $cbdata = []) {
    if (empty($this->windowRegistrations[$windowIdentifier])) {
      return [
        'html' => '<p>No service with name: <em>'.htmlentities($windowIdentifier).'</em></p>',
        'title' => 'No Service!',
      ];
    } else {
      $cbD = $this->windowRegistrations[$windowIdentifier];
      return $this->modules[$cbD['module']]['class']->{$cbD['cb']}($cbdata);
    }

  }

}

abstract class HC_Module {
  protected $hc;
  private $windowRegistrations = [];
  private $apiRegistrations = [];
  public function __construct(Hippocampus $hc) {
    $this->hc = $hc;
  }
  public static function setup($hc) { return true; }
  protected function registerWindowCallback($identifier, $callback) {
    $this->windowRegistrations[] = [
      'identifier' => $identifier,
      'cb' => $callback,
    ];
  }
  public function getWindowRegistrations() {
    return $this->windowRegistrations;
  }
  protected function registerApiCallback($identifier, $callback, $cbdata = '') {
    $this->apiRegistrations[] = [
      'identifier' => $identifier,
      'cb' => $callback,
      'cbdata' => $cbdata,
    ];
  }
  public function getApiRegistrations() {
    return $this->apiRegistrations;
  }
  public function onCreatingSidebar(&$sidebar) {}
  public function onCreatingNotifications(&$notifications) {}
}
