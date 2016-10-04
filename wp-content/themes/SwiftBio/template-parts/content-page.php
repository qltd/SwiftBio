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

        <?php if (isset($_GET['success'])): ?>
            <div id="form-message">
            <?php if ($_GET['success'] == 'true'): ?>
               Thank you for your inquiry. A representative from Swift Biosciences will be in contact with you shortly regarding your inquiry.
            <?php elseif ($_GET['success'] == 'false'): ?>
                ERROR: Please verify that you are a human.
            <?php endif; ?>

            </div>
        <?php endif; ?>

        <?php the_content(); ?>

    </div>
<?php endwhile; endif; ?>
