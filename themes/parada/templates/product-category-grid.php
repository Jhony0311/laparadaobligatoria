<div class="content-wrapper">
    <div class="row">
        <p class="heading">Explora nuestros productos</p>
        <p class="sub-heading">Lorem ipsum dolor sit amet</p>
    </div>
    <?php if (get_field('grid_widget_block1')) : ?>
        <div class="row">
        <?php
            $block = get_field('grid_widget_block1');
            setup_postdata($block);
        ?>
            <div class="grid-item columns small-12">
                <div class="grid-item__wrapper">
                    <div class="grid-item__image" style="background-image: url('<?php get_field('product_type_text', ('product_type_'.$block->term_id)) ?>');"></div>
                    <a href="<?php the_permalink(); ?>"><div class="grid-item__title"><?php echo $block->name ?></div></a>
                </div>
            </div>
        <?php wp_reset_postdata(); ?>
        </div>
    <?php endif; ?>
    <?php if (get_field('grid_widget_block2')) : ?>
        <div class="row">
        <?php
            $block = get_field('grid_widget_block2');
            setup_postdata($block);
        ?>
            <div class="grid-item columns small-12 medium-6">
                <div class="grid-item__wrapper">
                    <div class="grid-item__image" style="background-image: url('<?php get_field('product_type_text', ('product_type_'.$block->term_id)) ?>');"></div>
                    <a href="<?php the_permalink(); ?>"><div class="grid-item__title"><?php echo $block->name ?></div></a>
                </div>
            </div>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>
    <?php if (get_field('grid_widget_block3')) : ?>
        <?php
            $block = get_field('grid_widget_block3');
            setup_postdata($block);
        ?>
            <div class="grid-item columns small-12 medium-6">
                <div class="grid-item__wrapper">
                    <div class="grid-item__image" style="background-image: url('<?php get_field('product_type_text', ('product_type_'.$block->term_id)) ?>');"></div>
                    <a href="<?php the_permalink(); ?>"><div class="grid-item__title"><?php echo $block->name ?></div></a>
                </div>
            </div>
        <?php wp_reset_postdata(); ?>
        </div>
    <?php endif; ?>


    <?php if (get_field('grid_widget_block4')) : ?>
        <div class="row">
        <?php
            $block = get_field('grid_widget_block4');
            setup_postdata($block);
        ?>
            <div class="grid-item columns small-12 medium-6">
                <div class="grid-item__wrapper">
                    <div class="grid-item__image" style="background-image: url('<?php get_field('product_type_text', ('product_type_'.$block->term_id)) ?>');"></div>
                    <a href="<?php the_permalink(); ?>"><div class="grid-item__title"><?php echo $block->name ?></div></a>
                </div>
            </div>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>
    <?php if (get_field('grid_widget_block5')) : ?>
        <?php
            $block = get_field('grid_widget_block5');
            setup_postdata($block);
        ?>
            <div class="grid-item columns small-12 medium-6">
                <div class="grid-item__wrapper">
                    <div class="grid-item__image" style="background-image: url('<?php get_field('product_type_text', ('product_type_'.$block->term_id)) ?>');"></div>
                    <a href="<?php the_permalink(); ?>"><div class="grid-item__title"><?php echo $block->name ?></div></a>
                </div>
            </div>
        <?php wp_reset_postdata(); ?>
        </div>
    <?php endif; ?>

    <?php if (get_field('grid_widget_block6')) : ?>
        <div class="row">
        <?php
            $block = get_field('grid_widget_block6');
            setup_postdata($block);
        ?>
            <div class="grid-item columns small-12">
                <div class="grid-item__wrapper">
                    <div class="grid-item__image" style="background-image: url('<?php get_field('product_type_text', ('product_type_'.$block->term_id)) ?>');"></div>
                    <a href="<?php the_permalink(); ?>"><div class="grid-item__title"><?php echo $block->name ?></div></a>
                </div>
            </div>
        <?php wp_reset_postdata(); ?>
        </div>
    <?php endif; ?>
</div>
