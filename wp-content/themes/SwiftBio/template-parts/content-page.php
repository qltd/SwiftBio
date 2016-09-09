<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package _q
 */

?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <h1 class="page-title"><?php the_title(); ?></h1>

    <?php if (get_field('product_tagline')): ?>
        <div class="product-tagline">
            <?php if ( has_post_thumbnail() ): ?>
                <img src="<?php the_post_thumbnail_url('product_tagline'); ?>" />
            <?php endif; ?>
            <div class="tagline">
                <?php the_field('product_tagline'); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="body-content">
            <?php the_content(); ?>
    </div>
<?php endwhile; endif; ?>
