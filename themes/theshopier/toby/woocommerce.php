<?php
	add_action( 'woocommerce_thankyou', 'toby_custom_tracking' );

	function toby_custom_tracking( $order_id ) {

		$script = array();

		$order_ga_status = get_post_meta( $order_id, 'tc_ga_tracking_sent', true );
		
		if ( $order_ga_status ) {
		
			//GA tracking already sent
			return $order_id;
			
		} else {
			update_post_meta( $order_id, 'tc_ga_tracking_sent', '1' );
		}

		// Lets grab the order
		$order = wc_get_order( $order_id );

		$transactionId = $order->get_transaction_id();
		
		//If transaction id is not available. Create our own transaction ID based on order id
		if ( empty( $transactionId ) ) {
			$transactionId = "ORDER-NO-" . $order_id;
		}
		
		$transactionAffiliation = get_bloginfo( 'name' );
		 
		// This is the order total
		$transactionTotal = $order->get_total();
		$transactionShippingTotal = $order->get_shipping_total();
		$transactionTaxTotal = $order->get_total_tax();
		
		$script[] = "var transactionId = '" . $transactionId . "';";
		
		$script[] = "dataLayer.push({";
		$script[] = "'transactionId' : '" . $transactionId . "',";
		$script[] = "'transactionAffiliation' : '" . $transactionAffiliation . "',";
		$script[] = "'transactionTotal' : '" . $transactionTotal . "',";
		$script[] = "'transactionShippingTotal' : " . ( $transactionShippingTotal ? $transactionShippingTotal : 0 ). ",";
		$script[] = "'transactionTaxTotal' : " . ( $transactionTaxTotal ? $transactionTaxTotal : 0 ) . ",";
		$script[] = "'transactionProducts' : [";
	 
		// This is how to grab line items from the order 
		$line_items = $order->get_items();

		// This loops over line items
		foreach ( $line_items as $item ) {
					
			// This will be a product
			$item_data = $item->get_data();
			
			// Get WC Product object
			$product = wc_get_product( $item_data['product_id'] );
			
			//Get Variation ID
			$variation_id = $item_data['variation_id'];
			
			// This is the products SKU
			$sku = $product->get_sku();
			
			//If sku is not available. Create our own SKU based on Product ID and Variant ID (if available)
			if ( empty( $sku ) ) {
				$sku = 'PRD-' . $item_data['product_id'] . ( $variation_id ? '-VRNT-' . $variation_id : '' );
			}
			
			// Get product's name
			$name = $product->get_title();
			
			// This is the qty purchased
			$qty = $item_data['quantity'];
			
			$product_categories = $product->get_category_ids();
			
			$categories = '';
			
			if ( count($product_categories) ) {
				//Get only the Primary/First category of the product				
				if( $term = get_term_by( 'id', $product_categories[0], 'product_cat' ) ) {
					$categories = $term->name;
				}
			}
			
			$price = $product->get_price();
			
			// Line item total cost including taxes and rounded
			//$total = $order->get_line_total( $item, true, true );
			
			// Line item subtotal (before discounts)
			//$subtotal = $order->get_line_subtotal( $item, true, true );
			
			$script[] = "{";
				$script[] = "'sku' : '" . $sku . "',";
				$script[] = "'name' : '" . $name . "',";
				$script[] = "'category' : '" . $categories . "',";
				$script[] = "'price' : " . $price . ",";
				$script[] = "'quantity' : " . $qty;
			$script[] = "},";
		}
		
		if ( $script[ count($script) - 1 ] == "}," ) {
		$script[ count($script) - 1 ] = "}";	//remove last comma
		}
		
		$script[] = "],";	//transactionProducts
		$script[] = "'event' : 'transactionComplete'";
		$script[] = "});";
		
		if ( count($script) ) {
			
			echo '<script type="text/javascript">';
				echo 'window.dataLayer = window.dataLayer || [];' . "\r\n";
				
				foreach( $script as $line ) {
					echo $line . "\r\n";
				}
				
			echo '</script>';
			
		}
		
		return $order_id;
	}
	
	// Add a new checkout field
	function toby_filter_checkout_fields($fields) {
		
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		
			$product_id = $cart_item['product_id'];  
			
			if ( has_term( 'gift-cards', 'product_cat', $product_id ) ) {
			
				$fields['toby_gift_cards_fields'] = array(
							'product_gift_card_address' => array(
								'type' => 'text',
								'required' => true,
								'label' => __( 'Gift Card Post Address:' )
							)
						);
				
				break;
			}
			
		}

		return $fields;
		
	}
	add_filter( 'woocommerce_checkout_fields', 'toby_filter_checkout_fields' );

	// display the extra field on the checkout form
	function toby_extra_checkout_fields() { 

		$checkout = WC()->checkout(); 
	
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		
			$product_id = $cart_item['product_id'];  
			
			if ( has_term( 'gift-cards', 'product_cat', $product_id ) ) {
				?>

					<div class="toby_gift_cards_fields">
						<h3><?php _e( 'Gift Cards' ); ?></h3>
						<?php 
							// because of this foreach, everything added to the array in the previous function will display automagically
							foreach ( $checkout->checkout_fields['toby_gift_cards_fields'] as $key => $field ) :

								woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );

							endforeach; 
						?>
					</div>

				<?php      
				break;
			}
		}
	}
	add_action( 'woocommerce_checkout_after_customer_details' ,'toby_extra_checkout_fields' );
	
	// save the extra field when checkout is processed
	function toby_save_extra_checkout_fields( $order, $data ){

		// don't forget appropriate sanitization if you are using a different field type
		if( isset( $data['product_gift_card_address'] ) ) {
			$order->update_meta_data( '_gift_cards_address', sanitize_text_field( $data['product_gift_card_address'] ) );
		}

	}
	add_action( 'woocommerce_checkout_create_order', 'toby_save_extra_checkout_fields', 10, 2 );

	function toby_display_order_data( $order_id ) {  
		$order = wc_get_order( $order_id ); 
		
		$gift_card = $order->get_meta( '_gift_cards_address' );
		
		if ( $gift_card ) {
	?>
			<h2><?php _e( 'Gift Cards' ); ?></h2>
			<table class="shop_table shop_table_responsive additional_info">
				<tbody>
					<tr>
						<th><?php _e( 'Gift Card Post Address:' ); ?></th>
						<td><?php echo $gift_card; ?></td>
					</tr>
				</tbody>
			</table>
	<?php 
		}
	}
	add_action( 'woocommerce_thankyou', 'toby_display_order_data', 20 );
	add_action( 'woocommerce_view_order', 'toby_display_order_data', 20 );
	
	// display the extra data in the order admin panel
	function toby_display_order_data_in_admin( $order ){  
	
		$order_details = wc_get_order( $order ); 
		
		$gift_card = $order_details->get_meta( '_gift_cards_address' );
		
		if ( $gift_card ) {
	?>
			<div class="form-field form-field-wide toby-gift_cards_address">
				<h4><?php _e( 'Gift Cards', 'woocommerce' ); ?></h4>
				<?php 
					echo '<p><strong>' . __( 'Gift Card Post Address' ) . ': </strong>' . $gift_card . '</p>';
				?>
			</div>
	<?php 
		}
	}
	add_action( 'woocommerce_admin_order_data_after_order_details', 'toby_display_order_data_in_admin' );

	// WooCommerce 3.0+
	function toby_email_order_meta_fields( $fields, $sent_to_admin, $order ) {
	
		$order_details = wc_get_order( $order ); 
		
		$gift_card = $order_details->get_meta( '_gift_cards_address' );
		
		if ( $gift_card ) {	
			$fields['toby_gift_cards_fields'] = array(
						'label' => __( 'Gift Card Post Address' ),
						'value' => $gift_card,
					);
		}
		
		return $fields;
	}
	add_filter( 'woocommerce_email_order_meta_fields', 'toby_email_order_meta_fields', 10, 3 );
	
	/* WooCommerce Custom Product Tab */
	add_filter( 'woocommerce_product_data_tabs', 'toby_add_custom_product_data_tab' );
	function toby_add_custom_product_data_tab( $product_data_tabs ) {
		
		$product_data_tabs['misc-product-settings'] = array(
			'label' => __( 'Misc. Product Settings', 'woocommerce' ),
			'target' => 'toby_custom_product_data',
			'class'	=> array( 'show_if_simple', 'show_if_variable', 'show_if_grouped', 'show_if_external' ),
			'priority' => '100'
		);
		return $product_data_tabs;
		
	}

	/** CSS To Add Custom Tab Icon */
	function toby_product_data_tab_custom_style() {
	?>
		<style>
			#woocommerce-product-data ul.wc-tabs li.misc-product-settings_options a:before { font-family: WooCommerce; content: '\e01c'; }
			#toby_product_data_colors, #toby_product_data_addons { height: auto; }
		</style>
	<?php 
	
	}
	add_action( 'admin_head', 'toby_product_data_tab_custom_style' );

		
	/* WooCommerce Product Custom Tab Fields */
	function toby_custom_product_data_fields() {
		?>
		<div id="toby_custom_product_data" class="panel woocommerce_options_panel">
			<div class="options_group">
		<?php
				// Checkbox
				woocommerce_wp_checkbox(
					array(
						'id' => 'toby_product_data_hide_in_shop',
						'label' => __('Hide in Shop page', 'woocommerce' ),
						'description' => __( 'Hide this product on Shop page?', 'woocommerce' )
					)
				);
		?> 
			</div>
		</div>
		<?php
	}
	add_action('woocommerce_product_data_panels', 'toby_custom_product_data_fields');

	/** WooCommerce Product Custom Tab Save Fields */
	function toby_save_product_data_custom_fields($post_id) {
	
		if ( isset( $_POST['toby_product_data_hide_in_shop'] ) ) {
			$toby_product_data_hide_in_shop = $_POST['toby_product_data_hide_in_shop'];
		} else {
			$toby_product_data_hide_in_shop = 'no';
		}
		
		$checkbox = ( $toby_product_data_hide_in_shop != 'yes' ? 'no' : 'yes' );

		update_post_meta( $post_id, 'toby_product_data_hide_in_shop', $checkbox );

	}
	add_action( 'woocommerce_process_product_meta', 'toby_save_product_data_custom_fields'  );
		
	function toby_custom_pre_get_posts_query( $q ) {

		if ( is_shop() && !is_search() ) {
		
			$meta_query = $q->get( 'meta_query' );

			$meta_query[] = array(
						'key'       => 'toby_product_data_hide_in_shop',
						'compare'   => '=',
						'value'		=> 'no'
					);

		 
			$q->set( 'meta_query', $meta_query );

		}

	}
	add_action( 'woocommerce_product_query', 'toby_custom_pre_get_posts_query' );

	function toby_extra_register_fields() {

		?>
		   <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			   <label><input type="checkbox" name="reg_subscribe_newsletter" value="1" checked /> Subscribe to our newsletter. Save 10% on your initial order!</label>
		   </p>
		   <div class="clear"></div>
		<?php

	}
	add_action( 'woocommerce_register_form', 'toby_extra_register_fields' );

	function toby_save_extra_register_fields( $customer_id ) {
		
		if ( isset( $_POST['reg_subscribe_newsletter'] ) ) {
		
			$email = $_POST['email'];
			
			if ( $email ) {
				TNP::subscribe( [ 'email' => $email ] );
			}
			
		}
		
	}
	add_action( 'woocommerce_created_customer', 'toby_save_extra_register_fields' );

	add_filter( 'woocommerce_loop_add_to_cart_link', 'toby_add_to_cart_remove_nofollow', 99, 3 );
	function toby_add_to_cart_remove_nofollow( $button_html, $product, $args ) {

		$button_html = str_replace( 'rel="nofollow"', '', $button_html );

		return $button_html;
		
	}

	add_filter( 'toby_theshopier_order_by_dropdown', 'toby_theshopier_order_by_dropdown_modify', 99, 1 );
	function toby_theshopier_order_by_dropdown_modify( $orderBy ) {

		$orderBy['Post In'] = 'post__in';

		return $orderBy;
		
	}
	
	add_filter( 'woocommerce_product_description_heading', 'toby_woocommerce_product_description_heading', 99, 1 );
	function toby_woocommerce_product_description_heading( $description ) {
		
		global $post;
		$product = wc_get_product( $post->ID );
		if ( $product ) {
			if ( $product->is_type('yith_bundle') ) {
				$description = false;
			}
		}
		
		return $description;
	}
	
	add_filter( 'woocommerce_product_tabs', 'toby_woocommerce_product_tabs', 99, 1 );
	function toby_woocommerce_product_tabs( $tabs ) {
	
		global $post;
		$product = wc_get_product( $post->ID );
		if ( $product ) {
			if ( $product->is_type('yith_bundle') ) {
				unset( $tabs['description'] );
			}
		}
		
		return $tabs;
	}
	
	add_filter( 'woocommerce_cart_item_remove_link', 'toby_woocommerce_cart_item_remove_link', 99, 2 );
	function toby_woocommerce_cart_item_remove_link( $link, $cart_item_key ) {

		$product = WC()->cart->get_cart_item( $cart_item_key );
		
		/* Remove 'Remove product' button if item is a bundled item */
		if ( array_key_exists( 'bundled_by', $product )  ) {
			$link = '';
		}
		
		return $link;
		
	}
	
	add_action( 'toby_product_bundle_summary', 'toby_product_bundle_summary_func', 10 );
	function toby_product_bundle_summary_func() {
		global $post;
		$product = wc_get_product( $post->ID );
		if ( $product ) {
			if ( $product->is_type('yith_bundle') ) {
				
				echo '<div id="toby_bundle_description">';
					the_content();
				echo '</div>';
				
			}
		}
	}
	
