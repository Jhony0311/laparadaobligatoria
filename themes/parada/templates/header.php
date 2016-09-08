<header class="header">
    <div class="header__wrapper content-wrapper">
        <div class="logo" id="logo">
            <?php
            if ( function_exists( 'the_custom_logo' ) ) {
                the_custom_logo();
            }
            ?>
        </div>
        <?php
        if (has_nav_menu('primary_navigation')) :
            wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav', 'container_class' => 'navigation', 'container' => 'nav' ]);
        endif;
        ?>
    </div>
</header>
