<?php

	remove_action( 'woocommerce_after_shop_loop_item_title', 'theshopier_shop_loop_title', 1 );
	add_action( 'woocommerce_after_shop_loop_item_title', 'toby_shop_loop_title', 1 );

	function toby_shop_loop_title(){
		if ( is_product() ) {
		
			global $product;
			
			printf('<h3 class="product-title"><a href="%1$s" title="%2$s">%2$s</a></h3>', esc_url(get_the_permalink()), esc_attr(get_the_title()));
			
			if ( $price_html = $product->get_price_html() ) {
				echo $price_html;
			}
			
		} else {
			printf('<h3 class="product-title"><a href="%1$s" title="%2$s">%2$s</a></h3>', esc_url(get_the_permalink()), esc_attr(get_the_title()));
		}
	}	