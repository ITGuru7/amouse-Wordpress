<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 */

get_header();
$datas = array(
	'show_bcrumb'	=> 1,
);

do_action( 'theshopier_breadcrumb', $datas );

?>
<style>
	.toby-sitemap-list {
		padding: 0;
		max-width: 480px;
		margin: 15px auto;	
		list-style-type: disc;
		-webkit-columns: 2;
		-moz-columns: 2;
		columns: 2;
		list-style-position: inside;
	}
	.toby-sitemap-list, .toby-sitemap-list li a {
		text-align: left;
		color: #fff;
	}
</style>
<div id="container" class="content-wrapper page-404 container">
	<div class="nth-content-main">
		
		<h1 class="page-heading page-title"><?php esc_html_e("404 Page Not Found", 'theshopier' );?></h1>
		
		<div class="page-404-content-inner col-sm-24">
			<?php
			theshopier_get_404_content();
			?>
		</div>
		
	</div>
</div>

<?php get_footer(); ?>