/* 	add_action('restrict_manage_posts', 'toby_featured_products_sorting');
	function toby_featured_products_sorting() {
		global $typenow;
		$post_type = 'product'; // change to your post type
		$taxonomy  = 'product_visibility'; // change to your taxonomy
		if ($typenow == $post_type) {
			$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
			$info_taxonomy = get_taxonomy($taxonomy);
			wp_dropdown_categories(array(
				'show_option_all' => __("Show all {$info_taxonomy->label}"),
				'taxonomy'        => $taxonomy,
				'name'            => $taxonomy,
				'orderby'         => 'name',
				'selected'        => $selected,
				'show_count'      => true,
				'hide_empty'      => true,
			));
		};
	}
	add_filter('parse_query', 'toby_featured_products_sorting_query');
	function toby_featured_products_sorting_query($query) {
		global $pagenow;
		$post_type = 'product'; // change to your post type
		$taxonomy  = 'product_visibility'; // change to your taxonomy
		$q_vars    = &$query->query_vars;
		if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
			$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
			$q_vars[$taxonomy] = $term->slug;
		}
	} */
	
/* 	add_action( 'woocommerce_ajax_added_to_cart', 'toby_ajax_custom_redirect', 99, 1 );
	function toby_ajax_custom_redirect( $product_id ) {

		if ( $product_id  ) {
		
			$product = wc_get_product( $product_id );
			
			if ( $product->is_type( 'woosb' ) ) {

				wc_add_notice( '<a href="' . site_url( '/cart/' ) . '" class="button wc-forward">View cart</a> "' . $product->get_title() . '" has been added to your cart.' );
				
				// custom redirect url
				$custom_redirect_url = wc_get_cart_url();

				if ( isset( $_POST['redirect_uri'] ) ) {
				
					$custom_redirect_url = get_site_url() . $_POST['redirect_uri'];

				}
				
				$data = array(
					'error'       => true,
					'product_url' => $custom_redirect_url
				);

				wp_send_json( $data );

				exit;

			}
			
		}

	}
	
	add_action( 'woocommerce_cart_redirect_after_error', 'toby_ajax_redirect_on_error', 99, 2 );
	function toby_ajax_redirect_on_error( $permalink, $product_id ) {

		if ( $product_id  ) {
		
			$product = wc_get_product( $product_id );
			
			if ( $product->is_type( 'woosb' ) ) {

				// custom redirect url
				$custom_redirect_url = wc_get_cart_url();
				
				if ( isset( $_POST['redirect_uri'] ) ) {
				
					$custom_redirect_url = get_site_url() . $_POST['redirect_uri'];

				}
				
				$data = array(
					'error'       => true,
					'product_url' => $custom_redirect_url
				);

				wp_send_json( $data );

				exit;

			}
			
		}

	} */