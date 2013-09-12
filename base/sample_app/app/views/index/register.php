<div class="span12">
  <h3>Register</h3>
  <p>Enter your email and password below.</p>
  <?php $this->registry->templates->snippet('messages'); ?>
  <form action="/auth/register" id="register" method="POST" class="well">
  	<div class="control-group">
        <label>First Name</label>
        <input type="text" id="first_name" name="first_name" placeholder="First Name" class="span3">
    </div>
    <div class="control-group">
        <label>Last Name</label>
        <input type="text" id="last_name" name="last_name" placeholder="Last Name" class="span3">
    </div>
  	<div class="control-group">
        <label>Email</label>
        <input name="email" type="text" class="span3" placeholder="email@domain.com">
    </div>
    <div class="control-group">
        <label>Password</label>
        <input name="password" type="password" class="span3" placeholder="password">
    </div>
  	<div class="control-group">
    <a class="btn btn-primary submit" id="register-btn" data-loading-text="Loading..." data-complete-text="Submit" href="#">Submit</a> <a class="btn" href="/index/forgot_pass">Forgot Password?</a>
    </div>
  </form>
</div>
