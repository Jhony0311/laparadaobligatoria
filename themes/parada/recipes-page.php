<?php
/**
 * Template Name: Recipes Page Template
 */
?>
<!-- Hero image -->
<?php if( get_field('hero_image') ): ?>
    <?php get_template_part('templates/hero-image'); ?>
<?php endif; ?>
<!-- /Hero image -->
<!-- Content -->
<?php
$args = array(
    'post_type' => 'recipes'
);
$query = new WP_Query( $args );
?>
<div class="featured-recipes">
    <div class="row">
        <div class="small-12 columns">
            <div class="small-12 columns">
                <h2 class="featured-recipes-title">Las Recetas Obligatorias</h2>
            </div>
        </div>
        <div class="small-12 columns">
         <?php if ($query->have_posts()) : ?>
             <?php while ($query->have_posts()) : $query->the_post(); ?>
                 <?php get_template_part('templates/featured-recipes-short', get_post_type() != 'recipes' ? get_post_type() : get_post_format()); ?>
             <?php endwhile; ?>
         <?php endif; ?>
        </div>
    </div>
</div>
<div class="content-wrapper row">
    <div class="small-12 columns">
        <?php if (!$query->have_posts()) : ?>
          <div class="alert alert-warning">
            <?php _e('Sorry, no results were found.', 'sage'); ?>
          </div>
          <?php get_search_form(); ?>
        <?php endif; ?>
        <?php while ($query->have_posts()) : $query->the_post(); ?>
          <?php get_template_part('templates/recipes-short', get_post_type() != 'recipes' ? get_post_type() : get_post_format()); ?>
        <?php endwhile; ?>
        <?php the_posts_navigation(); ?>
    </div>
</div>
<!-- /Content -->
<!-- Banner block -->
<?php if( get_field('banner_space') ):
        $banners = get_field('banner_space');
        foreach( $banners as $banner ):
            setup_postdata($banner);
?>
    <div class="banner-cta banner-cta--<?php the_field('banner_layout', $banner->ID) ?>">
        <div style="background-image: url(<?php the_field('banner_image', $banner->ID) ?>)" class="banner-cta__wrapper">
            <div class="banner-cta__title"><?php echo $banner->post_title ?></div>
            <div class="banner-cta__copy"><?php the_content(); ?></div>
            <a href="<?php the_field('banner_link', $banner->ID) ?>" class="banner-cta__link">Ver más ></a>
        </div>
    </div>
<?php
            wp_reset_postdata();
        endforeach;
    endif;
?>
<!-- /Banner block -->
