<?php

class AsociacionesModule extends HC_Module {
  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('asoci', 'ExampleWindowCallback');
  }

  public function onCreatingSidebar(&$sidebar) {
    $newEntry = [
      'icon' => 'asociations',
      'text' => 'Asociaciones',
      'id' => 'asoci',
    ];
    array_unshift($sidebar, $newEntry); // To prepend the entry
  }

  public function ExampleWindowCallback() {
    return [
      'html' => '
      <p></p>
      <p id="librelab">
      LibreLabUCM (LLU)
      </p>
      <p>Asociación de estudiantes gestada en la Facultad de Informática de la Universidad Complutense de Madrid.
      Nuestra asociación nace con la vocación de usar y promover el software libre, y, al mismo tiempo, aprender con él tanto dentro como fuera de la universidad.
      Pero no sólo nos queremos quedar en el software libre, también estamos interesados en el hardware libre, las tecnologías libres, la cultura libre, el conocimiento abierto o libre y todo aquello relacionado con éstos.
      </p>

      <a href="mailto:librelab@ucm.es">
        <input type="submit" value="MANDAR MAIL A LIBRELAB" />
      </a>

      <p></p>
      <p id="librelab">ASCII</p>
      <p>
      Asociación sociocultural de ingenierías en informática de la UCM. Realizamos multitud de actividades, ven y conocenos.
      </p>

      <a href="mailto:asciifdi@gmail.com">
        <input type="submit" value="MANDAR MAIL A ASCII" />
      </a>

      <p></p>
      <p id="librelab">LAG</p>
      <p>
      Asociación dedicada a los videojuegos.
      </p>
      <a href="mailto:LagAsociacion@gmail.com">
        <input type="submit" value="MANDAR MAIL A LAG" />
      </a>

      ',

      'title' => '<svg class="icon asociations windowicon">
        <use xlink:href="#asociations">
        </use>
      </svg>
       Información de Asociaciones',
    ];
  }

}
