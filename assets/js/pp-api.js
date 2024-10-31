/**
 * Pagely partner API form js *
 * https://pagely.com
 * http://docs.jquery.com/Plugins/Validation
 *
 * Copyright (c) 2009 - 2012 Joshua Strebel
 *
 * http://www.gnu.org/licenses/gpl.html
 */
window.pagely_cookie_name = 'wordpress_pagely_order';
window.date = new Date();
window.time = date.getTime();
jQuery(document).ready(function($) {
	//create cookie if needed
	//$.cookie.json = true
	
	pagely_test_cookie();
	renderView();
	// show the promo code field
	$('#pp.pagely_order').on("click", "a.showcode", function(event) {
		event.preventDefault();
		$(this).fadeOut(500, function() {
			$('#promocode').fadeIn(1000);
		});
		return false;
	});
	// account form validation
	$("#pp.pagely_order").on("click", "#acc_submit", function(event) {
		acc_form_validate();
	});
	// enroll form verifcation
	$("#pp.pagely_order").on("click", "#pagely_enroll_submit", function(event) {
		enroll_form_validate();
	});
	// client side validation of account setup form	
	var acc_form_validate = function() {
			$("#pp.pagely_order #pagely_form_acc").validate({
				rules: {
					"pagely_order[mobile]": {
						minlength: 10,
						maxlength: 18
					},
					"pagely_order[sanswer]": {
						minlength: 3,
						maxlength: 65
					}
				},
				success: function(label) {
					label.remove();
					label.removeClass().addClass("valid");
				},
				errorPlacement: function(error, element) {
					//error.appendTo( element.parent("p") );
					///element.after( error );
				},
				invalidHandler: function() {
					done_waiting();
				},
				submitHandler: function(form) {
					//load_view('processing');
					var acc_options = {
						//target:        '.form_msg',   // target element(s) to be updated with server response 
						//	beforeSubmit:  add_action,  // pre-submit callback 
						success: acc_response,
						// post-submit callback 
						// other available options: 
						url: pagelyJax.ajaxurl,
						// override for form's 'action' attribute 
						type: 'post',
						// 'get' or 'post', override for form's 'method' attribute 
						dataType: 'json' // 'xml', 'script', or 'json' (expected server response type) 
						//clearForm: true        // clear all form fields after successful submit 
						//resetForm: true        // reset the form after successful submit 
						// $.ajax options can be used here too, for example: 
						//timeout:   3000 
					};
					$(form).ajaxSubmit(acc_options);
				}
			});
		};
	// client side validation of subscription enroll form
	var enroll_form_validate = function(myform) {
			$('#pp.pagely_order #pagely_form_enroll').validate({
				rules: {},
				success: function(label) {
					label.remove();
					label.removeClass().addClass("valid");
				},
				errorPlacement: function(error, element) {
					//error.appendTo( element.parent("p") );
					element.after(error);
				},
				invalidHandler: function() {
					done_waiting();
				},
				submitHandler: function(form) {
					//load_view('processing');
					var sub_enroll = {
						//target:        '.form_msg',   // target element(s) to be updated with server response 
						//beforeSubmit:  foobar,  // pre-submit callback 
						success: sub_enroll_response,
						// post-submit callback 
						// other available options: 
						url: pagelyJax.ajaxurl,
						// override for form's 'action' attribute 
						type: 'post',
						// 'get' or 'post', override for form's 'method' attribute 
						dataType: 'json' // 'xml', 'script', or 'json' (expected server response type) 
					};
					$(form).ajaxSubmit(sub_enroll);
				}
			});
		};
	// validate each field
	$('#pp.pagely_order').on("blur", ' #pagely_form_acc:input', function() {
		$(this).removeClass();
		$("#pp.pagely_order #pagely_form_acc").validate().element(this);
	});
	// email remote validation
	$('#pp.pagely_order').on("blur", "#po_email", function() {
		//alert(event);
		var field = $(this);
		//alert(event.target)
		$(field).addClass('remote');
		var nounce = $(field).parents('form').find('#pagely_order_acc_nounce').val();
		$.ajax({
			url: pagelyJax.ajaxurl,
			type: "post",
			data: {
				action: "pagely_jax_formvalidate_callback",
				ppjaxnounce: nounce,
				field: "pagely_order[email]",
				value: $(field).val(),
				security: pagelyJax.ppjaxnounce
			},
			success: function(data) {
				if (data.result == 3) {
					//error
					$(field).siblings('p.help-block').remove();
					$(field).removeClass('valid').addClass('error');
					if (data.message == "That email address is already in use.") {
						$(field).parent('.form-group').append('<p class="help-block alert alert-danger">Address Taken</p>');
					}
				} else {
					//success
					$(field).removeClass('error').addClass('valid');
					$(field).siblings('p.help-block').remove();
				}
			}
		});
	});
	// get cookie data
	$("#session").click(function() {
		//alert(pagely_get_cookie());
		alert(pagely_get_cookie().toSource());
	});
	// changed the default plan
	$('#pp.pagely_order').on('click', '#planchange', function(event) {
		event.preventDefault();
		$(this).parent('p').hide();
		$('#planselector').show();
		return false;
	});
	// load legal click
	$('#pp.pagely_order').on('click', '.pp_remote', function(event) {
		event.preventDefault();
		var which = $(this).attr('rel');
		pp_load_legal(which);
		return false;
	});
	
	$('#pp.pagely_order').on("click",'.pre_pay', function(event) {
		$('.pre_pay checkbox').click();
		if ($("#p_cycle").is(":checked")) {
			$('.pre_pay').addClass('sel');
		} else {
			$('.pre_pay').removeClass('sel');
		}
		
	});
	// plan_selector change
	$('#pp.pagely_order').on('change', '#planselector', function() {
		display_plan_right();
	});
	// annual, monthly
	$('#pp.pagely_order').on('change', '#p_cycle', function() {
		var new_cookie = {
			billing_cycle: ""
		};
		if ($("#p_cycle").is(":checked")) {
			new_cookie.billing_cycle = 'yy';
		} else {
			new_cookie.billing_cycle = 'mm';
		}
		pagely_set_cookie(new_cookie);
		display_plan_right();
	});
	
	// annual, monthly
	$('#pp.pagely_order').on('click', '.form_msg', function() {
		cookie = pagely_get_cookie();
		//redirect if complete		
		if (cookie.complete) {
			redirect_by_post(url, {
				send_to_atomic: 'true',
				atomic_url: '' + responseObj.atomic_login,
				username: '' + responseObj.atomic_username,
				password_token: '' + responseObj.atomic_one_time_use_token
			}, false);
		}
		// show the plan as normal
		if (cookie.billing_cycle == 'yy') {
			$("#p_cycle").prop('checked', true);
		}
		display_plan_right();
	});
	// click to activate
	
	// apply waiting state
	$('#pp.pagely_order').on('click', 'button', function() {
		$(this).addClass('disabled');
		$('.waiting', this).show();
	});
	// remove loading state..
});


