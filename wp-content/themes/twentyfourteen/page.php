<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

<div id="main-content" class="main-content">

<?php
	if ( is_front_page() && twentyfourteen_has_featured_posts() ) {
		// Include the featured content template.
		get_template_part( 'featured-content' );
	}
?>
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<?php
            		// Start the Loop.
            		while ( have_posts() ) : the_post();

            			// Include the page content template.
            			get_template_part( 'content', 'page' );

            			// If comments are open or we have at least one comment, load up the comment template.
            			if ( comments_open() || get_comments_number() ) {
            				comments_template();
            			}
                                ?>

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
                                                <?php endwhile;
				endwhile;
			?>

                                            </tbody>
                                        </table>



		</div><!-- #content -->
	</div><!-- #primary -->
	<?php get_sidebar( 'content' ); ?>
</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();
