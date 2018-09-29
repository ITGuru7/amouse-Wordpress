<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); 

$sidebar_data = theshopier_pages_sidebar_act('single_prod');
extract( $sidebar_data );

$datas = array(
	'show_bcrumb'	=> 1,
	'is_shop'		=> 1
); 
do_action( 'theshopier_breadcrumb', $datas );

?>

<div class="content-wrapper single-toy-pack container">
	<div class="row">
	
		<?php if( strlen(trim($_left_class)) > 0 ):?>
		<div class="nth-content-left nth-sidebar<?php echo esc_attr( $_left_class );?>">
			<?php if( is_active_sidebar( $_left_sidebar ) ):?>
			<ul class="widgets-sidebar">
				<?php dynamic_sidebar( $_left_sidebar ); ?>
			</ul>
			<?php else:
				esc_html_e( "Please add some widgets here!", 'theshopier' );
			endif;?>
		</div><!-- .nth-content-left -->
		<?php endif;?>
		
		<div class="nth-content-main <?php echo esc_attr( $_cont_class );?>">
		<?php
			/**
			 * woocommerce_before_main_content hook
			 *
			 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
			 * @hooked woocommerce_breadcrumb - 20
			 */
			do_action( 'woocommerce_before_main_content' );
		?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php 
					wc_print_notices();
					
					echo '<div class="toby-product-bundle-featured-image">' . get_the_post_thumbnail( get_the_ID(), 'full' ) . '</div>';
					
					the_content(); 
					
					$product_bundle = get_field( 'toby_product_bundle' );
					if ( $product_bundle ) {
					
						$product_obj = wc_get_product( $product_bundle->ID );
												
						if ( $product_obj->is_type( 'woosb' ) ) {
						
							$bundled_produtcs = get_post_meta( $product_obj->get_id(), 'woosb_ids', true );
							
							if ( $bundled_produtcs ) {
							
								$woosb_items = explode( ',', $bundled_produtcs );
								
								if ( is_array( $woosb_items ) && count( $woosb_items ) > 0 ) {
									foreach ( $woosb_items as $woosb_item ) {
										$woosb_item_arr = explode( '/', $woosb_item );
										$woosb_arr[]    = array(
											'id'  => absint( $woosb_item_arr[0] ? $woosb_item_arr[0] : 0 ),
											'qty' => absint( $woosb_item_arr[1] ? $woosb_item_arr[1] : 1 )
										);
									}

									if ( count( $woosb_arr ) > 0 ) {
									
										echo '<div class="toby-product-bundle-items">';
										
											echo '<table class="table table-bordered">';
												echo '<thead>';
													echo '<tr>';
														echo '<th width="100"></th>';
														echo '<th>Product Name</th>';
														echo '<th width="100">Price</th>';
													echo '</tr>';
												echo '</thead>';
												
												$stock_qty = array();
												
												foreach( $woosb_arr as $item ) {
													$product_item = wc_get_product( $item['id'] );
													if ( $product_item ) {
													
														$stock = $product_item->get_stock_quantity();
														
														$stock_qty[] = ( $stock ? $stock : 1 );
														
														echo '<tr>';
															echo '<td>';
																echo '<div class="toby-product-thumb">';
																	echo $product_item->get_image( 'shop_thumbnail' );
																echo '</div>';
															echo '</td>';
															echo '<td>';
																echo '<div class="toby-product-details">';
																	echo $item['qty'] . ' x <a href="' . $product_item->get_permalink() . '">' . $product_item->get_title() . '</a>';
																echo '</div>';
															echo '</td>';
															echo '<td>';
																echo '<div class="toby-product-qty">';
																	echo $product_item->get_price_html();
																echo '</div>';
															echo '</td>';
														echo '<tr>';
													}
												}
												
											echo '</table>';

										echo '</div>';
									
										echo '<div class="toby-product-bundle-add-to-cart">';
											
											$max_qty = 4;
											
											if ( $stock_qty ) {
												asort( $stock_qty, SORT_NUMERIC );
												$max_qty = $stock_qty[0];
											}
											
											echo '<input id="toby_product_bundle_qty" type="number" min="1" max="' . $max_qty . '" value="1" />';
											$add_to_cart = do_shortcode( '[add_to_cart id=" ' . $product_bundle->ID . ' " style=""]' );
											echo str_replace( ' data-quantity', ' data-redirect_uri="'. htmlspecialchars( str_replace( get_site_url(), '', get_the_permalink() ) ) . '" data-quantity', $add_to_cart );
										echo '</div>';
									
									}
									
								}
							}
						}						
					}
				?>

			<?php endwhile; // end of the loop. ?>

		<?php
			/**
			 * woocommerce_after_main_content hook
			 *
			 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
			 */
			do_action( 'woocommerce_after_main_content' );
		?>
		
		</div><!-- .nth-content-main -->
	
		<?php if( strlen(trim($_right_class)) > 0 ):?>
		<div class="nth-content-right nth-sidebar<?php echo esc_attr( $_right_class );?>">
			<?php if( is_active_sidebar( $_right_sidebar ) ):?>
			<ul class="widgets-sidebar">
				<?php dynamic_sidebar( $_right_sidebar ); ?>
			</ul>
			<?php else:
				esc_html_e( "Please add some widgets here!", 'theshopier' );
			endif;?>
		</div><!-- .nth-content-right -->
		<?php endif;?>
	
	</div>
</div>
<?php get_footer( 'shop' ); ?>
