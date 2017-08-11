<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart
 *
 * @author 		Netbase
 * @package 	WooCommerce-NB-Cart/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce,$product;
$widget_params = get_option('nbadcart_plugin_settings');
?>
<?php do_action( 'woocommerce_cart_actions' ); ?>

<?php wp_nonce_field( 'woocommerce-cart' ); ?>
<div class="nw-cart-drop-content-in">

<?php do_action( 'woocommerce_before_mini_cart' ); ?>

<ul class="cart_list product_list_widget <?php echo (isset($args['list_class'])) ? $args['list_class'] : "";?>">

	<?php if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) : ?>

		<?php foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) :

			$_product = $cart_item['data'];
			$quantity = $cart_item['quantity'];
			// Only display if allowed
			
			if ( ! apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) || ! $_product->exists() || $cart_item['quantity'] == 0 )
				continue;

			// Get price
			$product_price = get_option( 'woocommerce_tax_display_cart' ) == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();

			$product_price = apply_filters( 'woocommerce_cart_item_price_html', woocommerce_price( $product_price ), $cart_item, $cart_item_key );
			?>

			<li>
				<div class="nw-cart-product-title">
					<div class="thumbnail-p">
						<?php echo $_product->get_image(); ?>
					</div>
					<div class="info-pro">
						<div class="p-title">
							<a href="<?php echo get_permalink( $cart_item['product_id'] ); ?>">
			
								<?php echo apply_filters('woocommerce_widget_cart_product_title', $_product->get_title(), $_product ); 
								?>
							</a>
						</div>
						<?php echo WC()->cart->get_item_data( $cart_item ); ?>
						<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
					</div>
				</div>

				<?php echo sprintf('<a href="javascript:void(0);" rel="'.$cart_item_key.'" class="ajax-remove-item remove" title="%s">&times;</a>', __( 'Remove this item', 'woocommerce' ) ); ?>
			</li>

		<?php endforeach; ?>

	<?php else : ?>

		<li class="empty">
		<?php _e( 'Empty cart!', 'woocommerce' ); ?></li>

	<?php endif; ?>

</ul><!-- end product list -->

<?php if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) : ?>
	
	<?php if($widget_params['adcart-subtotal']==1) : ?>
		<p class="total"><strong><?php _e( 'Subtotal', 'woocommerce' ); ?>:</strong> <?php echo $woocommerce->cart->get_cart_subtotal(); ?></p>
	<?php endif; ?>
	
	<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

	<ul class="nw-buttons">
		<li>
			<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" class="button"><?php _e( 'View Cart &rarr;', 'woocommerce' ); ?></a>
		</li>
		<li>
			<a href="<?php echo $woocommerce->cart->get_checkout_url(); ?>" class="button checkout"><?php _e( 'Checkout &rarr;', 'woocommerce' ); ?></a>
		</li>
		

	</ul>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
</div>