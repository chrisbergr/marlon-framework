<?php
if( ! $utils = marlon_framework()->get_module( 'post_utilities' ) ) {
	return;
}
$comments_count    = wp_count_comments( get_the_ID() );
$approved_comments = $comments_count->approved;
//TODO: Add class 'p-category' to category and tag links
?>

<?php if ( is_single() || $approved_comments > 0 ) : ?>
<footer class="entry-footer">
	<?php if ( is_single() ) : ?>
		<p><?php the_title( '<strong class="meta-title p-name">', '</strong> | ' ); ?><span itemprop="articleSection"><?php the_category( ', ' ); ?></span><?php $utils->the_permalink_date( ' | ' ); ?></p>
		<?php do_action( 'marlon_entry_footer' ); ?>
		<p><?php esc_html_e( 'Shortlink:', 'marlon' ); ?> <?php $utils->the_shorturl(); ?></p>
		<?php the_tags( '<p>' . __( 'Tags:', 'marlon' ) . ' <span itemprop="keywords">', ', ', '</span></p>' ); ?>
	<?php endif; ?>
	<?php get_template_part( 'template-parts/partial-interactions' ); ?>
</footer><!-- .entry-footer -->
<?php endif; ?>
