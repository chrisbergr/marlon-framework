<?php
if( ! $utils = marlon_framework()->get_module( 'post_utilities' ) ) {
	return;
}
?>

<div class="entry-response entry-context">
	<div class="response-icon">
		<svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
			<path d="M0 512V48C0 21.49 21.49 0 48 0h288c26.51 0 48 21.49 48 48v464L192 400 0 512z"></path>
		</svg>
	</div>

	<section class="h-cite response p-bookmark-of">
		<div class="response-meta">
			<span class="kind-display-text">Bookmarked</span>
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
