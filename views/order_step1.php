<?php
$plans = $this->_get_plans();
//$products = $this->_get_products();
$api_options = get_option('pp_api');
//$form = $this->_get_pp_cookie('acc_form_post');
$form = false;
// we dont want to use a nounce here since the referrer may be cached.

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$referer_parse = parse_url($referer);
$this_site = str_replace(array('http://','https://'),'',site_url());

if (is_array($plans)) { ?>


    <div class="row">
        <div class="span8">
            <div class="form_msg"></div>

            <section id="pagely_account_form" class>
                <form method="post" action="" id="pagely_form_acc" class="pagely_form" autocomplete="off">
                    
                    <legend>New Account Setup</legend>
						  
                    
                    <div id="plan_select">
                        <div class="form-group">
                            <label for="plan">Plan</label> 
                            <select id="planselector" name="pagely_order[plan]" class="required form-control input-lg input-block-level hide">
                                <?php foreach ($plans['plans'] as $p) { ?>
                                <option value="<?php echo $p->id?>">
                                    <?php echo $p->name;?>
                                </option>
                                <?php } ?>
                            </select>
                            <script>
	                            var cookie = pagely_get_cookie();
	                            var plan = cookie.preselected_plan;
	                            if (plan) {
	                            	jQuery('#planselector').val( plan );
	                            }
	                            jQuery('#planselector').show();
	                            display_plan_right();
                            </script>
                        </div>
                    </div>
						  
						  <?php if ($api_options['reseller_id'] == 1) { ?>
						   <div class="form-group">
						   <label for="name">Annual Pre-pay</label>

                    <div class="pre_pay">
                        <label class="checkbox">Pre-Pay Annually and Save 15% <input type="checkbox" name="pagely_order[cycle]" id="p_cycle"></label>
                    </div>
						   </div>
                    <?php }?>
                    <div id="user_data">
                        <div class="form-group">
                            <label for="name">Your Name</label> <input id="po_fname" name="pagely_order[first_name]" type="text" class="form-control input-lg input-block-level required" value="<?php if (isset($form['first_name']) ) { echo $form['first_name']; }?>" placeholder='First'> <input id="po_lname" name="pagely_order[last_name]" type="text" class="form-control input-lg input-block-level required" value="<?php if (isset($form['last_name']) ) { echo $form['last_name']; }?>" placeholder='Last'>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputFile">Email</label> <input id="po_email" name="pagely_order[email]" type="text" class="form-control input-lg input-block-level required email" value="<?php if (isset($form['email']) ) { echo $form['email']; }?>" required="">
                        </div>
                    </div>

                    <div id="security_data">
                        <div class="form-group">
                            <label for="auth_q">Security Question</label> <select name="pagely_order[squestion]" tabindex="" class="input-lg form-control">
                                <option value="smaiden">
                                    Mothers Maiden Name
                                </option>

                                <option value="sbirth">
                                    City of Birth
                                </option>

                                <option value="ssocial">
                                    Last 4 digits of Social Security Number
                                </option>

                                <option value="shigh">
                                    Name of High School
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="sanswer">Security Answer</label> <input id="sanswer" name="pagely_order[sanswer]" type="text" class="input-lg form-control input-block-level required" value="">
                        </div>
                    </div>

                    <div id="promo_code" class="form-group">
                        <?php if ( !isset($form['promo_code']) || (isset($form['promo_code']) && $form['promo_code'] == '' ) ) { ?>

                        <p class="form-control-static"><a href="#" class="showcode"><em>Have a promo code?</em></a></p><?php } ?>

                        <div id="promocode" class="<?php if ( !isset($form['promo_code']) || (isset($form['promo_code']) && $form['promo_code'] == '') ) { ?>hide<?php } ?>">
                            <label for="promocode">Promo Code</label> <input id="promocode" name="pagely_order[promo_code]" type="text" class="input-lg form-control input-block-level" value="<?php if (isset($form['promo_code']) ) { echo $form['promo_code']; }?>">
                        </div>
                    </div>

                    <div id="form_submit" class="create_account">
                        <button id="acc_submit" type="submit" class="btn btn-primary btn-large pp_submit btn-block"><?php _e('Continue') ?> <i style="display:none;" class="waiting fa fa-spinner fa-spin"></i></button>

                        <p class="cookie_notice"><small>Cookie Notice: This site uses cookies to process your transaction.</small></p>
                        <p class="agree_message"><small>By proceeding you agree to our <a class="pp_remote" rel="tos" href="">Terms of Service</a>, <a class="pp_remote" rel="aup" href="#">Acceptable use</a>, and <a class="pp_remote" rel="privacy" href="#">Privacy</a> policies.</small></p>
                    </div><input type="hidden" id="sec" name="security" value=""> <input type="hidden" name="action" value="pagely_jax_accformsubmit_callback"> <input type="hidden" id="pagely_affiliate_code" name="pagely_order[affiliate_code]" value=""> <script src="<?php echo $this->api_endpoint ?>/go/affiliate_js" type="text/javascript">
</script>
                </form>
            </section>
        </div>

        <div class="span4">
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
    </div><!--row-->
    <?php } else { ?>

    <p class="alert alert-warning">Problem with the API. Someone will be flogged momentarily.</p><?php } ?><!-- /basic account page -->
