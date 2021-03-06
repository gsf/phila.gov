<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package phila-gov
 */

get_header(); ?>

<section id="primary" class="content-area archive row">

  <?php
    if ( have_posts() ) : ?>
      <header class="columns">
        <h1 class="contrast">
          <?php get_the_archive_title(); ?>
        </h1>
      </header><!-- .page-header -->
      <main id="main" class="site-main small-24 columns" role="main">
        <?php while ( have_posts() ) : the_post(); ?>

          <?php get_template_part( 'partials/content', 'list-featured-image' ) ?>

        <?php endwhile; ?>

        <?php phila_gov_paging_nav(); ?>

        <?php else : ?>

          <?php get_template_part( 'partials/content', 'none' ); ?>

        <?php endif; ?>

      </main><!-- #main -->
</section><!-- #primary -->
<?php get_footer(); ?>
