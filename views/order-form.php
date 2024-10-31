<?php 
	// ajax set the cookie of plan id posted from plan/pricing page
	if (isset($_POST['pagely_order']['plan'])) { 
		$plan = htmlentities($_POST['pagely_order']['plan']);
		$cycle = htmlentities($_POST['pagely_order']['cycle']);
		$array = array('preselected_plan' => $plan, 'billing_cycle' => $cycle );
		//$cookie = json_encode($array);
		
		?>
		
		<script>
			var obj = {'preselected_plan':'<?php echo $plan;?>','billing_cycle':'<?php echo $cycle;?>'};
			pagely_set_cookie(obj);
		</script>
		<?php 
	}
?>

<?php 
	// ajax set the cookie of plan id posted from plan/pricing page
	if ( isset($_POST['pagely_response']) ) {
		$newcookie = $this->_process_payment_response();
		
		?>
		
		<script>
			var obj = <?php echo $newcookie;?>;
			pagely_set_cookie(obj);
		</script>
		<?php 
	}
?>
<!-- pagely plugin styles -->
<style>
<?php 
/* Some default css styles.
 * We recommend you leave the pp-form.css file in place as it handles some validation and such 
 * loaded with wp_enqueue_style()
 * If you want to overide the form styles, you could add the css to file and link in header.php, or save css from the plugins option page that will echo out below. DO NOT save edit this file. Your changes will be overwritten when the plugin updates. 
*/
echo $this->_custom_css();

?>
/*body {
	padding-top: 0px;
}

body footer#colophon, body header#mainnav {
transition: opacity .25s ease-in-out;
   -moz-transition: opacity .25s ease-in-out;
   -webkit-transition: opacity .25s ease-in-out;
	opacity: .2;
}
#signup {
	padding-top:60px;
}
body footer#colophon:hover, body header#mainnav:hover {
	opacity: 1;
}  
.pagely_plan_details_inner {
	border:1px solid #c9d6d6;
	padding:10px;
	background:#fff;

}
.pagely_plan_details_inner .plan_name {
padding-bottom:3px;
border-bottom:1px solid #f2f2f2;
color:#111;
}

.pagely_plan_details_inner .plan_price {
	font-size:130%;
	line-height: 2em;
	
}
.pagely_plan_details_inner .plan_price strong {
	font-weight: 200;
	font-size:26px;
}
.pagely_plan_details_inner ul {
	list-style-type:none;
	padding:0;
	margin:0 0;
}

.pagely_plan_details_inner ul li {
	line-height: 2em;
}
.pagely_plan_details_inner ul li span {
	font-weight: bold;
}
.gravtar_email img {
	margin-right:2em;
}
#pagely_enroll_form,#pagely_enroll_form .form-submit  {
	padding:0px;
	border:0;
	margin:0;
	margin-top:1em;
}
.pagely_confirmation_message_inner h2 {
	margin-top:0;
}

#pagely_account_form form,#misfire,.complete,.pagely_confirmation_message div {
	padding:2em;
	border:1px solid #c9d6d6;
	margin:0 auto;
	background:#fff;
}
#pagely_account_form  label {
	font-weight: 200;
}
#pagely_account_form .form-group {
	margin-bottom:1em;
}
#pagely_account_form select {
	width:100%;
	font-size: 18px;
   height: 42px;
   line-height: 1.33;
   padding: 8px 8px;
}
#pagely_account_form input[type=text], #pagely_account_form input[type=password]{
	 font-size: 18px;
    height: 42px;
    line-height: 1.33;
    padding: 8px 8px;
}
#pagely_account_form {
	position: relative;
}
#pagely_account_form .pre_pay {

	padding:8px 8px 4px 8px;
	border: 1px solid #27ae61;
   background: #fff;
	color:#27ae61;
	cursor: pointer;
	display: block;
	
}
#pagely_account_form .pre_pay:hover,#pagely_account_form .pre_pay.sel {
	background:#1bbc9b;
	color:#fff;
}
#pagely_account_form .pre_pay:hover label.checkbox,#pagely_account_form .pre_pay.sel label.checkbox {
	color:#fff;
}
#pagely_account_form label.checkbox{
		color:#27ae61;
cursor: pointer;
	font-size:18px;
	font-weight: normal;
	
}


#pagely_account_form input[type=checkbox] {
	
}

#pagely_account_form  .cookie_notice,#pagely_account_form  .agree_message {
	text-align: center;
	margin:1em 0 0 0;
}
*/
</style>
<?php //print_r($_POST);?>
<?php if($this->_is_sandbox()) {?><p class="alert">SANDBOX MODE</p><?php } ?>

<div id="pp" class="pagely_order">
	<div id="loader" style="padding:5em 0em;text-align:center"><h2><span>Loading...</span> <i class="waiting fa fa-spinner fa-spin"></i></h2>
	</div>
</div>
<div id="legal_dialog" style="max-height:400px;overflow:scroll;"></div>



<?php if ($this->_is_sandbox() ) { ?>
<p><button id="session">show session</button></p>
<?php //echo '<pre>'; print_r($this->_get_pp_cookie()); ?>
<?php } ?>

<div id="pp_dialog"></div>


