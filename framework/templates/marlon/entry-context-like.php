<?php
if( ! $utils = marlon_framework()->get_module( 'post_utilities' ) ) {
	return;
}
?>

<div class="entry-response entry-context">
	<div class="response-icon">
		<svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
			<path d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z"></path>
		</svg>
	</div>

	<section class="h-cite response p-like-of">
		<div class="response-meta">
			<span class="kind-display-text">Liked</span>
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

</div><!-- .entry-context -->
