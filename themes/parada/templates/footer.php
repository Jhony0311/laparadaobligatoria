<footer class="footer">
    <div class="footer__body">
        <div class="content-wrapper">
            <?php if ( ! dynamic_sidebar('sidebar-footer') ) : ?>
            <div class="footer__item-wrapper">
                <div class="footer__item ft-item">
            	    {static sidebar item 1}
                </div>
                <div class="footer__item ft-item">
            	    {static sidebar item 2}
                </div>
            </div>
            <div class="footer__item-wrapper">
                <div class="footer__item ft-item">
                    {static sidebar item 3}
                </div>
                <div class="footer__item ft-item">
                    {static sidebar item 4}
                </div>
            </div>
            <?php endif; ?>
            <div class="footer__item-wrapper">
                <div class="footer__item ft-item--full ft-item">
                    <?php
                    if ( function_exists( 'the_custom_logo' ) ) {
                        the_custom_logo();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="footer__legal">
        <div class="content-wrapper">
            <p class="footer__copyright"><?php bloginfo('name'); ?> <?php the_date('Y'); ?>. Derechos reservados.</p>
        </div>
    </div>
</footer>
