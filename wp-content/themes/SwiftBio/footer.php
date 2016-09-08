<?php
/**
* The template for displaying the footer.
*
* Contains the closing of the #content div and all content after.
*
* @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
*
* @package _q
*/

?>

            </div><!-- #content -->

            <footer id="colophon" class="site-footer" role="contentinfo">
                <div class="row">
                        <div class="col">
                        <a href="/" title="home" class="logo"><img src="<?php bloginfo('template_directory'); ?>/img/swift-logo.svg" alt="Swift Biosciences" /></a>

                        <address>58 Parkland Plaza, Ste, 100<br />
                        Ann Arbor, MI 48103 USA</address>

                        <p>
                            Tel: 734-330-2568<br />
                            Toll-Free: 844-867-7028<br />
                            Fax: 734-527-6709
                        </p>

                        <a href="#" class="email-button"><i class="fa fa-envelope"></i> Email Technical Support</a>
                        <a href="#" class="email-button"><i class="fa fa-envelope"></i> Email Orders</a>

                        <ul class="social-icons">
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                        </ul>
                        </div>
                    <nav>
                        <?php get_template_part('template-parts/primary-nav'); ?>
                    </nav>
                </div>
                <div class="footer-btm">
                    <div class="row">
                        <p>&copy; <?php echo date('Y'); ?> Swift Biosciences Inc. All rights reserved. This product is for Research Use Only. Not for use in diagnostic procedures.  |  <a href="<?php echo get_permalink(115); ?>">Terms & Conditions</a>  |  <a href="<?php echo get_permalink(117); ?>">Trademark Usage</a>
                    </div>
                </div>
            </footer><!-- #colophon -->


        </div> <!-- #wrapper -->
    </div> <!-- #container -->


    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/app.js"></script>
    <?php wp_footer(); ?>

    </body>
</html>