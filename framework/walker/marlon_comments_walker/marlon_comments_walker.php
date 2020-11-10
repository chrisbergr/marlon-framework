<?php

if ( ! class_exists( 'Marlon_Comments_Walker' ) ) {
	class Marlon_Comments_Walker extends Walker_Comment {

		private $walker_style = 'ul';

		public function __construct( $style = 'ul' ) {
			$this->walker_style = $style;
			switch ( $this->walker_style ) {
				case 'div':
					?>
					<div class="comments-list">
					<?php
					break;
				case 'ol':
					?>
					<ol class="comments-list">
					<?php
					break;
				case 'ul':
				default:
					?>
					<ul class="comments-list">
					<?php
					break;
			}
		}
		public function __destruct() {
			switch ( $this->walker_style ) {
				case 'div':
					?>
					</div><!-- .comments-list -->
					<?php
					break;
				case 'ol':
					?>
					</ol><!-- .comments-list -->
					<?php
					break;
				case 'ul':
				default:
					?>
					</ul><!-- .comments-list -->
					<?php
					break;
			}
		}

		protected function html5_comment( $comment, $depth, $args ) {
			if( ! $utils = marlon_framework()->get_module( 'comment_utilities' ) ) {
				return;
			}
			$tag       = ( 'div' === $args['style'] ) ? 'div' : 'li';
			$add_below = 'comment';
			$commenter = wp_get_current_commenter();
			if ( $commenter['comment_author_email'] ) {
				$moderation_note = __( 'Your comment is awaiting moderation.', 'themeberger-basic' );
			} else {
				$moderation_note = __( 'Your comment is awaiting moderation. This is a preview, your comment will be visible after it has been approved.', 'themeberger-basic' );
			}
			?>
			<<?php echo esc_attr( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'u-comment h-cite parent' : 'u-comment h-cite', $comment ); ?> itemprop="comment" itemscope itemtype="http://schema.org/Comment">

				<!-- ••••• -->

				<div class="comment-meta" role="complementary">
					<?php $utils->the_comment_author_vcard( '', '', $comment->comment_ID ); ?>
					<?php $utils->the_commentlink_date( '<span class="themeberger-comment-date">', '</span>', true, $comment->comment_ID ); ?>
					<?php edit_comment_link( 'edit', '<small class="edit-comment">', '</small>' ); ?>
					<?php
					comment_reply_link(
						array_merge(
							$args,
							array(
								'add_below' => $add_below,
								'depth'     => $depth,
								'max_depth' => $args['max_depth'],
								'before'    => '<small class="reply-comment">',
								'after'     => '</small>',
							)
						)
					);
					?>
				</div>
				<div class="comment-content" itemprop="text">
					<?php if ( '0' === $comment->comment_approved ) : ?>
					<p class="comment-meta-item"><em class="comment-awaiting-moderation"><?php echo esc_html( $moderation_note ); ?></em></p>
					<?php endif; ?>
					<?php comment_text(); ?>
				</div>
				<?php do_action( 'themeberger_comment_footer' ); ?>

				<!-- ••••• -->

				<?php
		}

	}
}
