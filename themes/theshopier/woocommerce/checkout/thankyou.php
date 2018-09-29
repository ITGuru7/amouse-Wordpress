<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

do_action('theshopier_shopping_progress');

if ( $order ) : ?>

	<div class="nth-row-grid">

		<div class="col-sm-24">

			<?php if ( $order->has_status( 'failed' ) ) : ?>

				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'theshopier' ); ?></p>

				<p><?php
					if ( is_user_logged_in() )
						esc_html_e( 'Please attempt your purchase again or go to your account page.', 'theshopier' );
					else
						esc_html_e( 'Please attempt your purchase again.', 'theshopier' );
					?></p>

				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
					<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'theshopier' ) ?></a>
					<?php if ( is_user_logged_in() ) : ?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My Account', 'theshopier' ); ?></a>
					<?php endif; ?>
				</p>

			<?php else : ?>

				<!--<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'theshopier' ), $order ); ?></p>-->

				<ul class="order_details">
					<li class="woocommerce-order-overview__order order">
						<span class="nth-label"><?php esc_html_e( 'Order Number:', 'theshopier' ); ?></span>
						<strong><?php echo $order->get_order_number(); ?></strong>
					</li>
					<li class="woocommerce-order-overview__date date">
						<span class="nth-label"><?php esc_html_e( 'Date:', 'theshopier' ); ?></span>
						<strong><?php echo wc_format_datetime( $order->get_date_created() ); ?></strong>
					</li>
					<li class="woocommerce-order-overview__total total">
						<span class="nth-label"><?php esc_html_e( 'Total:', 'theshopier' ); ?></span>
						<strong><?php echo $order->get_formatted_order_total(); ?></strong>
					</li>
					<?php if ( $order->get_payment_method_title() ) : ?>
						<li class="woocommerce-order-overview__payment-method method">
							<span class="nth-label"><?php esc_html_e( 'Payment Method:', 'theshopier' ); ?></span>
							<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
						</li>
					<?php endif; ?>
				</ul>
				<div class="clear"></div>

			<?php endif; ?>

			<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>

		</div>

	</div>

	<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

<?php else : ?>

	<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'theshopier' ), null ); ?></p>

<?php endif; ?>
