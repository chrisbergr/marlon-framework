<?php
if( ! $utils = marlon_framework()->get_module( 'post_utilities' ) ) {
	return;
}
?>

<div class="entry-response entry-context">
	<div class="response-icon">
		<svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
			<path d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
		</svg>
	</div>

	<section class="h-cite response p-favorite-of">
		<div class="response-meta">
			<span class="kind-display-text">Favorited</span>
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
