<!-- upgrade card -->
<div id="progress"><span class="meter" style="width:66%"><span class="progress_title">Sign-Up Progress 2/3</span>
</span></div>
<section id="pagely_upgrades" class="">
		<div class="hero-unit">
		<?php if ($this->_get_pp_session('domain_queued')) { ?>
	
			<h2>Success! Your order is in queue for setup. Check your email for some welcome messages.</h2>
			<p>Thank you for your purchase. New sites typically take 2-5 minutes to provision. If you do not receive a welcome email shortly please <a href="<?php echo $this->_supportsite();?>">contact support</a>. Thanks and Welcome aboard!</p>
		<?php } else {?>
			<h2>Your Order has been received.</h2>
			<p>However there was an error starting the install. Support has already been notified.</h2>
		<?php } ?>
		</div>
	<?php $products = $this->_get_products();
		if ($products) {
		?>
	 	<div id="pagely_cart_wrapper" class="pagely_form">
		 	<legend>Optional Hosting Upgrades</legend>
			<p class="lead">PowerUp your website with these available upgrades. <a href="?complete=true" class="btn btn-mini">No Thanks, I'm done.</a></p>
			<?php if ($this->_get_trial_length() > 0) { ?>
				<p class="alert"><span class="label label-warning">Heads Up</span> PowerUps will be billed immediately and are not part of the <?php echo $this->_get_trial_length();?> day trial.</p>
			<?php } ?>
			
			<fieldset id="powerups">
				<div class="row">
					<div class="span7">
					<!-- products list -->
							<form method="post" action="" id="pagely_form_products" class="pagely_form">
								<table width="100%" id="product_list" class="table table-condensed table-bordered table-striped">
								<?php foreach ($products as $p) { ?>
									<tr class="product_row">
										<td class="span1"><input type="checkbox" id="u<?php echo $p->id?>" name="pagely_order[product_upgrades][]" value="<?php echo $p->id?>" /></td>
										<td class="desc"><p><strong><label for="u<?php echo $p->id?>"><?php _e($p->name);?></label></strong><?php _e(stripslashes($p->desc));?></p></td>		
										<td class="price"><?php echo $this->_cash($p->price);?><?php if ( 1 == $p->recurring) {?>/<em class="cycle">mo</em><?php } ?></td>
									</tr>
								<?php	 } ?>
								</table>
								
								<div class="add_to_cart form-actions">
									<input type="submit" id="add_to_cart" class="btn btn-primary" value="Add to Cart">
								</div>
								<!-- /products list -->
								<?php wp_nonce_field('pagely_cart_submit','pagely_order_cart_nounce'); ?>
								<input type="hidden" name="action" value="pagely_jax_addtocart_callback"/>
							</form>
					</div>
					
						<div id="cartholder" class="span4 offset1">						
						<?php echo $this->_get_view( PP_PLUGIN_ASSETS_PATH . 'views/cart.php'); ?>
						</div>
				</div>	
			</fieldset>
			</div>
	 <?php } else {  $this->_clear_pp_session(); // clear the session ?>
		
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Return to Home</a>


	<?php } ?>
</section>
<!-- / upgrade card -->
