<?php
if( ! $utils = marlon_framework()->get_module( 'post_utilities' ) ) {
	return;
}
$bookmark    = '';
if( ! is_singular() ) {
	$bookmark = get_permalink();
}
?>

<div class="entry-image entry-context">
	<?php if( '' !== $bookmark ) : ?><a href="<?php echo $bookmark; ?>"><?php endif; ?><?php $utils->the_first_image_of_post(); ?><?php if( '' !== $bookmark ) : ?></a><?php endif; ?>
</div><!-- .entry-context -->
