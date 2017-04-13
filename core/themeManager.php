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
                $t = new Theme($this->hc, $id, $c['features']);
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
}
