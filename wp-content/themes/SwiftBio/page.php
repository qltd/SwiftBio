<?php
/**
* Basic page template
*
* @package _q
*/

get_header(); ?>
    <div class="row share">
            <?php get_template_part('template-parts/social-sharing'); ?>
    </div>

    <div id="body-wrap" class="row">

        <div class="main <?php if ($post->ID == 5 || $post->ID == 6): //if cart page go full width ?>no-pad<?php endif;?>">
            <?php get_template_part('template-parts/content-page'); ?>

            <?php if (get_field('accordion_block')): ?>
                <?php get_template_part('buckets/accordion'); ?>
            <?php endif; ?>

            <?php if (get_field('products') && $post->post_name != 'sample-page'): ?>
                <h2 class="sub-page-title">Place Order</h2>
                <h3 class="product-lead-in"><?php the_field('product_table_lead_in_text'); ?></h3>
                <?php get_template_part('template-parts/product-table'); ?>

                <?php if(get_field('additional_products_table')): ?>
                    <h3 class="product-lead-in"><?php the_field('additional_products_lead_in_text'); ?></h3>
                    <?php get_template_part('template-parts/additional-product-table'); ?>
                <?php endif; ?>

                <?php if(get_field('optional_products')): ?>
                    <h3 class="product-lead-in"><?php the_field('optional_products_lead_in_text'); ?></h3>
                    <?php get_template_part('template-parts/optional-product-table'); ?>
                <?php endif; ?>

                <div class="product-table-footer">
                    <div class="col">
                        <strong>For Customers Outside the United States:</strong> Please <a href="<?php echo get_permalink(1082); ?>">request a quote</a> or select from our <a href="<?php echo get_permalink(278); ?>">list of distributors</a>.
                    </div>
                    <div class="col">
                        <a href="<?php echo get_permalink(1080); ?>" class="button">Request Sample</a>
                    </div>
                    <div class="col">
                        <?php if (detectLocation()):  ?>
                            <a href="<?php echo get_permalink(5); ?>" class="button">View Cart</a>
                        <?php else: ?>
                            <a href="<?php echo get_permalink(278); ?>" class="button">Find Distributor</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="clear:both;"></div>
            <?php endif; ?>

            <?php if (get_field('related_products')): ?>
                <div class="related-products">
                    <?php the_field('related_products'); ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($post->ID != 5 && $post->ID != 6): //if cart page go full width ?>
            <div class="sidebar">
                <?php get_sidebar(); ?>
            </div>
        <?php endif; ?>
                <div style="clear:both;"></div>

    </div>
<?php get_footer(); ?>