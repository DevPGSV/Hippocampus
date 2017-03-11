<?php

class ThemeManager {
  private $themePath = __DIR__ . '/../themes/';
  private $hc;

  public function __construct($hc) {
    $this->hc = $hc;
  }

  public function loadTheme($id){
    if(is_dir($this->themePath.$id)){
      if(is_file($this->themePath.$id.'/config.php')){
        $c = require($this->themePath.$id.'/config.php');
        $t = new Theme($this->hc, $id, $c['features']);
        return $t;
      }
    }
    return false;
  }
}
