<?php

class IdentityModule extends HC_Module {
  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('ID', 'MiIdentidadInicio');
    $this->registerWindowCallback('datospersonales', 'MiIdentidadMisDatosPersonales');
  }

  public function onCreatingSidebar(&$sidebar) {
    $newEntry = [
      'icon' => 'identity',
      'text' => 'Mi identidad',
      'id' => 'ID',
    ];
    array_unshift($sidebar, $newEntry); // To prepend the entry
  }

  public function MiIdentidadInicio() {
   $currentuser = $this->hc->getUserManager()->getLoggedInUser();
    return [
      'html' => '<div class="row content">
                    <div class="col-sm-3 sidenav">
                      <h4>'.$currentuser->getUsername().'</h4>
                      <ul class="nav nav-pills nav-stacked">
                        <li><a data-updatewindowboxservice="ID" class="pointercursor">Inicio</a></li>
                        <li><a data-updatewindowboxservice="datospersonales" class="pointercursor">Mis datos personales</a></li>
                      </ul><br>

                    </div>

                    <div class="col-sm-9" >

                      <h4 id="librelab">Bienvenido/a al Portal de Servicios de Gestión De Usuario de Hippocampus</small></h4>
                      <hr>
                      <h2 class="whitefont">Importante!</h2>
                      <p>En esta seccion podrás ver datos de tu perfil. En la parte izquierda de esta página se muestran los accesos a los servicios que corresponden a tu perfil si echas en falta todos o alguno de los servicios que esperabas tener disponibles contacta con nosotros. </p>
                      <hr>
                      <br><br>


                    </div>
                  </div>
                ',
      'title' => '<svg class="icon identity windowicon">
                    <use xlink:href="#identity">
                    </use>
                  </svg>
                  Gestor de identidad',
    ];
  }

    public function MiIdentidadMisDatosPersonales() {
      $currentuser = $this->hc->getUserManager()->getLoggedInUser();
    return [
      'html' => '<div class="row content">
                    <div class="col-sm-3 sidenav">
                      <h4>'.$currentuser->getUsername().'</h4>
                      <ul class="nav nav-pills nav-stacked">
                        <li><a data-updatewindowboxservice="ID" class="pointercursor">Inicio</a></li>
                        <li><a data-updatewindowboxservice="datospersonales" class="pointercursor">Mis datos personales</a></li>
                      </ul><br>

                    </div>

                    <div class="col-sm-9" >
                      <h4 id="librelab">Mis datos personales</h4>
                      <hr>

                      <fieldset>
                        <legend>Datos básicos</legend>
                        <label class="blackfontlabel " for="nombrecompleto">Nombre:</label>
                        <div >
                          <input class="identityinputs" id="nombrecompleto" value="Nombre" type="text" disabled="true">
                        </div>
                        <br>
                        <label class="blackfontlabel " for="apellido1">Primer apellido:</label>
                        <div>
                         <input class="identityinputs" id="apellido1" value="Primer apellido" type="text" disabled="true">
                        </div>
                        <br>
                        <label class="blackfontlabel " for="segundoapellido">Segundo apellido:</label>
                        <div >
                          <input class="identityinputs" id="segundoapellido" value="Segundo apellido" type="text" disabled="true">
                        </div>
                        <br>
                        <label class="blackfontlabel " for="username">Nombre de usuario:</label>
                        <div>
                         <input class="identityinputs" id="sexo" value="'.$currentuser->getUsername().'" type="text" disabled="true">
                        </div>
                        <br>
                        <label class="blackfontlabel" for="dni">DNI:</label>
                        <div>
                         <input class="identityinputs" id="dni" value="11111111X" type="text" disabled="true">
                        </div>
                        <br>
                        <label class="blackfontlabel " for="sexo">Sexo:</label>
                        <div>
                         <input class="identityinputs" id="sexo" value="Hombre/Mujer" type="text" disabled="true">
                        </div>
                        <br>
                      </fieldset>
                      <br><br>
                      <fieldset>
                        <legend>Datos de contacto</legend>
                        <label class="blackfontlabel " for="correo">Email:</label>
                        <div >
                          <input class="identityinputs" id="correo" value="'.$currentuser->getEmail().'" type="text" disabled="true">
                        </div>
                        <br>
                      </fieldset>
                    </div>
                  </div>
                ',
      'title' => '<svg class="icon identity windowicon">
                    <use xlink:href="#identity">
                    </use>
                  </svg>
                  Gestor de identidad',
    ];
  }

}
