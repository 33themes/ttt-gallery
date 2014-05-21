/**
* Custom Gallery Setting
*/
( function( $ ) {
	var media = wp.media;

	// Wrap the render() function to append controls
	media.view.Settings.Gallery = media.view.Settings.Gallery.extend({
		render: function() {
	
			media.view.Settings.prototype.render.apply( this, arguments );
	
			// Append the custom template
			this.$el.append( media.template( 'ttt-gallery-setting' ) );
	
			var _desc = ""+ $( media.gallery.defaults.ttt_gallery_el ).trigger('tttgallery:description').attr('data-description');
			if ( _desc == 'undefined') _desc = '';

			this.$el.find('.tttgallery-description').val( _desc );

			$( media.gallery.defaults.ttt_gallery_el ).data( 'ttt_gallery_description',this.$el.find('.tttgallery-description') );

			//this.model.set('ttt_gallery_description', this.$el.find('.tttgallery-description') );
			//this.update.apply( this, [ 'tttgallery-description '] );
			return this;
		}
	} );
} )( jQuery );

/**
* Create gallery
*/

;(function ( $ ) {

	$.fn.ttt_gallery_create = function( options ) {
		
		// This is the easiest way to have default options.
		var settings = $.extend({
			p: '.tttgallery-content'
		}, options );

		return $(this).each(function() {

			$(this).on('click',function(event) {
				event.preventDefault();

				var _p = $( settings.p );

				_.extend(wp.media.gallery.defaults, {
					ttt_gallery_el: _p,
					ttt_gallery_description: ''
				});
			
				var frame = wp.media.gallery.edit('[gallery ids="-1"]');
				frame.on('update', function(obj) {
					var obj = obj;

					var description =  $(_p).data('ttt_gallery_description');

					var imageList = [];
					var mediasIds = [];

					$.each(obj.models, function(id,val) {
						imageList.push( val.attributes );
						mediasIds.push( val.attributes.id );
					});
			
					var data = {
						'action': 'ttt-gallery_create',
						'post': tttgalleryConf.post,
						'medias': mediasIds,
						'description': $(description).val()
					};

					$.ajax({
						url: tttgalleryConf.ajax,
						type: 'post',
						context: _p,
						data: data,
						complete: function(data) {
							response = jQuery.parseJSON(data.responseText);

							if ( response.success !== true ) {
								console.log( 'error', response );
								return false;
							}

							$(this).trigger('tttgallery:addItem', {
								id: response.id,
								medias: imageList,
								description: $(description).val(),
								created_at: '',
								updated_at: '',
								used_at: ''
							});
						}
					});

					
					// $.post(tttgalleryConf.ajax, data, function(response) {
			
					// 	console.log( response );
			
					// 	var template = $("#tttgallery-tmpl-metabox").html();
					// 	$(".tttgallery-content").append(_.template( template , { id: response , images:imageList } ));
					// 	$('a.tttgallery-invoke-edit').unbind('click').click( tttgallery_invoke_edit );
					// 	$('a.tttgallery-invoke-remove').ttt_gallery_removepost();
					// })
				});

			});

			return this;
		});
	}
} )( jQuery );


/**
* Edit Gallery
*/

;(function ( $ ) {

	$.fn.ttt_gallery_edit = function( options ) {
		
		// This is the easiest way to have default options.
		var settings = $.extend({
		}, options );

		return $(this).each(function() {
			
			$(this).unbind('click');

			$(this).on('click',function(event) {
				event.preventDefault();
				
				var _p = $(this).parents('.tttgallery-gallery');

				//var ids = settings.ids.call(_p);
				var ids = $( _p ).trigger('tttgallery:ids').attr('data-items');
				if ( $.type(ids) == 'array' ) {
					ids = ids.join(',');
				}



				_.extend(wp.media.gallery.defaults, {
					ttt_gallery_el: _p,
					ttt_gallery_description: ''
				});

				var frame = wp.media.gallery;
				var frame_edit = frame.edit('[gallery ids="' + ids + '"]');
				frame_edit.on('update', function(obj) {

					var obj = obj;

					var description =  $(_p).data('ttt_gallery_description');


					var ids = [];
					var imageList = [];
					$.each(obj.models, function( _i,val) {
						ids.push( val.attributes.id );
						imageList.push( val.attributes );
					});

					$(_p).trigger('tttgallery_update:start', {
						ids: ids,
						medias: imageList,
						description: $(description).val(),
						created_at: '',
						updated_at: '',
						used_at: ''
					});


					var data = {
						'action': 'ttt-gallery_update',
						'id': $( _p ).attr('data-galleryid'),
						'post': $( _p ).attr('data-post'),
						'description': $(description).val(),
						'medias': ids
					};

					$.ajax({
						url: tttgalleryConf.ajax,
						type: 'post',
						context: _p,
						data: data,
						complete: function(data) {
							response = jQuery.parseJSON(data.responseText);
							if ( response.success !== true ) {
								console.log( 'error', response );
								return false;
							}

							$(this).trigger('tttgallery_update:done', {
								id: $(this).attr('data-galleryid'),
								medias: imageList,
								description: $(description).val(),
								created_at: '',
								updated_at: '',
								used_at: ''
							});
						}
					});

				});

			});
		});
	};
}( jQuery ));

;(function ( $ ) {

	$.fn.ttt_gallery_remove = function( options ) {
		
		// This is the easiest way to have default options.
		var settings = $.extend({
		}, options );

		return $(this).each(function() {
			
			$(this).unbind('click');

			$(this).on('click',function(event) {
				event.preventDefault();
				
				if (!confirm('are sure you want to delete this gallery?')) return false;
				
				var _p = $(this).parents('.tttgallery-gallery');
				var data = {
					'action': 'ttt-gallery_remove',
					'id': $( _p ).attr('data-galleryid'),
				};

				$.ajax({
					url: tttgalleryConf.ajax,
					type: 'post',
					context: _p,
					data: data,
					complete: function(data) {
						response = jQuery.parseJSON(data.responseText);
						if ( response.success !== true ) {
							console.log( error, response );
							return false;
						}
						$(_p).fadeOut();
					}
				});
			});
		});
	};
}( jQuery ));

;(function ( $ ) {

	$.fn.ttt_gallery_removepost = function( options ) {
		
		// This is the easiest way to have default options.
		var settings = $.extend({
		}, options );

		return $(this).each(function() {
			
			$(this).unbind('click');

			$(this).on('click',function(event) {
				event.preventDefault();
				
				if (!confirm('are sure you want to delete this gallery?')) return false;
				
				var _p = $(this).parents('.tttgallery-gallery');
				var data = {
					'action': 'ttt-gallery_removepost',
					'id': $( _p ).attr('data-galleryid'),
					'post': $( _p ).attr('data-post')
				};

				$.ajax({
					url: tttgalleryConf.ajax,
					type: 'post',
					context: _p,
					data: data,
					complete: function(data) {
						response = jQuery.parseJSON(data.responseText);
						if ( response.success !== true ) {
							console.log( error, response );
							return false;
						}
						$(this).fadeOut().remove();
					}
				});
			});
		});
	};
}( jQuery ));
