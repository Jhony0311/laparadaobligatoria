<!-- Hero image -->
<section class="hero">
    <div class="hero__image" style="background-image: url('<?php the_field('hero_image'); ?>')"></div>
    <div class="hero__opacity"></div>
    <div class="hero__tt">
        <p class="hero__title"><?php the_title(); ?></p>
        <p class="hero__subtitle"><?php the_field('hero_tagline'); ?></p>
    </div>
</section>
