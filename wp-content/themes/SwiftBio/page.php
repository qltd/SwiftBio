<?php
/**
* Basic page template
*
* @package _q
*/

get_header(); ?>
    <div class="row share">
            //share icons
    </div>

    <div class="row">

        <div class="main">
            <?php get_template_part('template-parts/content-page'); ?>

            <?php if (get_field('accordion_content')): ?>
                <?php get_template_part('buckets/accordion'); ?>
            <?php endif; ?>

            <?php if (get_field('related_products')): ?>
                <div class="related-products">
                    <?php the_field('related_products'); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="sidebar">
            <?php get_sidebar(); ?>
        </div>

    </div>
<?php get_footer(); ?>