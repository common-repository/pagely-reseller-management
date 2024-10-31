		<!-- Cart -->
						<div class="cartwrapper <?php if ($this->_get_pp_session('domain_queued')) { ?>minicart<?php } ?> well">
							<table id="<?php if ($this->_get_pp_session('domain_queued')) {?>powerupcart<?php } else {?>totals<?php } ?>" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th colspan="2" class="cart_title">Your Cart</th>
									</tr>
								</thead>
								
								<tbody>
								<?php $i = 1; $recurring_total = 0;
								if($this->_cart_total_items() > 0) { // cart Items
								 	foreach($this->_cart_contents() as $k => $item):
								 		if($item['recurring'] == 1) {$recurring_total += $item['price'];} 
								 		?>		
								<tr class="product_row">
			  						<td class="<?=$item['id'];?>" rel="<?=$k?>">
										<p><?php echo $item['name']; ?></p>
										<?php if ($this->_get_pp_session('domain_queued')) { ?>
										<small><a href="" class="removeitem btn btn-mini btn-danger" rel="<?=$k?>">Remove</a></small>
										<?php } ?>
			  						</td>
			  						<td class="<?=$item['id'];?> price span1">
			  							<?php if($item['price'] > 0) { ?>
			  								<?php echo $this->_cash($item['price']);?> <?php if ($item['recurring']) { echo '/<em class="cycle">mo</em>'; } ?>
			  								<?php } else {?>-<?php }?>
			  							</td>
								</tr>
		
						<?php $i++; ?>
		
						<?php endforeach; ?>
								<tr class="total_row">
		  							<th style="text-align:right">Total Due Now</th>
		  							<td class="price">
		  								<strong><?php echo $this->_cash($this->_cart_total()); ?></strong>
		  							</td>
								</tr>
								</tbody>
							</table>
	
						<?php } else { ?> <!-- /Cart Items -->
								<tr class="empty total_row">
									<td colspan="2"><em>Empty</em></td>
								</tr>
							</table>
						<?php } ?>
						<?php if ($this->_get_pp_session('domain_queued') && $this->_cart_total_items() > 0) { ?>
							<div class="form-actions purchase_cart">
							<button class="btn btn-primary btn-large" id="pagely_purchase_cart" <?php if($this->_cart_total_items() == 0) {?>disabled<?php }?>>Purchase &raquo;</button>
							</div>		
						<?php } ?>

						<input type="hidden" name="action" value="pagely_jax_purchase_cart_callback"/>
						</div>
						<!-- /Cart -->