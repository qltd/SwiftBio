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

    <div class="body-content">
            <?php the_content(); ?>
    </div>
<?php endwhile; endif; ?>
