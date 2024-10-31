<div id="pp" class="pagely_order complete">
	<h2>Success! Please remember to check your email for your new account information.</h2>
	<p class="lead">Please login to our <a href="https://atomic.pagely.com">control panel</a> here.<br/>Thank you.</p>
</div>
<script>
 var cookie = pagely_get_cookie();
 pagely_nuke_cookie();
 jQuery(document).ready(function($) {
 	if (cookie.send_to_atomic == 'true') {
 		$('p.lead').html("Logging you into our <a href='https://atomic.pagely.com'>control panel</a>. Please stand by... <i class='fa fa-spinner fa-spin'></i>");
 		setTimeout(function(){
		 	redirect_by_post(cookie.atomic_url, {
					username: '' + cookie.atomic_username,
					password_token: '' + cookie.atomic_one_time_use_token
				}, false);
		
		},1500);
	}
 });
</script>