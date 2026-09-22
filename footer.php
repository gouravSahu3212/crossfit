<?php
/**
 * Custom Footer
 *
 * @package Codyweb_Child
 */
?>
<footer class="site-footer">
    <div class="page-width">
        <div class="footer-grid">

            <div class="footer-col footer-brand">
                <h3 class="footer-logo">CROSSFIT KOUVOLA</h3>
                <p class="footer-tagline">Coached functional training in Kouvola since 2013. CrossFit, HYROX and everything in between.</p>
            </div>

            <div class="footer-col">
                <h4 class="footer-heading">Menu</h4>
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'footer_menu',
                    'container'      => false,
                    'menu_class'     => 'footer-links',
                    'fallback_cb'    => false,
                    'depth'          => 1,
                ) );
                ?>
            </div>

            <div class="footer-col">
                <h4 class="footer-heading">Contact</h4>
                <ul class="footer-links footer-contact">
                    <li>Salpausselänkatu 42, 45100 Kouvola</li>
                    <li><a href="mailto:info@crossfitkouvola.com">info@crossfitkouvola.com</a></li>
                    <li><a href="tel:+358400000000">+358 40 000 0000</a></li>
                </ul>
            </div>

        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date( 'Y' ); ?> CrossFit Kouvola &mdash; All rights reserved.</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>