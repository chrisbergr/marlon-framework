( function( $ ) {
	'use strict';

	 function getUrlParameter( name ) {
		name = name.replace( /[\[]/, '\\[' ).replace( /[\]]/, '\\]' );
		var regex = new RegExp( '[\\?&]' + name + '=([^&#]*)' );
		var results = regex.exec( location.search );
		return results === null ? '' : decodeURIComponent( results[1].replace( /\+/g, ' ' ) );
	};

	 $( document ).ready( function() {

		 var set_post_format = getUrlParameter( 'set_post_format' );
		 if ( set_post_format ) {
			 console.log( set_post_format );
			 $( 'input[type="radio"]#post-format-' + set_post_format ).prop( 'checked', 'checked' );
		 }

	 } );

} )( jQuery );
