<?php

class BolotweetModule extends HC_Module {
  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('bolotweet', 'BolotweetWindowCallback');

    $this->registerWindowCallback('bolotweet2', 'BolotweetWindowCallback2');
  }

  public static function setup($hc) {
    $sql = "CREATE TABLE IF NOT EXISTS hc_m_BolotweetModule_data(
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
      'icon' => 'bolotweet',
      'text' => 'Bolotweet',
      'id' => 'bolotweet',
    ];
    array_unshift($sidebar, $newEntry); // To prepend the entry
  }

  public function BolotweetWindowCallback() {
    return [
      'title' => '<svg class="icon bolotweet windowicon">
        <use xlink:href="#bolotweet">
        </use>
      </svg>Bolotweet',
      'html' => '<div id="content">
        <div id="content_inner">
         <form method="POST" id="form_login" class="form_settings" action="">
          <fieldset>
             <label for="nickname" class="logintext">Nombre de usuario o email </label>
             <input id="nickname" name="nickname" type="text" class="logintext">

             <label for="password"  class="logintext">Contraseña</label>
             <input name="password" class="password logintext" id="password" type="password">
             <br>
           <button id="submit" name="submit" title="Login" class="loginbutton" type="button" data-updatewindowboxservice="bolotweet2">Login</button>
          </fieldset><br>
         </form>
        </div>
       </div>',
    ];
  }

  public function BolotweetWindowCallback2($fields = []) {
    $html = '<p id="librelab">
     TAREAS
    </p>';
    $html .= '<table class="table table-responsive twittermodule_tweettable">
                <tr><td><p class="whitefont">Día 3 de Junio</p><p class="whitefont">#php #js</p>
                <input type="text" placeholder="Mensaje" class="form-control logintext"><br>
                <input type="submit" value="Enviar"></td></tr>
                <tr><td><p class="whitefont">Día 5 de Junio</p><p class="whitefont">#apache</p>
                <input type="text" placeholder="Mensaje" class="form-control logintext"><br>
                <input type="submit" value="Enviar"></td></tr>
              </table>
              ';
    $html .= '<p data-updatewindowboxservice="bolotweet" class="pointercursor">Log out</p>';
    return [
      'html' => $html,
      'title' => '<svg class="icon bolotweet windowicon">
        <use xlink:href="#bolotweet">
        </use>
      </svg>Bolotweet',
    ];
  }



  public function ExampleNotificationCallback($cbData) {
    return '<p>Module dummy data for notification: <em>Example</em></p><br><pre>'.print_r($cbData, true).'</pre>';
  }

}
