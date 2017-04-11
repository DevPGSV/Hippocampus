<?php

class Theme {
  private $name;
  private $id;
  private $features = [
    'index'     => false,
    'register'  => false,
    'userview'  => false,
    'style'     => false,
    'javascript'=> false,
    'window'    => false,
  ];

  private $hc;

  public function __construct($hc, $id, $features) {
    $this->hc = $hc;
    $this->id = $id;
    $this->setFeatures($features);
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

  public function hasFeature($feature){
    if(!in_array($feature, $this->features)){
      return false;
    }
    else {
      return $this->features[$feature];
    }
  }

  public function getFeaturePath($feature){
    switch($feature){
      case 'index':
        return $this->getIndexPath();
      break;
      case 'register':
        return $this->getRegisterPath();
      break;
      case 'userview':
        return $this->getUserviewPath();
      break;
      case 'style':
        return $this->getStylePath();
      break;
      case 'javascript':
        return $this->getJavascriptPath();
      break;
      case 'window':
        return $this->getWindowPath();
      break;
      default:
        return false;
        break;
    }

  }

  public function getIndexPath(){
    return "$this->id/index.php";
  }

  public function getRegisterPath(){
    return "$this->id/signup.php";
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
