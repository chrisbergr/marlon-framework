<?php
/**
 * Template part for displaying posts (Status)
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package marlon
 */

if( ! $utils = marlon_framework()->get_module( 'post_utilities' ) ) {
	return;
}

?>


<?php //$utils->get_marlon_template( 'entry-meta', $post_type_slug ); ?>

<div class='river-card'>
	<div class='river-card-content h-entry post-entry'>
		<div class="p-name e-content river-content">
			<a href="<?php echo get_permalink(); ?>"><?php $utils->the_first_galleryimage_of_post(); ?></a>
		</div>
		<div class="river-icon">
			<svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
				<path fill="currentColor" d="M480 416v16c0 26.51-21.49 48-48 48H48c-26.51 0-48-21.49-48-48V176c0-26.51 21.49-48 48-48h16v48H54a6 6 0 0 0-6 6v244a6 6 0 0 0 6 6h372a6 6 0 0 0 6-6v-10h48zm42-336H150a6 6 0 0 0-6 6v244a6 6 0 0 0 6 6h372a6 6 0 0 0 6-6V86a6 6 0 0 0-6-6zm6-48c26.51 0 48 21.49 48 48v256c0 26.51-21.49 48-48 48H144c-26.51 0-48-21.49-48-48V80c0-26.51 21.49-48 48-48h384zM264 144c0 22.091-17.909 40-40 40s-40-17.909-40-40 17.909-40 40-40 40 17.909 40 40zm-72 96l39.515-39.515c4.686-4.686 12.284-4.686 16.971 0L288 240l103.515-103.515c4.686-4.686 12.284-4.686 16.971 0L480 208v80H192v-48z" class=""></path>
			</svg>
		</div>
	</div>
</div>
