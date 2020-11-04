<?php
/**
 * Template part for displaying posts (Status)
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package marlon
 */

$post_type_slug = 'status';

if( ! $utils = marlon_framework()->get_module( 'post_utilities' ) ) {
	return;
}

?>


<?php //$utils->get_marlon_template( 'entry-meta', $post_type_slug ); ?>

<div class='river-card'>
	<div class='river-card-content h-entry post-entry'>
		<?php the_content(); ?>
	</div>
</div>
