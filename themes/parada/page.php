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
<?php if( get_field('grid_widget_status') ): ?>
    <div class="content-wrapper">
    <?php
        if (get_field('grid_widget_block1')) :
    ?>
        <div class="row">
            <?php
            $block = get_field('grid_widget_block1');
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
    <?php
        endif;
    ?>
        <div class="row">
            <?php
            if (get_field('grid_widget_block2')) :
            ?>
                <?php
                $block = get_field('grid_widget_block2');
                foreach ($block as $b) :
                setup_postdata($b);
                ?>
                <div class="grid-item columns small-12 medium-6">
                    <div class="grid-item__wrapper">
                        <div class="grid-item__image" style="background-image: url('<?php the_field('hero_image', $b->ID) ?>');"></div>
                        <a href="<?php the_permalink(); ?>"><div class="grid-item__title"><?php echo $b->post_title ?></div></a>
                    </div>
                </div>
                <?php
                wp_reset_postdata();
                endforeach;
                ?>
            <?php
            endif;
            ?>

            <?php
            if (get_field('grid_widget_block3')) :
            ?>
                <?php
                $block = get_field('grid_widget_block3');
                foreach ($block as $b) :
                setup_postdata($b);
                ?>
                <div class="grid-item columns small-12 medium-6">
                    <div class="grid-item__wrapper">
                        <div class="grid-item__image" style="background-image: url('<?php the_field('hero_image', $b->ID) ?>');"></div>
                        <a href="<?php the_permalink(); ?>"><div class="grid-item__title"><?php echo $b->post_title ?></div></a>
                    </div>
                </div>
                <?php
                wp_reset_postdata();
                endforeach;
                ?>
            <?php
            endif;
            ?>
        </div>

        <div class="row">
            <?php
            if (get_field('grid_widget_block4')) :
            ?>
                <?php
                $block = get_field('grid_widget_block4');
                foreach ($block as $b) :
                setup_postdata($b);
                ?>
                <div class="grid-item columns small-12 medium-6">
                    <div class="grid-item__wrapper">
                        <div class="grid-item__image" style="background-image: url('<?php the_field('hero_image', $b->ID) ?>');"></div>
                        <a href="<?php the_permalink(); ?>"><div class="grid-item__title"><?php echo $b->post_title ?></div></a>
                    </div>
                </div>
                <?php
                wp_reset_postdata();
                endforeach;
                ?>
            <?php
            endif;
            ?>

            <?php
            if (get_field('grid_widget_block5')) :
            ?>
                <?php
                $block = get_field('grid_widget_block5');
                foreach ($block as $b) :
                setup_postdata($b);
                ?>
                <div class="grid-item columns small-12 medium-6">
                    <div class="grid-item__wrapper">
                        <div class="grid-item__image" style="background-image: url('<?php the_field('hero_image', $b->ID) ?>');"></div>
                        <a href="<?php the_permalink(); ?>"><div class="grid-item__title"><?php echo $b->post_title ?></div></a>
                    </div>
                </div>
                <?php
                wp_reset_postdata();
                endforeach;
                ?>
            <?php
            endif;
            ?>
        </div>
        <?php
            if (get_field('grid_widget_block6')) :
        ?>
            <div class="row">
                <?php
                $block = get_field('grid_widget_block6');
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
        <?php
            endif;
        ?>
    </div>
<?php endif; ?>
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
