<?php get_template_part('templates/category-hero-image'); ?>
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