// post-submit callback for account

function acc_response(responseObj, statusText, xhr, $form) {
	if (responseObj.result == 2) {
		//account was created
		// set the cookie
		pagely_set_cookie(responseObj.cookie);
		
		var url = window.location.protocol + "//" + window.location.host + window.location.pathname + '?complete=almost';
		//alert(responseObj.toSource());
		// redirect to hosted billing page
		redirect_by_post(responseObj.hosted_billing_page, {
			hosted_return_url: '' + url,
			hosted_nonce: '' + responseObj.hosted_nonce
		}, false);
	} else if (responseObj.result == 3) {
		// some sort of error
		var error = responseObj.message;
		done_waiting();
		jQuery('#pp.pagely_order .form_msg').html('<p class="alert alert-error">' + error + '</p>').addClass('error');
		if (error) {
			jQuery('#pp.pagely_order form input[type=text]').removeClass('valid');
		}
		if (error == "That email address is already in use.") {
			error = error + " Please <a href='" + pagelyJax.pp_portal_url + "'>login to our Account panel</a> to add a new site.";
		}
		if (error == "Invalid input, username invalid characters") {
			jQuery('#pp.pagely_order #po_email').removeClass('valid');
			jQuery('#pp.pagely_order #po_email').addClass('error');
		}
	} else {
		api_error();
	}
}
// post-submit callback for subscription enroll form

