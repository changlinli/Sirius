var UserView = Backbone.View.extend({
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
			
			if(val.name == 'role_id') {
				var role_id = val.value.split(',');
				$('input[type=checkbox]').prop("checked",false);
				$.each(role_id, function(key, val) {
					var item_id = load+ ' input[name=\'role_id['+val+']\']';
					$(item_id).prop("checked",true);
				});
			}
			
			$(load+' input[name='+val.name+']').val(val.value);
		});
	},
	
	remove: function(e) {
		var id 		 = $(e.currentTarget).attr('data-id');
		this.AppView = new AppView({form: '#options-'+id, el: $('#options-'+id), action: '/admin/delete_user.json'});
		this.AppView.render();
	}
});

$(function(){
	var add_user 	= new AppModalView({form: '#add_item', el: $('#add_item .submit'), action: '/admin/add_user.json'});
	var edit_user	= new AppModalView({form: '#edit_item', el: $('#edit_item .submit'), action: '/admin/update_user.json'});
	var user 		= new UserView({form: '#options', el: $('.options'), load: '#edit_item'});
});
