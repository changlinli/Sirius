<div class="well">
	<form id="profile">
	<div class="control-group">
		<img id="profile_image" data-src="holder.js/140x140" class="img-polaroid" alt="140x140" src="/uploads/<?php echo $data['profile_image']; ?>">
	</div>
	<div class="control-group">
		<div id="upload">
			<div id="files"></div>
		    <a id="browse_button" class="btn" href="javascript:;">Select an Image</a> 
		    <!--<button id="upload" href="javascript:;">[Upload files]</button>-->
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="first_name">
			First Name
		</label>
		<div class="controls">
			<input type="text" name="first_name" id="first_name" value="<?php echo $data['first_name']; ?>" placeholder="First Name">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="last_name">
			Last Name
		</label>
		<div class="controls">
			<input type="text" name="last_name" id="last_name" value="<?php echo $data['last_name']; ?>" placeholder="Last Name">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="email">
			Email
		</label>
		<div class="controls">
			<input type="text" name="email" id="email" value="<?php echo $data['email']; ?>" placeholder="Email">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="password">
			Password
		</label>
		<div class="controls">
			<input type="password" name="password" id="password" placeholder="Password">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="confirm_password">
			Confirm Password
		</label>
		<div class="controls">
			<input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<a name="submit" id="submit" class="submit btn" href="#">Submit</a>
		</div>
	</div>
	</form>
</div>
<!--<script type="text/javascript" src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>-->
