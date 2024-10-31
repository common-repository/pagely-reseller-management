<?php 
class PagelyAPIOptions extends PagelyApi{
	
 	var $api = '';
	public function __construct() {
	}
	

	/************
	* THE PAGE.LY API OPTIONS PAGE
	*************/
	public function _create_menu() {
		//create new top-level menu
		$pagely_options = get_option('pp_api');
		if (isset($pagely_options['pp_menu_placement']) && 'sub' == $pagely_options['pp_menu_placement'] ) {
			add_options_page( 'Pagely Partner API', 'Pagely API', 'administrator',  __FILE__, array('PagelyAPIOptions', '_settings_page'));
		} else {
			add_menu_page('Pagely Partner API', 'Pagely API', 'administrator', __FILE__, array('PagelyAPIOptions', '_settings_page'),'',99);					

		}
		
		
		add_action( 'admin_init', array('PagelyAPIOptions', '_register_PagelyApi_settings') ); 
	}
	
	// api options page settings and sections
	public function _register_PagelyApi_settings() { 
		
		$api = parent::init();
	
		// TODO check the validity of the API key(s)
		$api_check = $api->_test_api();
		
		// we store the options in an array called pp_api
		$pagely_options = get_option('pp_api');

		if ( !isset($pagely_options['reseller_id']) ) {
			// get reseller id
			$reseller = $api->_pagely_api_request($method = 'GET','/resellers/single', $params = array());
			$reseller = json_decode($reseller);
			$pagely_options['reseller_id'] = $reseller->id;
			$pagely_options['support_url'] = $reseller->support_link;
			$pagely_options['products_only'] = 0;
			$pagely_options['free_trial_days'] = 0;
			update_option('pp_api',$pagely_options);
			$pagely_options = get_option('pp_api');		

		}
	
		register_setting( 'pp_api', 'pp_api', array('PagelyAPIOptions', '_sanitize_options'));
		
		// settings sections
		add_settings_section(  
			'pp_api',         											// ID used to identify this section and with which to register options  
			'Partner API Settings',                  				// Title to be displayed on the administration page  
			array('PagelyAPIOptions', '_pagely_options_section_callback'), // Callback used to render the description of the section  
			'partner-api'                          				// Page on which to add this section of options  
		); 
		
		// api mode fields
		add_settings_field(  
			'pp_mode',                      							// ID used to identify the field throughout the theme  
			'API Mode',                       					// The label to the left of the option interface element  
			array('PagelyAPIOptions', '_pagely_options_field_mode'),   	// The name of the public function responsible for rendering the option interface  
			'partner-api',                         				// The page on which this option will be displayed  
			'pp_api',         											// The name of the section to which this field belongs  
			array( $pagely_options['pp_mode'], 'pp_mode' )     // The array of arguments to pass to the 
		); 
		
		// menu placement fields
		add_settings_field(  
			'pp_menu_placement',                      							// ID used to identify the field throughout the theme  
			'WP Menu Placement',                       					// The label to the left of the option interface element  
			array('PagelyAPIOptions', '_pagely_options_field_menu'),   	// The name of the public function responsible for rendering the option interface  
			'partner-api',                         				// The page on which this option will be displayed  
			'pp_api',         											// The name of the section to which this field belongs  
			array( $pagely_options['pp_menu_placement'], 'pp_menu_placement' )     // The array of arguments to pass to the 
		); 
		// api fields
		add_settings_field(  
			'api_key',                      							
			'Your API key',                       					 
			array('PagelyAPIOptions', '_pagely_options_field_apikey'),   	  
			'partner-api',                         				 
			'pp_api',         											  
			array( $pagely_options['api_key'], 'api_key' )     
		); 
				
		if ($api_check) { // show these settings if the apu check passed
		
			// the support url settings field
			if (!isset($pagely_options['signups_active'])) { $pagely_options['signups_active'] = 1; }
			add_settings_field(  
				'signups_active',                      			  
				'Allow new Signups',                       
				array('PagelyAPIOptions', '_pagely_options_field_active'),   				
				'partner-api',                          
				'pp_api',         							 
				array( $pagely_options['signups_active'], 'signups_active' )  
			); 
			
			// Only sale non-hosting products (CDN, domain names, etc)
		/*	if (!isset($pagely_options['products_only'])) { $pagely_options['products_only'] = 1; }
			add_settings_field(  
				'products_only',                      			  
				'What to sell?',                       
				array('PagelyAPIOptions', '_pagely_options_products_only'),   				
				'partner-api',                          
				'pp_api',         							 
				array( $pagely_options['products_only'], 'products_only' )  
			); 
			*/
			// the support url settings field
			if (!isset($pagely_options['support_url'])) { $pagely_options['support_url'] = 'https://support.pagely.com'; }
			add_settings_field(  
				'support_url',                      			  
				'Support Site',                       
				array('PagelyAPIOptions', '_pagely_options_field_support_url'),   				
				'partner-api',                          
				'pp_api',         							 
				array( $pagely_options['support_url'], 'support_url' )  
			);
			
		
			// free_trial length
			/*if ($pagely_options['reseller_id'] == 1) {
				if (!isset($pagely_options['free_trial_days'])) { $pagely_options['free_trial_days'] = 0; }
				add_settings_field(  
					'free_trial_days',                      			  
					'Length of Free Trial (Days)',                       
					array('PagelyAPIOptions', '_pagely_options_field_free_trial'),   				
					'partner-api',                          
					'pp_api',         							 
					array( $pagely_options['free_trial_days'], 'free_trial_days' )  
				); 
			}*/
			
			// order_by
			if (!isset($pagely_options['order_by'])) { $pagely_options['order_by'] = array(); }
			add_settings_field(  
				'order_by',                      			  
				'Order objects by',                        
				array('PagelyAPIOptions', '_pagely_options_field_order_by'),   				  
				'partner-api',                         
				'pp_api',         							 
				array( $pagely_options['order_by'], 'order_by' )  
			); 
			
			// the plans to show settings field
			if (!isset($pagely_options['products_only']) || $pagely_options['products_only'] == 0) {  
				if (!isset($pagely_options['plans_to_show'])) { $pagely_options['plans_to_show'] = array(); }
				add_settings_field(  
					'plans_to_show',                      			 
					'Choose up to 3 PLANS for customers to purchase',                         
					array('PagelyAPIOptions', '_pagely_options_field_plans_to_show'),   				
					'partner-api',                        
					'pp_api',         							 
					array( $pagely_options['plans_to_show'], 'plans_to_show' )  
				); 
			}
			// the products to show settings field
			if (!isset($pagely_options['products_to_show'])) { $pagely_options['products_to_show'] = array(); }

			add_settings_field(  
				'products_to_show',                      			  
				'Choose which PRODUCTS to show',                        
				array('PagelyAPIOptions', '_pagely_options_field_products_to_show'),   				  
				'partner-api',                         
				'pp_api',         							 
				array( $pagely_options['products_to_show'], 'products_to_show' )  
			); 
			
			// custom css
			if (!isset($pagely_options['custom_css'])) { $pagely_options['custom_css'] = ""; }

			add_settings_field(  
				'custom_css',                      			  
				'Your Custom CSS (optional)',                        
				array('PagelyAPIOptions', '_pagely_options_field_custom_css'),   				  
				'partner-api',                         
				'pp_api',         							 
				array( $pagely_options['custom_css'], 'custom_css' )  
			); 
			
			
		}
	}
	
	
	// WP Menu Placement
	public function _pagely_options_field_menu($args) {  

		$args[0] == 'main' ? $checked1 = "selected='selected'" : $checked1 = '';
		$args[0] == 'sub' ? $checked2 = "selected='selected'" : $checked2 = '';

		echo "<select id='{$args[1]}' name='pp_api[{$args[1]}]'>
					<option value='main' {$checked1}>Top Level</option>
					<option value='sub' {$checked2}>Settings Sub-menu item</option>
				</select> <span class='description'>Access this page via a Top level menu item, or move under the Settings Menu.</span>";
	}
	
