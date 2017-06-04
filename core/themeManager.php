<?php

class ThemeManager
{
    private $themePath = __DIR__ . '/../themes/';
    private $hc;
    private $themeList = [
        'default',
    ];
    private $themes=[];

    public function __construct($hc)
    {
        $this->hc = $hc;
    }

    public function loadTheme($id)
    {
        if (is_dir($this->themePath.$id)) {
            if (is_file($this->themePath.$id.'/config.php')) {
                $c = require($this->themePath.$id.'/config.php');
                $metacode = [];
                if (!empty($c['metacode'])) $metacode = $c['metacode'];
                $t = new Theme($this->hc, $id, $c['features'], $metacode);
                return $t;
            }
        }
        return false;
    }

    public function loadAllThemes()
    {
        foreach (scandir($this->themePath, true) as $t) {
            if (is_dir($this->themePath.$t)) {
                if ($t != '.' && $t != '..') {
                    $aux = $this->loadTheme($t);
                    if ($aux) {
                        $this->themes[$t] = $aux;
                    }
                }
            }
        }
    }

    public function getFeaturePath($feature)
    {
        foreach ($this->themeList as $t) {
            if ($this->themes[$t]->hasFeature($feature)) {
                return $this->themes[$t]->getFeaturePath($feature);
            }
        }
    }

    public function getMetacode() {
      $metacode = [];
      foreach ($this->themeList as $t) {
        $metacode = array_merge($metacode, $this->themes[$t]->getMetacode());
      }
      return $metacode;
    }
}
