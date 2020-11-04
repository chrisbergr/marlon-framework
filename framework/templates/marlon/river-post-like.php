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
			<svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
				<path d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z"></path>
			</svg>
		</div>
	</div>
</div>
