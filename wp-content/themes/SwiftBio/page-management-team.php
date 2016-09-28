<?php
/**
* Management Team page template
*
* @package _q
*/

get_header(); ?>
    <div class="row share">
            <?php get_template_part('template-parts/social-sharing'); ?>
    </div>

    <div id="body-wrap" class="row">

        <div class="main">
            <?php get_template_part('template-parts/content-page'); ?>

            <?php if (get_field('management_team')): ?>
                <ul class="management-team">
                <?php while (have_rows('management_team')): the_row(); ?>
                    <li>
                        <div class="left">
                            <?php $img = get_sub_field('image'); ?>
                            <img src="<?php echo $img['sizes']['large']; ?>" />
                        </div>
                        <div class="right">
                            <h4><?php the_sub_field('name'); ?></h4>
                            <h5><?php the_sub_field('title'); ?></h5>
                            <p><?php the_sub_field('bio'); ?></p>
                        </div>
                    </li>
                <?php endwhile; ?>
                </ul>
            <?php endif; ?>

            <?php if (get_field('accordion_block')): ?>
                <?php get_template_part('buckets/accordion'); ?>
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