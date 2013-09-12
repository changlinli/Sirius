<div class="row-fluid">
	<div class="span12">
		<div class="alert popup <?php echo (!empty($_SESSION['validator']['reason'])) ? key($_SESSION['validator']['reason']) : '';?>" style="<?php if(!isset($_SESSION['validator'])) { ?>display: none;<?php } ?>">
			<div><?php if(isset($_SESSION['validator'])) { if(is_array($_SESSION['validator']['messages'])) { foreach($_SESSION['validator']['messages'] as $key => $message) { echo $message . '<br/>'; } } else { /* do nothing */ } } ?></div>
		</div>
	</div>
</div>