function sub_enroll_response(responseObj, statusText, xhr, $form) {
	if (responseObj.result == 2) {
		// set complete cookie
		var new_cookie = {
			complete: "true",
			send_to_atomic: 'true',
			atomic_url: '' + responseObj.atomic_login,
			atomic_username: '' + responseObj.atomic_username,
			atomic_one_time_use_token: '' + responseObj.atomic_one_time_use_token
		};
		// new_cookie = JSON.stringify(new_cookie);
		pagely_set_cookie(new_cookie);
		//reload the page with get var for analytics tracking
		var url = window.location.protocol + "//" + window.location.host + window.location.pathname + '?complete=true';
		redirect_by_post(url, {
			send_to_atomic: 'true',
			
		}, false);
	} else if (responseObj.result == 3) {
		//alert('error');
		// some sort of error
		var error = responseObj.message;
		done_waiting();
		var new_cookie = {
			complete: "true"
		};
		// new_cookie = JSON.stringify(new_cookie);
		pagely_set_cookie(new_cookie);
		//reload the page with get var for analytics tracking
		var url = window.location.protocol + "//" + window.location.host + window.location.pathname + '?complete=true';
		redirect_by_post(url, {
			send_to_atomic: 'false',
			
		}, false);
	} else {
		api_error();
	}
}

function redirect_by_post(purl, pparameters, in_new_tab) {
	pparameters = (typeof pparameters == 'undefined') ? {} : pparameters;
	in_new_tab = (typeof in_new_tab == 'undefined') ? true : in_new_tab;
	var form = document.createElement("form");
	jQuery(form).attr("id", "pagely-redir-form").attr("name", "pagely-redir-form").attr("action", purl).attr("method", "post").attr("enctype", "multipart/form-data");
	if (in_new_tab) {
		$(form).attr("target", "_blank");
	}
	jQuery.each(pparameters, function(key) {
		jQuery(form).append('<input type="text" name="' + key + '" value="' + this + '" />');
	});
	document.body.appendChild(form);
	form.submit();
	document.body.removeChild(form);
	return false;
}

function done_waiting() {
	jQuery('#pp.pagely_order button').removeClass('disabled');
	jQuery('#pp.pagely_order button .waiting').hide();
}

function set_waiting() {
	jQuery('#pp.pagely_order button').addClass('disabled');
	jQuery('#pp.pagely_order button .waiting').hide();
}
//api error

function api_error() {
	var error = "Error Processing request. We're sorry, please try again later.";
	jQuery("#pp.pagely_order").html('<div class="hero-unit"><h2>' + error + '</h2></div>');
}

function process_sub() {
	var sub_enroll = {
		success: sub_enroll_response,
		url: pagelyJax.ajaxurl,
		type: 'post',
		dataType: 'json' // 'xml', 'script', or 'json' (expected server response type) 
	};
	jQuery('#pp.pagely_order #pagely_form_enroll').ajaxSubmit(sub_enroll);
}

function display_plan_right() {
	var cookie = pagely_get_cookie();
	var plans_json = pagelyJax.all_plans;
	//alert(plans_json);
	var plans = jQuery.parseJSON(plans_json);
	var selected = jQuery('#planselector').val();
	
	if (!selected) {
		selected = cookie.plan_id;
	}
	
	var setup_time = "Instant";
	var price = 0;
	var chosen_plan = jQuery.map(plans.plans, function(val, key) {
		//alert(val.id);
		if (Number(val.id) == selected) return val;
	});
	
	if (cookie.billing_cycle == 'yy') {
		jQuery("#p_cycle").prop( "checked", true );
		jQuery('.pre_pay').addClass('sel');
	}
	
	jQuery('.pagely_plan_details_inner .plan_name').html(chosen_plan[0].name);
	jQuery('.pagely_plan_details_inner .plan_desc').html(chosen_plan[0].desc);
	if (chosen_plan[0].manual_setup == 1) {
		setup_time = "2-3 days";
	}
	jQuery('.pagely_plan_details_inner .plan_setup_time span').html(setup_time);
	if (cookie.billing_cycle == 'yy') {
		var num = chosen_plan[0].price * 10.5;
		var fixed_num = parseFloat(Math.round(num * 100) / 100).toFixed(2);
		//fixed_num = fixed_num.replace(/.00$/, '');
		price = '<strong>' + fixed_num + '</strong>/yr';
	} else {
		price = '<strong>' + chosen_plan[0].price + '</strong>/mo';
	}
	jQuery('.pagely_plan_details_inner .plan_price span').html('<sup>$</sup>' + price);
}
// render a view inside #pp

