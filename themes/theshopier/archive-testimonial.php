<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
get_header();

$sidebar_data = theshopier_pages_sidebar_act('blog');
extract( $sidebar_data );

$datas = array(
	'show_bcrumb'	=> $_show_breadcrumb,
); 
do_action( 'theshopier_breadcrumb', $datas );
?>	
<div id="container" class="content-wrapper archive-page container">
	<div class="row">
		<?php if( strlen(trim($_left_class)) ):?>
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
			the_archive_title('<h1 class="page-title">', '</h1>');
			?>

			<?php if(have_posts()) : ?>
			
			<ul class="list-posts row">
			
				<?php while( have_posts() ): the_post();?>
				
				<?php get_template_part( 'content', get_post_format() );?>
				
				<?php endwhile;?>
			
			</ul>
			
			<?php endif;?>
			
			<div class="wpb_wrapper">
			<h2 style="text-align: left" class="vc_custom_heading vc_custom_1510128797085 client_title">Read what our clients say about AMouseWithAHouse</h2>
			<p>AMouseWithAHouse likes to focus on a customer service approach. We encourage you to read what our customers have to say about the AMouseWithAHouse online toy store on Google and Facebook reviews. If you would like to leave a comment about your experience with AMouseWithAHouse you can email <a href="mailto:info@amousewithahouse.com.au">info@amousewithahouse.com.au</a> and we welcome you to add your own review on Facebook or our Google My Business page.</p>
			<div class="wpb_text_column wpb_content_element  vc_custom_1510128707816">
			<div class="wpb_wrapper">
				<p><a rel="noopener" href="https://www.facebook.com/pg/amousewithahouse/reviews/?ref=page_internal" target="_blank"><img class="review-logo" src="https://amousewithahouse.com.au/wp-content/uploads/2016/01/facebook-reviews.png" alt="Read reviews of AMouseWithAHouse on Facebook" width="125" height="55">Read reviews of AMouseWithAHouse on Facebook</a></p>
				<p><a rel="noopener" href="https://www.google.com.au/maps/place/A+Mouse+With+a+House/@-31.92075,115.7657013,17z/data=!3m1!4b1!4m5!3m4!1s0x2a32af4f96016409:0x41510c52ce1baf46!8m2!3d-31.92075!4d115.76789" target="_blank"><img class="review-logo" src="https://amousewithahouse.com.au/wp-content/uploads/2016/01/google-reviews.png" alt="Read reviews of AMouseWithAHouse on Google" width="125" height="55">Read reviews of AMouseWithAHouse on Google</a></p>
			</div>
			</div>
			</div>
			
			
			<?php theshopier_paging_nav();?>
			
			
			
		</div><!-- .nth-content-main -->
		
		<?php if( strlen(trim($_right_class)) ):?>
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
</div><!-- .container -->
<?php get_footer(); ?>