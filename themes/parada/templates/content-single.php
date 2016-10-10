<?php
    $p = get_page(16);
?>
<!-- Hero image -->
<?php if( get_field('hero_image', $p->ID) ): ?>
    <section class="hero">
        <div class="hero__image" style="background-image: url('<?php the_field('hero_image', $p->ID); ?>')"></div>
        <div class="hero__opacity"></div>
        <div class="hero__tt">
            <div class="hero__title"><?php echo $p->post_title; ?></div>
            <div class="hero__subtitle"><?php the_field('hero_tagline', $p->ID); ?></div>
        </div>
    </section>
<?php endif; ?>
<div class="content-wrapper row">
    <div class="small-12 medium-8 columns">
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class(); ?>>
                <header>
                    <?php if(get_post_thumbnail_id()): ?>
                        <?php
                            $thumb_id = get_post_thumbnail_id();
                            $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail-size', true);
                            $thumb_url = $thumb_url_array[0];
                        ?>
                        <img src="<?php echo $thumb_url ?>" alt="" class="featured-image">
                    <?php endif; ?>
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <?php get_template_part('templates/entry-meta'); ?>
                </header>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
                <footer>
                    <?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
                </footer>
                <?php comments_template('/templates/comments.php'); ?>
            </article>
        <?php endwhile; ?>
    </div>
    <div class="small-12 medium-4 columns">
        <?php dynamic_sidebar('sidebar-primary'); ?>
    </div>
</div>
