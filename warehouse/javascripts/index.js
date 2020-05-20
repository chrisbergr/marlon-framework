( function( $ ) {
	'use strict';

	var $document = $( document );
	var $window   = $( window );

	var class_prefix               = '.';
	var duration                   = 200;
	var image_elm                  = 'figure img';
	var active_gallery_item_class  = 'active';
	var _active_gallery_item_class = class_prefix + active_gallery_item_class;

	var gallery_class       = 'marlon-gallery';
	var _gallery_class      = class_prefix + gallery_class;
	var gallery_item_class  = 'marlon-gallery-item';
	var _gallery_item_class = class_prefix + gallery_item_class;

	var dots_html         = '<ul></ul>';
	var dot_html          = '<li></li>';
	var dots_class        = 'gallery-nav-dots';
	var _dots_class       = class_prefix + dots_class;
	var dot_class         = 'gallery-nav-dot';
	var _dot_class        = class_prefix + dot_class;
	var dot_class_active  = 'active-dot';
	var _dot_class_active = class_prefix + dot_class_active;

	var arrow_html         = '<div></div>';
	var arrow_left_class   = 'gallery-nav-left';
	var _arrow_left_class  = class_prefix + arrow_left_class;
	var arrow_right_class  = 'gallery-nav-right';
	var _arrow_right_class = class_prefix + arrow_right_class;

	function setup_gallery( $container ) {

		var container_width = $container.width();
		var index = 0;

		$container.find( _gallery_item_class ).each( function() {
			var left = container_width * index;
			$( this ).css( { 'transform': 'translate(' + left +'px, 0)' } );
			index++;
		} );

		var nav_left_elm = $container.find( _arrow_left_class ).length;
		if( ! nav_left_elm ) {
			setup_gallery_nav_left( $container );
		}
		var nav_right_elm = $container.find( _arrow_right_class ).length;
		if( ! nav_right_elm ) {
			setup_gallery_nav_right( $container );
		}

		var nav_dots_elm = $container.find( _dots_class ).length;
		if( ! nav_dots_elm ) {
			setup_gallery_nav_dots( $container );
		}

	}

	function setup_gallery_nav_dots( $container ) {
		var $dots_container = $( dots_html ).addClass( dots_class );
		var $dot;
		var count = $container.find( _gallery_item_class ).length;
		for( var i = 0; i < count; i++ ) {
			$dot = $( dot_html ).addClass( dot_class );
			$dots_container.append( $dot );
		}
		$container.append( $dots_container );
		$container.find( _dot_class ).click( function() {
			var index = $( this ).index();
			var $next = $container.find( _gallery_item_class ).eq( index );
			set_active_gallery_item( $next, $container );
		} );
	}

	function setup_gallery_nav_left( $container ) {
		var $arrow_left = $( arrow_html ).addClass( arrow_left_class );
		$container.append( $arrow_left );
		$arrow_left.click( function() {
			var $prev = $container.find( _active_gallery_item_class ).prev();
			set_active_gallery_item( $prev, $container );
		} );
	}

	function setup_gallery_nav_right( $container ) {
		var $arrow_right = $( arrow_html ).addClass( arrow_right_class );
		$container.append( $arrow_right );
		$arrow_right.click( function() {
			var $next = $container.find( _active_gallery_item_class ).next();
			set_active_gallery_item( $next, $container );
		} );
	}

	function set_active_gallery_item( $item, $container ) {
		var container_width = $container.width();
		$container.find( _active_gallery_item_class ).removeClass( active_gallery_item_class );
		//var img_height = $item.find( 'figure' ).first().height();
		var img_height = $item.find( image_elm ).first()[0].clientHeight;
		$container.animate( { height: img_height }, duration );
		$item.addClass( active_gallery_item_class );
		var left = container_width * $item.index();
		$item.parent().css( { 'transform': 'translate(-' + left +'px, 0)' } );

		$container.find( _dot_class_active ).removeClass( dot_class_active );
		$container.find( _dot_class ).eq( $item.index() ).addClass( dot_class_active );

		var right_elm = $item.next().length;
		if( ! right_elm ) {
			$container.find( _arrow_right_class ).fadeOut( duration );
		} else {
			$container.find( _arrow_right_class ).fadeIn( duration );
		}
		var left_elm = $item.prev().length;
		if( ! left_elm ) {
			$container.find( _arrow_left_class ).fadeOut( duration );
		} else {
			$container.find( _arrow_left_class ).fadeIn( duration );
		}
	}

	$document.ready( function() {

		$( _gallery_class ).each( function() {
			var $container = $( this );
			var $first = $container.find( _gallery_item_class ).first();
			setup_gallery( $container );
			set_active_gallery_item( $first, $container );
		} );

	} );

	$window.resize( function() {

		$( _gallery_class ).each( function() {
			var $container = $( this );
			var $first = $container.find( _active_gallery_item_class ).first();
			setup_gallery( $container );
			set_active_gallery_item( $first, $container );
		} );

	} );

} )( jQuery );
