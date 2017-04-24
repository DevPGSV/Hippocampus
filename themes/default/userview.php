<html>
<head>
  <title>Página de usuario</title>
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
  <?php include_once("themes/default/css/apps-icons.svg"); ?>

  <div class="page sidebar-expansible" id="mainpage">

      <div class="sidebar dont-select">
        <div class="sidebar-wrapper">
          <ul class="sidebar-nav sidebar-nav-head">
            <li><a id="mainsidebar-toogle"> <div id="menu-nav-icon">
                                              <span></span>
                                              <span></span>
                                              <span></span>
                                            </div></a></li>
          </ul>
          <ul class="sidebar-nav sidebar-nav-items" id="sidebar">
            <?php
            $sidebartabs = [
              'home' => [
                'icon' => 'home',
                'text' => 'Inicio',
              ],
              'cv' => [
                'icon' => 'ucm',
                'text' => 'CV',
              ],
              'library' => [
                'icon' => 'library',
                'text' => 'Biblioteca',
              ],
              'gmail' => [
                'icon' => 'gmail',
                'text' => 'Gmail',
              ],
              'drive' => [
                'icon' => 'drive',
                'text' => 'Drive',
              ],
              'calendar' => [
                'icon' => 'calendar',
                'text' => 'Calendar',
              ],
              'classroom' => [
                'icon' => 'classroom',
                'text' => 'Classroom',
              ],
              'github' => [
                'icon' => 'github',
                'text' => 'Github',
              ],
              'bolotweet' => [
                'icon' => 'bolotweet',
                'text' => 'Bolotweet',
              ],
              'facebook' => [
                'icon' => 'facebook',
                'text' => 'Facebook',
              ],
              'twitter' => [
                'icon' => 'twitter',
                'text' => 'Twitter',
              ],
              'chat' => [
                'icon' => 'chat',
                'text' => 'Mensajes',
              ],
              'chat' => [
                'icon' => 'chat',
                'text' => 'Mensajes',
              ],
              'asociations' => [
                'icon' => 'asociations',
                'text' => 'Asociaciones',
              ],
              'coffee' => [
                'icon' => 'coffee',
                'text' => 'Cafetería',
              ],
              'software' => [
                'icon' => 'software',
                'text' => 'Software',
              ],
              'settings' => [
                'icon' => 'settings',
                'text' => 'Ajustes',
              ],
              'about' => [
                'icon' => 'about',
                'text' => 'Ayuda',
              ],
            ];
            foreach ($sidebartabs as $sidebartabid => $sidebartabdata) {
              echo '
            <li>
              <a>
                <span class="sidebar-item-content" data-service="'.$sidebartabid.'">
                  <svg class="icon '.$sidebartabdata['icon'].'">
                    <use xlink:href="#'.$sidebartabdata['icon'].'">
                    </use>
                  </svg>
                  <span class="sidebar-item-text">'.$sidebartabdata['text'].'</span>
                </span>
              </a>
            </li>';
            }
            ?>
          </ul>
        </div>
      </div>

      <div id="toplogo" class="container-fluid">
        <a href="home"><img src="http://i.imgur.com/yJuHskd.png" title="Hippocampus"></a>
      </div>

      <div class="container-fluid">
        <div class="row">
          <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#barraBasica">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"></a><!-- HE QUITADO TOP MENU-->
                </div>

                <div class="collapse navbar-collapse" id="barraBasica">
                    <ul class="nav navbar-nav">
                        <li><a href="#"  class=" navbar-element navbar-main-title">Home</a></li> <!-- HE QUITADO ACTIVE Y <a href="#">-->
                        <!--<li><a href="#">Aplicaciones</a></li>-->
                        <li><a href="admin" class="navbar-element navbar-main-title">Administración</a></li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                      <li><a data-toggle="pill" href="#menu1"><span class="glyphicon glyphicon-plus navbar-element"></span></a></li>

                      <!-- MENU NOTIFICACIONES -->
                      <li><div class="dropdown" id="div-menu2"><button class="btn btn-default dropdown-toggle" type="button" id="menu2" data-toggle="dropdown"><span class="glyphicon glyphicon-bullhorn navbar-element"><span class="badge">4</span></span></button>
                        <ul class="dropdown-menu" role="menu">
                          <li role="presentation " class="notification-text-inside"><svg class="notification-icon gmail"><use xlink:href="#gmail"></use></svg><a role="menuitem" tabindex="-1" href="#" class="notification-text">  Tienes 1 mensaje nuevo.</a></li>
                          <li role="presentation " class="notification-text-inside"><svg class="notification-icon bolotweet"><use xlink:href="#bolotweet"></use></svg><a role="menuitem" tabindex="-1" href="#" class="notification-text">  Tienes 1 mensaje nuevo.</a></li>
                          <li role="presentation"><svg class="notification-icon ucm"><use xlink:href="#ucm"></use></svg><a role="menuitem" tabindex="-1" href="#" class="notification-text">    Tienes 2 nuevos mensajes.</a></li>
                        </ul></div></li>
                      <!-- END MENU NOTIFICACIONES -->

                      <!-- MENU PERFIL -->
                      <li><a data-toggle="pill" href="#menu3"><span class="glyphicon glyphicon-user navbar-element" title="<?php echo $u->getUsername(); ?>"></span></a></li>
                      <!-- END MENU PERFIL -->
                      <li><a href="logout"><span class="glyphicon glyphicon-off navbar-element" title="Log out"></span></a></li>
                    </ul>
                </div>

                <div class="tab-content">
                    <div id="menu1" class="tab-pane fade">
                      <h3>Configuración de columnas</h3>
                      <p> ¿Cuántas columnas quieres mostrar? </p>
                      <button button type="button" class="btn btn-link btn-xl1" onclick="myFunctionForThis(1)"></button>
                      <button button type="button" class="btn btn-link btn-xl2" onclick="myFunctionForThis(2)"></button>
                      <button button type="button" class="btn btn-link btn-xl3" onclick="myFunctionForThis(3)"></button>
                      <button button type="button" class="btn btn-link btn-xl4" onclick="myFunctionForThis(4)"></button>
                    </div>

                    <!-- MENU PERFIL -->
                     <div id="menu3" class="tab-pane fade">

                     <div class="container">
                         <h1>Editar tu perfil</h1>
                         <hr>
                       <div class="row">
                           <!-- left column -->
                           <div class="col-md-3">
                             <div class="text-center">
                               <img src="http://i.imgur.com/cTCsZeR.png">
                               <!--<h6>Sube una nueva imagen</h6>

                               <input class="form-control" type="file">-->
                             </div>
                           </div>

                           <!-- edit form column -->
                             <div class="col-md-9 personal-info">
                               <!--<div class="alert alert-info alert-dismissable">
                                 <a class="panel-close close" data-dismiss="alert">×</a>
                                 <i class="fa fa-coffee"></i>
                                  Puedes cambiar tus datos desde el menú de Administración
                                </div>-->
                               <div id="alerta"><h3>Información de la cuenta</h3></div>

                               <form class="form-horizontal" role="form">
                                 <div class="form-group">
                                   <label class="col-lg-3 control-label">Nombre</label>
                                   <div class="col-lg-8">
                                     <input class="form-control" value="User" type="text">
                                   </div>
                                 </div>
                                 <div class="form-group">
                                   <label class="col-lg-3 control-label">Apellidos</label>
                                   <div class="col-lg-8">
                                     <input class="form-control" value="Lastname" type="text">
                                   </div>
                                 </div>
                                 <div class="form-group">
                                   <label class="col-lg-3 control-label">Correo electrónico</label>
                                   <div class="col-lg-8">
                                     <input class="form-control" value="<?php echo $u->getEmail(); ?>" type="text">
                                   </div>
                                 </div>
                                 <div class="form-group">
                                   <label class="col-md-3 control-label">Nombre de usuario</label>
                                   <div class="col-md-8">
                                     <input class="form-control" value="<?php echo $u->getUsername(); ?>" type="text">
                                   </div>
                                 </div>
                                 <div class="form-group">
                                   <label class="col-md-3 control-label">Contraseña</label>
                                   <div class="col-md-8">
                                     <input class="form-control" value="" type="password">
                                   </div>
                                 </div>
                                 <div class="form-group">
                                   <label class="col-md-3 control-label">Confirmar contraseña</label>
                                   <div class="col-md-8">
                                     <input class="form-control" value="" type="password">
                                   </div>
                                 </div>
                               </form>
                             </div>
                         </div>
                       </div>
                       <hr>
                     </div>
                     <!-- END MENU PERFIL -->

                    </div>
              </div>
            </nav>
          </div>
          <div id="userview-content">
            <?php
            $rows = $hc->db->getUserDataById($u->getId())['boxesconfig'];
            $rowNumber = count($rows);
            foreach ($rows as $r => $row) {
              echo '<div class="row row'.$rowNumber.'">';
              $colSize = floor(12/count($row));
              foreach ($row as $c => $colum) {
                  echo '<div class="col-sm-'.$colSize.' userview-content-column-wrapper">
                    <div class="userview-window-toolbar">
                      <div class="userview-window-toolbar-service"> Nombre del servicio </div>
                      <div class="userview-window-toolbar-icons"> <a href="localhost"><span class="glyphicon glyphicon-new-window"></span></a> <a href="localhost"><span class="glyphicon glyphicon-resize-full"></span></a> </div>
                    </div>
                    <div class="userview-content-column" data-boxrow="'.$r.'" data-boxcol="'.$c.'" data-boxcontent="'.$colum.'">Loading...</div>
                    </div>';
              }
              echo '</div>';
            }
            ?>
          </div>
      </div>
  </div>
  <?php /*
  <div class="page">
    <div class="wrapper">
      <!--<div class="content-wrapper">
        <div class="content">
          <h1>Content</h1>
          <p>&larr; Responsive Width &rarr;</p>
        </div>
      </div>-->

       <div class="sidebar">

         <!--<nav id="main-nav" class="main-nav">
           <ul>
             <li class="home">
               <a href="/"><
                 <svg class="icon-nav-home" width="26px" height="26px">
                   <use xlink:href="#icon-nav-home"></use>
                 </svg>
                 <span>Home</span>
               </a>
             </li>
             <li class="videos">
               <a href="/"><
                 <svg class="icon-nav-video" width="26px" height="26px">
                   <use xlink:href="#icon-nav-video"></use>
                 </svg>
                 <span>Home</span>
               </a>
             </li>
           </ul>
         </nav> -->

            <div id="sidebar-wrapper">
                <ul id="sidebar_menu" class="sidebar-nav">
                  <li class="sidebar-brand"><a id="menu-toggle" href="#"><span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span></a></li>
                </ul>
                <ul class="sidebar-nav" id="sidebar">
                  <li><a>Algo<span class="glyphicon glyphicon-user"></span></a></li>
                  <ul class="sidebar-nav" id="sidebar">
                    <li><a>Algo<span class="glyphicon glyphicon-search"></span></a></li>
                    <li><a>Algo<span class="glyphicon glyphicon-heart"></span></a></li>
                  </ul>
                  <li><a>Algo<span class="glyphicon glyphicon-music"></span></a></li>
                  <li><a>Algo<span class="glyphicon glyphicon-list-alt"></span></a></li>
                  <li><a>Algo<span class="glyphicon glyphicon-envelope"></span></a></li>
               </ul>
            </div>

             <!-- This ends new -->

              <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#barraBasica">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">Site</a>
                    </div>

                    <div class="collapse navbar-collapse" id="barraBasica">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="#"> Home <span class="sr-only">(current)</span></a></li>
                            <li><a href="#"> Aplicaciones </a></li>
                            <li><a href="#"> Administración </a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                          <li><a href="#"><span class="glyphicon glyphicon-plus"></span></a></li>
                          <li><a href="#"><span class="glyphicon glyphicon-bullhorn"></span></a></li>
                          <li><a href="#"><span class="glyphicon glyphicon-user"></span></a></li>
                        </ul>
                    </div>
                </div>
              </nav>

                <!--<script src="https://code.jquery.com/jquery-2.2.1.min.js"></script>-->
              <div id="content">
                  <div class="column"></div>
                  <div class="column"></div>
                  <div class="column"></div>
              </div>
                <!-- Latest compiled and minified JavaScript -->
      </div>
    </div>
  </div>

<!--<div class="main">
<div class="top">TOP
  Output - Logged in as <?php echo $u->getEmail(); ?>
  <br>
  <a href="?">Log out</a>
</div>
<div class="left">MIS APLICACIONES</div>
<div class="right">MAIN</div>
<div class="clear"></div>
</div>-->
 */ ?>
</body>

</html>
