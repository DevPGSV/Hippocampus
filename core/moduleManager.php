<?php


class ModuleManager
{
  private $modulesPath = __DIR__ . '/../modules/';
  private $hc;
  private $modules = [];
  private $windowRegistrations = [];

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
              $t = new $id($this->hc);
              return $t;
          }
      }
      return false;
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
                  }
              }
          }
      }
  }

  public function onCreatingSidebar(&$sidebar) {
    foreach ($this->modules as $m) {
      $m['class']->onCreatingSidebar($sidebar);
    }
    return $sidebar;
  }

  public function onWindowContent($windowIdentifier) {
    if (empty($this->windowRegistrations[$windowIdentifier])) {
      return [
        'html' => '<p>No service with name: <em>'.htmlentities($windowIdentifier).'</em></p>',
        'title' => 'No Service!',
      ];
    } else {
      $cbD = $this->windowRegistrations[$windowIdentifier];
      return $this->modules[$cbD['module']]['class']->{$cbD['cb']}();
    }

  }

}

abstract class HC_Module {
  protected $hc;
  private $windowRegistrations = [];
  public function __construct(Hippocampus $hc) {
    $this->hc = $hc;
  }
  protected function registerWindowCallback($identifier, $callback) {
    $this->windowRegistrations[] = [
      'identifier' => $identifier,
      'cb' => $callback,
    ];
  }
  public function getWindowRegistrations() {
    return $this->windowRegistrations;
  }
  public function onCreatingSidebar(&$sidebar) {}
  public function onCreatingNotifications(&$notifications) {}
}
