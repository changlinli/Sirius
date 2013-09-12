<?php $this->registry->templates->snippet('messages'); ?>
<div class="row">
	<div class="span12">
		<div class="pull-left">
			<h3>Sources</h3>
		</div>
		<div class="pull-right">
	    	<a href="#add_model" data-toggle="modal" class="btn btn-mini btn-primary">Add Source</a>
		</div>
	</div>
</div>
<div class="row">
    <div class="span12">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Source Name</th>
            <th>Options</th>
          </tr>
        </thead>
        <tbody>
          <?php if(is_array($data) && count($data) > 0) { foreach($data as $item) { ?>
          <tr>
            <td><?php echo $item['id']; ?></td>
            <td><?php echo $item['name']; ?></td>
            <td><div class="btn-group">
            	<form class="options" id="options-<?php echo $item['id']; ?>">
            		<input type="hidden" name="id" value="<?php echo $item['id']; ?>">
            		<input type="hidden" name="shortname" value="<?php echo $item['shortname']; ?>">
            		<input type="hidden" name="name" value="<?php echo $item['name']; ?>">
            		<input type="hidden" name="values" value="<?php echo $item['values']; ?>">
	          		<a data-id="<?php echo $item['id']; ?>" class="btn btn-mini edit" href="#edit_modal" data-toggle="modal"><i class="icon-cog"></i> Edit</a>
	          		<a data-id="<?php echo $item['id']; ?>" class="btn btn-mini btn-danger remove"><i class="icon-remove icon-white"></i></a>
          		</form>
        	</div></td>
          </tr>
          <?php } } else { ?>
          	<tr>
            	<td colspan="4">There are currently no sources.</td>
          	</tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  
  <?php require_once (APP_ROOT.'views/web/layouts/includes/paging.php');  ?>
  
<!-- Modal -->
<div id="add_model" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form id="add">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				×
			</button>
			<h3 id="myModalLabel">Add Source</h3>
		</div>
		<div class="modal-body">
			<div class="row-fluid">
			<?php $this->registry->templates->snippet('messages_modal'); ?>
			<div class="control-group">
		        	<label>Source Name</label>
			        <input class="span12" type="text" placeholder="Type something…" value="" name="name">
	        </div>
	        <div class="control-group">
		        	<label>Short Name</label>
			        <input class="span12" type="text" placeholder="Type something…" value="" name="shortname">
	        </div>
	        <div class="control-group">
		        	<label>Values</label>
			        <input class="span12" type="text" placeholder="Type something…" value="" name="values">
	        </div>
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
  
<div id="edit_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form id="edit">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			×
		</button>
		<h3 id="myModalLabel">Edit Source</h3>
	</div>
	<div class="modal-body">
		<div class="row-fluid">
		<?php $this->registry->templates->snippet('messages_modal'); ?>
			<input type="hidden" placeholder="Type something…" value="" name="id">
			<div class="control-group">
		        	<label>Name</label>
			        <input class="span12" type="text" placeholder="Type something…" value="" name="name">
	        </div>
	        <div class="control-group">
		        	<label>Short Name</label>
			        <input class="span12" type="text" placeholder="Type something…" value="" name="shortname">
	        </div>
	        <div class="control-group">
		        	<label>Values</label>
			        <input class="span12" type="text" placeholder="Type something…" value="" name="values">
	        </div>
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
  