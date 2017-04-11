<html>
<head>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="themes/default/css/style.css">
    <script src="themes/default/js/scripts.js"></script>

      <title>Formulario de login</title>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">


  <script src="themes/"></script>
</head>

<body>

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

</body>

</html>
