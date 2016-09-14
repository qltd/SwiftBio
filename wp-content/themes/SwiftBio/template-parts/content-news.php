<?php
/**
 * Template part for displaying page content for news
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package _q
 */

?>

<h1 class="page-title"><?php echo get_archive_title(); ?></h1>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="post">
        <?php if (is_single()): ?>
            <h2 class="post-title"><?php the_title(); ?></h2>
        <?php else: ?>
            <h1 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
        <?php endif; ?>
        <div class="body-content">
                <?php if (is_single()): ?>
                    <?php the_content(); ?>
                <?php else: ?>
                    <?php the_excerpt(); ?>
                <?php endif; ?>
        </div>
    </div>
<?php endwhile; endif; ?>
