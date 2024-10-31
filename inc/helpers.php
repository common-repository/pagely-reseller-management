<?php

/************************
* THIS IS THE FUNCTION YOU ADD TO YOUR THEME TEMPLATE WHERE
* YOU WISH THE CART TO SHOW UP. PASS THE PAGE SLUG WITH THE FUNCTION
* EXAMPLE 1: <?php pagely_order_form($post->post_name) ?>

<?php get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'page' ); ?>

					<?php pagely_order_form($post->post_name) ?>  <!-- Pagely order form -->

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>
************************/
function pagely_order_form($slug) {
	
	// load the class			
	$pagely = PagelyApi::init();
	
	// check if page is ssl and in live mode, else redirect.

	$location = "https://" . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];
	if ( !is_ssl() && !$pagely->_is_sandbox() ) {
	
		echo "<script type='text/javascript'>window.location = '$location'</script>";
		die();
	}

	
		
	echo $pagely->_render_pagely_order_form($slug);
	
}

function get_plans() {
	
	$pagely = PagelyApi::init();
	return $pagely->_get_plans();
}

function get_products() {
	
	$pagely = PagelyApi::init();
	return $pagely->_get_products();
}
// enable sessions
function pp_create_cookie(){
	return;
}
