<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package NexThemes
 * @subpackage nth-theme
 * @since nth-theme
 */
$classes = array(
    '_row_class' => array('footer-wrapper'),
    '_class_cont' => array()
);
$classes = apply_filters('theshopier_main_footer_class', $classes);
?>
</div><!--.body-wrapper-->
</div><!--.main-content-->

<footer id="footer" class="<?php echo esc_attr(implode(' ', $classes['_class_cont'])) ?>">
    <div class="<?php echo esc_attr(implode(' ', $classes['_row_class'])) ?>">
        <?php do_action('theshopier_footer_init'); ?>
    </div>
</footer>

</div><!--#main-content-wrapper-->
<!--div id="main-right-sidebar"></div--><!--#main-right-sidebar-->
</div><!--#body-wrapper-->
<div id="msgDialog" class="main_popup" style="display: none;">
    <div class="popup_img"><img class="theshopier-image" src="https://amousewithahouse.com.au/wp-content/uploads/2016/01/kid.png" alt="Wooden Toys for Sale" width="539" height="779" /></div>
    <div class="text_section">
        <h2>GET A 5% DISCOUNT VOUCHER</h2>
        <br />
        <h5>Enter your name and email to get an instant discount voucher code delivered in your mailbox. For quick response, chat with us now!</h5>
    </div>
    <div class="form_section"><?php echo do_shortcode('[newsletter_form button_label="Subscribe!"] [newsletter_field name="email" label="Your best email"] [newsletter_field name="first_name" label="Your name"] [/newsletter_form]'); ?></div>
</div>
<?php do_action('theshopier_footer_before_body_endtag'); ?>

<?php wp_footer(); ?>
</body>
</html>

