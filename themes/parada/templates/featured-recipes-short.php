<?php $classes = array(
    'recipe-prev',
    'recipe-prev--featured'
); ?>
<article <?php post_class($classes); ?>>
    <?php if(get_post_thumbnail_id()): ?>
        <?php
        $thumb_id = get_post_thumbnail_id();
        $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail-size', true);
        $thumb_url = $thumb_url_array[0];
        ?>
        <div class="recipe-prev--featured__thumb" style="background-image: url(<?php echo $thumb_url ?>)"></div>
    <?php endif; ?>
    <h2 class="recipe-prev--featured__title"><?php the_title(); ?></h2>
</article>
