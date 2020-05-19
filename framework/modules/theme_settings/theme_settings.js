( function( $ ) {

	wp.customize( 'marlon_theme_primary_color', function( value ) {
		value.bind( function( newval ) {
			$( 'html' ).get(0).style.setProperty( '--color-primary', newval );
		} );
	} );

	wp.customize( 'marlon_theme_secondary_color', function( value ) {
		value.bind( function( newval ) {
			$( 'html' ).get(0).style.setProperty( '--color-secondary', newval );
		} );
	} );

	wp.customize( 'marlon_theme_special_color', function( value ) {
		value.bind( function( newval ) {
			$( 'html' ).get(0).style.setProperty( '--color-special', newval );
		} );
	} );

	wp.customize( 'marlon_theme', function( value ) {
		value.bind( function( newval ) {
			$( 'html' ).attr( 'data-theme', newval );
		} );
	} );

} )( jQuery );
