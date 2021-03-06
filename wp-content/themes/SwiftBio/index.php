<?php
/**
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

            <?php if (get_field('accordion_block') && !is_search()): ?>
                <?php get_template_part('buckets/accordion'); ?>
            <?php endif; ?>
        </div>

        <div class="sidebar">
            <?php if (!is_search()): ?>
                <?php get_sidebar(); ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="row pagination">
        <?php if (function_exists("pagination")) {
            pagination();
        } ?>
        </div>
<?php get_footer(); ?>