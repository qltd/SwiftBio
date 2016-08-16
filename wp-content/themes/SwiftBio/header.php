<?php
/**
* The header for our theme.
*
* This is the template that displays all of the <head> section and everything up until <div id="content">
    *
    * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
    *
    * @package _q
    */

    ?><!DOCTYPE html>
    <html <?php language_attributes(); ?>>
        <head>
            <meta charset="<?php bloginfo( 'charset' ); ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="profile" href="http://gmpg.org/xfn/11">
            <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
            <script src="https://use.typekit.net/ilp1uhx.js"></script>
            <script>try{Typekit.load({ async: true });}catch(e){}</script>
            <?php wp_head(); ?>
        </head>

        <body <?php body_class(); ?>>
            <div class="nav-overlay">
-                <a href="#" class="nav-close">Close X</a>
+
-                <nav>
                    <?php get_template_part('template-parts/primary-nav'); ?>
                 </nav>-
-            </div>

-            <div class="bg-overlay"></div>


            <header class="site-header">

                <div class="top-bar">
                    <a href="/" title="home" class="logo"><img src="<?php bloginfo('template_directory'); ?>/img/qltd-logo.svg" alt="Swift Biosciences" /></a>

                    <form>
                        <input type="text" />
                    </form>

                    <a href="#">View Cart</a>

                    <a href="#">Login/MyAccount</a>
                </div>

                <div class="menu-bar">
                    <button class="nav-toggle">MENU <span class="bar"></span><span class="bar"></span><span class="bar"></span></button>
                </div>

            </header><!-- #masthead -->

            <div id="content" class="site-content">
