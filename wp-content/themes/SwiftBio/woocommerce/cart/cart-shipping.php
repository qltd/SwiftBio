<?php
/**
 * Shipping Methods Display
 *
 * In 2.1 we show methods per package. This allows for multiple methods per order if so desired.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.2.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Q Edits */
global $post;
$other = false;
$account = false;
/*** Q Edits */
?>
<tr class="shipping">
	<th><?php echo wp_kses_post( $package_name ); ?></th>
	<td data-title="<?php echo esc_attr( $package_name ); ?>" style="max-width: 450px;">
		<?php if ( 1 < count( $available_methods ) ) : ?>
                                <?php /* Q Edits - Turned radio buttons into dropdown instead */ ?>
			<select name="shipping_method[<?php echo $index; ?>]" data-index="<?php echo $index; ?>" id="shipping_method_<?php echo $index; ?>" class="shipping_method select-me">
				<option value=""><?php echo "Select Shipping Method"; ?></option>
                                            <?php foreach ( $available_methods as $method ) : ?>
                                                <option value="<?php echo esc_attr( $method->id ); ?>" <?php selected( $method->id, $chosen_method ); ?>><?php echo wp_kses_post( wc_cart_totals_shipping_method_label( $method ) ); ?>
                                                <?php
                                                    if ($chosen_method == $method->id && $method->label == 'Other') {
                                                        $other = true;
                                                    } elseif ($chosen_method == $method->id && $method->label != 'Flat Rate') {
                                                        $account = true;
                                                    }

                                                    if ($method->label == 'Flat Rate'){
                                                        echo ' Shipping & Handling';
                                                    } else {
                                                        echo ' Handling';
                                                    }
                                                ?></option>
                                            <?php endforeach; ?>
                        	</select>
                            <?php /*** Q Edits */ ?>
                            <?php /* Q Edits - Added Special Shipping Rules */ ?>
                            <?php if ($post->ID == 6): ?>
                                <?php  parse_str($_POST['post_data'], $shipping); ?>

                                <?php if ($other == true): ?>
                                    <?php
                                        if ($shipping['shipping_other'] != ''){
                                            $so = $shipping['shipping_other'];
                                        } else {
                                            $so = $_SESSION['shipping_other'];
                                        }
                                    ?>
                                  <div class="shipping-other">
                                        <label>Shipping Company: <span class="req">*</span></label><br />
                                        <input type="text" class="input-text" name="shipping_other" value="<?php echo $so; ?>" />
                                    </div>
                                <?php endif; ?>

                                <?php if ($account == true || $other == true): ?>
                                    <?php
                                        if ($shipping['shipping_account'] != ''){
                                            $sa = $shipping['shipping_account'];
                                        } else {
                                            $sa = $_SESSION['shipping_account'];
                                        }
                                    ?>
                                    <div class="shipping-account">
                                        <p><small>$20 Handling fee applies.<br />
Shipping charges will be billed to your company directly from your carrier.</small></p>
                                        <label>Shipping Account Number: <span class="req">*</span></label><br />
                                        <input type="text" class="input-text" name="shipping_account" value="<?php echo $sa; ?>"  />
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php /*** Q Edits */ ?>
        <?php elseif ( 1 === count( $available_methods ) ) :  ?>
            <?php
                $method = current( $available_methods );
                printf( '%3$s <input type="hidden" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d" value="%2$s" class="shipping_method" />', $index, esc_attr( $method->id ), wc_cart_totals_shipping_method_label( $method ) );
                do_action( 'woocommerce_after_shipping_rate', $method, $index );
            ?>
        <?php elseif ( WC()->customer->has_calculated_shipping() ) : ?>
            <?php echo apply_filters( is_cart() ? 'woocommerce_cart_no_shipping_available_html' : 'woocommerce_no_shipping_available_html', wpautop( __( 'There are no shipping methods available. Please ensure that your address has been entered correctly, or contact us if you need any help.', 'woocommerce' ) ) ); ?>
        <?php elseif ( ! is_cart() ) : ?>
            <?php echo wpautop( __( 'Enter your full address to see shipping costs.', 'woocommerce' ) ); ?>
        <?php endif; ?>

        <?php if ( $show_package_details ) : ?>
            <?php echo '<p class="woocommerce-shipping-contents"><small>' . esc_html( $package_details ) . '</small></p>'; ?>
        <?php endif; ?>

        <?php if ( ! empty( $show_shipping_calculator ) ) : ?>
            <?php woocommerce_shipping_calculator(); ?>
        <?php endif; ?>
    </td>
</tr>