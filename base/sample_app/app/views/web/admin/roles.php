<?php $this->registry->templates->snippet('messages'); ?>
<div class="row">
	<div class="span12">
		<div class="pull-left">
			<h3>Roles</h3>
		</div>
		<div class="pull-right">
	    	<a href="#add_item_modal" data-toggle="modal" class="btn btn-mini btn-primary">Add Role</a>
		</div>
	</div>
</div>
<div class="row">
    <div class="span12">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Role Name</th>
            <th>Role Redirect URL</th>
            <th>Created At</th>
            <th>Options</th>
          </tr>
        </thead>
        <tbody>
          <?php if(is_array($items) && count($items) > 0) { foreach($items as $item) { ?>
          <tr>
            <td><?php echo $item['name'] ?></td>
            <td><?php echo $item['auth_redirect']; ?></td>
            <td><?php echo date("F jS, Y @ g:i A", $item['created_at']); ?></td>
            <td><div class="btn-group">
            	<form class="options" id="options-<?php echo $item['id']; ?>">
            		<input type="hidden" name="id" value="<?php echo $item['id']; ?>">
            		<input type="hidden" name="name" value="<?php echo $item['name']; ?>">
            		<input type="hidden" name="auth_redirect" value="<?php echo $item['auth_redirect']; ?>">
	          		<a data-id="<?php echo $item['id']; ?>" class="btn btn-mini edit" href="#edit_item_modal" data-toggle="modal"><i class="icon-cog"></i> Edit</a>
	          		<a data-id="<?php echo $item['id']; ?>" class="btn btn-mini btn-danger remove"><i class="icon-remove icon-white"></i></a>
          		</form>
        	</div></td>
          </tr>
          <?php } } else { ?>
          	<tr>
            	<td colspan="6">There are currently no cache servers.</td>
          	</tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  
  <?php require_once (APP_ROOT.'views/web/layouts/includes/paging.php');  ?>
  
<!-- Modal -->
<div id="add_item_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form id="add_item">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				×
			</button>
			<h3 id="myModalLabel">Add Role</h3>
		</div>
		<div class="modal-body">
			<?php $this->registry->templates->snippet('messages_modal'); ?>
				<div class="control-group">
			        	<label>Name</label>
				        <input type="text" placeholder="Type something…" value="" name="name">
		        </div>
		        <div class="control-group">
			        	<label>Role Redirect URL</label>
				        <input type="text" placeholder="Type something…" value="" name="auth_redirect">
		        </div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">
				Close
			</button>
			<a class="btn btn-primary submit">
				Save changes
			</a>
		</div>
	</form>
</div>
  
<div id="edit_item_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form id="edit_item">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			×
		</button>
		<h3 id="myModalLabel">Edit Role</h3>
	</div>
	<div class="modal-body">
		<?php $this->registry->templates->snippet('messages_modal'); ?>
		<input type="hidden" placeholder="Type something…" value="" name="id">
			<div class="control-group">
		        	<label>Name</label>
			        <input type="text" placeholder="Type something…" value="" name="name">
	        </div>
	        <div class="control-group">
		        	<label>Role Redirect URL</label>
			        <input type="text" placeholder="Type something…" value="" name="auth_redirect">
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
  