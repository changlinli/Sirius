var KeyView = Backbone.View.extend({
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
			$(load+' input[name='+val.name+']').val(val.value);
		});
	},
	
	remove: function(e) {
		var id 		 = $(e.currentTarget).attr('data-id');
		this.AppView = new AppView({form: '#options-'+id, el: $('#options-'+id), action: '/developer/delete_key.json'});
		this.AppView.render();
	}
});

$(function(){
	var add_key 	= new AppModalView({form: '#add_key', el: $('#add_key .submit'), action: '/developer/add_key.json'});
	var update_key	= new AppModalView({form: '#edit_key', el: $('#edit_key .submit'), action: '/developer/update_key.json'});
	var cache 		= new KeyView({form: '#options', el: $('.options'), load: '#edit_key'});
});
