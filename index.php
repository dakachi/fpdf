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


function fpdf_add_pdf_meta_boxes()
{
    add_meta_box('pdf_printer', __('PDF Printer'), 'fpdf_printer_button', 'shop_order', 'side', 'high');
}
add_action('add_meta_boxes', 'fpdf_add_pdf_meta_boxes');

function fpdf_printer_button() {
	global $post;
	$order = wc_get_order($post->ID);
	$items = $order->get_items();

	$item = array_pop($items);
	$product_id = $item['product_id'];

?>
	<a target="_blank" href="<?php print wp_nonce_url(admin_url('?product_id='.$product_id.'&order_id=' . $post->ID), 'create_pdf', '_wp_nonce');?>"
        class="button button-primary"><?php esc_html_e('Print', 'fpdf');?></a>
<?php
}