<!-- Hero image -->
<?php if( get_field('hero_image') ): ?>
    <section class="hero">
        <div class="hero__image" style="background-image: url('<?php the_field('hero_image'); ?>')"></div>
        <div class="hero__opacity"></div>
        <div class="hero__tt">
            <img src="<?php site_icon_url(); ?>" alt="<?php bloginfo('name'); ?> logo" class="hero__logo site__logo">
            <h1 class="hero__title"><?php bloginfo('name'); ?></h1>
        </div>
        <div class="hero__arrow"></div>
    </section>
<?php endif; ?>
<!-- /Hero image -->
<!-- Content -->
<div class="content-wrapper">
    <?php if(get_field('grid_homepagewidget_status')):
        if (get_field('grid_widget_mainblock')) : ?>
        <div class="row">
            <?php
            $block = get_field('grid_widget_mainblock');
            foreach ($block as $b) :
            setup_postdata($b);
            ?>
            <div class="grid-item columns small-12">
                <div class="grid-item__wrapper">
                    <div class="grid-item__image" style="background-image: url('<?php the_field('hero_image', $b->ID) ?>');"></div>
                    <a href="<?php the_permalink(); ?>"><div class="grid-item__title"><?php echo $b->post_title ?></div></a>
                </div>
            </div>
            <?php
            wp_reset_postdata();
            endforeach;
            ?>
        </div>
    <?php endif; ?>
        <div class="row">
            <?php if (get_field('grid_widget_secondblock1')) : ?>
            <?php
                $block = get_field('grid_widget_secondblock1');
                foreach ($block as $b) :
                setup_postdata($b);
            ?>
                <div class="grid-item columns small-12 medium-4">
                    <div class="grid-item__wrapper">
                        <div class="grid-item__image" style="background-image: url('<?php the_field('hero_image', $b->ID) ?>');"></div>
                        <a href="<?php the_permalink(); ?>"><div class="grid-item__title"><?php echo $b->post_title ?></div></a>
                    </div>
                </div>
                <?php
                wp_reset_postdata();
                endforeach;
                ?>
            <?php endif; ?>

            <?php if (get_field('grid_widget_secondblock2')) : ?>
            <?php
            $block = get_field('grid_widget_secondblock2');
            foreach ($block as $b) :
            setup_postdata($b);
            ?>
                <div class="grid-item columns small-12 medium-4">
                    <div class="grid-item__wrapper">
                        <div class="grid-item__image" style="background-image: url('<?php the_field('hero_image', $b->ID) ?>');"></div>
                        <a href="<?php the_permalink(); ?>"><div class="grid-item__title"><?php echo $b->post_title ?></div></a>
                    </div>
                </div>
                <?php
                wp_reset_postdata();
                endforeach;
                ?>
            <?php endif; ?>

            <?php if (get_field('grid_widget_secondblock3')) : ?>
            <?php
            $block = get_field('grid_widget_secondblock3');
            foreach ($block as $b) :
            setup_postdata($b);
            ?>
                <div class="grid-item columns small-12 medium-4">
                    <div class="grid-item__wrapper">
                        <div class="grid-item__image" style="background-image: url('<?php the_field('hero_image', $b->ID) ?>');"></div>
                        <a href="<?php the_permalink(); ?>"><div class="grid-item__title"><?php echo $b->post_title ?></div></a>
                    </div>
                </div>
                <?php
                wp_reset_postdata();
                endforeach;
                ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
<!-- /Content -->
<!-- Testimony slider  -->
<?php if( get_field('testimony_list') ):
    $testimonies = get_field('testimony_list');
?>
    <div class="slider testimonial-slider">
<?php
    foreach( $testimonies as $t ):
    setup_postdata($t);
?>
    <div class="slide slide--full testimonial">
        <div class="slide__image" style="background-image: url('<?php the_field('testimony_image', $t->ID); ?>')"></div>
        <div class="slide__opacity"></div>
        <div class="slide__text slide__text--center">
            <div class="testimonial__copy">
                <div class="testimonial__story"><?php the_content(); ?></div>
                <div class="testimonial__source">
                    <?php the_field('testimony_deponent', $t->ID); if( get_field('testimony_nationality', $t->ID)): ?>,
                        <?php the_field('testimony_nationality', $t->ID); ?>
                    <?php
                        endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
    wp_reset_postdata();
    endforeach;
?>
</div>
<?php endif; ?>
<!-- /Testimony slider -->
<!-- Banner block -->
<?php print_r(the_field('banner_status')) ?>
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
<!-- /Banner block  -->
