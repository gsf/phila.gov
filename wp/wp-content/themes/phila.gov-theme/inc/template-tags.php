<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package phila-gov
 */

if ( ! function_exists( 'phila_gov_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */

function phila_gov_paging_nav() {
  // Don't print empty markup if there's only one page.
  if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
    return;
  }
  ?>
  <nav class="navigation paging-navigation" role="navigation">
    <h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'phila-gov' ); ?></h1>
      <?php
      global $wp_query;

      $big = 999999999; // need an unlikely integer
      $translated = __( 'Page', 'phila-gov' ); // Supply translatable string

      echo paginate_links( array(
      	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
      	'format' => '?paged=%#%',
      	'current' => max( 1, get_query_var('paged') ),
      	'total' => $wp_query->max_num_pages,
              'before_page_number' => '<span class="screen-reader-text">'.$translated.' </span>',
        'prev_text' => __('<i class="fa fa-arrow-left"></i> Previous'),
	      'next_text' => __('Next <i class="fa fa-arrow-right"></i> '),
        )
      );
      ?>
  </nav><!-- .navigation -->
  <?php
}
endif;

if ( ! function_exists( 'phila_gov_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function phila_gov_post_nav() {
  // Don't print empty markup if there's nowhere to navigate.
  $previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
  $next     = get_adjacent_post( false, '', false );

  if ( ! $next && ! $previous ) {
    return;
  }
  ?>
  <nav class="navigation post-navigation pure-u-1" role="navigation">
    <h1 class="screen-reader-text"><?php _e( 'Post navigation', 'phila-gov' ); ?></h1>
    <div class="nav-links">
      <?php
        previous_post_link( '<div class="nav-previous"><i class="fa fa-arrow-left"></i> %link</div>', _x( '<span class="meta-nav">&larr;</span>&nbsp;%title', 'Previous post link', 'phila-gov' ) );
        next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title&nbsp;<span class="meta-nav">&rarr;</span>', 'Next post link',     'phila-gov' ) );
      ?>
    </div><!-- .nav-links -->
  </nav><!-- .navigation -->
  <?php
}
endif;

if ( ! function_exists( 'phila_gov_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * phila_posts and news_posts now use phila_get_posted_on in functions.php
 */
function phila_gov_posted_on() {
  global $post;

  $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

  $time_string = sprintf( $time_string,
    esc_attr( get_the_date( 'c' ) ),
    esc_html( get_the_date() ),
    esc_attr( get_the_modified_date( 'c' ) ),
    esc_html( get_the_modified_date() )
  );

  $posted_on = sprintf(
    esc_html_x( '%s', 'post date', 'phila-gov' ),
    '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="small-text">' . $time_string . '</a>'
  );

  $byline = sprintf(
    esc_html_x( '%s', 'post author', 'phila-gov' ),
    '<span class="author small-text"><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
  );
  echo '<h3 class="alternate posted-on">Posted On</h3>' . $posted_on;

  if ( $post->post_type != 'news_post' && $post->post_type != 'notices' ) {
    echo '<h3 class="alternate">Posted by</h3>' . $byline;
  }
}
endif;

if ( ! function_exists( 'phila_gov_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function phila_gov_entry_footer() {
  // Hide category and tag text for pages.
  if ( 'phila_post' == get_post_type() ) {
    /* translators: used between list items, there is a space after the comma */
    $tags_list = get_the_tag_list( '', __( ', ', 'phila-gov' ) );
    if ( $tags_list ) {
      echo '<div class="mvs"><span>Tagged in:<br>';
      printf(  __( '%1$s', 'phila-gov' ), $tags_list );
      echo '</span</div>';  
    }
  }
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function phila_gov_categorized_blog() {
  if ( false === ( $all_the_cool_cats = get_transient( 'phila_gov_categories' ) ) ) {
    // Create an array of all the categories that are attached to posts.
    $all_the_cool_cats = get_categories( array(
      'fields'     => 'ids',
      'hide_empty' => 1,

      // We only need to know if there is more than one category.
      'number'     => 2,
    ) );

    // Count the number of categories that are attached to the posts.
    $all_the_cool_cats = count( $all_the_cool_cats );

    set_transient( 'phila_gov_categories', $all_the_cool_cats );
  }

  if ( $all_the_cool_cats > 1 ) {
    // This blog has more than 1 category so phila_gov_categorized_blog should return true.
    return true;
  } else {
    // This blog has only 1 category so phila_gov_categorized_blog should return false.
    return false;
  }
}

/**
 * Flush out the transients used in phila_gov_categorized_blog.
 */
function phila_gov_category_transient_flusher() {
  // Like, beat it. Dig?
  delete_transient( 'phila_gov_categories' );
}
add_action( 'edit_category', 'phila_gov_category_transient_flusher' );
add_action( 'save_post',     'phila_gov_category_transient_flusher' );
