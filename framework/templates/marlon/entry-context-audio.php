<?php
if( ! $utils = marlon_framework()->get_module( 'post_utilities' ) ) {
	return;
}
?>

<div class="entry-audio entry-context">
	<?php $utils->the_first_audio_of_post(); ?>
</div><!-- .entry-context -->
