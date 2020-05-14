<?php
if( ! $utils = marlon_framework()->get_module( 'post_utilities' ) ) {
	return;
}
?>

<div class="entry-video entry-context">
	<?php $utils->the_first_video_of_post(); ?>
</div><!-- .entry-context -->
