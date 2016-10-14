<?php while (have_rows('accordion_block')): the_row(); $c = preg_replace('/[^A-Za-z0-9\-\']/', "", str_replace(' ', '-', strtolower(get_sub_field('title')))); ?>
<div id="exp-<?php echo $c; ?>" class="bucket expand-collapse">
    <a href="#exp-<?php echo $c; ?>" class="expander collapsed"><?php the_sub_field('title');?> <i class="fa fa-plus-circle"></i></a>
    <div class="content">
        <?php the_sub_field('content'); ?>
    </div>
</div>
<?php endwhile; ?>