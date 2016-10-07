<div class="bucket sidebar_callout">
    <h5><?php the_sub_field('title'); ?></h5>
    <div class="text">
         <?php if (get_sub_field('text')): ?><p><?php the_sub_field('text'); ?></p><?php endif; ?>
         <?php $img = get_sub_field('image'); //sizes/sidebar_thumb ?>
         <?php if (get_sub_field('image')): ?>
            <img src="<?php echo $img['sizes']['sidebar_thumb']; ?>" />
         <?php endif; ?>
    </div>
    <?php if (get_sub_field('link_type') == 'Page') {
        $url = get_sub_field('page');
    } else {
        $url = get_sub_field('pdf');
        $target='target="_blank"';
    }
    ?>
    <a href="<?php echo $url;?>" class="bucket button" <?php echo $target; ?>><?php the_sub_field('button_text'); ?></a>
</div>
