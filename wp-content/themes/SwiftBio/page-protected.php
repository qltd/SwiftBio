<?php
/**
* Password protected page template
* Template Name: Password Protected
*
* @package _q
*/

get_header(); ?>
    <div class="row share">
            <?php get_template_part('template-parts/social-sharing'); ?>
    </div>

    <div id="body-wrap" class="row">

        <div class="main <?php if ($post->ID == 5 || $post->ID == 6): //if cart page go full width ?>no-pad<?php endif;?>">
            <?php if (is_user_logged_in()): ?>
                <?php get_template_part('template-parts/content-page'); ?>

                <?php if (get_field('accordion_block')): ?>
                    <?php get_template_part('buckets/accordion'); ?>
                <?php endif; ?>

            <?php else: ?>
                <?php if (get_field('password_protected_alternate_content')): ?>
                    <?php the_field('password_protected_alternate_content'); ?>
                <?php else: ?>
                    You must be logged in to view this content. Register using the form below or log in if you already have an account.

                <?php endif; ?>
            <?php endif; ?>
        </div>



        <?php if ($post->ID != 5 && $post->ID != 6): //if cart page go full width ?>
            <div class="sidebar">
                <?php get_sidebar(); ?>
            </div>
        <?php endif; ?>
                <div style="clear:both;"></div>

    </div>
<?php get_footer(); ?>