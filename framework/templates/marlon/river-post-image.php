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
			<a href="<?php echo get_permalink(); ?>"><?php $utils->the_first_image_of_post(); ?></a>
		</div>
	</div>
</div>
