<?php
if( ! $utils = marlon_framework()->get_module( 'post_utilities' ) ) {
	return;
}
?>

<div class="entry-gallery entry-context">
	<?php $utils->the_first_gallery_of_post(); ?>
</div><!-- .entry-context -->
