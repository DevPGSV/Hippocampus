<?php

class IdentityModule extends HC_Module {
  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('ID', 'MiIdentidadInicio');
    $this->registerWindowCallback('datospersonales', 'MiIdentidadMisDatosPersonales');
  }

  public function onCreatingSidebar(&$sidebar) {
    $newEntry = [
      'icon' => 'about',
      'text' => 'Mi identidad',
      'id' => 'ID',
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

  public function MiIdentidadInicio() {
   // $currentuser = $hc->getUserManager()->getLoggedInUser();
    return [
      'html' => '<div class="row content">
                    <div class="col-sm-3 sidenav">
                      <h4>Usuario</h4> 
                      <ul class="nav nav-pills nav-stacked">
                        <li><a data-updatewindowboxservice="ID">Inicio</a></li>
                        <li><a data-updatewindowboxservice="datospersonales">Mis datos perosnales</a></li>
                      </ul><br>
                      
                    </div>

                    <div class="col-sm-9" >
                      
                      <h4><small>Bienvenido/a al Portal de Servicios de Gestión De Usuario de Hippocampus</small></h4>
                      <hr>
                      <h2>Importatnte</h2>
                      <p>En esta seccion podrás ver y modificar datos de tu perfil. En la parte izquierda de esta página se muestran los accesos a los servicios que corresponden a tu perfil si echas en falta todos o alguno de los servicios que esperabas tener disponibles contacta con nosotros. </p>
                      <hr>
                    </div>
                  </div>
                ',
      'title' => 'Gestor de identidad',
    ];
  }

    public function MiIdentidadMisDatosPersonales() {
    return [
      'html' => '<div class="row content">
                    <div class="col-sm-3 sidenav">
                      <h4>Usuario</h4>
                      <ul class="nav nav-pills nav-stacked">
                        <li><a data-updatewindowboxservice="ID">Inicio</a></li>
                        <li><a data-updatewindowboxservice="datospersonales">Mis datos personales</a></li>
                      </ul><br>
                      
                    </div>

                    <div class="col-sm-9" >
                      <h4><small>Mis datos personales</small></h4>
                      <hr>
                      
                    </div>
                  </div>
                ',
      'title' => 'Gestor de identidad',
    ];
  }

  public function ExampleNotificationCallback($cbData) {
    return '<p>Module dummy data for notification: <em>Example</em></p><br><pre>'.print_r($cbData, true).'</pre>';
  }

}