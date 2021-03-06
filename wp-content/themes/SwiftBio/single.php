<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package _q
 */

get_header(); ?>
    <div class="row share">
            <?php get_template_part('template-parts/social-sharing'); ?>
    </div>

    <div id="body-wrap" class="row">

        <div class="main">
            <?php get_template_part('template-parts/content-news'); ?>

            <?php if (get_field('accordion_block')): ?>
                <?php get_template_part('buckets/accordion'); ?>
            <?php endif; ?>
        </div>

        <div class="sidebar">
            <?php get_sidebar(); ?>
        </div>

    </div>
<?php get_footer(); ?>
