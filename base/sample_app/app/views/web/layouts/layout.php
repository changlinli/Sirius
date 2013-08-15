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
    <link rel="stylesheet" href="/assets/css/bootstrap.css"/>
    <style>
      /*60px to make the container go all the way to the bottom of the topbar*/
      body {
        padding-top: 60px; 
      }
    </style>

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
          <a class="brand" href="<?php echo '/'.$this->registry->config['default_controller'] .'/'. $this->registry->config['default_action']; ?>"><?php echo $this->registry->config['project_name']; ?></a>
        <div class="nav-collapse collapse pull-left">
            <ul class="nav">
              <li><a href="/index/sample">Sample</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
	<?php echo $dp_content; ?>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<?php echo $ep_js;?>
  </body>
</html>
