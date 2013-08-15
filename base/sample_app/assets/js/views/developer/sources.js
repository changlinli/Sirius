var View = Backbone.View.extend({
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
		this.AppView = new AppView({form: '#options-'+id, el: $('#options-'+id), action: '/developer/delete_source.json'});
		this.AppView.render();
	}
});

$(function(){
	var add 	= new AppModalView({form: '#add', el: $('#add .submit'), action: '/developer/add_source.json'});
	var edit	= new AppModalView({form: '#edit', el: $('#edit .submit'), action: '/developer/edit_source.json'});
	var cache 	= new View({form: '#options', el: $('.options'), load: '#edit'});
});