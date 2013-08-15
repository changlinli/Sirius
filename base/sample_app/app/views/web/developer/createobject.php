<script type="text/javascript">
$(function(){
	$('.btn').click(function(e){
		e.preventDefault();
		var data = $('#create-object').serializeArray();
		result = post_action('/manage/createobject_submit.json', data);
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
      <h3>Create an Object</h3>
      <p>Creating an object creates the basic models, views, and controllers for you.</p>
      <form id="create-object" method="POST" class="well">
      	<div class="control-group">
	        <label>Object Name</label>
	        <input name="value" type="text" class="span3" placeholder="Type something…">
	        <p class="help-block">e.g. Users, Campaigns, Offers</p>
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