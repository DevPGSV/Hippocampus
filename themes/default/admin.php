<!-- ADMINNNNNNN BITCH
-Administrar usuarios
  -crear usuarios
  -borrar usuarios
  -editar usuarios (cambiar roles)

-Apariencia de la página
  -tema
    -logo
    -fondo
  -nombre
-->
<html lang="es">
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

		<link rel="stylesheet" type="text/css" href="themes/default/css/style.css">
		<script src="themes/default/js/scripts.js"></script>

		<title>Panel de Administración</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">
	</head>

	<body>
     <div id="maincontainer" class="container">
      <ul class="nav nav-pills nav-justified" id="ul-admin">
         <li class="active"><a data-toggle="pill" href="#admin-users"><h5>Administrar usuarios</h5></a></li>
         <li><a data-toggle="pill" href="#admin-page"><h5>Administrar página</h5></a></li>
       </ul>
         <div class="tab-content" id="tab-usersadmin">
           <div id="admin-users" class="tab-pane fade in active">
             <div class="container panel-group" id="accordion">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h2 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#crear">CREAR UN USUARIO</a>
                    </h2>
                  </div>
                  <div id="crear" class="panel-collapse collapse in">
                    <div class="panel-body">

                        <input type="text" placeholder="Usuario" name="usuario" id="usuario">

                				<input type="email" placeholder="Email" name="email" id="email">

                        <input type="text" placeholder="Rol" name="rol" id="rol">

                    </div>
                  </div>
                </div>
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h2 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#borrar">BORRAR UN USUARIO</a>
                    </h2>
                  </div>
                  <div id="borrar" class="panel-collapse collapse">
                    <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
                    minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                    commodo consequat.</div>
                  </div>
                 </div>
                </div>
           </div>
           <div id="admin-page" class="tab-pane fade">
               <p>Usuarios creados (o promovidos) por la cuenta de root.</p>
               <p>Tienen permiso para realizar todas las acciones de administración.</p>
           </div>
         </div>
  </body>
</html>
