<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package st-jo
 */

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function st_jo_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'st_jo_pingback_header' );

/**
 * Changes comment form default fields.
 *
 * @param array $defaults The default comment form arguments.
 *
 * @return array Returns the modified fields.
 */
function st_jo_comment_form_defaults( $defaults ) {
	$comment_field = $defaults['comment_field'];

	// Adjust height of comment form.
	$defaults['comment_field'] = preg_replace( '/rows="\d+"/', 'rows="5"', $comment_field );

	return $defaults;
}
add_filter( 'comment_form_defaults', 'st_jo_comment_form_defaults' );

/**
 * Filters the default archive titles.
 */
function st_jo_get_the_archive_title() {
	if ( is_category() ) {
		$title = __( 'Category Archives: ', 'st-jo' ) . '<span>' . single_term_title( '', false ) . '</span>';
	} elseif ( is_tag() ) {
		$title = __( 'Tag Archives: ', 'st-jo' ) . '<span>' . single_term_title( '', false ) . '</span>';
	} elseif ( is_author() ) {
		$title = __( 'Author Archives: ', 'st-jo' ) . '<span>' . get_the_author_meta( 'display_name' ) . '</span>';
	} elseif ( is_year() ) {
		$title = __( 'Yearly Archives: ', 'st-jo' ) . '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'st-jo' ) ) . '</span>';
	} elseif ( is_month() ) {
		$title = __( 'Monthly Archives: ', 'st-jo' ) . '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'st-jo' ) ) . '</span>';
	} elseif ( is_day() ) {
		$title = __( 'Daily Archives: ', 'st-jo' ) . '<span>' . get_the_date() . '</span>';
	} elseif ( is_post_type_archive() ) {
		$cpt   = get_post_type_object( get_queried_object()->name );
		$title = sprintf(
			/* translators: %s: Post type singular name */
			esc_html__( '%s Archives', 'st-jo' ),
			$cpt->labels->singular_name
		);
	} elseif ( is_tax() ) {
		$tax   = get_taxonomy( get_queried_object()->taxonomy );
		$title = sprintf(
			/* translators: %s: Taxonomy singular name */
			esc_html__( '%s Archives', 'st-jo' ),
			$tax->labels->singular_name
		);
	} else {
		$title = __( 'Archives:', 'st-jo' );
	}
	return $title;
}
add_filter( 'get_the_archive_title', 'st_jo_get_the_archive_title' );

/**
 * Determines whether the post thumbnail can be displayed.
 */
function st_jo_can_show_post_thumbnail() {
	return apply_filters( 'st_jo_can_show_post_thumbnail', ! post_password_required() && ! is_attachment() && has_post_thumbnail() );
}

/**
 * Returns the size for avatars used in the theme.
 */
function st_jo_get_avatar_size() {
	return 60;
}

/**
 * Create the continue reading link
 *
 * @param string $more_string The string shown within the more link.
 */
function st_jo_continue_reading_link( $more_string ) {

	if ( ! is_admin() ) {
		$continue_reading = sprintf(
			/* translators: %s: Name of current post. */
			wp_kses( __( 'Continue reading %s', 'st-jo' ), array( 'span' => array( 'class' => array() ) ) ),
			the_title( '<span class="sr-only">"', '"</span>', false )
		);

		$more_string = '<a href="' . esc_url( get_permalink() ) . '">' . $continue_reading . '</a>';
	}

	return $more_string;
}

// Filter the excerpt more link.
add_filter( 'excerpt_more', 'st_jo_continue_reading_link' );

// Filter the content more link.
add_filter( 'the_content_more_link', 'st_jo_continue_reading_link' );

/**
 * Generate a button element
 *
 * @param array $args {
 *     Optional. An array of arguments.
 *
 *     @type string $text      Button text. Default 'Button'.
 *     @type string $url       URL for link buttons. Default empty.
 *     @type string $variant   Button variant (primary, secondary). Default 'primary'.
 *     @type string $size      Button size (small, medium, large). Default 'medium'.
 *     @type string $icon      Icon HTML or dashicon name. Default empty.
 *     @type string $icon_position Icon position (start, end). Default 'start'.
 *     @type string $class     Additional CSS classes. Default empty.
 *     @type array  $attributes Additional HTML attributes. Default empty array.
 *     @type bool   $disabled  Whether the button is disabled. Default false.
 *     @type bool   $full_width Whether the button spans full width. Default false.
 *     @type string $type      Button type attribute (button, submit, reset). Default 'button'.
 *     @type bool   $echo      Whether to echo or return. Default true.
 * }
 * @return string|void Button HTML markup if echo is false.
 */
