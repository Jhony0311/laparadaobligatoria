<?php
/**
 * Template Name: Contact Us Template
 */
?>
<!-- Hero image -->
<?php if( get_field('hero_image') ): ?>
    <?php get_template_part('templates/hero-image'); ?>
<?php endif; ?>
<!-- /Hero image -->
<!-- Content -->
<div class="content-wrapper">
    <?php if(get_field('google_map')): $location = get_field('google_map'); ?>
        <div class="row">
            <div class="small-12 medium-10 small-centered columns">
                <div class="row">
                    <div class="small-12 medium-6 columns">
                        <h3 class="contact__heading">Encuéntranos</h3>
                        <div class="contact__content">
                            <p class="spaced"><i class="fa-map-marker"></i><?php the_field('contact_address') ?></p>
                            <br>
                            <p class="spaced"><i class="fa-phone"></i><?php the_field('contact_phones') ?></p>
                        </div>
                        <h3 class="contact__heading">Horario Laboral</h3>
                        <div class="contact__content">
                            <?php the_field('contact_working_hours') ?>
                        </div>
                        <h3 class="contact__heading">Síguenos </h3>
                        <div class="contact__content">
                            <?php echo cn_social_icon(); ?>
                        </div>
                    </div>
                    <div class="small-12 medium-6 columns">
                        <?php the_field('contact_form_code') ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="acf-map" data-lat="<?php echo $location['lat']; ?>" data-long="<?php echo $location['lng']; ?>"></div>
        </div>
    <?php endif; ?>
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
