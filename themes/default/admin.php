<!DOCTYPE html>
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
         <li><a data-toggle="pill" href="#admin-page"><h5 class="admin-title">Administrar página</h5></a></li>
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
                              <th>Username</th><th>Rol</th><th>Email</th><th class= "glyphs-users">Editar</th><th class= "glyphs-users">Eliminar</th>
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
																<td class= "glyphs-users"><span class="glyphicon glyphicon-pencil admin-edit-user"></span></td>
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

				 <!-- ADMINISTRAR PAGINA -->

           <div id="admin-page" class="tab-pane fade">
	             <div class="container panel-group" id="accordion2">
								 <!-- PANEL NOMBRE-->
	                <div class="panel panel-default">
	                  <div class="panel-heading">
	                    <h2 class="panel-title">
	                      <a data-toggle="collapse" data-parent="#accordion2" href="#nombre-admin">NOMBRE</a>
	                    </h2>
	                  </div>
	                  <div id="nombre-admin" class="panel-collapse collapse in active">
	                    <div class="panel-body form-group">

	                        <input type="text" class="form-control" name="nombre">
	                        <br>
	                        <input type="submit" value="Guardar" class="button-crear">
	                    </div>
	                  </div>
	                </div>

									<!-- PANEL LOGO-->
	                <div class="panel panel-default">
	                  <div class="panel-heading">
	                    <h2 class="panel-title">
	                      <a data-toggle="collapse" data-parent="#accordion2" href="#logo-admin">LOGO</a>
	                    </h2>
	                  </div>
	                  <div id="logo-admin" class="panel-collapse collapse">
	                    <div class="panel-body">

												<h4> Subir un nuevo logo: </h4>

												<div class="input-group">
													<label class="input-group-btn">
														 <span class="btn btn-primary">
																 Buscar&hellip; <input type="file" style="display: none;" multiple>
														 </span>
												 	</label>
												 <input type="text" class="form-control"  accept="image/*">
                       </div>
											 <br>
											<input type="submit" value="Subir" class="button-crear">
	                    </div>
	                  </div>
	                 </div>

									 <!-- PANEL TEMA -->
									 <div class="panel panel-default">
										 <div class="panel-heading">
											 <h2 class="panel-title">
												 <a data-toggle="collapse" data-parent="#accordion2" href="#tema-admin">TEMA</a>
											 </h2>
										 </div>
										 <div id="tema-admin" class="panel-collapse collapse">
											 <div class="panel-body">
												 <table class="table table-hover table-condensed table-responsive table-admin">
													 <thead>
                             <tr>
                               <th>Tema</th><th class= "glyphs-users">Activar</th>
                             </tr>
                           </thead>
													 <tbody>
														 <tr>
                               <td>Default</td>
 															 <td class= "glyphs-users"><span class="glyphicon glyphicon-eye-open admin-active-theme"></span></td>
														</tr>
														<tr>
															<td>Tema 1</td>
														  <td class= "glyphs-users"><span class="glyphicon glyphicon-eye-close admin-unactive-theme"></span></td>
													 </tr>
													 <tr>
														 <td>Tema 2</td>
														 <td class= "glyphs-users"><span class="glyphicon glyphicon-eye-close admin-unactive-theme"></span></td>
													</tr>
													</tbody>
												</table>
											 </div>
										 </div>
										</div>

										<!-- PANEL MODULES -->
										<div class="panel panel-default">
										 <div class="panel-heading">
											 <h2 class="panel-title">
												 <a data-toggle="collapse" data-parent="#accordion2" href="#module-admin">MÓDULOS</a>
											 </h2>
										 </div>
										 <div id="module-admin" class="panel-collapse collapse">
											 <div class="panel-body">
												 <table class="table table-hover table-condensed table-responsive table-admin">
													 <thead>
                             <tr>
                               <th>Módulo</th><th class= "glyphs-users">Activar</th>
                             </tr>
                           </thead>
													 <tbody>
														 <tr>
                               <td>Módulo 1</td>
 															 <td class= "glyphs-users"><span class="glyphicon glyphicon-ok-sign admin-active-module"></span></td>
														</tr>
														<tr>
															<td>Módulo 2</td>
														  <td class= "glyphs-users"><span class="glyphicon glyphicon-plus-sign admin-unactive-module"></span></td>
													 </tr>
													 <tr>
														 <td>Módulo 3</td>
														 <td class= "glyphs-users"><span class="glyphicon glyphicon-plus-sign admin-unactive-module"></span></td>
													</tr>
													</tbody>
												</table>
											 </div>
										 </div>
										</div>
										<!-- END-PANEL MODULES -->

	                </div>
	           </div>

					 <!-- END-ADMINISTRAR PAGINA -->
				 </div>
		  <a href="index.php" id="homelinkadmin"><span class="glyphicon glyphicon-circle-arrow-left"></span> HOME </a>
		</div>
  </body>
</html>
