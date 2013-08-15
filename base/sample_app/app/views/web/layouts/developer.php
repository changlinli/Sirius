<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $this->registry->config['project_name']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <?php echo $ep_css;?>
	
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <!--link href="//assets/css/bootstrap-responsive.css" rel="stylesheet"-->

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="/assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="/assets/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body>
  	
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand pull-left" href="#"><?php echo $this->registry->config['project_name']; ?></a>
          <div class="nav-collapse collapse pull-left">
            <ul class="nav">
              <li><a href="/developer/config">Config</a></li>
              <li><a href="/developer/keys">Keys</a></li>
              <li><a href="/developer/databases">Databases</a></li>
              <li><a href="/developer/sources">Sources</a></li>
              <li><a href="/developer/cache">Cache</a></li>
              <li class="dropdown">
              	<a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown">Tools<b class="caret"></b></a>
              	<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
		  		<li><a href="/developer/logs">Log Checker</a></li>
		    </ul>
              </li>
            </ul>
          </div><!--/.nav-collapse -->
          <?php $this->registry->templates->snippet('welcome'); ?>
        </div>
      </div>
    </div>

    <div class="container">
	<?php echo $dp_content; ?>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/assets/js/libs/jquery.js"></script>
    <script src="/assets/js/libs/bootstrap.js"></script>
    <script src="/assets/js/libs/underscore-min.js"></script>
    <script src="/assets/js/libs/backbone-min.js"></script>
	<?php echo $ep_js;?>
  </body>
</html>
