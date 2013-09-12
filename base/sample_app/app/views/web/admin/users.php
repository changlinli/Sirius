<?php $this->registry->templates->snippet('messages'); ?>
<div class="row">
	<div class="span12">
		<div class="pull-left">
			<h3>Users</h3>
		</div>
		<div class="pull-right">
	    	<a href="#add_item_modal" data-toggle="modal" class="btn btn-mini btn-primary">Add User</a>
		</div>
	</div>
</div>
<div class="row">
    <div class="span12">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Options</th>
          </tr>
        </thead>
        <tbody>
          <?php if(is_array($items) && count($items) > 0) { foreach($items as $item) { $role_string = array(); ?>
          <tr>
            <td><?php echo $item['first_name'] . ' ' . $item['last_name']; ?></td>
            <td><?php echo $item['email']; ?></td>
            <td><?php foreach(explode(',',$item['role_id']) as $role_id) { $role_string[] = $roles[$role_id]['name']; } echo implode(', ',$role_string); ?></td>
            <td><?php echo ($item['active'] == 1) ? 'Active' : 'In-Active'; ?></td>
            <td><?php echo date("F jS, Y @ g:i A", $item['created_at']); ?></td>
            <td><div class="btn-group">
            	<form class="options" id="options-<?php echo $item['id']; ?>">
            		<input type="hidden" name="id" value="<?php echo $item['id']; ?>">
            		<input type="hidden" name="first_name" value="<?php echo $item['first_name']; ?>">
            		<input type="hidden" name="last_name" value="<?php echo $item['last_name']; ?>">
	            	<input type="hidden" name="email" value="<?php echo $item['email']; ?>">
	            	<input type="hidden" name="role_id" value="<?php echo $item['role_id']; ?>">
	            	<input type="hidden" name="active" value="<?php echo $item['active']; ?>">
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
			<h3 id="myModalLabel">Add User</h3>
		</div>
		<div class="modal-body">
				<?php $this->registry->templates->snippet('messages_modal'); ?>
				<div class="control-group">
			        	<label>First Name</label>
				        <input type="text" placeholder="Type something…" value="" name="first_name">
		        </div>
		        <div class="control-group">
			        	<label>Last Name</label>
				        <input type="text" placeholder="Type something…" value="" name="last_name">
		        </div>
		        <div class="control-group">
			        	<label>Email</label>
				        <input type="text" placeholder="Type something…" value="" name="email">
		        </div>
		        <div class="control-group">
			        	<label>Password</label>
				        <input type="password" placeholder="******" value="" name="password">
		        </div>
		        <div class="control-group">
			        	<label>Role</label>
			        	<?php $i = 0; foreach($roles as $role) { ?>
			        	<label class="checkbox"><input type="checkbox" name="role_id[<?php echo $role['id']; ?>]" value="<?php echo $role['id']; ?>"/><?php echo $role['name']; ?></label>
			        	<?php $i++; } ?>
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
		<h3 id="myModalLabel">Edit User</h3>
	</div>
	<div class="modal-body">
			<?php $this->registry->templates->snippet('messages_modal'); ?>
			<input type="hidden" placeholder="Type something…" value="" name="id">
			<div class="control-group">
		        	<label>First Name</label>
			        <input type="text" placeholder="Type something…" value="" name="first_name">
	        </div>
	        <div class="control-group">
		        	<label>Last Name</label>
			        <input type="text" placeholder="Type something…" value="" name="last_name">
	        </div>
	        <div class="control-group">
		        	<label>Email</label>
			        <input type="text" placeholder="Type something…" value="" name="email">
	        </div>
	        <div class="control-group">
		        	<label>Password</label>
			        <input type="password" placeholder="******" value="" name="password">
	        </div>
	        <div class="control-group">
		        	<label>Role</label>
			        	<?php $i = 0; foreach($roles as $role) { ?>
			        	<label class="checkbox"><input  type="checkbox" name="role_id[<?php echo $role['id']; ?>]" value="<?php echo $role['id']; ?>"/><?php echo $role['name']; ?></label>
			        	<?php $i++; } ?>
	        </div>
	        <div class="control-group">
		        	<label>Status</label>
			        <select id="active" name="active">
			        	<option value="0">In-Active</option>
			        	<option value="1">Active</option>
			        </select>
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
  