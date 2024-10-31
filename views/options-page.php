<div class="wrap">
	<h2>Pagely Partner API Options</h2>
	<img src="<?php echo PP_PLUGIN_ASSETS.'/assets/gfx/pagely-api-small.png';?>" style="float:right"/>
	<?php if (!$this->_test_api()) { ?>
		<div id="message" class="updated below-h2">
			<p>Error communicaring with the API. Check your API key and Secret. Or the API may be offline.</p>
		</div>	
   <?php } ?>
	
	<form method="post" action="">
		<h3>Cached API Responses</h3>
		<p>Most responses from the API are cached for a period of time.<br/>If you have recently made changes you may wish to clear the cached values.</p>
		<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Clear API Cache') ?>" name="cache_clear"/>
	</p>
	</form>
	<hr/>
	<form method="post" action="options.php"> 
	<?php settings_fields( 'pp_api' ); ?>
	<?php do_settings_sections( 'partner-api' ); ?>
	<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" name="options_save"/>
	</p>
	</form>
	<p><em>If you would like more information on this API, view the <a href="https://docs-api.pagely.com">Documentation</a></em></p>
</div>