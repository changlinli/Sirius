<?php $this->registry->templates->snippet('messages'); ?>
<div class="row">
	<div class="span12">
		<div class="pull-left">
			<h3>Configuration</h3>
		</div>
		<div class="pull-right">
	    	<a href="#add_config_modal" data-toggle="modal" class="btn btn-mini btn-primary">Add Config Item</a>
		</div>
	</div>
</div>

<div class="row-fluid">
  	<div class="span3 well">
  		<div class="control-group">
	        <label>Application Name</label>
	        <form id="project_name">
		        <input type="hidden" name="name" value="project_name" class="span12">
		        <input type="text" placeholder="Type something…" value="<?php echo $primary_items['project_name']; ?>" class="span12" name="value">
	        	<div class="pull-right">
	        		<a class="btn btn-mini btn-primary submit">Update</a>
	        	</div>
        	</form>
        </div>
  	</div>
  	<div class="span3 well">
  		<div class="control-group">
	        <label>Default Email</label>
	        <form id="site_email">
	        	<input type="hidden" name="name" value="site_email" class="span12">
	        	<input type="text" placeholder="Type something…" value="<?php echo $primary_items['site_email']; ?>" class="span12" name="value">
	        	<div class="pull-right">
	        		<a class="btn btn-mini btn-primary submit">Update</a>
	        	</div>
	        </form>
        </div>
  	</div>
  	<div class="span2 well">
  		<div class="control-group">
	        <label>Default Module</label>
	        <form id="default_controller">
	        	<input type="hidden" name="name" value="default_controller" class="span12">
		        <input type="text" placeholder="Type something…" value="<?php echo $primary_items['default_controller']; ?>" class="span12" name="value">
	        	<div class="pull-right">
	        		<a class="btn btn-mini btn-primary submit">Update</a>
	        	</div>
	        </form>
        </div>
  	</div>
  	<div class="span2 well">
  		<div class="control-group">
	        <label>Default Page</label>
	        <form id="default_action">
	        	<input type="hidden" name="name" value="default_action" class="span12">
		        <input type="text" placeholder="Type something…" value="<?php echo $primary_items['default_action']; ?>" class="span12" name="value">
	        	<div class="pull-right">
	        		<a class="btn btn-mini btn-primary submit">Update</a>
	        	</div>
	        </form>
        </div>
  	</div>
  	<div class="span2 well">
  		<div class="control-group">
	        <label>Google Analytics</label>
	        <form id="google_analytics">
	        	<input type="hidden" name="name" value="google_analytics" class="span12">
		        <input type="text" placeholder="Type something…" value="<?php echo $primary_items['google_analytics']; ?>" class="span12" name="value">
	        	<div class="pull-right">
	        		<a class="btn btn-mini btn-primary submit">Update</a>
	        	</div>
	        </form>
        </div>
  	</div>
 </div>
<div class="row">
    <div class="span12">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Name</th>
            <th>Value</th>
            <th>Options</th>
          </tr>
        </thead>
        <tbody>
          <?php if(is_array($items) && count($items) > 0) { foreach($items as $item) { ?>
          <tr>
            <td><?php echo $item['name']; ?></td>
            <td><?php echo $item['value']; ?></td>
            <td><div class="btn-group">
            	<form class="options" id="options-<?php echo $item['id']; ?>">
            		<input type="hidden" name="name" value="<?php echo $item['name']; ?>">
            		<input type="hidden" name="value" value="<?php echo $item['value']; ?>">
	            	<input type="hidden" name="configuration_id" value="<?php echo $item['id']; ?>">
	          		<a data-id="<?php echo $item['id']; ?>" class="btn btn-mini edit" href="#edit_config_modal" data-toggle="modal"><i class="icon-cog"></i> Edit</a>
	          		<a data-id="<?php echo $item['id']; ?>" class="btn btn-mini btn-danger remove"><i class="icon-remove icon-white"></i></a>
          		</form>
        	</div></td>
          </tr>
          <?php } } else { ?>
          	<tr>
            	<td colspan="2">There are currently no configuration items.</td>
          	</tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  
  <?php require_once (APP_ROOT.'views/web/layouts/includes/paging.php');  ?>
  
<!-- Modal -->
<div id="add_config_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form id="add_config">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			×
		</button>
		<h3 id="myModalLabel">Add Config Item</h3>
	</div>
	<div class="modal-body">
		<?php $this->registry->templates->snippet('messages_modal'); ?>
			<div class="control-group">
		        	<label>Name</label>
			        <input type="text" placeholder="Type something…" value="" name="name">
	        </div>
	        <div class="control-group">
		        	<label>Value</label>
			        <input type="text" placeholder="Type something…" value="" name="value">
	        </div>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">
			Close
		</button>
		<button class="btn btn-primary submit">
			Save changes
		</button>
	</div>
	</form>
</div>
  
<div id="edit_config_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form id="edit_config">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			×
		</button>
		<h3 id="myModalLabel">Edit Config Item</h3>
	</div>
	<div class="modal-body">
		<?php $this->registry->templates->snippet('messages_modal'); ?>
			<div class="control-group">
		        	<label>Name</label>
			        <input type="hidden" placeholder="Type something…" value="" name="name">
			        <div><strong id="config-name"></strong></div>
	        </div>
	        <div class="control-group">
		        	<label>Value</label>
			        <input type="text" placeholder="Type something…" value="" name="value">
	        </div>
	</div>
	<div class="modal-footer">
		<a class="btn" data-dismiss="modal" aria-hidden="true">
			Close
		</a>
		<a class="btn btn-primary edit_submit">
			Save changes
		</a>
	</div>
	</form>
</div>
  