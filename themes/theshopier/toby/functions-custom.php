<?php
	/* require_once( 'post-types/toy-pack.php' ); */
	
	add_action('init', 'remove_then_add_image_sizes', 99);

	function remove_then_add_image_sizes() {		
		/* WooCommerce */
		// remove_image_size('shop_catalog');
		// remove_image_size('shop_thumbnail');
		// remove_image_size('shop_single');		
		// remove_image_size('woocommerce_thumbnail');
		// remove_image_size('woocommerce_single');
		// remove_image_size('woocommerce_gallery_thumbnail');
		
		/* Theme */
		remove_image_size('theshopier_mega_menu_icon');
		remove_image_size('theshopier_mini_product_img');
		
		remove_image_size('theshopier_blog_thumb');
		// remove_image_size('theshopier_blog_thumb_list');
		remove_image_size('theshopier_blog_thumb_grid');
		// remove_image_size('theshopier_blog_thumb_widget');
		// remove_image_size('theshopier_blog_single');
		// remove_image_size('theshopier_shop_subcat');
		// remove_image_size('theshopier_shop_subcat_large');
		// remove_image_size('theshopier_product_super_deal');
		
		/* ----------------- */
				
		/* WooCommerce */
		// add_image_size('shop_catalog', 300, 300, true);
		// add_image_size('shop_thumbnail', 150, 150, true);
		// add_image_size('shop_single', 600, 600, true);		
		// add_image_size('woocommerce_thumbnail', 150, 150, true);
		// add_image_size('woocommerce_single', 600, 600, true);
		// add_image_size('woocommerce_gallery_thumbnail', 150, 150, true);

		/* Theme */
		add_image_size('theshopier_mega_menu_icon', 150, 150, true);
		add_image_size('theshopier_mini_product_img', 150, 150, true);
		
		add_image_size('theshopier_blog_thumb', 600, 600, false);
		// add_image_size('theshopier_blog_thumb_list', 300, 300, false);
		add_image_size('theshopier_blog_thumb_grid', 600, 600, false);
		// add_image_size('theshopier_blog_thumb_widget', 150, 150, false);
		// add_image_size('theshopier_blog_single', 600, 600, true);
		// add_image_size('theshopier_shop_subcat', 150, 150, true);
		
		// add_image_size('theshopier_shop_subcat_large', 300, 300, true);
		// add_image_size('theshopier_product_super_deal', 300, 300, true);

	}
	
	include_once( 'theshopier-functions-override.php' );
	include_once( 'meta-data.php' );
	include_once( 'woocommerce.php' );
	
	include_once( 'shortcodes/sitemap.php' );

	/**
	 * # toby_write_log()
	 * Write text to a log file on the theme's directory
	 */
	if ( !function_exists( 'toby_write_log' ) ) {
		
		function toby_write_log( $msg )
		{
			$file = dirname(__FILE__).'/toby_log.log';
			
			$date = date('m/d/Y h:i:s a');
			
			if ( is_array( $msg ) || is_object( $msg ) ) {
				$msg = print_r( $msg, true );
			}
			
			$msg = '[ '. $date .' ]: ' . $msg;
			$msg .= "\r\n\r\n";
			
			// Write the contents to the file, 
			// using the FILE_APPEND flag to append the content to the end of the file
			// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
			file_put_contents($file, $msg, FILE_APPEND | LOCK_EX);
		}
		
	}
	
	function toby_enqueue_scripts() {
	
		wp_enqueue_script( 'toby-fancybox', get_stylesheet_directory_uri() . '/toby/assets/vendors/fancybox/jquery.fancybox.min.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'toby-script', get_stylesheet_directory_uri() . '/toby/assets/js/script.js', array( 'jquery' ), null, true );
		
		wp_enqueue_style( 'toby-fancybox', get_stylesheet_directory_uri() . '/toby/assets/vendors/fancybox/jquery.fancybox.min.css');
		wp_enqueue_style( 'toby-moved-style', get_stylesheet_directory_uri() . '/toby/assets/css/moved-styles.css');
		wp_enqueue_style( 'toby-style', get_stylesheet_directory_uri() . '/toby/assets/css/style.css');

	}
	
	add_action( 'wp_enqueue_scripts', 'toby_enqueue_scripts' );
	
	/*===================================================================================
	 * Custom Posts Query with Sorting Order
	 * =================================================================================*/
	function toby_get_custom_posts($type, $limit = 20, $order = "DESC", $add_args = array(), $post_ids = array(), $meta_query = array())
	{
		//wp_reset_postdata();
		$args = array(
			"posts_per_page" 	=> $limit,
			"post_type" 		=> $type,
			"include"			=> $post_ids,
			//'orderby' 			=> 'menu_order',
			"order" 			=> $order,
		);
		
		if ( !empty($add_args) )
			$args = array_merge($args, $add_args);
		
		if ( !empty( $meta_query ) )
			$args['meta_query'] = $meta_query;
		
		$custom_posts = get_posts($args);
		return $custom_posts;
	}
		
	remove_action( 'init', 'woocommerce_breadcrumb', 20 );
	remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20 );
	remove_action( 'theshopier_breadcrumb', 'theshopier_breadcrumb_function', 10 );
	
	add_action( 'theshopier_breadcrumb', 'toby_breadcrumb_function', 10, 1 );
	
	function toby_breadcrumb_function( $datas = array() ) {
		if ( is_front_page() ) return '';

		if ( !isset($datas['show_bcrumb']) || absint($datas['show_bcrumb']) == 1 || is_home() ) {
		
			echo '<div class="container">';
				yoast_breadcrumb( '<div id="yoast_breadcrumbs">', '</div>' );
			echo '</div>';
		
		}

	}
	
	add_filter( 'woocommerce_page_title', 'toby_woocommerce_page_title', 99, 1 );
	function toby_woocommerce_page_title( $page_title ) {
		
		if ( is_product_category() ) {
			$page_title = str_replace( 'page-title', 'page-title product-category-bg', $page_title );
		}
		
		return $page_title;
	}

	function toby_mime_types($mime_types) {
	
		$mime_types['eps'] = 'application/postscript'; //Adding postscript files
		
		return $mime_types;
		
	}
	add_filter('upload_mimes', 'toby_mime_types', 1, 1);
	