<?php 
/**
 *  Plugin Name: PMPRO Custom Changes
 *  Description: Add Custom Changes to PMPRO
 *  Description: Creates a Gallery Index of your posts as a card flip.
 *  Version: 1.0
 *  Author: Vanstone Online | Jason Vanstone
 *  Author URI: https://vanstoneonline.com
 */

function pmpro_custom_activate() {

    add_option( 'Activated_Plugin', 'PMRPO-Custom' );
  
    /* activation code here */
}
register_activation_hook( __FILE__, 'pmpro_custom_activate' );
  
function load_plugin() {

    if ( is_admin() && get_option( 'Activated_Plugin' ) == 'PMRPO-Custom' ) {

        delete_option( 'Activated_Plugin' );

        /* do stuff once right after activation */
        // add_action( 'init', 'pmpro_changes_init_function' );
        // function pmpro_changes_init_function(){
        
    }
}
add_action( 'admin_init', 'load_plugin' );

  
function pmpro_changes_init_function(){
    if ( is_admin() ) {
        // we are in admin mode
        require_once __DIR__ . '/admin/pmpro-changes-admin.php';
    }

    // Access the working part of the plugin
    require_once 'pmpro-custom/pmpro-changes.php';
}
add_action( 'init', 'pmpro_changes_init_function' );



