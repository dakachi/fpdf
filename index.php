<?php
/*
Plugin Name: PDF Generator for print
Author: Wordpress
*/

add_action( 'admin_init', 'fpdf_load_fpdf' );
function fpdf_load_fpdf() {
	if($_GET['_wp_nonce'] && wp_verify_nonce( $_GET['_wp_nonce'], 'create_pdf' )) {
		require_once dirname(__FILE__)  . '/abc.php';
	}
}


// function fpdf_add_pdf_meta_boxes()
// {
//     add_meta_box('pdf_printer', __('PDF Printer'), 'fpdf_printer_button', 'shop_order', 'side', 'high');
// }
add_action('woocommerce_after_order_itemmeta', 'fpdf_printer_button', 10, 3);

function fpdf_printer_button($item_id, $item, $_product) {
	global $post;
	$order = wc_get_order($post->ID);

	$product_id = $_product->get_id();

?>
	<a target="_blank" href="<?php print wp_nonce_url(admin_url('?product_id='.$product_id.'&order_id=' . $post->ID.'&item_id=' . $item_id), 'create_pdf', '_wp_nonce');?>"
        ><?php esc_html_e('Print', 'fpdf');?></a>
<?php
}