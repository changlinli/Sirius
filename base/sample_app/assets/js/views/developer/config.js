var ConfigView = Backbone.View.extend({
	initialize: function() {
		form 	= this.options.form;
		el 		= this.options.el;
		load 	= this.options.load;
	},
	events: {
		"click a.edit": "edit",
		"click a.remove": "remove",
	},
	edit: function(e) {
		var id = $(e.currentTarget).attr('data-id');
		
		var data = $(form+'-'+id).serializeArray();
		$.each(data, function(key, val) {
			if(val.name == 'name') {
				$(load+' #config-name').html(val.value);
			}
			$(load+' input[name='+val.name+']').val(val.value);
		});
	},
	
	remove: function(e) {
		var id = $(e.currentTarget).attr('data-id');
		this.AppView = new AppView({form: '#options-'+id, el: $('#options-'+id), action: '/developer/delete_config.json'});
		this.AppView.render();
	}
});

$(function(){
	var project_name 		= new AppView({form: '#project_name', el: $('#project_name .submit'), action: '/developer/update_config.json'});
	var site_email 			= new AppView({form: '#site_email', el: $('#site_email .submit'), action: '/developer/update_config.json'});
	var default_controller 	= new AppView({form: '#default_controller', el: $('#default_controller .submit'), action: '/developer/update_config.json'});
	var default_action 		= new AppView({form: '#default_action', el: $('#default_action .submit'), action: '/developer/update_config.json'});
	var google_analytics 	= new AppView({form: '#google_analytics', el: $('#google_analytics .submit'), action: '/developer/update_config.json'});
	
	var add_config 			= new AppModalView({form: '#add_config', el: $('#add_config .submit'), action: '/developer/add_config.json'});
	var update_config		= new AppModalView({form: '#edit_config', el: $('#edit_config .edit_submit'), action: '/developer/update_config.json'});
	var config 				= new ConfigView({form: '#options', el: $('.options'), load: '#edit_config'});
});
