<?php

if ( ! class_exists( 'Marlon_Shortcodes' ) ) {
	class Marlon_Shortcodes extends Marlon_Module {

		protected function init_module() {
			$this->loader->add_action( 'init', $this, 'add_module_shortcodes', 11 );
			$this->loader->add_action( 'wp_enqueue_scripts', $this, 'shortcode_scripts' );
			$this->loader->add_action( 'wp_footer', $this, 'shortcode_script', 70 );
		}

		public function shortcode_scripts() {
			wp_enqueue_script( 'marlon-masonry', plugins_url( '/masonry.min.js', __FILE__ ), array(), false, true );
		}
		public function shortcode_script() {
			?>
			<script type="text/javascript">
				jQuery(function() {
					var cardsgridsettings = {
						columnWidth: ".grid-sizer",
						itemSelector: ".river-item"
					};
					$cardsgrid = jQuery(".river").masonry(cardsgridsettings);
					$cardsgrid.masonry("layout");
				});
			</script>
			<?php
		}

		public function add_module_shortcodes() {
			add_shortcode( 'marlon-recent-posts', array( $this, 'recent_posts' ) );
			add_shortcode( 'marlon-recent-post', array( $this, 'recent_post' ) );
			add_shortcode( 'marlon-recent-post-image', array( $this, 'recent_post_image' ) );
			add_shortcode( 'marlon-posts-river', array( $this, 'posts_river' ) );
		}

		public function recent_posts( $atts = array() ) {

			extract(
				shortcode_atts(
					array(
						'category' => 'uncategorized',
						'title'    => __( 'Recent Articles', 'marlon' ),
						'offset'   => 0,
						'limit'    => 7
					),
					$atts
				)
			);

			$utils = marlon_framework()->get_module( 'post_utilities' );
			$kicker = marlon_framework()->get_module( 'post_kicker' );
			$subtitle = marlon_framework()->get_module( 'post_subtitle' );

			ob_start();
			?>

			<div class="scrollarea">
				<div class="scrollarea-header">
					<h2 class="scrollarea-title"><?php echo esc_html( $title ); ?></h2>
					<div class="scrollarea-arrows"><a class="arrow arrow-prev disabled"></a><a class="arrow arrow-next"></a></div>
				</div>
				<ul class="slider">

					<?php $the_query = new WP_Query( array( 'category_name' => $category, 'offset' => $offset, 'posts_per_page' => $limit ) ); ?>

					<?php if ( $the_query->have_posts() ) : ?>
						<?php while ( $the_query->have_posts() ) : ?>
							<?php $the_query->the_post(); ?>
							<?php if ( has_post_thumbnail() ) : ?>
							<li class="slide">
								<a href="<?php the_permalink(); ?>" class="slide-image-wrapper"><img class="slide-image" src="<?php echo esc_url( get_the_post_thumbnail_url() ); ?>" alt="<?php the_title(); ?>"></a>
								<div class="slide-description">
									<?php $kicker->the_kicker( '<span class="slide-kicker">', '</span>' ); ?>
									<span class="slide-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>
									<?php $subtitle->the_subtitle( '<span class="slide-subtitle">', '</span>' ); ?>
								</div>
								<div class="slide-meta">
									<a href="<?php the_permalink(); ?>" class="readmore"><?php esc_html_e( 'Read article', 'marlon' ); ?></a>
								</div>
								<div class="slide-category-date"><?php $utils->the_permalink_date( '', ' | ', false ); ?><?php the_category( ', ' ); ?></div>
							</li>
							<?php endif; ?>
							<?php wp_reset_postdata(); ?>
						<?php endwhile; ?>
					<?php endif; ?>

				</ul>
			</div><!-- .scrollarea -->

			<?php
			return ob_get_clean();

		}


		public function recent_post( $atts = array() ) {

			extract(
				shortcode_atts(
					array(
						'category' => 'uncategorized',
						'title'    => __( 'Recent Article', 'marlon' ),
						'offset'   => 0,
						'limit'    => 1
					),
					$atts
				)
			);

			$utils = marlon_framework()->get_module( 'post_utilities' );
			$kicker = marlon_framework()->get_module( 'post_kicker' );
			$subtitle = marlon_framework()->get_module( 'post_subtitle' );

			ob_start();
			?>

			<?php $the_query = new WP_Query( array( 'category_name' => $category, 'offset' => $offset, 'posts_per_page' => $limit ) ); ?>

			<?php if ( $the_query->have_posts() ) : ?>
				<?php while ( $the_query->have_posts() ) : ?>
					<?php $the_query->the_post(); ?>
					<?php if ( has_post_thumbnail() ) : ?>
					<div class="recent-post-container">

						<div class="recent-post-header">
							<h2 class="recent-post-header-title"><?php echo esc_html( $title ); ?></h2>
						</div>

						<div class="recent-post">
							<div class="recent-post--left">

								<?php $kicker->the_kicker( '<h6 class="recent-post-kicker">', '</h6>' ); ?>
								<?php the_title( '<h3 class="recent-post-title">', '</h3>' ); ?>
								<?php $subtitle->the_subtitle( '<h4 class="recent-post-subtitle">', '</h4>' ); ?>

								<a href="<?php the_permalink(); ?>" class="readmore"><?php esc_html_e( 'Read article', 'marlon' ); ?></a>

							</div>
							<div class="recent-post--right">
								<h6 class="recent-post-category-date"><?php $utils->the_permalink_date( '', '<br>', false ); ?><?php the_category( ', ' ); ?></h6>
							</div>
						</div>

						<div class="recent-post-image"><img src="<?php echo esc_url( get_the_post_thumbnail_url() ); ?>" alt="<?php the_title(); ?>"></div>

					</div><!-- .recent-post-container -->
					<?php endif; ?>
					<?php wp_reset_postdata(); ?>
				<?php endwhile; ?>
			<?php endif; ?>

			<?php
			return ob_get_clean();

		}


		public function recent_post_image( $atts = array() ) {

			extract(
				shortcode_atts(
					array(
						'category' => 'uncategorized',
						'title'    => __( 'Recent Article', 'marlon' ),
						'offset'   => 0,
						'limit'    => 1
					),
					$atts
				)
			);

			ob_start();
			?>

			<?php $the_query = new WP_Query( array( 'category_name' => $category, 'offset' => $offset, 'posts_per_page' => $limit ) ); ?>

			<?php if ( $the_query->have_posts() ) : ?>
				<?php while ( $the_query->have_posts() ) : ?>
					<?php $the_query->the_post(); ?>
					<?php if ( has_post_thumbnail() ) : ?>
					<div class="recent-post-container">

						<?php if ( $title ) : ?>
						<div class="recent-post-header">
							<h2 class="recent-post-header-title"><?php echo esc_html( $title ); ?></h2>
						</div>
						<?php endif; ?>

						<div class="recent-post-image"><img src="<?php echo esc_url( get_the_post_thumbnail_url() ); ?>" alt="<?php the_title(); ?>"></div>

					</div><!-- .recent-post-container -->
					<?php endif; ?>
					<?php wp_reset_postdata(); ?>
				<?php endwhile; ?>
			<?php endif; ?>

			<?php
			return ob_get_clean();

		}


		public function posts_river( $atts = array() ) {

			extract(
				shortcode_atts(
					array(
						'category' => 'uncategorized',
						'offset'   => 0,
						'limit'    => 50,
						'cover'    => true,
					),
					$atts
				)
			);

			$utils = marlon_framework()->get_module( 'post_utilities' );
			$kicker = marlon_framework()->get_module( 'post_kicker' );
			$subtitle = marlon_framework()->get_module( 'post_subtitle' );

			ob_start();
			?>

			<div class="marlon-posts-river">
				<ul class="river">

					<?php
						if( $cover ) {
							//TODO: Check other categories used by posts of this category
							$category_obj = get_category_by_slug( $category );
							$subcategories = get_terms(
								array(
									'parent' => $category_obj->term_id,
									'taxonomy' => 'category'
								)
							);
							//print_r($subcategories);
							foreach( $subcategories as $key => $cat ) {
								?>
								<li class="river-item river-cover river-cover--<?php echo $cat->slug; ?>">
									<div class='river-card'>
										<div class='river-card-content'>
											<div class="river-content">

												<p><a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><?php echo $cat->name; ?></a></p>

											</div>
											<div class="river-count">
												<?php echo $cat->count; ?>
											</div>
										</div>
									</div>
								</li>
								<?php
							}
						}
					?>

					<?php $the_query = new WP_Query( array( 'category_name' => $category, 'offset' => $offset, 'posts_per_page' => $limit ) ); ?>

					<?php if ( $the_query->have_posts() ) : ?>
						<?php while ( $the_query->have_posts() ) : ?>
							<?php $the_query->the_post(); ?>

							<?php
							$this_type   = get_post_type();
							$this_format = get_post_format() ? : 'standard';
							$this_kind   = 'note';
							if ( function_exists( 'has_post_kind' ) && has_post_kind() ) {
								$this_kind = strtolower( get_post_kind() );
							}
							if ( 'standard' === $this_format && 'note' !== $this_kind ) {
								$this_format = $this_kind;
							}
							$template = $this_type . '-' . $this_format;
							?>

							<li class="river-item river-item--<?php echo $template; ?>">
								<?php //the_permalink(); ?>
								<?php //the_title(); ?>

								<?php $utils->get_marlon_template( 'river', $template ); ?>

							</li>
							<?php wp_reset_postdata(); ?>
						<?php endwhile; ?>
					<?php endif; ?>

					<li class="grid-sizer"></li>

				</ul>
			</div><!-- .scrollarea -->

			<?php
			return ob_get_clean();

		}

	}
}
