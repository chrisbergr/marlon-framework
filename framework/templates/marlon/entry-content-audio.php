<?php
if( ! $utils = marlon_framework()->get_module( 'post_utilities' ) ) {
	return;
}
?>

<div class="entry-content e-content p-summary" itemprop="articleBody">
	<?php $utils->the_content_without_first_audio(); ?>
	<?php
	wp_link_pages(
		array(
			'before' => '<p class="page-links">' . esc_html__( 'Pages:', 'marlon' ),
			'after'  => '</p>',
		)
	);
	?>
	<?php do_action( 'marlon_entry_content' ); ?>
</div><!-- .entry-content -->

<?php do_action( 'marlon_after_entry_content' ); ?>
