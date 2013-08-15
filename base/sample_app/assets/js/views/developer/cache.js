var CacheView = Backbone.View.extend({
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
			if(val.name == 'name') {
				$(load+' #config-name').html(val.value);
			}
			$(load+' input[name='+val.name+']').val(val.value);
		});
	},
	
	remove: function(e) {
		var id 		 = $(e.currentTarget).attr('data-id');
		this.AppView = new AppView({form: '#options-'+id, el: $('#options-'+id), action: '/developer/delete_cache.json'});
		this.AppView.render();
	}
});

$(function(){
	var add_cache 	= new AppModalView({form: '#add_cache', el: $('#add_cache .submit'), action: '/developer/add_cache.json'});
	var update_cache= new AppModalView({form: '#edit_cache', el: $('#edit_cache .submit'), action: '/developer/update_cache.json'});
	var cache 		= new CacheView({form: '#options', el: $('.options'), load: '#edit_cache'});
});
