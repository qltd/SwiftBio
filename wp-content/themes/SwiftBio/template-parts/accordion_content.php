<?php while (have_rows('accordion_block')): the_row(); ?>
<div class="bucket expand-collapse">
    <a href="#" class="expander collapsed"><?php the_sub_field('title');?> <i class="fa fa-plus-circle"></i></a>
    <div class="content">
        <?php the_sub_field('content'); ?>
    </div>
</div>
<?php endwhile; ?>