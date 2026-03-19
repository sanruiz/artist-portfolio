/**
 * Admin JavaScript for Artist Portfolio plugin.
 *
 * @package ArtistPortfolio
 */

jQuery( document ).ready( function( $ ) {
	'use strict';

	var artworkGallery = {
		init: function() {
			this.bindEvents();
		},

		bindEvents: function() {
			$( '#sa-add-gallery-images' ).on( 'click', this.openMediaUploader );
			$( document ).on( 'click', '.sa-remove-image', this.removeImage );
		},

		openMediaUploader: function( e ) {
			e.preventDefault();

			var mediaUploader = wp.media({
				title: 'Select Gallery Images',
				button: {
					text: 'Add to Gallery'
				},
				multiple: true,
				library: {
					type: 'image'
				}
			});

			mediaUploader.on( 'select', function() {
				var attachments = mediaUploader.state().get( 'selection' ).toJSON();
				artworkGallery.addImagesToGallery( attachments );
			});

			mediaUploader.open();
		},

		addImagesToGallery: function( attachments ) {
			var $container = $( '.sa-gallery-images' );
			var $hiddenField = $( '#sa_artwork_gallery' );
			var currentIds = $hiddenField.val() ? $hiddenField.val().split( ',' ) : [];

			$.each( attachments, function( index, attachment ) {
				// Skip if image already in gallery
				if ( currentIds.indexOf( attachment.id.toString() ) !== -1 ) {
					return;
				}

				currentIds.push( attachment.id );

				var imageHtml = '<div class="sa-gallery-image" data-id="' + attachment.id + '">';
				
				// Use thumbnail size if available, otherwise use the full URL
				var imageUrl = attachment.sizes && attachment.sizes.thumbnail ? 
					attachment.sizes.thumbnail.url : attachment.url;
				
				imageHtml += '<img src="' + imageUrl + '" alt="' + attachment.alt + '" width="150" height="150" />';
				imageHtml += '<button type="button" class="sa-remove-image" title="Remove image">×</button>';
				imageHtml += '</div>';

				$container.append( imageHtml );
			});

			$hiddenField.val( currentIds.join( ',' ) );
		},

		removeImage: function( e ) {
			e.preventDefault();
			
			var $imageDiv = $( this ).closest( '.sa-gallery-image' );
			var imageId = $imageDiv.data( 'id' ).toString();
			var $hiddenField = $( '#sa_artwork_gallery' );
			var currentIds = $hiddenField.val() ? $hiddenField.val().split( ',' ) : [];

			// Remove the ID from the array
			var index = currentIds.indexOf( imageId );
			if ( index > -1 ) {
				currentIds.splice( index, 1 );
			}

			// Update hidden field
			$hiddenField.val( currentIds.join( ',' ) );

			// Remove the image div
			$imageDiv.remove();
		}
	};

	// Initialize gallery functionality
	artworkGallery.init();

	// Make gallery sortable
	if ( $.fn.sortable ) {
		$( '.sa-gallery-images' ).sortable({
			items: '.sa-gallery-image',
			cursor: 'move',
			update: function() {
				var ids = [];
				$( this ).find( '.sa-gallery-image' ).each( function() {
					ids.push( $( this ).data( 'id' ) );
				});
				$( '#sa_artwork_gallery' ).val( ids.join( ',' ) );
			}
		});
	}

	// Add some visual feedback for drag and drop
	$( document ).on( 'mouseenter', '.sa-gallery-image', function() {
		$( this ).css( 'opacity', '0.8' );
	}).on( 'mouseleave', '.sa-gallery-image', function() {
		$( this ).css( 'opacity', '1' );
	});
});
