<?php
    $target = false;
    if (get_sub_field('link_type') == 'Internal Link'){
        $url = get_sub_field('page');
    } elseif (get_sub_field('link_type') == 'External Link'){
        $url = get_sub_field('url');
        $target='target="_blank"';
    } elseif (get_sub_field('link_type') == 'File'){
        $url = get_sub_field('file');
    }
    if (isset($url)):
?>
<a href="<?php echo $url; ?>" <?php echo $target; ?> class="bucket button">
    <?php the_sub_field('text'); ?>
</a>
<?php endif; ?>