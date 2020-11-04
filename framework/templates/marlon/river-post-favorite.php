<?php
/**
 * Template part for displaying posts (Status)
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package marlon
 */

if( ! $utils = marlon_framework()->get_module( 'post_utilities' ) ) {
	return;
}

$url = $utils->get_the_context_permalink();
$url_parts = parse_url( $url );

?>


<?php //$utils->get_marlon_template( 'entry-meta' ); ?>

<div class='river-card'>
	<div class='river-card-content h-entry post-entry'>
		<div class="p-name e-content river-content">
			<p class="highlight"><a href="<?php echo $url; ?>"><?php the_title(); ?></a></p>
			<p class="small"><a href="<?php echo $url_parts['scheme'] . '://' . $url_parts['host']; ?>"><?php echo $url_parts['host']; ?></a></p>
		</div>
		<div class="river-footer">
			<?php $utils->the_permalink_date( '', '', true ); ?>
		</div>
		<div class="river-icon">
			<svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
				<path d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
			</svg>
		</div>
	</div>
</div>