function renderView() {
	var cookie = pagely_get_cookie();
	
	if (cookie.finished) {
		// nuke cookie
	}
	//alert(cookie.toSource());
	if (cookie.act_id && cookie.billing_info_success == 1 && cookie.complete) {
		// nuke cookie data
		//pagely_nuke_cookie();
		load_view('complete');
	} else if (cookie.act_id && cookie.billing_info_success == 1) {
		load_view('order_step2');
	} else if (!cookie.act_id) {
		load_view('order_step1');
	} else {
		// we have an account id, but no billing_info_success.. redirect them to hosted billing page in the php view
		load_view('misfire');
	}
}
// load a view for rendering

function load_view(my_view) {
	jQuery.ajax({
		url: pagelyJax.ajaxurl,
		type: "post",
		data: {
			action: "pagely_jax_generic_callback",
			ppdo: "load_view",
			view: my_view,
			security: pagelyJax.ppjaxnounce
		},
		success: function(html) {
			//sec_field = $(html).find('#sec');
			//$('#sec',html).val(pagelyJax.ppjaxnounce);
			jQuery('#pp.pagely_order').fadeOut('fast', function() {
				jQuery(this).html(html).fadeIn('slow');
				jQuery('#sec', this).val(pagelyJax.ppjaxnounce);
			});
		}
	});
}

function pagely_test_cookie() {
	if (!jQuery.cookie(pagely_cookie_name)) {
		var obj = JSON.stringify({
			last_activity: time
		});
		obj = btoa(obj);
		jQuery.cookie(pagely_cookie_name, obj, {
			path: window.location.pathname
		});
	}
}
function pagely_get_cookie() {
	pagely_test_cookie();
	var c = jQuery.cookie(pagely_cookie_name);
	//alert(c.toSource());
	c = atob(c);
	return JSON.parse(c);
}

function pagely_set_cookie(new_obj) {
	date = new Date();
	time = date.getTime();
	var existing_cookie = pagely_get_cookie();
	//alert(new_obj.toSource());
	for (var key in new_obj) {
		existing_cookie[key] = new_obj[key];
	}
	existing_cookie.last_activity = time;
	json = JSON.stringify(existing_cookie);
	json = btoa(json);
	jQuery.cookie(pagely_cookie_name, json, {
		path: window.location.pathname
	});
}

function pagely_nuke_cookie() {
	jQuery.removeCookie(pagely_cookie_name,{path: window.location.pathname});
}

/*function pp_pagely_get_cookie() {
	jQuery.ajax({
		url: pagelyJax.ajaxurl,
		type: "post",
		async: false,
		data: {
			action: "pagely_jax_generic_callback",
			ppdo: 'pagely_get_cookie',
			security: pagelyJax.ppjaxnounce
		},
		success: function(data) {
			result = data;
		}
	});
	return jQuery.parseJSON(result);
}

function pp_nuke_cookie() {
	jQuery.ajax({
		url: pagelyJax.ajaxurl,
		type: "post",
		data: {
			action: "pagely_jax_generic_callback",
			ppdo: 'clear_cookie',
			security: pagelyJax.ppjaxnounce
		},
		success: function(data) {}
	});
}

function pp_pagely_set_cookie(cookie_array) {
	jQuery.ajax({
		url: pagelyJax.ajaxurl,
		type: "post",
		data: {
			action: "pagely_jax_generic_callback",
			ppdo: 'pagely_set_cookie',
			value: cookie_array,
			security: pagelyJax.ppjaxnounce
		},
		success: function(data) {}
	});
}
*/

