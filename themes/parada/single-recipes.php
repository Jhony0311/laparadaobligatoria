<?php
    $p = get_page(14);
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
<!-- /Hero image -->
<?php while (have_posts()) : the_post(); ?>
    <div class="content-wrapper">
        <article <?php post_class('recipe-full'); ?>>
            <div class="recipe-full__content">
                <h1 class="recipe-full__title"><?php the_title(); ?></h1>
                <div class="recipe-full__story"><?php the_content(); ?></div>
            </div>
            <div class="recipe-full__sidebar">
                <div class="recipe-full__ingredient-box">
                    <h3 class="ingredient-box__title">Ingredientes</h3>
                    <div class="ingredient-box__content">
                        <?php the_field('ingredients') ?>
                    </div>
                </div>
                <div class="recipe-full__step-box">
                    <h3 class="step-box__title">Pasos</h3>
                    <div class="step-box__content">
                        <?php
                        // check if the repeater field has rows of data
                        if( have_rows('steps') ):
                            $index = 1;
                            // loop through the rows of data
                            echo '<ol>';
                            while ( have_rows('steps') ) : the_row();

                            // display a sub field value
                                echo '<li>'.get_sub_field('step_text').'</li>';
                                $index++;
                            endwhile;
                            echo '</ol>';
                        else :

                            echo 'Lo sentimos, no hay pasos para esta receta';

                        endif;

                        ?>
                    </div>
                </div>
            </div>
        </article>
        <?php comments_template('/templates/comments.php'); ?>
    </div>
<?php endwhile; ?>
<?php
$args = array(
    'post_type' => 'recipes'
);
$query = new WP_Query( $args );
?>
<div class="featured-recipes">
    <div class="row small-collapse">
        <div class="small-12 columns">
            <div class="small-12 columns">
                <h2 class="featured-recipes-title">Las Recetas Obligatorias</h2>
            </div>
        </div>
        <div class="small-12 columns">
         <?php if ($query->have_posts()) : ?>
             <?php while ($query->have_posts()) : $query->the_post(); ?>
                 <?php get_template_part('templates/featured-recipes-short', get_post_type() != 'recipes' ? get_post_type() : get_post_format()); ?>
             <?php endwhile; ?>
         <?php endif; ?>
        </div>
    </div>
</div>
