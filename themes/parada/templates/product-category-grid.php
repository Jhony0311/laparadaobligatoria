<div class="content-wrapper">
    <div class="row">
        <p class="heading">Explora nuestros productos</p>
        <p class="sub-heading">Lorem ipsum dolor sit amet</p>
    </div>
    <?php
        $block = get_field('grid_widget_block1');
        foreach ($block as $b) :
        setup_postdata($b);
    ?>
        <div class="row">
            <div class="grid-item columns small-12">
                <div class="grid-item__wrapper">
                    <div class="grid-item__image" style="background-image: url('<?php the_field('hero_image', $b->ID) ?>');"></div>
                    <a href="<?php echo get_permalink($b->ID); ?>"><div class="grid-item__title"><?php echo $b->post_title ?></div></a>
                </div>
            </div>
        </div>
    <?php
        wp_reset_postdata();
        endforeach;
    ?>
        <div class="row">
    <?php
        $block = get_field('grid_widget_block2');
        foreach ($block as $b) :
        setup_postdata($b);
    ?>
            <div class="grid-item columns small-12 medium-6">
                <div class="grid-item__wrapper">
                    <div class="grid-item__image" style="background-image: url('<?php the_field('hero_image', $b->ID) ?>');"></div>
                    <a href="<?php echo get_permalink($b->ID); ?>"><div class="grid-item__title"><?php echo $b->post_title ?></div></a>
                </div>
            </div>
    <?php
        wp_reset_postdata();
        endforeach;
    ?>

    <?php
        $block = get_field('grid_widget_block3');
        foreach ($block as $b) :
        setup_postdata($b);
    ?>
            <div class="grid-item columns small-12 medium-6">
                <div class="grid-item__wrapper">
                    <div class="grid-item__image" style="background-image: url('<?php the_field('hero_image', $b->ID) ?>');"></div>
                    <a href="<?php echo get_permalink($b->ID); ?>"><div class="grid-item__title"><?php echo $b->post_title ?></div></a>
                </div>
            </div>
    <?php
        wp_reset_postdata();
        endforeach;
    ?>
        </div>
        <div class="row">
    <?php
        $block = get_field('grid_widget_block4');
        foreach ($block as $b) :
        setup_postdata($b);
    ?>
        <div class="grid-item columns small-12 medium-6">
            <div class="grid-item__wrapper">
                <div class="grid-item__image" style="background-image: url('<?php the_field('hero_image', $b->ID) ?>');"></div>
                <a href="<?php echo get_permalink($b->ID); ?>"><div class="grid-item__title"><?php echo $b->post_title ?></div></a>
            </div>
        </div>
    <?php
        wp_reset_postdata();
        endforeach;
    ?>
    <?php
        $block = get_field('grid_widget_block5');
        foreach ($block as $b) :
        setup_postdata($b);
    ?>
        <div class="grid-item columns small-12 medium-6">
            <div class="grid-item__wrapper">
                <div class="grid-item__image" style="background-image: url('<?php the_field('hero_image', $b->ID) ?>');"></div>
                <a href="<?php echo get_permalink($b->ID); ?>"><div class="grid-item__title"><?php echo $b->post_title ?></div></a>
            </div>
        </div>
    <?php
        wp_reset_postdata();
        endforeach;
    ?>
        </div>

    <?php
        $block = get_field('grid_widget_block6');
        foreach ($block as $b) :
        setup_postdata($b);
    ?>
        <div class="row">
            <div class="grid-item columns small-12">
                <div class="grid-item__wrapper">
                    <div class="grid-item__image" style="background-image: url('<?php the_field('hero_image', $b->ID) ?>');"></div>
                    <a href="<?php echo get_permalink($b->ID); ?>"><div class="grid-item__title"><?php echo $b->post_title ?></div></a>
                </div>
            </div>
        </div>
    <?php
        wp_reset_postdata();
        endforeach;
    ?>
</div>
