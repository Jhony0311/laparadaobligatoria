<header class="header">
    <div class="header__wrapper content-wrapper">
        <div class="logo" id="logo">
            <a href="<?= esc_url(home_url('/')); ?>"><img src="{{ url }}" alt="<?php bloginfo('name'); ?>" /></a>
        </div>
        <nav class="navigation">
            <?php
            if (has_nav_menu('primary_navigation')) :
              wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']);
            endif;
            ?>
        </nav>
    </div>
</header>
