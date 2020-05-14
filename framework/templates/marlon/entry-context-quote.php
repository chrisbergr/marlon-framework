<?php
if( ! $utils = marlon_framework()->get_module( 'post_utilities' ) ) {
	return;
}
?>

<div class="entry-quote entry-context">
	<?php $utils->the_first_quote_of_post(); ?>
</div><!-- .entry-context -->
