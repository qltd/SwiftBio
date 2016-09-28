<ul class="bucket distributor-list">
    <?php while(have_rows('distributors')): the_row(); ?>
        <li>
            <h3><?php the_sub_field('location'); ?></h3>
            <div class="row">
                <div class="left">
                    <?php $logo = get_sub_field('logo'); ?>
                    <img src="<?php echo $logo['sizes']['large']; ?>" />
                </div>
                <div class="right">
                    <?php the_sub_field('information'); ?>
                </div>
            </div>
        </li>
    <?php endwhile; ?>
</ul>