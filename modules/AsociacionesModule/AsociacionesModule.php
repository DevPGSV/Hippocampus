<?php

class AsociacionesModule extends HC_Module {
  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('asoci', 'ExampleWindowCallback');
    /*$this->registerWindowCallback('example2', 'ExampleWindowCallback2');*/
  }

  public static function setup($hc) {
    $sql = "CREATE TABLE IF NOT EXISTS hc_m_AsociacionesModule_data(
      id INT NOT NULL AUTO_INCREMENT,
      user INT NOT NULL,
      token VARCHAR(64) NOT NULL,
      data VARCHAR(32) NOT NULL,
      PRIMARY KEY (`id`)
    )";
    $db = $stmt = $hc->getDB()->getDBo();
    $stmt = $db->prepare($sql);
    return $stmt->execute();
  }

  public function onCreatingSidebar(&$sidebar) {
    $newEntry = [
      'icon' => 'asociations',
      'text' => 'Asociaciones',
      'id' => 'asoci',
    ];
    array_unshift($sidebar, $newEntry); // To prepend the entry
  }

  public function onCreatingNotifications(&$notifications) {
    $newEntry = [
      'notificationCounter' => 2,
      'text' => 'Tienes {COUNTER} mensajes nuevos',
      'cb' => 'ExampleNotificationCallback',
      'cbData' => [],
    ];
    array_unshift($notifications, $newEntry); // To prepend the entry
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

  /*public function ExampleWindowCallback2() {
    return [
      'html' => '<p>Second callback!</p><p data-updatewindowboxservice="example">Back....</p>',
      'title' => 'Example Second Title',
    ];
  }*/

  public function ExampleNotificationCallback($cbData) {
    return '<p>Module dummy data for notification: <em>Example</em></p><br><pre>'.print_r($cbData, true).'</pre>';
  }

}
