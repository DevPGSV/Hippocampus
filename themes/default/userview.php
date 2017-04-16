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
  <script src='//www.google.com/recaptcha/api.js'></script>

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
            <li><a id="mainsidebar-toogle"><i class="glyphicon glyphicon-menu-hamburger"></i></a></li>
          </ul>
          <ul class="sidebar-nav sidebar-nav-items" id="sidebar">
            <li><a><span class="sidebar-item-content">
              <span class="sidebar-item-text">CV</span><svg class="icon ucm"><use xlink:href="#ucm"></use></svg>
            </span></a></li>
            <li><a><span class="sidebar-item-content">
              <span class="sidebar-item-text">Biblioteca</span><svg class="icon library"><use xlink:href="#library"></use></svg>
            </span></a></li>
            <li><a><span class="sidebar-item-content">
              <span class="sidebar-item-text">Gmail</span><svg class="icon gmail"><use xlink:href="#gmail"></use></svg>
            </span></a></li>
            <li><a><span class="sidebar-item-content">
              <span class="sidebar-item-text">Drive</span><svg class="icon drive"><use xlink:href="#drive"></use></svg>
            </span></a></li>
            <li><a><span class="sidebar-item-content">
              <span class="sidebar-item-text">Calendar</span><svg class="icon calendar"><use xlink:href="#calendar"></use></svg>
            </span></a></li>
            <li><a><span class="sidebar-item-content">
              <span class="sidebar-item-text">Classroom</span><svg class="icon classroom"><use xlink:href="#classroom"></use></svg>
            </span></a></li>
            <li><a><span class="sidebar-item-content">
              <span class="sidebar-item-text">Github</span><svg class="icon github"><use xlink:href="#github"></use></svg>
            </span></a></li>
            <li><a><span class="sidebar-item-content">
              <span class="sidebar-item-text">Bolotweet</span><svg class="icon bolotweet"><use xlink:href="#bolotweet"></use></svg>
            </span></a></li>
            <li><a><span class="sidebar-item-content">
              <span class="sidebar-item-text">Facebook</span><svg class="icon facebook"><use xlink:href="#facebook"></use></svg>
            </span></a></li>
            <li><a><span class="sidebar-item-content">
              <span class="sidebar-item-text">Twitter</span><svg class="icon twitter"><use xlink:href="#twitter"></use></svg>
            </span></a></li>
            <li><a><span class="sidebar-item-content">
              <span class="sidebar-item-text">Mensajes</span><svg class="icon chat"><use xlink:href="#chat"></use></svg>
            </span></a></li>
            <li><a><span class="sidebar-item-content">
              <span class="sidebar-item-text">Asociaciones</span><svg class="icon asociations"><use xlink:href="#asociations"></use></svg>
            </span></a></li>
            <li><a><span class="sidebar-item-content">
              <span class="sidebar-item-text">Cafetería</span><svg class="icon coffee"><use xlink:href="#coffee"></use></svg>
            </span></a></li>
            <li><a><span class="sidebar-item-content">
              <span class="sidebar-item-text">Software</span><svg class="icon software"><use xlink:href="#software"></use></svg>
            </span></a></li>
            <li><a><span class="sidebar-item-content">
              <span class="sidebar-item-text">Ajustes</span><svg class="icon settings"><use xlink:href="#settings"></use></svg>
            </span></a></li>
            <li><a><span class="sidebar-item-content">
              <span class="sidebar-item-text">Ayuda</span><svg class="icon about"><use xlink:href="#about"></use></svg>
            </span></a></li>
          </ul>
        </div>
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
                    <a class="navbar-brand" href="#">Top Menu</a>
                </div>

                <div class="collapse navbar-collapse" id="barraBasica">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#">Home</a></li>
                        <li><a href="#">Aplicaciones</a></li>
                        <li><a href="admin">Administración</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                      <li><a href="#"><span class="glyphicon glyphicon-plus"></span></a></li>
                      <li><a href="#"><span class="glyphicon glyphicon-bullhorn"><span class="badge">5</span></span></a></li>
                      <li><a href="#"><span class="glyphicon glyphicon-user" title="<?php echo $u->getUsername(); ?>"></span></a></li>
                      <li><a href="logout"><span class="glyphicon glyphicon-off" title="Log out"></span></a></li>
                    </ul>
                </div>
            </div>
          </nav>
        </div>
        <div class="row" id="userview-content">
          <?php
          for ($i = 0; $i < 3; $i++) {
              echo '<div class="col-sm-4 userview-content-column-wrapper">
                <div class="userview-content-column" style=""></div>
                </div>';
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
