<!-- This form is a little messy right now, we should just to all the return proceessing with js, future upate will fox -->
<script>
 var cookie = pagely_get_cookie();

 jQuery(document).ready(function($) {
 	$('#plan_name').html(cookie.plan_id);
 	$('#act_name').html(cookie.act_name);
 	$('#inputactid').val(cookie.act_id);
 	$('#inputplanid').val(cookie.plan_id);
 	$('#inputbillingcycle').val(cookie.billing_cycle);
 	$('#inputpromo').val(cookie.promo_code);
 	display_plan_right();
 });
</script>
<div id="pagely_purchase_confirmation" class="row">

	<div class="span8 col-md-8 pagely_confirmation_message">
		<div class="pagely_confirmation_message_inner">
		<?php //echo '<pre>';print_r($act);?>
		<h2 class="welcome_name"><i class="fa fa-check"></i> Thank you <strong id="act_name"></strong>,</h2>
		<p class="lead confirm_message"> Your new account has been created. <strong>Please check your inbox for details.</strong></p>
		<h4>Please wait while we finish up... <i class="fa fa-spinner fa-spin"></i></h4>
		 
		 
			<form method="post" name="enroll" action="" id="pagely_form_enroll" class="pagely_form" autocomplete="off">
				<input type="hidden" name="pagely_enroll[plan_id]" id="inputplanid" value=""/>			
				<input type="hidden" name="pagely_enroll[cycle]" id="inputbillingcycle" value=""/>
				<input type="hidden" name="pagely_enroll[account_id]" id="inputactid" value=""/>
				<input type="hidden" name="pagely_enroll[promo_code]" id="inputpromo" value=""/>
				<input type="hidden" id="sec" name="security" value=""/>
				<input type="hidden" name="action" value="pagely_jax_accenroll_callback"/>
				
				
						
				<!-- <div class="form-submit">
				      <button type="submit" id="pagely_enroll_submit" class="btn btn-primary btn-block btn-lg btn-large pp_submit">Complete Checkout <span style="display:none;" class="waiting fa fa-refresh fa-spin"></span></button>
			    </div>-->
			    
			</form>
			<script>
			 jQuery(document).ready(function($) {
			 	setTimeout(function(){ process_sub(); }, 100);
			 });
			 </script>
			 
		</div>
	</div>
	<div class="span4 col-md-4 pagely_plan_details">
		<div class="pagely_plan_details_inner">
			<h4 class="plan_name"></h4>
         <p class="plan_desc"></p>

         <ul class="list-unstyled data-list">
           <li class="plan_setup_time">Setup Time <span class="pull-right"></span></li>
           <li class="plan_cycle">Billing Interval <span class="pull-right">Monthly</span></li>
           <li class="font-lg plan_price">Price <span class="pull-right"></span></li>
         </ul>		
		</div>
	</div>
</div>

<div class="form_msg"></div> 