	// Only sale products
	/*public function _pagely_options_products_only($args) {  

		$args[0] == 0 ? $checked1 = "selected='selected'" : $checked1 = '';
		$args[0] == 1 ? $checked2 = "selected='selected'" : $checked2 = '';

		echo "<select id='{$args[1]}' name='pp_api[{$args[1]}]'>
					<option value='0' {$checked1}>Hosting Plans & Products</option>
					<option value='1' {$checked2}>Products only</option>
				</select> <span class='description'>'Products only' will forgo installation of a hosted site, but still requires a domain.</span>";
	}*/
	
	// Order by key field
	public function _pagely_options_field_order_by($args) {  

		$args[0] == 0 ? $checked1 = "selected='selected'" : $checked1 = '';
		$args[0] == 1 ? $checked2 = "selected='selected'" : $checked2 = '';

		echo "<select id='{$args[1]}' name='pp_api[{$args[1]}]'>
					<option value='0' {$checked1}>Price: Low-to-High</option>
					<option value='1' {$checked2}>Price: High-to-Low</option>
				</select>";
	}
	
	// free trial days_dropdown
	/*public function _pagely_options_field_free_trial($args) {  

		$args[0] == 0 ? $checked1 = "selected='selected'" : $checked1 = '';
		$args[0] == 5 ? $checked2 = "selected='selected'" : $checked2 = '';
		$args[0] == 10 ? $checked3 = "selected='selected'" : $checked3 = '';
		$args[0] == 15 ? $checked4 = "selected='selected'" : $checked4 = '';
		$args[0] == 30 ? $checked5 = "selected='selected'" : $checked5 = '';

		echo "<select id='{$args[1]}' name='pp_api[{$args[1]}]'>
					<option value='0' {$checked1}>0</option>
					<option value='5' {$checked2}>5</option>
					<option value='10' {$checked3}>10</option>
					<option value='15' {$checked4}>15</option>
					<option value='30' {$checked5}>30</option>
				</select>";
	}  */
	
