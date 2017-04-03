<html lang="es">

<head>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <!-- <link rel="stylesheet" type="text/css" href="themes/default/css/style.css"> -->
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <!-- <script src="themes/default/js/scripts.js"></script> -->
  <script src="js/validateform.js" type="application/javascript"></script>

    <title>Formulario de registro</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">

    <script src='https://www.google.com/recaptcha/api.js'></script>

</head>
<body>
  <form action="" method="POST">
    <h2>Bienvenido a Hippocampus</h2>

    <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-font"></i></span>
                <input type="text" placeholder="Nombre" name="nombre" id="nombre">
    </div>

    <br width="50%">

    <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                <input type="text" placeholder="Email" name="email" id="email">
    </div>

    <br width="50%">

    <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input type="text" placeholder="Usuario" name="usuario" id="usuario">
    </div>

    <br width="50%">

    <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="password" placeholder="Contraseña" name="password" id="password">
    </div>

    <br width="50%">

    <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="password" placeholder="Confirme su contraseña" name="confirmpassword" id="confirmpassword">
    </div>

    <br width="50%">

    <div class="g-recaptcha" align="center" data-sitekey="6LekVhsUAAAAAGJmHKj_RSg7wWzmlephZt2wPpvs"></div>

    <br width="50%">

    <input type="submit" onclick="return validaRegistro(this.form);" value="Crear cuenta">

  </form>
</body>

<script type="application/javascript">
    document.getElementById('email').addEventListener("change", function () {
        if (!validemail.test(this.value)) {
            document.getElementById('email').style.border = "2px solid red";
            this.focus();
        } else {
            document.getElementById('email').style.border = "2px solid green";;
        }
    }, false);
</script>

</html>
