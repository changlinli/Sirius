<script type="text/javascript">
$(function(){
		$('#reset-password').click(function(e){
			$(this).button('loading');
			e.preventDefault();
			var data = $('#forgot-password-confirm').serializeArray();
			post_action('/index/reset_pass_submit.json',data);
			$(this).button('complete');
		});
});
</script>

<div class="span12">
  <h3>Forgot Password?</h3>
  <p>Enter your new password below.</p>
  <?php $this->registry->templates->snippet('messages'); ?>
  <form action="/index/forgot_pass_submit" id="forgot-password-confirm" method="POST" autocomplete="off" class="well">
  	<div class="control-group">
        <label>Password</label>
        <input type="password" name="password" class="span3" id="password" placeholder="Enter your new password" />
		<input type="hidden" name="token" value="<?php echo $token;?>">
    </div>
  	<div class="control-group">
    	<a class="btn btn-primary" id="reset-password" data-loading-text="Loading..." data-complete-text="Submit" href="#">Submit</a> <a class="btn" href="/index/login">Back To Login</a>
    </div>
  </form>
</div>

