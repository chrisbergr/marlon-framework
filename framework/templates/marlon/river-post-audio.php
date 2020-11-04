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

?>


<?php //$utils->get_marlon_template( 'entry-meta' ); ?>

<div class='river-card'>
	<div class='river-card-content h-entry post-entry'>
		<div class="p-name e-content river-content">

			<?php $utils->the_first_audio_of_post(); ?>

		</div>
		<div class="river-footer">
			<?php $utils->the_permalink_date( '', '', true ); ?>
		</div>
		<div class="river-icon">
			<svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512">
				<path fill="currentColor" d="M176 352c53.02 0 96-42.98 96-96V96c0-53.02-42.98-96-96-96S80 42.98 80 96v160c0 53.02 42.98 96 96 96zm160-160h-16c-8.84 0-16 7.16-16 16v48c0 74.8-64.49 134.82-140.79 127.38C96.71 376.89 48 317.11 48 250.3V208c0-8.84-7.16-16-16-16H16c-8.84 0-16 7.16-16 16v40.16c0 89.64 63.97 169.55 152 181.69V464H96c-8.84 0-16 7.16-16 16v16c0 8.84 7.16 16 16 16h160c8.84 0 16-7.16 16-16v-16c0-8.84-7.16-16-16-16h-56v-33.77C285.71 418.47 352 344.9 352 256v-48c0-8.84-7.16-16-16-16z" class=""></path>
			</svg>
		</div>
	</div>
</div>
