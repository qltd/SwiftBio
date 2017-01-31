<?php
    $link = get_permalink();
    $title = str_replace('&#038;', '&', get_the_title());
    $description = get_the_excerpt();
?>
<p>Share this page:</p>
<a href="mailto:?subject=I wanted you to see this site&amp;body=<?php echo rawurlencode($title) . ': ' . rawurlencode($link); ?>" data-network="email"><i class="fa fa-envelope-o"></i></a>
<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $link; ?>" target="_blank" data-network="facebook" class="icon"><i class="fa fa-facebook"></i></a>
<a href="https://twitter.com/share?text=<?php echo $title; ?>&url=<?php echo $link; ?>" target="_blank" data-network="twitter" class="icon"><i class="fa fa-twitter"></i></a>
<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $link; ?>&title=<?php echo $title; ?>&summary=<?php echo $description; ?>&source=Swift%20BioSciences" target="_blank" data-network="linkedin" class="icon"><i class="fa fa-linkedin"></i></a>
