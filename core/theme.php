<?php

class Theme {
  private $name;
  private $id;
  private $features = [
    'index'     => false,
    'userview'  => false,
    'style'     => false,
    'javascript'=> false,
    'window'    => false,
  ];

  private $hc;

  public function __construct($hc, $id, $features) {
    $this->hc = $hc;
    $this->id = $id;
  }

  public function setFeatures($features) {
    foreach ($this->features as $f=>$value){
      if(in_array($f, $features)){
        $this->features[$f] = true;
      }
      else {
        $this->features[$f] = false;
      }
    }
  }

  public function getIndexPath(){
    return "$this->id/index.php";
  }

  public function getUserviewPath(){
    return "$this->id/userview.php";
  }

  public function getStylePath(){
    return "$this->id/css/style.css";
  }

  public function getJavascriptPath(){
    return "$this->id/js/scripts.js";
  }

  public function getWindowPath(){
    return "$this->id/window.php";
  }

}
