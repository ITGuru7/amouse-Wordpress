<?php
/**
 * @package WordPress
 * @subpackage Nexthemes
 * @since Nexthemes 1.0
 */

require_once get_template_directory() . '/framework/theme.php';

$theshopier = new Theshopier();
$theshopier->init();


add_filter('woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2);

function change_existing_currency_symbol( $currency_symbol, $currency ) {
     switch( $currency ) {
          case 'AUD': $currency_symbol = 'AUD$'; break;
     }
     return $currency_symbol;
}

add_filter( 'woocommerce_get_availability', 'wcs_custom_get_availability', 1, 2);
function wcs_custom_get_availability( $availability, $_product ) {
    
    // Change In Stock Text
    if ( $_product->is_in_stock() ) {
        $availability['availability'] = __('', 'woocommerce');
    }
    // Change Out of Stock Text
    if ( ! $_product->is_in_stock() ) {
        $availability['availability'] = __('Out Of Stock', 'woocommerce');
    }
    return $availability;
}

/* delete for cart items after sending contact form*/
function remove_cart_custom(){
global $woocommerce;
$woocommerce->cart->empty_cart(); 
wp_die(); }
add_action('wp_ajax_nopriv_remove_cart_custom', 'remove_cart_custom'); 
add_action('wp_ajax_remove_cart_custom', 'remove_cart_custom');

// $products = get_posts(array('post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => -1));
// // echo '<!--<pre>';
// // print_r($products);
// // echo '</pre>-->';
// foreach($products as $p){
// //	echo '<!--'.$p->ID.'-->';
	// update_post_meta($p->ID, '_backorders', 'no');
// }


add_shortcode('show_popup', 'show_popup');

function show_popup() {
    if (is_home() || is_front_page()) {
        if ($_GET['nk']) {
            echo do_shortcode("[Wow-Modal-Windows id=2]");
        } else {
            echo do_shortcode("[Wow-Modal-Windows id=1]");
        }
    }
}

function filter_woocommerce_page_title($page_title) { 
    $output = '';  

    
    if(is_tax('product_brand')){
    
        $category = get_term_by('slug', get_query_var( 'term' ), 'product_brand');   
        if ($category) {
            $description = explode(".",$category->description);
            $cnt = strlen($category->description);
            $ncnt=0;
            $newtext = '';
            $hidetext = '';
            $term_img='';           

            for( $i = 0; $i <= $cnt ; $i++ ) {

            $val= substr( $category->description, $i, 1 );
                if ($i < 560) {
                    $newtext .= $val;                   
                } else {
                    $hidetext .= $val;
                }
           }

            $thumb_id = get_woocommerce_term_meta($category->term_id, 'thumbnail_id', true);
            $term_img = wp_get_attachment_url($thumb_id);

            $output .= '<div class=brandinfo">';
            if ($term_img != '') {
                $output .= '<h1 class="page-title" style="background: url(' . $term_img . ') center center / cover no-repeat; height: 200px; line-height: 200px; text-align: center; font-size: 51px; margin-top: -85px;"></h1>';
            }
            $output .= '<p class="showbrandparent">' . $newtext;
            if ($hidetext != '') {
                $output .= '<span style="display:none;" class="showbrandchild">' . $hidetext . '</span>';
                $output .= '  <a class="showbranddet" data-check="0" href="javascript:void(0);"><b>Read More</b></a>';
            }
            $output .= '</p>';
            $output .= '</div>';
            $output .= '<script>jQuery(document).ready(function(){  jQuery(".showbranddet").click(function(){
                var check=jQuery(".showbranddet").attr("data-check");
                if(check==0)
                {
                jQuery(".showbrandchild").show();
                jQuery(".showbranddet").attr("data-check","1");
                jQuery(".showbranddet").text("Read Less");
                }
                else
                {
                jQuery(".showbrandchild").hide();
                jQuery(".showbranddet").attr("data-check","0");
                jQuery(".showbranddet").text("Read More");
}
    }); });</script>';
        }
    } else {

        $output .= '<h1 class="page-title">' . $page_title . '</h1>';
    }
    

    return $output;
}


         
// add the filter 
add_filter( 'woocommerce_page_title', 'filter_woocommerce_page_title', 10, 1 );

 


require_once( 'toby/functions-custom.php' );
