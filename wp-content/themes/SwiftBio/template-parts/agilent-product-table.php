<table class="product-table">
    <thead>
        <tr>
            <th>Catalog No.</th>
            <th>Description</th>
            <th>Price</th>
            <th style="width: 270px;"><?php if (detectLocation()):  ?>QTY<?php endif; ?></th>
        </tr>
    </thead>
    <tbody>

        <?php
        while(have_rows('agilent_products_table')): the_row();
            $product = get_sub_field('product');
            $sku = get_post_meta( $product->ID, '_sku');
            $price = get_post_meta( $product->ID, '_price');
            ?>
            <tr>
                <td><?php echo $sku[0]; ?>&nbsp;</td>
                <td style="width: 450px; padding-right: 4rem;"><?php echo $product->post_title; ?></td>
                <?php if (detectLocation()):  ?>
                    <td>$<?php echo $price[0]; ?>.00</td>
                    <td><input type="number" data-product="<?php echo $product->ID ?>" step="1" min="1" max="999" name="quantity" value="1" title="Qty" class="input-text qty text" size="4" pattern="[0-9]*" inputmode="numeric">
                    <?php echo do_shortcode('[add_to_cart id="' . $product->ID . '"]'); ?></td>
                <?php else: ?>
                    <td>Inquire</td>
                    <td><a href="<?php echo get_permalink(278); ?>" class="button product_type_simple add_to_cart_button ajax_add_to_cart">Find a Distributor</a></td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>

    </tbody>
</table>