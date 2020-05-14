<?php
if( ! $utils = marlon_framework()->get_module( 'post_utilities' ) ) {
	return;
}
?>

<div class="entry-image entry-context">
	<?php $utils->the_first_image_of_post(); ?>
</div><!-- .entry-context -->
