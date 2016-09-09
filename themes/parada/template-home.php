<?php
/**
 * Template Name: Homepage Template
 */
?>

<?php if( get_field('hero_image') ): ?>
    <section class="hero">
        <div class="hero__image" style="background-image: url('<?php the_field('hero_image'); ?>')"></div>
        <div class="hero__opacity"></div>
        <div class="hero__tt">
            <img src="<?php site_icon_url(); ?>" alt="<?php bloginfo('name'); ?> logo" class="hero__logo site__logo">
            <h1 class="hero__title"><?php bloginfo('name'); ?></h1>
        </div>
    </section>
<?php endif; ?>
<?php while (have_posts()) : the_post(); ?>
    <!-- Page content -->
    <?php get_template_part('templates/content', 'page'); ?>
<?php endwhile; ?>
