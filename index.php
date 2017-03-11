<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/core/hippocampus.php');

<form action="/action_page.php">
  <div class="imgcontainer">
    <a href="http://imgur.com/cTCsZeR"><img src="http://i.imgur.com/cTCsZeR.png" title="source: imgur.com" alt="Imagen Perfil" class="img-responsive boceto" /></a>
  </div>

  <div class="container">
    <label><b>Username</b></label>
    <input type="text" placeholder="Introduce nombre de usuario" name="uname" required>

    <label><b>Password</b></label>
    <input type="password" placeholder="Introduce contraseña" name="psw" required>

    <button type="submit">Login</button>
    <input type="checkbox" checked="checked"> Remember me
  </div>

  <div class="container" style="background-color:#f1f1f1">
    <button type="button" class="cancelbtn">Cancel</button>
    <span class="psw">Forgot <a href="#">password?</a></span>
  </div>
</form>

$hc = new Hippocampus();
$hc->run();
