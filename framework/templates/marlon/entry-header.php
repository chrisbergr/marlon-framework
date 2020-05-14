<?php
if( ! $utils = marlon_framework()->get_module( 'post_utilities' ) ) {
	return;
}
?>

<header class="entry-header">
	<?php $utils->the_author_vcard(); ?><?php if ( ! is_single() ) : ?>
		<?php $utils->the_permalink_date( '', '', true ); ?>
	<?php endif; ?>
</header><!-- .entry-header -->