	// api key field
	public function _pagely_options_field_apikey($args) {  
		echo "<input id='{$args[1]}' name='pp_api[{$args[1]}]' size='40' type='text' value='{$args[0]}' required/> <span class='description'>Key given to you at registration</span>";
	}
	
	// api mode selection live/sandbox
	public function _pagely_options_field_mode($args) {  
		$args[0] == 0 ? $checked1 = "selected='selected'" : $checked1 = '';
		$args[0] == 1 ? $checked2 = "selected='selected'" : $checked2 = '';
		echo "<select id='{$args[1]}' name='pp_api[{$args[1]}]'><option value='0' {$checked1}>Sandbox</option><option value='1' {$checked2}>Live</option></select> <span class='description'>Sandbox for is for testing, be sure to switch to live when ready. Recently added plans may not be available in Sandbox mode right away.</span>";

	}  

	// support url field
	public function _pagely_options_field_support_url($args) {  
		echo "<input id='{$args[1]}' name='pp_api[{$args[1]}]' size='40' type='text' value='{$args[0]}' required/> <span class='description'>Url of your support desk, or use https://support.pagely.com</span>";
	} 
	
	// active field
	public function _pagely_options_field_active($args) {  
	
		$args[0] == 0 ? $checked1 = "selected='selected'" : $checked1 = '';
		$args[0] == 1 ? $checked2 = "selected='selected'" : $checked2 = '';

		echo "<select id='{$args[1]}' name='pp_api[{$args[1]}]'>
					<option value='0' {$checked1}>No</option>
					<option value='1' {$checked2}>Yes</option>
				</select> <span class='description'>Will show a maintenance message on cart pages.</span>";
	} 
	
	
	
	// checkboxes for which plans to show.
	public function _pagely_options_field_plans_to_show($args) {  
		// this can be improved
		$portal = $pp_portal_url;	
		// print_r($args[0]);
		$api = PagelyApi::init();
		$all_plans = $api->_get_reseller_plans($limit = 50,$offset = 0);
	
		if ( is_array($all_plans->objects) && count($all_plans->objects) > 0 ) {
			echo "<div style='height:300px;overflow:scroll;'>
					<div id='plans_check'>
						<table class='wp-list-table widefat' cellspacing='0'>
							<thead>
								<tr>
									<th scope='col' id='cb' class='manage-column column-show column-cb check-column'></th>
									<th scope='col' id='plan' class='manage-column column-plan'>Plan</th>
									<th scope='col' id='price' class='manage-column column-price'>Price</th>
									<th scope='col' id='featured' class='manage-column column-featured'>Featured</th>
									<th scope='col' id='hidden' class='manage-column column-hidden'>*Hidden</th>
								</tr>
							</thead>";
							
			foreach ($all_plans->objects as $p) { 
				if ($p->active == "1") {
					$checked = '';
					$checkradio = '';
					if (is_array($args[0])) {
						if ( in_array($p->id,$args[0]) ) { $checked = "checked='checked'";}
					}
					
					$visible = $p->visible == 0 ? '<span style="color:#ccc">Yes</span>' : '<span style="color:green;">No</span>';
					
					if ( isset($args[0]['plan_default']) && $args[0]['plan_default'] == $p->id) {$checkradio = "checked='checked'";}		
					echo "<tr>
								<td>
									<input type='checkbox' class='required' id='{$args[1]}{$p->id}' name='pp_api[{$args[1]}][]' value='{$p->id}' {$checked} />
								</td>
								<td>{$p->name}</td>
								<td>".$api->_cash($p->price)."</td>
								<td><input type='radio' name='pp_api[{$args[1]}][plan_default]' value='{$p->id}' {$checkradio}/></td>
								<td>{$visible}</td>
							</tr>";
				}
			}
			
			echo "</table></div></div><span class='description'>These are all the hosting plans that are active in our system for your account. You may choose 3 at any one time to list for sale via this plugin. If you wish to modify or create plans, visit the <a href='https://photon.pagely.com'>API Portal</a>.</span> <strong>*Hidden</strong> plans may be purchased by the customer here, but are hidden from within the dashboard. Use case is for plans you only want new customers to see.";
		} else {
		
			// no plans
			echo "<span class='description'>You have not created any plans yet. <a href='https://photon.pagely.com'>Login to the Portal and create some.</a></span>";
		
		}
		
		
	}
	
