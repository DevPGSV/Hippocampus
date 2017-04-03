<html lang="es">

<head>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <link rel="stylesheet" type="text/css" href="themes/default/css/style.css">
  <script src="themes/default/js/scripts.js"></script>

    <title>Formulario de login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">

</head>
<body>
  <form action="">
    <h2>Bienvenido a Hippocampus</h2>
      <div class="input-group">
                       <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                       <input type="text" placeholder="Usuario" name="usuario">
      </div>

      <br width="50%"> <!-- salto de linea -->

      <div class="input-group">
                       <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                       <input type="password" placeholder="Contraseña" name="password">
      </div>

      <br width="50%">
  
        <input type="checkbox" name="forgotpasswd" id="squaredThree">    Olvidé mi contraseña
          <br>

      <br width="50%">

    <input type="submit" value="Ingresar">

    <br />
    <P ALIGN="center"> ¿Aún no tienes tu cuenta? <a id="register" href="themes/default/signup.php">Regístrate</a> </p>

  </form>

</body>
</html>
