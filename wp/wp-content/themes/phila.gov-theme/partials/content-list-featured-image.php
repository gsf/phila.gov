<?php
/**
 * The template used for displaying a featured image, description and link
 *
 * @package phila-gov
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('news-item row'); ?>>
  <?php if ( has_post_thumbnail() ) {
    $thumb_active = true;  ?>
    <div class="logo columns medium-7">
      <?php the_post_thumbnail('home-thumb'); ?>
    </div>
  <?php } ?>

  <div class="medium-17 columns">
  	<header class="entry-header small-text">
      <?php
        $categories = get_the_category($post->ID);
          if ( !$categories == 0 ) {
            $current_cat = $categories[0]->name;
          }else {
            $current_cat = null;
          }
        ?>
      <span class="entry-date"><strong><?php echo get_the_date(); ?> </span></strong> <span class="category">
        <?php echo $current_cat == null ?  '' : ' | ' . $current_cat  ?> </span>
        <a href="<?php echo the_permalink(); ?>"><?php the_title('<h2 class="h4">', '</h2>' ); ?></a>
  	</header><!-- .entry-header -->
    <?php
      if (function_exists('rwmb_meta')) :
      $news_desc = rwmb_meta( 'phila_news_desc', $args = array( 'type' => 'textrea' ) );
      $post_desc = rwmb_meta( 'phila_post_desc', $args = array( 'type' => 'textrea' ) );

        if ( '' != $news_desc ) :
          ?><p class="description"><?php echo $news_desc ?></p>
        <?php else : ?>
        <p class="description"><?php echo $post_desc ?></p>
    <?php
      endif;
    endif;
    ?>
  </div>
</article><!-- #post-## -->
<hr>
