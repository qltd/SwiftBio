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

                            <address><?php the_field('footer_address', 'options'); ?></address>

                            <p>
                                Tel: <?php the_field('footer_telephone_number', 'options'); ?><br />
                                Toll-Free: <?php the_field('footer_toll_free_number', 'options'); ?><br />
                                Fax: <?php the_field('footer_fax_number', 'options'); ?>
                            </p>

                            <a href="mailto:<?php the_field('tech_support_email', 'options'); ?>" class="email-button"><i class="fa fa-envelope"></i> Email Tech Support</a>
                            <a href="mailto:<?php the_field('order_support_email', 'options'); ?>" class="email-button"><i class="fa fa-envelope"></i> Email Orders</a>

                            <ul class="social-icons">
                                <li><a href="https://twitter.com/SwiftBioSci" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="https://www.facebook.com/Swift-Biosciences-120346024708339/" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="https://www.linkedin.com/company/2098428?trk=tyah&trkInfo=clickedVertical%3Acompany%2CclickedEntityId%3A2098428%2Cidx%3A2-1-2%2CtarId%3A1473958793258%2Ctas%3Aswift%20biosc" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    <nav>
                        <?php get_template_part('template-parts/primary-nav'); ?>
                    </nav>
                </div>
                <div class="footer-btm">
                    <div class="row">
                        <p>&copy; <?php echo date('Y'); ?> Swift Biosciences Inc. All rights reserved. This product is for Research Use Only. Not for use in diagnostic procedures.  |  <a href="<?php echo get_permalink(2099); ?>">Terms of Use</a>  |  <a href="<?php echo get_permalink(288); ?>">Trademark Usage</a> | <a href="<?php echo get_permalink(1722); ?>">Privacy Policy</a>
                    </div>
                </div>
            </footer><!-- #colophon -->


        </div> <!-- #wrapper -->
    </div> <!-- #container -->


    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/app.js"></script>
    <?php /* if (!is_post_type_archive('careers') && !is_page('1080')): ?>
        <script src="https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit" async defer></script>
    <?php endif; */ ?>
    <?php wp_footer(); ?>
<script>
 function convTracker(conv_id, conv_label) {
       var image = new Image(1, 1);
       image.src = "//www.googleadservices.com/pagead/conversion/" + conv_id + "/?label=" + conv_label + "&script=0";
   }
window.addEventListener('load',function(){
jQuery('[href="mailto:TechSupport@swiftbiosci.com"]').click(function(){
convTracker(991539610, "4rIkCJ_-7G4QmuPm2AM");
});
jQuery('[href="mailto:Orders@swiftbiosci.com"]').click(function(){
convTracker(991539610, "BefeCOCL2W4QmuPm2AM");
});
})

</script>
    </body>
</html>