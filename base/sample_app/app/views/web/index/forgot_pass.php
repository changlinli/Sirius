<div class="span12">
  <h3>Forgot Password?</h3>
  <p>Enter your email below.</p>
  <?php $this->registry->templates->snippet('messages'); ?>
  <form action="/index/forgot_pass_submit" id="forgot-password" method="POST" autocomplete="off" class="well">
  	<div class="control-group">
        <label>Email</label>
        <input name="email" type="text" class="span3" placeholder="email@domain.com">
    </div>
  	<div class="control-group">
    <a class="btn btn-primary" id="reset-password" data-loading-text="Loading..." data-complete-text="Submit">Submit</a> <a class="btn" href="/index/login">Back To Login</a>
    </div>
  </form>
</div>
