<div class="nw-cart-contents <?php echo (isset($settings['adcart-icon-position']) && $settings['adcart-icon-position']=="right") ? ' nw-pull-left': "" ;?>">
<?php 
	global $woocommerce;
	$settings = get_option( "nbadcart_plugin_settings" );
	$cart_contents_count = 0;
	$show_only_individual = false;
	foreach($woocommerce->cart->cart_contents as $key => $product) {

		if($show_only_individual) {
			if($product['data']->product_type=='simple' && !isset($product['bundled_by']) ) $cart_contents_count++;
			if($product['data']->product_type=='bundle') $cart_contents_count++;
			if($product['data']->product_type=='variation') $cart_contents_count++;
		}else {
			if($product['data']->product_type=='simple' && !isset($product['bundled_by']) ) $cart_contents_count += $product['quantity'] ;
			if($product['data']->product_type=='bundle') $cart_contents_count += $product['quantity'];
			if($product['data']->product_type=='variation') $cart_contents_count += $product['quantity'];
		}
	}
?>

<?php if($settings['adcart-numsub'] == "sub") : ?>
	<span class="nw-visible-desktop nw-hidden-phone">
			<?php echo sprintf(_n('%d '.'item', '%d '.'items', $cart_contents_count, 'netbasecart'), $cart_contents_count);?> - <?php echo strip_tags($woocommerce->cart->get_cart_total()); ?>
	</span>
	<span class="nw-short-contents visible-tablet nw-visible-phone">
		<?php echo $cart_contents_count;?>
	</span>
<?php else : ?>
	<span class="nw-short-contents number-cont">
		<?php echo $cart_contents_count;?>
	</span>
<?php endif; ?>
</div>