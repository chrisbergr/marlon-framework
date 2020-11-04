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
			<svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
				<path d="M0 512V48C0 21.49 21.49 0 48 0h288c26.51 0 48 21.49 48 48v464L192 400 0 512z"></path>
			</svg>
		</div>
	</div>
</div>
