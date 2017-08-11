<div class="site-inner nw-drop-cart">
	<?php 
	global $woocommerce;
	$settings = get_option( "nbadcart_plugin_settings" );?>
	<div class="nw-cart-container <?php echo (isset($settings['adcart-drop-trigger'])) ? " nw-cart-".$settings['adcart-drop-trigger']: " nw-cart-hover";?><?php echo (isset($settings['adcart-position']) && ($settings['adcart-position'] == "left" || $settings['adcart-position'] == "right")) ? " nw-cart-side" : "" ;?>">
		<?php //if($settings['adcart-position']=='top') : ?>
		<?php if($settings['adcart-drop-trigger'] == "click") : ?>

			<div class="nw-cart-drop-toggle cart_anchor">
				<?php include_once( NB_ADCart::get_template_path('summary') ); ?>
				<?php if($settings['adcart-icon-display']=='show') : ?>
					<div class="nw-cart-icns<?php echo (isset($settings['adcart-icon-position']) && $settings['adcart-icon-position']=="right") ? " nw-pull-right": "" ;?>">
						<?php if($settings['adcart-icon-skin']==0 && $settings['adcart-custom-icon']!="") :?>
						<img src="<?php echo $settings['adcart-custom-icon'];?>" width="20px" height="20px" alt="Shopping Cart"/>
						<?php else: ?>
						<div class="nw-cart-icns-shape icns-adcartfont icns-style<?php echo $settings['adcart-icon-style'];?>"></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php else: ?>
			<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>">
			<div class="nw-cart-drop-toggle cart_anchor">
				<?php include_once( NB_ADCart::get_template_path('summary') ); ?>
				<?php if($settings['adcart-icon-display']=='show') : ?>
					<div class="nw-cart-icns<?php echo (isset($settings['adcart-icon-position']) && $settings['adcart-icon-position']=="right") ? " nw-pull-right": "" ;?>">
						<?php if($settings['adcart-icon-skin']==0 && $settings['adcart-custom-icon']!="") :?>
						<img src="<?php echo $settings['adcart-custom-icon'];?>" width="20px" height="20px" alt="Shopping Cart"/>
						<?php else: ?>
						<div class="nw-cart-icns-shape icns-adcartfont icns-style<?php echo $settings['adcart-icon-style'];?>"></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
			</a>
		<?php endif; ?>
		<div class="nw-cart-drop-content nw-hidden<?php echo ($settings['adcart-position']!='top') ? " nw-cart-".$settings['adcart-position'] : "" ;?>">
			<?php
				include_once( NB_ADCart::get_template_path('mini-cart') );
			?>
		</div>
	</div>
</div>