var RoleView = Backbone.View.extend({
	initialize: function() {
		el = this.options.el;
		form = this.options.form;
		load = this.options.load;
	},
	events: {
		"click a.edit": "edit",
		"click a.remove": "remove",
	},
	edit: function(e) {
		var id 		= $(e.currentTarget).attr('data-id');
		var data 	= $(form+'-'+id).serializeArray();
		$.each(data, function(key, val) {
			if(val.name == 'role_id') {
				$(load+ ' select[name='+val.name+'] option[value="'+val.value+'"]').prop("selected",true);
			}
			
			if(val.name == 'active') {
				$(load+ ' select[name='+val.name+'] option[value="'+val.value+'"]').prop("selected",true);
			}
			
			$(load+' input[name='+val.name+']').val(val.value);
		});
	},
	
	remove: function(e) {
		var id 		 = $(e.currentTarget).attr('data-id');
		this.AppView = new AppView({form: '#options-'+id, el: $('#options-'+id), action: '/admin/delete_role.json'});
		this.AppView.render();
	}
});

$(function(){
	var add_role 	= new AppModalView({form: '#add_item', el: $('#add_item .submit'), action: '/admin/add_role.json'});
	var edit_role	= new AppModalView({form: '#edit_item', el: $('#edit_item .submit'), action: '/admin/update_role.json'});
	var user 		= new RoleView({form: '#options', el: $('.options'), load: '#edit_item'});
});
