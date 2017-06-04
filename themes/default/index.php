<html lang="es">
<head>
  <title>Formulario de login</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">

  <?php echo $hc->getMetacode(); ?>

</head>

	<body>
            <a><img id="hippo-izq" src="img/961SrDv.gif"></a>

            <form action="" method="POST" id="form-login">

              <div class="container-fluid">
                <a href="home"><img id="logo-ini" src="img/yJuHskd.png" title="Hippocampus"></a>
              </div>

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

            <a><img id="hippo-der" src="img/pQLItby.gif"></a>

            <a class="hideDisplay">

              <span class="showDisplayOnHover">
                Heading
                <span class="showBodyOfDisplayOnHover">
                  <p>
                    Bienvenido a Hippocampus
                  </p>
                </span>
              </span>
            </a>
            <a class="button" id=button href="who"> ¿QUIÉNES SOMOS? <span class="glyphicon glyphicon-home"><i class="glyphicon glyphicon-leaf"></i></span> </button>
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
          </div>
        </div>
      </div>
  </body>
</html>
