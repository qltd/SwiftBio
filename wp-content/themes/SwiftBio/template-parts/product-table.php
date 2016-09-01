<table>
    <thead>
        <tr>
            <td>Catalog No.</td>
            <td>Library Kit</td>
            <td>RXNS</td>
            <td>Price</td>
            <td>QTY</td>
            <td></td>
        </tr>
    </thead>
    <tbody>

        <?php
        while(have_rows('products')): the_row();
            $product = get_sub_field('product');
            $sku = get_post_meta( $product[0]->ID, '_sku');
            $price = get_post_meta( $product[0]->ID, '_price');
            ?>
            <tr>
                <td><?php echo $sku[0]; ?></td>
                <td><?php echo $product[0]->post_title; ?></td>
                <td>12</td>
                <?php if (detectLocation()):  ?>
                <td>$<?php echo $price[0]; ?>.00</td>
                <td><input type="number" data-product="<?php echo $product[0]->ID ?>" step="1" min="1" max="999" name="quantity" value="1" title="Qty" class="input-text qty text" size="4" pattern="[0-9]*" inputmode="numeric"></td>
                <td><?php echo do_shortcode('[add_to_cart id="' . $product[0]->ID . '"]'); ?></td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>

    </tbody>
</table>