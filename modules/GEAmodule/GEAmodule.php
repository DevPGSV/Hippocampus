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

}
