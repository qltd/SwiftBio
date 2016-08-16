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
                <div class="col">
                    <a href="#" class="logo"></a>

                    <address>58 Parkland Plaza, Ste, 100<br />
                    Ann Arbor, MI 48103 USA</address>

                    <p>
                        Tel: 734-330-2568<br />
                        Toll-Free: 844-867-7028<br />
                        Fax: 734-527-6709
                    </p>

                    <a href="#">Email Technical Support</a>
                    <a href="#">Email Orders</a>

                    <ul class="social-icons">
                        <li><a href="#">Twitter</a></li>
                        <li><a href="#">Twitter</a></li>
                        <li><a href="#">Twitter</a></li>
                    </ul>
                </div>

                <nav>
                    <?php get_template_part('template-parts/primary-nav'); ?>
                </nav>

            </footer><!-- #colophon -->

        </div> <!-- #wrapper -->
    </div> <!-- #container -->


    <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/app.js"></script>
    <?php wp_footer(); ?>

    </body>
</html>