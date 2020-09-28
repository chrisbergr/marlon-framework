<?php
if( ! $utils = marlon_framework()->get_module( 'post_utilities' ) ) {
	return;
}
$bookmark       = get_post_meta( get_the_id(), 'pins-bookmark', true );
$bookmark_label = parse_url( $bookmark )['host'];
$description    = get_post_meta( get_the_id(), 'pins-description', true );
?>

<div class="entry-content e-content p-summary" itemprop="articleBody">
	<p><?php echo $description; ?></p>
	<?php if( '' !== $bookmark ) : ?>
	<p class="small">Found on <a href="<?php echo $bookmark; ?>" target="_blank"><?php echo $bookmark_label; ?></a>
	<?php endif; ?>
	<?php do_action( 'marlon_entry_content' ); ?>
</div><!-- .entry-content -->

<?php do_action( 'marlon_after_entry_content' ); ?>
