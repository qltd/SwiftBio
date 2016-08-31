<div class="bucket sidebar_callout video">
    <h5><?php the_sub_field('title'); ?></h5>
    <div class="text">
         <?php $img = get_sub_field('thumbnail'); //sizes/sidebar_thumb ?>
         <?php if (get_sub_field('thumbnail')): ?>
            <a href="<?php the_sub_field('video_url'); ?>" class="lightbox-video" target="_blank"><img src="<?php echo $img['sizes']['sidebar_thumb']; ?>" /></a>
         <?php endif; ?>
         <?php if (get_sub_field('text')): ?><p><?php the_sub_field('text'); ?></p><?php endif; ?>
    </div>
    <a href="<?php the_sub_field('video_url'); ?>" class="bucket button lightbox-video" target="_blank">Watch Now</a>
</div>
