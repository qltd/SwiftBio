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

            <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,700" rel="stylesheet">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
            <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
            <?php wp_head(); ?>

            <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js?bc_t=SbxqoR3c','ga');

    ga('create', 'UA-53866426-1', 'auto');
    ga('send', 'pageview');

  </script>

        </head>

        <body <?php body_class(); ?>>

            <div class="nav-overlay">
                <a href="#" class="nav-close">Close <i class="fa fa-times-circle"></i></a>
                <nav>
                    <?php get_template_part('template-parts/primary-nav'); ?>
                </nav>
            </div>

            <div class="bg-overlay"></div>


            <header class="site-header">

                <div class="top-bar">
                    <div class="row">

                        <div class="search">

                           <form method="get" id="search" action="<?php bloginfo('url'); ?>/">
                                <input type="text" size="18" value="<?php echo $s; ?>" name="s" id="s" />
                                <button id="searchsubmit" ><i class="fa fa-search"></i></button>
                            </form>
                        </div>

                        <a href="<?php echo get_permalink(5); ?>"><i class="fa fa-shopping-cart"></i></a>

                        <?php if (is_user_logged_in()): ?>
                        <a href="<?php echo get_permalink(7); ?>">Account</a>
                        <?php else: ?>
                        <a href="<?php echo wp_login_url(); ?>" title="Login">Login</a>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="menu-bar">
                    <div class="row">
                    <a href="/" title="home" class="logo"><img src="<?php bloginfo('template_directory'); ?>/img/swift-logo.svg" alt="Swift Biosciences" /></a>
                    <button class="nav-toggle"><span>MENU</span> <i class="fa fa-bars"></i></button>
                    </div>
                </div>
                </header><!-- #masthead -->

                <div id="content" class="site-content">