<?php
    $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
?>
<!-- Hero image -->
<!-- Hero image -->
<section class="hero">
    <div class="hero__image" style="background-image: url('<?php the_field('product_type_image', $term) ?>')"></div>
    <div class="hero__opacity"></div>
    <div class="hero__tt">
        <p class="hero__title"><?php echo $term->name ?></p>
    </div>
</section>
<!-- /Hero image -->
<!-- Content -->
<div class="content-wrapper">
    <?php while (have_posts()) : the_post(); ?>
      <?php get_template_part('templates/product-short', get_post_type() != 'post' ? get_post_type() : get_post_format()); ?>
    <?php endwhile; ?>
</div>
<!-- /Content -->
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
