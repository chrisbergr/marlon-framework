<?php
if( ! $utils = marlon_framework()->get_module( 'post_utilities' ) ) {
	return;
}
$image       = get_post_meta( get_the_id(), 'pins-media', true );
$bookmark    = ''; //get_post_meta( get_the_id(), 'pins-bookmark', true );
if( ! is_singular() ) {
	$bookmark = get_permalink();
}
?>

<?php if( '' !== $image ) : ?>
<div class="entry-image entry-context">
	<?php if( '' !== $bookmark ) : ?><a href="<?php echo $bookmark; ?>"><?php endif; ?><img src="<?php echo $image; ?>"><?php if( '' !== $bookmark ) : ?></a><?php endif; ?>
</div><!-- .entry-context -->
<?php endif; ?>
