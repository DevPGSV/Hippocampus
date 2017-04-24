<html lang="es">

<head>
  <title>Formulario de login</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">

  <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <link rel='stylesheet' href='//fonts.googleapis.com/css?family=Actor'>
  <!--<script src='//www.google.com/recaptcha/api.js'></script>-->

  <script src="lib/jssha256/jssha256.js"></script>
  <script src="scripts.js"></script>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

	<body>

    <div class="outer">
      <div class="middle">
        <div class="inner">
    <div class=".col-md-4">
      <a><img id="hippo-izq" src="http://i.imgur.com/961SrDv.gif"></a>
    </div>
  </div>
</div>
</div>

    <div id="divi" class=".col-md-4">
      <div class="outer">
        <div class="middle">
          <div class="inner">
            <form action="" method="POST" id="form-login">
              <h2>Bienvenido a Hippocampus</h2>
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input type="text" placeholder="Usuario" name="usuario" id="usuario">
              </div>
              <br>

              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="password" placeholder="Contraseña" name="password" id="password">
              </div>
              <br>

              <input type="checkbox" name="forgotpasswd" id="squaredThree">    Olvidé mi contraseña
              <br>
              <br>

              <input type="submit" value="Ingresar">
              <br>
              <p align="center"> ¿Aún no tienes tu cuenta?<br><a id="register" href="register">Regístrate</a> </p>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="outer">
      <div class="middle">
        <div class="inner">
    <div class=".col-md-4">
      <a><img id="hippo-der" src="http://i.imgur.com/pQLItby.gif"></a>
    </div>
  </div>
</div>
</div>

      <!-- Modal -->
      <div class="modal fade" id="dummyModal" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title" id="dummyModal-title">?</h4>
            </div>
            <div class="modal-body" id="dummyModal-body">
              <p>???</p>
            </div>
            <!--
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            -->
          </div>
        </div>
      </div>
  </body>
</html>
