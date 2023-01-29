<?php
/*
Plugin Name: SHOPEO WC Sort
Plugin URI: https://shopeo.cn/
Description: SHOPEO WC Sort
Author: SHOPEO
Author URI: https://shopeo.cn/
Text Domain: shopeo-wc-sort
Domain Path: /languages/
Version: 0.0.1
*/

if ( ! function_exists( 'shopeo_wc_sort_activation' ) ) {
	function shopeo_wc_sort_activation() {

	}
}
register_activation_hook( __FILE__, 'shopeo_wc_sort_activation' );

if ( ! function_exists( 'shopeo_wc_sort_deactivation' ) ) {
	function shopeo_wc_sort_deactivation() {

	}
}
register_deactivation_hook( __FILE__, 'shopeo_wc_sort_deactivation' );

add_action( 'init', function () {
	load_plugin_textdomain( 'shopeo-wc-sort', false, dirname( __FILE__ ) . '/languages' );
} );

function shopeo_wc_sort_meta_box_html( $post ) {
	$value = get_post_meta( $post->ID, 'shopeo_wc_sort_order', true );
	?>
    <label for="shopeo_wc_sort_order">Order</label>
    <input id="shopeo_wc_sort_order" type="number" style="margin-top: 5px;width: 100%;" min="0"
           name="shopeo_wc_sort_order" value="<?php echo $value > 0 ? $value : 0; ?>">
	<?php
}

add_action( 'add_meta_boxes', function () {
	$screens = [ 'product' ];
	foreach ( $screens as $screen ) {
		add_meta_box(
			'sort_by_meta_box',
			'Sort By',
			'shopeo_wc_sort_meta_box_html',
			$screen
		);
	}
} );

add_action( 'save_post', function ( $post_id ) {
	if ( array_key_exists( 'shopeo_wc_sort_order', $_POST ) ) {
		update_post_meta(
			$post_id,
			'shopeo_wc_sort_order',
			$_POST['shopeo_wc_sort_order']
		);
	}
} );

add_action( 'woocommerce_product_query', function ( $q ) {
	if ( ! isset( $_GET['orderby'] ) ) {
		$q->set( 'orderby', 'meta_value_num' );
		$q->set( 'order', 'DESC' );
		$q->set( 'meta_key', 'shopeo_wc_sort_order' );
	}
} );



