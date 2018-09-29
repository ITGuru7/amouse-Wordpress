<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage ctheme
 * @since Ctheme
 */

get_header();
if(is_page('checkout-form-international')){
foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) { 
 $items[]= $cart_item['product_id'];
 $product_title[]= get_the_title($cart_item['product_id']);
$quantityvar[]= $cart_item['quantity'];

}
$product_title= implode(",",$product_title);
 $itemsnumber= implode(",",$items);
 $quantityvar= implode(",",$quantityvar);
}

$sidebar_data = theshopier_pages_sidebar_act();
extract( $sidebar_data );

$datas = array(
	'show_title'	=> $_show_title,
	'show_bcrumb'	=> $_show_breadcrumb
); 
do_action( 'theshopier_breadcrumb', $datas );

?>
<div id="container" class="content-wrapper page-content container">
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

			<?php if(!is_home() && !is_front_page() && absint($datas['show_title'])): ?>
				<h1 class="page-title">
					<?php
						echo apply_filters( 'theshopier_page_title', get_the_title() );
					?>
				</h1>
				<?php 
					$value = get_field( "subtitle" );
					echo ( $value ? '<h2 class="page-sub-title">' . $value . '</h2>' : '' ); 
				?>
			<?php endif;?>
			
			<?php
			while ( have_posts() ) : the_post();
				get_template_part( 'content', 'page' );

				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			endwhile;
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
</div><!--.content-wrapper-->
<?php 
if(is_page('checkout-form-international')){ ?>

<script type="text/javascript">

jQuery( document ).ready(function() {
  var product_title= '<?php echo htmlentities($product_title);?>';
   var quantitynumber= '<?php echo $quantityvar;?>';
   
   jQuery('#hiddenitem').val(product_title);
    jQuery('#hiddenpr').val(quantitynumber);
   
   
});

//jQuery( document ).ajaxComplete(function( event,request, settings ) {
jQuery(".page-id-4387 .wpcf7").on('wpcf7:mailsent', function(event){


   if(jQuery('.sent').length > 0){
       var ajaxUrl = "<?php echo admin_url('admin-ajax.php')?>";
    
        jQuery.post(ajaxUrl, {
            action:"remove_cart_custom"
           
        }).success(function(posts){
            location.reload();
        });

 
      
   }

});



</script>
 <?php } get_footer();
