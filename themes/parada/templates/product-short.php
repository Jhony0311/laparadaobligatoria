<article <?php post_class('product-prev equalize'); ?>>
    <?php if(get_post_thumbnail_id()): ?>
        <?php
        $thumb_id = get_post_thumbnail_id();
        $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail-size', true);
        $thumb_url = $thumb_url_array[0];
        ?>
        <div class="product-prev__thumb" style="background-image: url(<?php echo $thumb_url ?>)"></div>
    <?php endif; ?>
    <div class="product-prev__detail">
        <h2 class="product-prev__title"><?php the_title(); ?></h2>
    </div>
</article>
