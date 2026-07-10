<?php
/**
 * Plugin Name: WooCommerce HSN Code Manager
 * Description: Adds HSN Code field to WooCommerce products with export/import, order meta, and admin column display.
 * Author: Lalith Raj Reddi & ChatGPT
 * Version: 1.1
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Step 1: Add HSN Code field to the Product Edit Page (General Tab)
 */
add_action( 'woocommerce_product_options_general_product_data', function() {
    woocommerce_wp_text_input( array(
        'id'          => 'hsn_code',
        'label'       => __( 'HSN Code', 'woocommerce' ),
        'placeholder' => 'Enter 8-digit HSN code',
        'desc_tip'    => true,
        'description' => __( 'Enter the HSN code for this product for GST purposes.', 'woocommerce' ),
    ) );
});

/**
 * Step 2: Save HSN Code when product is saved
 */
add_action( 'woocommerce_process_product_meta', function( $post_id ) {
    $hsn_code = isset( $_POST['hsn_code'] ) ? sanitize_text_field( $_POST['hsn_code'] ) : '';
    update_post_meta( $post_id, 'hsn_code', $hsn_code );
});

/**
 * Step 3: Add HSN Code to order line items
 */
add_action( 'woocommerce_checkout_create_order_line_item', function( $item, $cart_item_key, $values, $order ) {
    $hsn_code = get_post_meta( $values['product_id'], 'hsn_code', true );
    if ( ! empty( $hsn_code ) ) {
        $item->add_meta_data( 'HSN Code', $hsn_code );
    }
}, 10, 4 );

/**
 * Step 4: Add HSN Code column to the Products list in Admin
 */
add_filter( 'manage_edit-product_columns', function( $columns ) {
    $columns['hsn_code'] = 'HSN Code';
    return $columns;
});

add_action( 'manage_product_posts_custom_column', function( $column, $post_id ) {
    if ( $column === 'hsn_code' ) {
        echo esc_html( get_post_meta( $post_id, 'hsn_code', true ) );
    }
}, 10, 2 );

add_action( 'admin_head', function() {
    echo '<style>
        th.column-hsn_code, td.column-hsn_code {
            white-space: nowrap !important;
            width: 120px !important;
            min-width: 100px !important;
            text-align: left !important;
            vertical-align: middle !important;
        }
    </style>';
});

/**
 * Step 5: Add HSN Code to WooCommerce Export & Import
 */
// Export Columns
add_filter( 'woocommerce_product_export_column_names', function( $columns ) {
    $columns['hsn_code'] = 'HSN Code';
    return $columns;
});
add_filter( 'woocommerce_product_export_product_default_columns', function( $columns ) {
    $columns['hsn_code'] = 'HSN Code';
    return $columns;
});
// Export Data
add_filter( 'woocommerce_product_export_product_column_hsn_code', function( $value, $product ) {
    return get_post_meta( $product->get_id(), 'hsn_code', true );
}, 10, 2 );
// Import Support
add_filter( 'woocommerce_product_import_pre_insert_product_object', function( $product, $data ) {
    if ( ! empty( $data['hsn_code'] ) ) {
        $product->update_meta_data( 'hsn_code', sanitize_text_field( $data['hsn_code'] ) );
    }
    return $product;
}, 10, 2 );

/**
 * Step 6: Register meta field for REST API and CSV Import Mapping
 */
add_action( 'init', function() {
    register_post_meta( 'product', 'hsn_code', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'description'  => 'HSN Code for GST',
        'auth_callback' => function() {
            return current_user_can( 'edit_products' );
        },
    ) );
});

/**
 * Step 7: Make importer mapping recognize "HSN Code" automatically
 */
add_filter( 'woocommerce_csv_product_import_mapping_options', function( $options ) {
    $options['hsn_code'] = __( 'HSN Code', 'woocommerce' );
    return $options;
});

add_filter( 'woocommerce_csv_product_import_mapping_default_columns', function( $columns ) {
    $columns['HSN Code'] = 'hsn_code';
    $columns['meta:hsn_code'] = 'hsn_code'; // Also support meta prefix
    return $columns;
});

add_action( 'woocommerce_product_import_inserted_product_object', function( $product, $data ) {
    if ( isset( $data['hsn_code'] ) ) {
        $product->update_meta_data( 'hsn_code', sanitize_text_field( $data['hsn_code'] ) );
        $product->save();
    }
}, 10, 2 );
