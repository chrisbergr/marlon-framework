<?php
if( ! $utils = marlon_framework()->get_module( 'post_utilities' ) ) {
	return;
}
//TODO: take over 'tb_helper' functions (themeberger-basic/includes/customizer-data)
?>

<span class="marlon-data" itemprop="name headline"><?php echo esc_html( get_the_title( get_the_ID() ) ); ?></span>
<span class="marlon-data" itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="<?php echo esc_url( get_the_permalink( get_the_ID() ) ); ?>"><?php echo esc_url( get_the_permalink( get_the_ID() ) ); ?></span>
<span class="marlon-data" itemprop="publisher" itemscope itemtype="http://schema.org/Organization">
	<span class="marlon-data" itemprop="name"><?php tb_helper_defaultpublishername(); ?></span>
	<span class="marlon-data" itemprop="logo" itemscope itemtype="http://schema.org/ImageObject">
		<span class="marlon-data" itemprop="url"><?php tb_helper_defaultpublisherlogo(); ?></span>
	</span>
</span>
<?php if ( ! has_post_thumbnail( get_the_ID() ) ) : ?>
	<span class="marlon-data" itemprop="image"><?php tb_helper_defaultcover(); ?></span>
<?php else : ?>
	<span class="marlon-data" itemprop="image"><?php echo esc_url( get_the_post_thumbnail_url( get_the_ID() ) ); ?></span>
<?php endif; ?>
