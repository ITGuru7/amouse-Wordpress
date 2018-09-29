<?php
/**
 * Package: TheShopier.
 * User: kinhdon
 * Date: 11/28/2015
 * Vertion: 1.0
 */

$sticky_class = apply_filters('theshopier_header_sticky_class', array('nth_header_bottom hidden-xs hidden-sm'));
?>
<div class="tot_header">
<div class="nth_header_top hidden-xs hidden-sm">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <?php
                $sidebar_name = "header-top-widget-area";
                if( is_active_sidebar( $sidebar_name ) ):?>
                    <ul class="widgets-sidebar">
                        <?php dynamic_sidebar( $sidebar_name ); ?>
                    </ul>
                <?php else:
                    esc_html_e( "Please add some widgets here!", 'theshopier' );
                endif;?>
            </div>
            <div class="col-sm-12">
                <?php if ( has_nav_menu( 'primary-menu' )) {
                    wp_nav_menu( array(
                        'container_class' => 'main-menu text-right pc-menu animated',
                        'theme_location' => 'primary-menu',
                        'walker' => new Theshopier_Mega_Menu_Frontend(),
                        'items_wrap' => '<ul class="%1$s %2$s">%3$s</ul>'
                    ) );
                }?>
            </div>
        </div>
    </div>
</div>

<div class="nth_header_middle hidden-xs hidden-sm bottom_margin_header_5" style="">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-8"><?php theshopier_search_form(); ?></div>
			<div class="col-sm-12 col-md-12"><img src="https://amousewithahouse.com.au/wp-content/uploads/2016/01/HeaderBanner.jpg" widht="607" height="130" alt="Buy Wooden Toys Australia"/></div>
            <div class="col-sm-12 col-md-4">
                <div class="text-right">
                    <!--<div class="nth-tini-wrapper"><?php //theshopier_login_form();?></div>-->
                    <div class="nth-tini-wrapper"><?php theshopier_shoppingCart();?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="<?php echo esc_attr(implode(' ', $sticky_class));?>">
    <div class="container">
        <div class="row">
            <div class="col-sm-24" style="position:relative">
                <div class="row">
                    <div class="col-sm-24 hidden-xs nth-menu-wrapper">
					<div class="col-sm-12 col-md-6 text-center logo_header_5" style=""><?php theshopier_getLogo();?></div>
                        <?php if ( has_nav_menu( 'vertical-menu' )) {
                            wp_nav_menu( array(
                                'container_class' => 'main-menu vertical-menu-hol text-center pc-menu animated',
                                'theme_location' => 'vertical-menu',
                                'walker' => new Theshopier_Mega_Menu_Frontend(),
                                'items_wrap' => '<ul class="%1$s %2$s">%3$s</ul>'
                            ) );
                        }?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
