<?php
if( ! $utils = marlon_framework()->get_module( 'post_utilities' ) ) {
	return;
}
?>

<div class="entry-response entry-context">
	<div class="response-icon">
		<svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
			<path d="M256 32C114.6 32 0 125.1 0 240c0 49.6 21.4 95 57 130.7C44.5 421.1 2.7 466 2.2 466.5c-2.2 2.3-2.8 5.7-1.5 8.7S4.8 480 8 480c66.3 0 116-31.8 140.6-51.4 32.7 12.3 69 19.4 107.4 19.4 141.4 0 256-93.1 256-208S397.4 32 256 32z"></path>
		</svg>
	</div>

	<section class="h-cite response p-in-reply-to">
		<div class="response-meta">
			<span class="kind-display-text">Replied to</span>
			<?php $utils->the_context_author_vcard(); ?>
			<?php if( is_singular() ) : ?>
				<?php $utils->the_context_permalink_date( null, null, true ); ?>
			<?php endif; ?>
		</div>
		<div class="response-content e-summary">
			<?php $utils->the_context_title( '<p class="response-title"><strong>', '</strong></p>' ); ?>
			<?php $utils->the_context_content( '<p>', '</p>' ); ?>
		</div>

	</section>

	<?php //$utils->get_postkinds_data_debug( get_the_id() ); ?>

</div><!-- .entry-context -->
