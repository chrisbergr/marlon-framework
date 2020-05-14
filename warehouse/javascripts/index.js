( function( $ ) {
	'use strict';

	var active_gallery_item_class = 'active';

	var gallery_class = 'marlon-gallery';
	var gallery_item_class = 'marlon-gallery-item';

	var dots_html = '<ul></ul>';
	var dot_html = '<li></li>';
	var dots_class = 'gallery-nav-dots';
	var dot_class = 'gallery-nav-dot';
	var dot_class_active = 'active-dot';

	var arrow_html = '<div></div>';
	var arrow_left_class = 'gallery-nav-left';
	var arrow_right_class = 'gallery-nav-right';

	function setup_gallery( $container ) {

		var container_width = $container.width();
		var index = 0;

		$container.find( '.' + gallery_item_class ).each( function() {
			var left = container_width * index;
			$( this ).css( { 'transform': 'translate(' + left +'px, 0)' } );
			index++;
		} );

		var nav_left_elm = $container.find( '.' + arrow_left_class ).length;
		if( ! nav_left_elm ) {
			setup_gallery_nav_left( $container );
		}
		var nav_right_elm = $container.find( '.' + arrow_right_class ).length;
		if( ! nav_right_elm ) {
			setup_gallery_nav_right( $container );
		}

		var nav_dots_elm = $container.find( '.' + dots_class ).length;
		if( ! nav_dots_elm ) {
			setup_gallery_nav_dots( $container );
		}

	}

	function setup_gallery_nav_dots( $container ) {
		var $dots_container = $( dots_html ).addClass( dots_class );
		var $dot;
		var count = $container.find( '.' + gallery_item_class ).length;
		for( var i = 0; i < count; i++ ) {
			$dot = $( dot_html ).addClass( dot_class );
			$dots_container.append( $dot );
		}
		$container.append( $dots_container );
		$container.find( '.' + dot_class ).click( function() {
			var index = $( this ).index();
			var $next = $container.find( '.' + gallery_item_class ).eq( index );
			set_active_gallery_item( $next, $container );
		} );
	}

	function setup_gallery_nav_left( $container ) {
		var $arrow_left = $( arrow_html ).addClass( arrow_left_class );
		$container.append( $arrow_left );
		$arrow_left.click( function() {
			var $prev = $container.find( '.' + active_gallery_item_class ).prev();
			set_active_gallery_item( $prev, $container );
		} );
	}

	function setup_gallery_nav_right( $container ) {
		var $arrow_right = $( arrow_html ).addClass( arrow_right_class );
		$container.append( $arrow_right );
		$arrow_right.click( function() {
			var $next = $container.find( '.' + active_gallery_item_class ).next();
			set_active_gallery_item( $next, $container );
		} );
	}

	function set_active_gallery_item( $item, $container ) {
		var container_width = $container.width();
		$container.find( '.' + active_gallery_item_class ).removeClass( active_gallery_item_class );
		//var img_height = $item.find( 'figure' ).first().height();
		var img_height = $item.find( 'figure img' ).first()[0].clientHeight;
		$container.animate( { height: img_height }, 200 );
		$item.addClass( active_gallery_item_class );
		var left = container_width * $item.index();
		$item.parent().css( { 'transform': 'translate(-' + left +'px, 0)' } );

		$container.find( '.' + dot_class_active ).removeClass( dot_class_active );
		$container.find( '.' + dot_class ).eq( $item.index() ).addClass( dot_class_active );

		var right_elm = $item.next().length;
		if( ! right_elm ) {
			$container.find( '.' + arrow_right_class ).fadeOut( 200 );
		} else {
			$container.find( '.' + arrow_right_class ).fadeIn( 200 );
		}
		var left_elm = $item.prev().length;
		if( ! left_elm ) {
			$container.find( '.' + arrow_left_class ).fadeOut( 200 );
		} else {
			$container.find( '.' + arrow_left_class ).fadeIn( 200 );
		}
	}

	$( document ).ready( function() {

		$( '.' + gallery_class ).each( function() {
			var $container = $( this );
			var $first = $container.find( '.' + gallery_item_class ).first();
			setup_gallery( $container );
			set_active_gallery_item( $first, $container );
		} );

	} );

	$( window ).resize( function() {

		$( '.' + gallery_class ).each( function() {
			var $container = $( this );
			var $first = $container.find( '.' + active_gallery_item_class ).first();
			setup_gallery( $container );
			set_active_gallery_item( $first, $container );
		} );

	} );

} )( jQuery );