function pp_load_legal(name) {
	var title = '';
	jQuery.ajax({
		url: pagelyJax.ajaxurl,
		type: "post",
		asynch: "false",
		dataType: 'html',
		data: {
			action: "pagely_jax_generic_callback",
			ppdo: 'load_legal',
			name: name,
			security: pagelyJax.ppjaxnounce
		},
		success: function(data) {
			switch (name) {
			case 'tos':
				title = "Terms of Service";
				break;
			case 'aup':
				title = "Acceptable Use Policy";
				break;
			case 'privacy':
				title = "Privacy Policy";
				break;
			}
			data = jQuery(data).find('div.entry-content');
			data.removeClass('row');
			data.find('div').removeClass('span10 offset1 row');
			// check for twitter bootstrap modal
			var bootstrap_enabled = (typeof jQuery().modal == 'function');
			if (bootstrap_enabled) {
				jQuery(document.body).append('<div id="legal_dialog_modal" class="modal fade" role="dialog" aria-labelledby="legal_dialog_modal" aria-hidden="true"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title" id="myModalLabel"></h4></div><div class="modal-body"></div></div></div></div>');
				jQuery('#legal_dialog_modal .modal-body').html(data);
				jQuery('#legal_dialog_modal .modal-title').html(title);
				jQuery('#legal_dialog_modal').modal('show');
			} else {
				jQuery('#legal_dialog').html(data);
				jQuery('#legal_dialog').prepend('<h2>' + title + '</h2>').fadeIn('fast');
			}
			//alert(data);
		}
	});
	return result;
}
/*!
 * jQuery Cookie Plugin v1.4.0
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 */
(function(factory) {
	if (typeof define === 'function' && define.amd) {
		// AMD
		define(['jquery'], factory);
	} else if (typeof exports === 'object') {
		// CommonJS
		factory(require('jquery'));
	} else {
		// Browser globals
		factory(jQuery);
	}
}(function($) {
	var pluses = /\+/g;

	function encode(s) {
		return config.raw ? s : encodeURIComponent(s);
	}

	function decode(s) {
		return config.raw ? s : decodeURIComponent(s);
	}

	function stringifyCookieValue(value) {
		return encode(config.json ? JSON.stringify(value) : String(value));
	}

	function parseCookieValue(s) {
		if (s.indexOf('"') === 0) {
			// This is a quoted cookie as according to RFC2068, unescape...
			s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
		}
		try {
			// Replace server-side written pluses with spaces.
			// If we can't decode the cookie, ignore it, it's unusable.
			// If we can't parse the cookie, ignore it, it's unusable.
			s = decodeURIComponent(s.replace(pluses, ' '));
			return config.json ? JSON.parse(s) : s;
		} catch (e) {}
	}

	function read(s, converter) {
		var value = config.raw ? s : parseCookieValue(s);
		return $.isFunction(converter) ? converter(value) : value;
	}
	var config = $.cookie = function(key, value, options) {
			// Write
			if (value !== undefined && !$.isFunction(value)) {
				options = $.extend({}, config.defaults, options);
				if (typeof options.expires === 'number') {
					var days = options.expires,
						t = options.expires = new Date();
					t.setTime(+t + days * 864e+5);
				}
				return (document.cookie = [
				encode(key), '=', stringifyCookieValue(value), options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
				options.path ? '; path=' + options.path : '', options.domain ? '; domain=' + options.domain : '', options.secure ? '; secure' : ''].join(''));
			}
			// Read
			var result = key ? undefined : {};
			// To prevent the for loop in the first place assign an empty array
			// in case there are no cookies at all. Also prevents odd result when
			// calling $.cookie().
			var cookies = document.cookie ? document.cookie.split('; ') : [];
			for (var i = 0, l = cookies.length; i < l; i++) {
				var parts = cookies[i].split('=');
				var name = decode(parts.shift());
				var cookie = parts.join('=');
				if (key && key === name) {
					// If second argument (value) is a function it's a converter...
					result = read(cookie, value);
					break;
				}
				// Prevent storing a cookie that we couldn't decode.
				if (!key && (cookie = read(cookie)) !== undefined) {
					result[name] = cookie;
				}
			}
			return result;
		};
	config.defaults = {};
	$.removeCookie = function(key, options) {
		if ($.cookie(key) === undefined) {
			return false;
		}
		// Must not alter options, thus extending a fresh object...
		$.cookie(key, '', $.extend({}, options, {
			expires: -1
		}));
		return !$.cookie(key);
	};
}));