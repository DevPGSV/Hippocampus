<html lang="es">
	<head>

		<title>Panel de Administración</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">

		<?php echo $hc->getMetacode(); ?>

		<script>
		$(document).ready(function() {
			$("#crear-admin").submit(function(e) {
			    e.preventDefault();
			    $.ajax({
			      type: "POST",
			      url: "api.php?action=admin",
			      dataType: 'json',
			      data: {
			        'newusuario': $("#usuario").val(),
			        'newemail': $("#email").val(),
							'newpassword': $("#passwd").val(),
							'isadmin': $("#checkbox-admin").is(":checked"),
			      },
			      success: function(data) {
			        if (data['status'] == 'ok') {
								$("#usuario").val('');
								$("#email").val('');
								$("#passwd").val('');
								$("#checkbox-admin").prop('checked', false);
								alert(data['msg']);
							} else {
								alert(data['msg']);
							}
			      },
			    });
			  });
			});
			</script>
	</head>

	<body>
     <div id="maincontainer" class="container">
      <ul class="nav nav-pills nav-justified" id="ul-admin">
         <li class="active"><a data-toggle="pill" href="#admin-users"><h5 class="admin-title">Administrar usuarios</h5></a></li>
       </ul>
         <div class="tab-content" id="tab-admin">
					 <!-- ADMINISTRAR USUARIOS-->
           <div id="admin-users" class="tab-pane fade in active">
             <div class="container panel-group" id="accordion1">

							 <!-- CREAR USUARIO -->
								<div class="panel panel-default">
                  <div class="panel-heading">
                    <h2 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion1" href="#crear-admin">CREAR UN USUARIO</a>
                    </h2>
                  </div>
                 <div id="crear-admin" class="panel-collapse collapse in active">
                    <div class="panel-body form-group">
											<form action= "" method="POST">
                        <input type="text" class="form-control" placeholder="Usuario" name="newusuario"  class="tooltip tooltip-top tooltip-arrow"  data-placement="top" title="Entre 4 y 20 caracteres"
												id="usuario">
                        <br>
                				<input type="email" class="form-control" placeholder="Email" name="newemail" id="email">
                        <br>
												<input type="password" class="form-control" placeholder="Contraseña" name="newpassword" id="passwd"  class="tooltip tooltip-top tooltip-arrow"  data-placement="top" title="La contraseña debe contener al menos un número, una letra minúscula, una mayúscula y un caracter espcecial y tener entre 8 y 20 caracteres">
                        <br>
                        <input type="checkbox" name="isadmin" value="false" id="checkbox-admin"><label id="label-admin">   Hacer administrador</label><br>
                        <input type="submit" value="Crear" class="button-crear">
											</form>
                    </div>
                  </div>
                </div>
								<!-- END -CREAR USER! -->

								<!-- EDITAR USER! -->
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h2 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion1" href="#editar-admin">EDITAR UN USUARIO</a>
                    </h2>
                  </div>
                  <div id="editar-admin" class="panel-collapse collapse">
                    <div class="panel-body">
                      <table class="table table-hover table-condensed table-responsive table-admin">
                          <thead>
                            <tr>
                              <th>Username</th><th>Rol</th><th>Email</th><th class= "glyphs-users">Eliminar</th>
                            </tr>
                          </thead>
                          <tbody>
														<?php
														foreach($hc->getDB()->getAllusersData() as $user){
															echo '<tr>
	                              <td>'.$user['username'].'</td>
																<td>';
																if($user['role'] == 1){
																	echo 'root';
																}
																elseif($user['role'] == 2){
																	echo 'administrador';
																}
																elseif($user['role'] == 3){
																	echo 'usuario';
																}
																echo '</td>
																<td>'.$user['email'].'</td>
																<td class= "glyphs-users"><span class="glyphicon glyphicon-trash admin-erase-user" data-user="';
																echo $user["username"];
																echo '"></span></td>
	                            </tr>';
														}
														?>
                          </tbody>
                        </table>
                    </div>
                  </div>
                 </div>
								 <!-- END - EDITAR USER! -->
                </div>
           </div>
				 <!-- END-ADMINISTRAR USUARIOS-->
				 </div>
		  <a href="index.php" id="homelinkadmin"><span class="glyphicon glyphicon-circle-arrow-left"></span> HOME </a>
		</div>
  </body>
</html>
