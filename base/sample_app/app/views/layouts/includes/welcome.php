<?php if(isset($_SESSION['auth']) && $_SESSION['auth'] == 1) { ?>
	<div class="nav-collapse collapse pull-right">
		<ul class="nav">
		  <li class="dropdown">
	  		<a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown">Welcome, <?php echo $_SESSION['user']['first_name'].'!'; ?><b class="caret"></b></a>
		  	<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
		  		<?php if(count($_SESSION['user']['roles']) > 0) { foreach($_SESSION['user']['roles'] as $user_role) { ?>
		  		<li><a href="<?php echo $user_role['auth_redirect']; ?>"><?php echo $user_role['name']; ?></a></li>	
		  		<?php } } ?>
		  		<?php ?>
		  		<li class="divider"></li>
		  		<li><a href="/settings/profile">Profile</a></li>
		  		<li class="divider"></li>
		  		<li><a href="/auth/logout">Log Out</a></li>
		    </ul>
		</ul><!--/.nav-collapse -->
	</div>
<?php } else { ?>
	<div class="nav-collapse collapse pull-right">
		<ul class="nav">
			<li><a href="/index/login">Log In</a></li>
		</ul>
	</div>
<?php } ?>
