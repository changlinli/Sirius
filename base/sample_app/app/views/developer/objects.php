<div class="row">
	<div class="span12">
	<div class="pull-left">
	<h3>Objects</h3>
	</div>
	<div class="pull-right">
		<p>
        	<a href="/manage/createobject"><button class="btn btn-mini btn-primary">Create an Object</button></a>
      	</p>
	</div>
	</div>
</div>
<div class="row">
    <div class="span12">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Status</th>
            <th>Options</th>
          </tr>
        </thead>
        <tbody>
          <?php if(is_array($objects) && count($objects['data']) > 0) { foreach($objects['data'] as $object) { ?>
          <tr>
            <td><?php echo $object['id']; ?></td>
            <td><?php echo $object['name']; ?></td>
            <td><?php echo ($object['status'] == 1) ? 'Active' : 'In-Active'; ?></td>
            <td><div class="btn-group">
          		<a class="btn btn-mini" href="/manage/editobject/<?php echo $object['id']; ?>"><i class="icon-cog"></i> Edit</a>
        	</div></td>
          </tr>
          <?php } } else { ?>
          	<tr>
            	<td colspan="5">There are currently no objects.</td>
          	</tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <div class="pagination pull-right">
	    <ul>
          <li class="active"><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li><a href="#">3</a></li>
          <li><a href="#">4</a></li>
          <li><a href="#">5</a></li>
        </ul>
	  </div>
  </div>
  