( function( $ ) {

	$.fn.MarlonPrimaryCategory = function( options ) {

		$( this ).append( '<input type="hidden" name="marlon_primary_category" id="marlon_primary_category" />' );
		var $this = this;

		return $this.each( function() {
			$( this ).find( '.tabs-panel label input[type=checkbox]' ).each( function() {
				var $label = $( this ).parent( 'label' ),
					$li    = $label.parent( 'li' ),
					link  = ' <a href="#" class="make_primary">Primary</a>';
				$label.after( link );
				if( options.current === this.value ) {
					$label.css( 'fontWeight', 'bold' );
					$( '#marlon_primary_category' ).val( options.current );
				}
				$li.hover( make_primary_hover_in, make_primary_hover_out );
				$( '.make_primary', $li ).click( make_primary );
				$( 'input', $label ).on( 'change', change_category );
			} );
		} );

		function reset_primary() {
			$( 'ul li label', $this ).css( 'fontWeight', '' );
		};

		function make_primary_hover_in() {
			$( this ).find( 'a.make_primary:first' ).show();
		};

		function make_primary_hover_out() {
			$( this ).find( 'a.make_primary' ).hide();
		};

		function make_primary( e ) {
			e.preventDefault();
			reset_primary();
			var current = $( this ).prev( 'label' ).find( 'input' ).val();

			$( '#in-popular-category-' + current + ', #in-category-' + current ).each( function() {
				$( this ).parent( 'label' ).css( 'fontWeight', 'bold' );
				$( this ).attr( 'checked', true );
			} );

			$( '#marlon_primary_category' ).val( current );
		};

		function change_category() {
			var current = $( '#marlon_primary_category' ).val();
			if ( ! this.checked && current === this.value ) {
				reset_primary();
				$( '#marlon_primary_category' ).val( '' );
			}
		};

	}
} )( jQuery );