function st_jo_button( $args = array() ) {
	$defaults = array(
		'text'          => __( 'Bouton', 'st-jo' ),
		'url'           => '',
		'variant'       => 'primary',
		'size'          => 'medium',
		'icon'          => '',
		'icon_position' => 'start',
		'class'         => '',
		'attributes'    => array(),
		'disabled'      => false,
		'full_width'    => false,
		'type'          => 'button',
		'echo'          => true,
	);

	$args = wp_parse_args( $args, $defaults );

	// Build CSS classes
	$classes = array( 'estjo-button' );
	
	// Add variant class
	if ( ! empty( $args['variant'] ) ) {
		$classes[] = 'estjo-button--' . esc_attr( $args['variant'] );
	}
	
	// Add size class if not medium
	if ( 'medium' !== $args['size'] && ! empty( $args['size'] ) ) {
		$classes[] = 'estjo-button--' . esc_attr( $args['size'] );
	}
	
	// Add full width class
	if ( $args['full_width'] ) {
		$classes[] = 'estjo-button--full';
	}
	
	// Add disabled class
	if ( $args['disabled'] ) {
		$classes[] = 'estjo-button--disabled';
	}
	
	// Add custom classes
	if ( ! empty( $args['class'] ) ) {
		$classes[] = esc_attr( $args['class'] );
	}
	
	// Build attributes
	$attributes = array();
	
	// Add base attributes
	if ( ! empty( $args['url'] ) ) {
		$attributes['href'] = esc_url( $args['url'] );
		$tag = 'a';
	} else {
		$attributes['type'] = esc_attr( $args['type'] );
		$tag = 'button';
	}
	
	$attributes['class'] = implode( ' ', $classes );
	
	if ( $args['disabled'] ) {
		if ( 'button' === $tag ) {
			$attributes['disabled'] = 'disabled';
		} else {
			$attributes['aria-disabled'] = 'true';
		}
	}
	
	// Merge custom attributes
	if ( ! empty( $args['attributes'] ) && is_array( $args['attributes'] ) ) {
		foreach ( $args['attributes'] as $key => $value ) {
			if ( 'class' === $key ) {
				$attributes['class'] .= ' ' . esc_attr( $value );
			} else {
				$attributes[ $key ] = esc_attr( $value );
			}
		}
	}
	
	// Build attribute string
	$attr_string = '';
	foreach ( $attributes as $key => $value ) {
		$attr_string .= sprintf( ' %s="%s"', $key, $value );
	}
	
	// Process icon
	$icon_html = '';
	if ( ! empty( $args['icon'] ) ) {
		// Check if it's a dashicon
		if ( strpos( $args['icon'], 'dashicons-' ) === 0 ) {
			$icon_html = sprintf(
				'<span class="estjo-button__icon estjo-button__icon--%s dashicons %s" aria-hidden="true"></span>',
				esc_attr( $args['icon_position'] ),
				esc_attr( $args['icon'] )
			);
		} else {
			// Assume it's custom HTML
			$icon_html = sprintf(
				'<span class="estjo-button__icon estjo-button__icon--%s" aria-hidden="true">%s</span>',
				esc_attr( $args['icon_position'] ),
				wp_kses_post( $args['icon'] )
			);
		}
	}
	
	// Build button HTML
	$button_content = '';
	if ( 'start' === $args['icon_position'] && ! empty( $icon_html ) ) {
		$button_content .= $icon_html;
	}
	
	$button_content .= '<span>' . esc_html( $args['text'] ) . '</span>';
	
	if ( 'end' === $args['icon_position'] && ! empty( $icon_html ) ) {
		$button_content .= $icon_html;
	}
	
	$html = sprintf(
		'<%1$s%2$s>%3$s</%1$s>',
		$tag,
		$attr_string,
		$button_content
	);
	
	if ( $args['echo'] ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} else {
		return $html;
	}
}

/**
 * Outputs a comment in the HTML5 format.
 *
 * This function overrides the default WordPress comment output in HTML5
 * format, adding the required class for Tailwind Typography. Based on the
 * `html5_comment()` function from WordPress core.
 *
 * @param WP_Comment $comment Comment to display.
 * @param array      $args    An array of arguments.
 * @param int        $depth   Depth of the current comment.
 */
function st_jo_html5_comment( $comment, $args, $depth ) {
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';

	$commenter          = wp_get_current_commenter();
	$show_pending_links = ! empty( $commenter['comment_author'] );

	if ( $commenter['comment_author_email'] ) {
		$moderation_note = __( 'Your comment is awaiting moderation.', 'st-jo' );
	} else {
		$moderation_note = __( 'Your comment is awaiting moderation. This is a preview; your comment will be visible after it has been approved.', 'st-jo' );
	}
	?>
	<<?php echo esc_attr( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $comment->has_children ? 'parent' : '', $comment ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
					if ( 0 !== $args['avatar_size'] ) {
						echo get_avatar( $comment, $args['avatar_size'] );
					}
					?>
					<?php
					$comment_author = get_comment_author_link( $comment );

					if ( '0' === $comment->comment_approved && ! $show_pending_links ) {
						$comment_author = get_comment_author( $comment );
					}

					printf(
						/* translators: %s: Comment author link. */
						wp_kses_post( __( '%s <span class="says">says:</span>', 'st-jo' ) ),
						sprintf( '<b class="fn">%s</b>', wp_kses_post( $comment_author ) )
					);
					?>
				</div><!-- .comment-author -->

				<div class="comment-metadata">
					<?php
					printf(
						'<a href="%s"><time datetime="%s">%s</time></a>',
						esc_url( get_comment_link( $comment, $args ) ),
						esc_attr( get_comment_time( 'c' ) ),
						esc_html(
							sprintf(
							/* translators: 1: Comment date, 2: Comment time. */
								__( '%1$s at %2$s', 'st-jo' ),
								get_comment_date( '', $comment ),
								get_comment_time()
							)
						)
					);

					edit_comment_link( __( 'Edit', 'st-jo' ), ' <span class="edit-link">', '</span>' );
					?>
				</div><!-- .comment-metadata -->

				<?php if ( '0' === $comment->comment_approved ) : ?>
				<em class="comment-awaiting-moderation"><?php echo esc_html( $moderation_note ); ?></em>
				<?php endif; ?>
			</footer><!-- .comment-meta -->

			<div <?php st_jo_content_class( 'comment-content' ); ?>>
				<?php comment_text(); ?>
			</div><!-- .comment-content -->

			<?php
			if ( '1' === $comment->comment_approved || $show_pending_links ) {
				comment_reply_link(
					array_merge(
						$args,
						array(
							'add_below' => 'div-comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'before'    => '<div class="reply">',
							'after'     => '</div>',
						)
					)
				);
			}
			?>
		</article><!-- .comment-body -->
	<?php
}
