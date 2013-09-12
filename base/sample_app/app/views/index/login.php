<?php $this->registry->templates->snippet('messages'); ?>
<div class="row-fluid">
	<div class="span6">
	  <h3>Log In</h3>
	  <p>Enter your email and password below. Need an account? <a href="/index/register">Register Here.</a></p>
	  <form action="/auth/auth" id="login" method="POST" class="well">
	  	<div class="control-group">
	        <label>Email</label>
	        <input name="email" type="text" class="span12" placeholder="email@domain.com">
	    </div>
	    <div class="control-group">
	        <label>Password</label>
	        <input name="password" type="password" class="span12" placeholder="1234">
	    </div>
	  	<div class="control-group">
	    <a class="btn btn-primary" id="sign-in" data-loading-text="Loading..." data-complete-text="Submit" href="#">Submit</a> <a class="btn" href="/index/forgot_pass">Forgot Password?</a>
	    </div>
	  </form>
	</div>
	<div class="span6">
		<h3>Log in with your Social Account</h3>
		<p>Choose a social account below.</p>
		<div class="well">
			<a class="btn" href="<?php echo $data['facebook']; ?>">Facebook</a>
			<a class="btn" href="<?php echo $data['twitter']; ?>">Twitter</a>
			<a class="btn" href="<?php echo $data['googleplus']; ?>">Google+</a>
		</div>
	</div>
</div>
