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


<?php //$utils->get_marlon_template( 'entry-meta' ); ?>

<div class='river-card'>
	<div class='river-card-content h-entry post-entry'>
		<div class="p-name e-content river-content">

			<?php $utils->the_first_video_of_post(); ?>

		</div>
		<div class="river-footer">
			<?php $utils->the_permalink_date( '', '', true ); ?>
		</div>
	</div>
</div>
