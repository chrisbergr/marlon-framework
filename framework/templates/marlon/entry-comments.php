<?php
if( ! $utils = marlon_framework()->get_module( 'post_utilities' ) ) {
	return;
}
?>

<?php if ( comments_open() || get_comments_number() ) : ?>
	<?php comments_template(); ?>
<?php endif; ?>
