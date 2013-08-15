var AppView;
var ProfileView = Backbone.View.extend({
	initialize: function() {
		el 			= this.options.el;
		action		= this.options.action;
		files_opts 	= this.options.files;
		ids			= this.options.ids;
		resize		= this.options.size;
		
		var uploader;
		
		this.upload_init();
	},
	
	events: {
		"click #upload": "upload",
	},
	
	upload_init: function() {
		uploader = new plupload.Uploader({
			runtimes : 'html5,flash',
			browse_button : ids.browse_button,
			container : ids.container,
			max_file_size : files_opts.max_file_size,
			url : action,
			flash_swf_url : '/assets/js/libs/plupload/plupload.flash.swf',
			silverlight_xap_url : '/assets/js/libs/plupload/plupload.silverlight.xap',
			filters : [
				{title : "Image Files", extensions : files_opts.extensions},
			],
			unique_names : true,
			resize : resize
		});
	
		uploader.bind('Init', function(up, params) {
			//do nothing
		});
	
		uploader.init();
	
		uploader.bind('QueueChanged',function(up){
			if(up.files.length >= files_opts.max_files) {
				//ability to replace file
				//no more files allowed
			}
			
			if(files_opts.max_files == 1) {
				uploader.start();
				//start upload
			}
		});
		
		uploader.bind('FilesAdded', function(up, files) {
			if(files_opts.max_files == 1) {
				//one file
				$.each(files, function(i, file) {
					extension = file.name.split('.').reverse();
					filename = file.id+'.'+extension[0];
					$('#'+ids.container).attr('src', '/uploads/'+filename);
				});
			} else {
				//multiple files
				$.each(files, function(i, file) {
					$('#files').append(
						'<div id="' + file.id + '">' +
						file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
					'</div>');
					filename = file.id+'.'+extension[0];
				});
			}
			
			up.refresh(); // Reposition Flash/Silverlight
		});
	
		uploader.bind('UploadProgress', function(up, file) {
			$('#' + file.id + " b").html(file.percent + "%");
		});
	
		uploader.bind('Error', function(up, err) {
			$('#files').append("<div>Error: " + err.code +
				", Message: " + err.message +
				(err.file ? ", File: " + err.file.name : "") +
				"</div>"
			);
	
			up.refresh(); // Reposition Flash/Silverlight
		});
	
		uploader.bind('FileUploaded', function(up, file) {
			//$('#' + file.id + " b").html("100%");
			extension = file.name.split('.').reverse();
			filename = file.id+'.'+extension[0];
			$('#'+ids.container).attr('src', '/uploads/'+filename);
		});
	},
	
	upload: function(e) {
		//upload function
		e.preventDefault();
		uploader.start();
	},
});

$(function(){
	AppView = new AppView({form: '#profile', el: $('#profile .submit'), action: '/settings/profile_update.json'});
	var profile_load = new ProfileView({
							el: $('#profile'),
							action: '/settings/profile_image.json/140/140',
							files: { max_files: 1, max_file_size: '10mb', extensions: 'jpg,gif,png' }, 
							ids: { container: 'profile_image', browse_button: 'browse_button' },
							size: { width : 300, height : 300, quality : 90},
						});
});
