<?php $this->registry->templates->snippet('messages'); ?>
<div class="row">
	<div class="span12">
		<div class="pull-left">
			<h3>API Keys</h3>
		</div>
		<div class="pull-right">
	    	<a href="#add_key_modal" data-toggle="modal" class="btn btn-mini btn-primary">Add API Key</a>
		</div>
	</div>
</div>
<div class="row">
    <div class="span12">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Application Name</th>
            <th>API Key</th>
            <th>Options</th>
          </tr>
        </thead>
        <tbody>
          <?php if(is_array($data) && count($data) > 0) { foreach($data as $item) { ?>
          <tr>
            <td><?php echo $item['id']; ?></td>
            <td><?php echo $item['application_name']; ?></td>
            <td><?php echo $item['key']; ?></td>
            <td><div class="btn-group">
            	<form class="options" id="options-<?php echo $item['id']; ?>">
            		<input type="hidden" name="id" value="<?php echo $item['id']; ?>">
            		<input type="hidden" name="key" value="<?php echo $item['key']; ?>">
            		<input type="hidden" name="application_name" value="<?php echo $item['application_name']; ?>">
	          		<a data-id="<?php echo $item['id']; ?>" class="btn btn-mini edit" href="#edit_key_modal" data-toggle="modal"><i class="icon-cog"></i> Edit</a>
	          		<a data-id="<?php echo $item['id']; ?>" class="btn btn-mini btn-danger remove"><i class="icon-remove icon-white"></i></a>
          		</form>
        	</div></td>
          </tr>
          <?php } } else { ?>
          	<tr>
            	<td colspan="4">There are currently no keys.</td>
          	</tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  
  <?php require_once (APP_ROOT.'views/web/layouts/includes/paging.php');  ?>
  
<!-- Modal -->
<div id="add_key_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form id="add_key">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				×
			</button>
			<h3 id="myModalLabel">Add API Key</h3>
		</div>
		<div class="modal-body">
			<?php $this->registry->templates->snippet('messages_modal'); ?>
			<div class="control-group">
		        	<label>Application Name</label>
			        <input type="text" placeholder="Type something…" value="" name="application_name">
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
  
<div id="edit_key_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form id="edit_key">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			×
		</button>
		<h3 id="myModalLabel">Edit API Key</h3>
	</div>
	<div class="modal-body">
		<?php $this->registry->templates->snippet('messages_modal'); ?>
			<input type="hidden" placeholder="Type something…" value="" name="id">
			<div class="control-group">
		        	<label>Application Name</label>
			        <input type="text" placeholder="Type something…" value="" name="application_name">
	        </div>
	</div>
	<div class="modal-footer">
		<a class="btn" data-dismiss="modal" aria-hidden="true">
			Close
		</a>
		<a class="btn btn-primary submit">
			Save changes
		</a>
	</div>
	</form>
</div>
  