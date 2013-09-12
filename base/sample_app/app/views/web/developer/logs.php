<div class="alert">
	<strong>Caution!</strong> This feature is still in development, certain components may not work.
</div>

  <h1>PHP Error Logs</h1>
  <hr />

  <div class="row">
<div class="span3"> 
    <ul class="nav nav-list well">
      <li class="nav-header">Logs at <?php echo $log_dir; ?></li>
      <?php foreach($log_files as $log_file) { ?>
      <li class="<?php echo $log_file['is_active']; ?>"><a href="/developer/logs/<?php echo $log_file['log']; ?>" ><?php echo $log_file['log_name']; ?><span class="label label-inverse"><?php echo $log_file['log_size']; ?></span></a></li>
      <?php } ?>
    </ul>
</div>
<div class="span9">
  <h2>Entries <a class="btn btn-large pull-right" onClick="document.location.reload(true)"><i class="icon-refresh"></i></a></h2>
  <div>
  
    <?php if($log_data == 0) { ?>
   	 	<div class="well pagination-centered"><p class="lead">Select a log file to begin.</p></div>
    <?php } elseif($log_data == 1) { ?>
    	<div class="well pagination-centered"><p class="lead">The log "<?php echo $log_file_name; ?>" does not exist.</p></div>
    <?php } elseif(count($log_data) <= 1) { ?>
    	<div class="well pagination-centered"><p class="lead">There are currently no errors in this log file.</p></div>
	<?php } else {
    	  foreach($log_data as $log_row) { ?>
    		<pre><?php echo $log_row; ?></pre><br />
    <?php } } ?>
  
  </div>
</div>