	// checkboxes for which products to show.
	public function _pagely_options_field_products_to_show($args) {  
		// this can be improved

		// checkboxes for which plans to show.
		$portal = $pp_portal_url;	
		$api = PagelyApi::init();
		$all_products = $api->_get_reseller_products($limit = 20,$offset = 0);
		//	echo '<pre>';
		//print_r($all_products);
		if ( is_array($all_products->objects) && count($all_products->objects) > 0 ) {
			echo "<div style='height:300px;overflow:scroll;'><table class='wp-list-table widefat' cellspacing='0'>
					<thead>
						<tr>
							<th scope='col' id='cb' class='manage-column column-show column-cb check-column'></th>
							<th scope='col' id='plan' class='manage-column column-plan'>Product</th>
							<th scope='col' id='plan' class='manage-column column-plan'>Price</th>
							<th scope='col' id='plan' class='manage-column column-plan'>Recurring</th>

						</tr>
					</thead>";
			
			foreach ($all_products->objects as $p) { 
				if ($p->active == "1") {
					$checked = '';
					
					if (is_array($args[0])) {
					
						if ( in_array($p->id,$args[0]) ) { 
							$checked = "checked='checked'";
						}	
					
					}
					
					($p->recurring == 1) ? $recurring = "Yes" : $recurring = "No";

		
					echo "<tr>
								<td><input type='checkbox' class='required' id='{$args[1]}{$p->id}' name='pp_api[{$args[1]}][]' value='{$p->id}' {$checked} /></td>
								<td><label for='{$args[1]}{$p->id}'>{$p->name}</label></td>
								<td> ".$api->_cash($p->price)."</td>
								<td>{$recurring}</td>
							</tr>";
				}
			}
			
			echo "</table></div><span class='description'>These are all the hosting plans that are active in our system for your account. You may choose 3 at any one time to list for sale via this plugin. If you wish to modify or create plans, visit the <a href='https://photon.pagely.com'>API Portal</a>.</span></div>";
		
		} else {
			// no products
			echo "<span class='description'>You have not created any products yet. <a href='https://photon.pagely.com'>Login to the Portal and create some.</a></span>";
		
		}
		
	}
	
	// custom css textarea
	public function _pagely_options_field_custom_css($args) {  
		echo "<textarea id='{$args[1]}' name='pp_api[{$args[1]}]' style='width:70%' rows='10'>{$args[0]}</textarea>";
	}
	

	// unused callback
	public function _pagely_options_section_callback() {  
		self::_clear_cached_keys();
		return; 
	} 	
	

	// sanitize some shiz
	public function _sanitize_options($input) {
		// Create our array for storing the validated options  
    	$output = array();  
  
    	// Loop through each of the incoming options  
    	foreach( $input as $key => $value ) {    
        if( isset( $input[$key] ) ) {  
              // Strip all HTML and PHP tags and properly handle quoted strings  
             if (is_array($input[ $key ])) {
             	foreach ($input[ $key ] as $k => $v) {
             		$output[$key][$k] = strip_tags( stripslashes( $v ) ); 
             	}
             } else {
            	$output[$key] = strip_tags( stripslashes( $input[ $key ] ) );  
        		 }
        }  
    	}  
  
		// Return the array processing any additional public functions filtered by this action  
    	return $output;
	}
		
	// the actual settings page
	public function _settings_page() {
		$api = PagelyApi::init();
		wp_enqueue_script( 'pp-options' ); // is this needed still?
		if ($_POST) {
			if ($_POST['cache_clear']) {
				
				self::_clear_cached_keys();
				
								
				$api->_dialog( 'Cache Cleared' ,$type = 'notice');
			} else {
				$api->_dialog( 'Posted' ,$type = 'notice');
			}
		}
		echo $api->_get_view( PP_PLUGIN_ASSETS_PATH . 'views/options-page.php');
		
		
	}
	
	public function _clear_cached_keys() {
		
		//clear cached plans
		delete_transient( 'pp_plans' );
		update_option( 'pp_plans', '' );
		//clear caches products
		delete_transient( 'pp_products' );
		update_option( 'pp_products', '' );

	}
}
//add_action( 'init', array( 'PagelyAPIOptions', 'init' ),2);
//new PagelyAPIOptions();
?>