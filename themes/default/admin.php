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
                    <div class="panel-body form-group">

                        <input type="text" class="form-control" placeholder="Usuario" name="usuario" id="usuario" data-toggle="tooltip" data-placement="top" title="Entre 4 y 20 caracteres">
                        <br>
                				<input type="email" class="form-control" placeholder="Email" name="email" id="email">
                        <br>
                        <input type="checkbox" name="rol" value="false" id="checkbox-admin"><label id="label-admin">   Hacer administrador</label><br>
                        <input type="submit" value="Crear" id="button-crear">
                    </div>
                  </div>
                </div>
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h2 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#editar">EDITAR UN USUARIO</a>
                    </h2>
                  </div>
                  <div id="editar" class="panel-collapse collapse">
                    <div class="panel-body">
                      <table class="table table-hover table-condensed table-responsive">
                          <thead>
                            <tr>
                              <th>Username</th>
                              <th>Rol</th>
                              <th>Email</th>
                              <th class= "glyphs-users">Editar</th>
                              <th class= "glyphs-users">Eliminar</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>John</td>
                              <td>Doe</td>
                              <td>john@example.com</td>
                              <td class= "glyphs-users"><span class="glyphicon glyphicon-pencil"></span></td>
                              <td class= "glyphs-users"><span class="glyphicon glyphicon-trash"></span></td>
                            </tr>
                            <tr>
                              <td>Mary</td>
                              <td>Moe</td>
                              <td>mary@example.com</td>
                              <td class= "glyphs-users"><span class="glyphicon glyphicon-pencil"></span></td>
                              <td class= "glyphs-users"><span class="glyphicon glyphicon-trash"></span></td>
                            </tr>
                            <tr>
                              <td>July</td>
                              <td>Dooley</td>
                              <td>july@example.com</td>
                              <td class= "glyphs-users"><span class="glyphicon glyphicon-pencil"></span></td>
                              <td class= "glyphs-users"><span class="glyphicon glyphicon-trash"></span></td>
                            </tr>
                          </tbody>
                        </table>
                    </div>
                  </div>
                 </div>
                </div>
           </div>
           <div id="admin-page" class="tab-pane fade">
               <p>Usuarios creados (o promovidos) por la cuenta de root.</p>
               <p>Tienen permiso para realizar todas las acciones de administración.</p>
           </div>
         </div>

				 <a href="index.php" target="_blank">Volver a Home</a>
  </body>
</html>
