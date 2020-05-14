<?php
/**
 * Template part for displaying posts (Image)
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package marlon
 */

$post_type_slug = 'image';

if( ! $utils = marlon_framework()->get_module( 'post_utilities' ) ) {
	return;
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'content h-entry post-entry type-post-' . $post_type_slug ); ?> <?php $utils->article_in_collection(); ?> itemscope itemtype="http://schema.org/BlogPosting">

	<?php $utils->get_marlon_template( 'entry-meta', $post_type_slug ); ?>

	<div class="content-card type-<?php echo esc_attr( $post_type_slug ); ?>">

		<?php $utils->get_marlon_template( 'entry-context', $post_type_slug ); ?>

		<?php $utils->get_marlon_template( 'entry-header', $post_type_slug ); ?>

		<?php $utils->get_marlon_template( 'entry-content', $post_type_slug ); ?>

		<?php $utils->get_marlon_template( 'entry-footer', $post_type_slug ); ?>

	</div><!-- .content-card -->

	<?php $utils->get_marlon_template( 'entry-comments', $post_type_slug ); ?>

</article><!-- #post-<?php the_ID(); ?> -->
