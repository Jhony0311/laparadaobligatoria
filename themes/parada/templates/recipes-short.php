<article <?php post_class('recipe-prev'); ?>>
    <div class="recipe-prev__detail">
        <a class="recipe-prev__handle" href="<?php echo get_post_permalink() ?>"><h2 class="recipe-prev__title"><?php the_title(); ?></h2></a>
        <?php the_excerpt(); ?>
    </div>
    <?php if(get_post_thumbnail_id()): ?>
        <?php
        $thumb_id = get_post_thumbnail_id();
        $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail-size', true);
        $thumb_url = $thumb_url_array[0];
        ?>
        <a href="<?php echo get_post_permalink() ?>"><div class="recipe-prev__thumb" style="background-image: url(<?php echo $thumb_url ?>)"></div></a>
    <?php endif; ?>
</article>
