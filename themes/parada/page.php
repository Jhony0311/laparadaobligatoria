<!-- Hero image -->
<?php if( get_field('hero_image') ): ?>
    <?php get_template_part('templates/hero-image'); ?>
<?php endif; ?>
<!-- /Hero image -->
<!-- Content -->
<?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('templates/content', 'page'); ?>
<?php endwhile; ?>
<!-- /Content -->
<!-- Grid -->
<?php if( get_field('grid_widget_status') ):
    get_template_part('templates/product-category-grid');
endif; ?>
<!-- /Grid -->
<!-- Banner block -->
<?php if( get_field('banner_space') && get_field('banner_status') ):
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
