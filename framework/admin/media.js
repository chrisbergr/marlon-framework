 jQuery(document).ready(function($){
	'use strict';
	var metaImageFrame;
	$( 'body' ).click(function(e) {
		var btn = e.target;
		if ( !btn || !$( btn ).attr( 'data-media-uploader-target' ) ) return;
		var field = $( btn ).data( 'media-uploader-target' );
		e.preventDefault();
		metaImageFrame = wp.media.frames.metaImageFrame = wp.media({
			title: meta_image.title,
			button: { text:  'Use this file' },
		});
		metaImageFrame.on('select', function() {
			var media_attachment = metaImageFrame.state().get('selection').first().toJSON();
			$( field ).val(media_attachment.url);
		});
		metaImageFrame.open();
	});
});
