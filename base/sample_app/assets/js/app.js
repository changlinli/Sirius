var App = Backbone.Model.extend({
	initialize: function() {
	},
	url: function() {
		var base = this.collection.url;
		return base;
	}
});

var AppCollection = Backbone.Collection.extend({
	initialize: function(models, options){
		this.url = options.action;
		this.options = options;
	},
	model: App,
});

var AppModalView = Backbone.View.extend({
	initialize: function() {
		//this.collection = new AppCollection({});
	},
	events: {
		"click": "render",
	},
	render: function(e) {
		e.preventDefault();
		var data = $(this.options.form).serializeArray();
		var post = new Array();
		$.each(data, function(key,val){
			post[val.name] = val.value;
		});
		this.collection = new AppCollection({},{form: this.options.form, action: this.options.action});
		this.collection.create(post, {
				success: function(collection, response) {
					var htmlv = '';
					var result = $.parseJSON(JSON.stringify(response));
					$('input, select').parent('.control-group').removeClass('error').removeClass('success');
					$('span.help-inline').remove();
					$.each(result.validator.messages, function(a, b) {
						if(a == '') {
							htmlv += b;
						}
						$(collection.collection.options.form+' input[name='+a+'], select[name='+a+']').after('<span class="help-inline">'+b+'</span>');
						$(collection.collection.options.form+' input[name='+a+'], select[name='+a+']').parent('.control-group').addClass(result.validator.reason);
					});
					
					if(result.validator.reason == 'success') {
						if(htmlv == '') {
							htmlv = 'You\'ve successfully submitted your form!';
						}
						$(collection.collection.options.form+' .alert.popup').show().removeClass('alert-error').addClass('alert-success').html(htmlv);
					}
					
					if(result.validator.reason == 'error') {
						if(htmlv == '') {
							htmlv = 'You have an error in your form, please review!';
						}
						$(collection.collection.options.form+' .alert.popup').show().addClass('alert-error').html(htmlv);
					}
					
					if(result.validator.redirect != null && result.validator.redirect != '') {
						setTimeout(function(){
							window.location.href=result.validator.redirect;
						}, 2000);
					}
					
					$('body').animate({scrollTop:$('.container').offset().top},250);
				},
				error: function(response) {
					var htmlv = '';
					if(htmlv == '') {
						htmlv = 'You have an error in your form, please review!';
					}
					$(collection.collection.options.form+' .alert.popup').show().addClass('alert-error').html(htmlv);
				}
		});
	}
});

var AppView = Backbone.View.extend({
	initialize: function() {
		//this.collection = new AppCollection({});
	},
	events: {
		"click": "render",
	},
	
	load: function() {
		this.collection = new AppCollection({},{form: this.options.form, action: this.options.action});
		this.collection.create({}, {
				success: function(collection, response) {
					var htmlv = '';
					var result = $.parseJSON(JSON.stringify(response));
					var form = collection.collection.options.form;
					$.each(response.data, function(key, val) {
						if(key.search('image') > 0) {
							$(form + ' #'+key).attr('src',val);
						}
						$(form+' input[name='+key+']').val(val);
					});
				},
				error: function(response) {
					var htmlv = '';
					if(htmlv == '') {
						htmlv = 'You have an error in your form, please review!';
					}
					$('.alert').show().removeClass('alert-warning').removeClass('alert-success').addClass('alert-error').html(htmlv);
				}
		});
	},
	render: function(e) {
		console.log(this.options);
		var data = $(this.options.form).serializeArray();
		var post = new Array();
		$.each(data, function(key,val){
			post[val.name] = val.value;
		});
		this.collection = new AppCollection({},{form: this.options.form, action: this.options.action});
		this.collection.create(post, {
				success: function(response) {
					var htmlv = '';
					var result = $.parseJSON(JSON.stringify(response));
					$('input, select').parent('.control-group').removeClass('error').removeClass('success');
					$('span.help-inline').remove();
					$.each(result.validator.messages, function(a, b) {
						if(a == '') {
							htmlv += b;
						}
						$('input[name='+a+'], select[name='+a+']').after('<span class="help-inline">'+b+'</span>');
						$('input[name='+a+'], select[name='+a+']').parent('.control-group').addClass(result.validator.reason);
					});
					
					if(result.validator.reason == 'success') {
						if(htmlv == '') {
							htmlv = 'You\'ve successfully submitted your form!';
						}
						$('.alert').show().removeClass('alert-warning').removeClass('alert-error').addClass('alert-success').html(htmlv);
					}
					
					if(result.validator.reason == 'warning') {
						if(htmlv == '') {
							htmlv = 'There is a warning for your form, please review!';
						}
						$('.alert').show().removeClass('alert-error').removeClass('alert-success').addClass('alert-warning').html(htmlv);
					}
					
					if(result.validator.reason == 'error') {
						if(htmlv == '') {
							htmlv = 'You have an error in your form, please review!';
						}
						$('.alert').show().removeClass('alert-warning').removeClass('alert-success').addClass('alert-error').html(htmlv);
					}
					
					if(result.validator.redirect != null && result.validator.redirect != '') {
						setTimeout(function(){
							window.location.href=result.validator.redirect;
						}, 2000);
					}
					
					$('body').animate({scrollTop:$('.container').offset().top},250);
				},
				error: function(response) {
					var htmlv = '';
					if(htmlv == '') {
						htmlv = 'You have an error in your form, please review!';
					}
					$('.alert').show().addClass('alert-error').html(htmlv);
				}
		});
	}
});
