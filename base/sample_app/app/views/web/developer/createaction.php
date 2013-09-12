<script type="text/javascript">
$(function(){
	$('.btn').click(function(e){
		e.preventDefault();
		var data = $('#create-action').serializeArray();
		result = post_action('/manage/createaction_submit.json', data);
	});
});
</script>
  <div class="row">
    <div class="span12">
      <div class="alert" style="display: none;">
        <strong>Well done!</strong> You successfully read this important alert message.
      </div>
    </div>
  </div>
  <div class="row">
    <div class="span12">
      <h3>Create a View</h3>
      <p>Creating the view creates the basic action, and template for you.</p>
      <form id="create-action" method="POST" class="well">
      	<div class="control-group">
        <label>Select an Object</label>
    	<select name="object_id" class="span3" id="select01">
        	<option value="">Select an Object</option>
        	<?php if($objects) { foreach($objects as $object) { ?>
            <option value="<?php echo $object['id']; ?>"><?php echo $object['name']; ?></option>
            <?php }}  ?>
      	</select>
      </div>
      	<div class="control-group">
        <label>View Name</label>
        <input name="value" type="text" class="span3" placeholder="Type something…">
        <p class="help-block">e.g. User Listing, Create a User, Single User View</p>
        </div>
        <div class="control-group">
        <label>Select a Layout</label>
        	<select name="template" class="span3" id="select01">
	        	<option value="">Select a Layout</option>
	            <option value="layout">Layout</option>
	            <option value="blank">Blank</option>
	            <option value="raw">Empty</option>
          	</select>
          	<p class="help-block">This is considered the "Frame" of the view. e.g. The layout typically contains the menu bar, the footer.</p>
          </div>
          <div class="control-group">
        <button type="submit" class="btn">Submit</button>
        </div>
      </form>
    </div><!-- /.span -->
  </div>