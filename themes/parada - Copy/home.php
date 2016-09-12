<?php
    $p = get_page(16);
?>
<!-- Hero image -->
<?php if( get_field('hero_image', $p->ID) ): ?>
    <section class="hero">
        <div class="hero__image" style="background-image: url('<?php the_field('hero_image', $p->ID); ?>')"></div>
        <div class="hero__opacity"></div>
        <div class="hero__tt">
            <h1 class="hero__title"><?php echo $p->post_title; ?></h1>
            <h1 class="hero__subtitle"><?php the_field('hero_tagline', $p->ID); ?></h1>
        </div>
    </section>
<?php endif; ?>
<!-- /Hero image -->
<div class="content-wrapper row">
    <div class="small-12 medium-8 columns">
        <?php if (!have_posts()) : ?>
          <div class="alert alert-warning">
            <?php _e('Sorry, no results were found.', 'sage'); ?>
          </div>
          <?php get_search_form(); ?>
        <?php endif; ?>
        <?php while (have_posts()) : the_post(); ?>
          <?php get_template_part('templates/post-short', get_post_type() != 'post' ? get_post_type() : get_post_format()); ?>
        <?php endwhile; ?>
        <?php the_posts_navigation(); ?>
    </div>
    <div class="small-12 medium-4 columns">
        <?php dynamic_sidebar('sidebar-primary'); ?>
    </div>
</div>
