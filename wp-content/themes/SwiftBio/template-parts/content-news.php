<?php
/**
 * Template part for displaying page content for news
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package _q
 */

if ($post->post_type == "careers"){
    $leadInID = 1133;
} elseif ($post->post_type == 'events') {
    $leadInID = 1140;
} else {
    $leadInID = 105;

}

?>
<h1 class="page-title"><a href="<?php echo get_post_type_archive_link($post->post_type); ?>"><?php echo get_archive_title(); ?></a></h1>
<?php if (!is_search() && !is_single()): the_field('lead-in-content', $leadInID); endif; ?>

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

                <?php
                    if (get_field('start_date')) {
                        the_field('start_date');
                    }

                    if (get_field('end_date')) {
                        echo ' to ' . get_field('end_date');
                    }

                    if (get_field('location')) {
                        echo '<br />' . get_field('location');
                    }

                    if (get_field('booth')) {
                        the_field('booth');
                    }
                ?>
        </div>
    </div>
<?php endwhile; endif; ?>
