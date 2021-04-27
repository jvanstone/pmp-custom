
<?php 

add_action( 'admin_menu', 'pmpro_custom_menu' );
function pmpro_custom_menu() {
	add_options_page( 'PMPRO Custom Options', 'PMPRO Custom Changes', 'manage_options', 'pmprocc', 'pmpro_custom_options' );
     add_menu_page( 'PMPRO Custom Options', 'PMPRO Custom Changes', 'manage_options', 'my-top-level-handle', 'pmpro_custom_options');
}


function pmpro_custom_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}



 // variables for the field and option names. 
 $main_name = 'pmpro_custom_main_name';
 $purchase_level = 'pmpro_custom_purchase_level';
 $hidden_field_name = 'pmpro_custom_submit_hidden';

 // variable of the data in the system. 
 $data_main_name = 'pmpro_custom_main_name';
 $data_purchase_level = 'pmpro_custom_purchase_level';

 // Read in existing option value from database


 $opt_val = get_option( $main_name );
 $opt_val2 = get_option( $purchase_level );

 // See if the user has posted us some information
 // If they did, this hidden field will be set to 'Y'
 if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
     // Read their posted value
     $opt_val = $_POST[ $data_main_name ];
     $opt_val2 = $_POST[ $data_purchase_level];

     // Save the posted value in the database
     update_option( $main_name, $opt_val );
     update_option( $purchase_level, $opt_val2 );

     // Put a "settings saved" message on the screen

?>
<div class="updated"><p><strong><?php _e('Settings saved.', 'pmpro_custom' ); ?></strong></p></div>
<?php

 }

 // Now display the settings editing screen

 echo '<div class="wrap">';

 // header

 echo "<h2>" . __( 'PMPRO Custom Settings', 'pmpro_custom' ) . "</h2>";
 ?>
 <p><?php _e('Here you will be able to reset some of the preset language of the language to customize to your website and needs.' , 'pmpro_custom')?></p>

<form name="form1" method="post" action="">
    <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

    <p><?php _e('Main Header:', 'pmpro_custom' ); ?> 
    <input type="text" name="<?php echo $data_main_name; ?>" value="<?php echo $opt_val; ?>" size="40">
    </p>
    
    <p><?php _e('Purchase Level:', 'pmpro_custom' ); ?>
        <input type="text" name="<?php echo $data_purchase_level; ?>" value="<?php echo $opt_val2; ?>" size="40">
    </p>
    <p> <?php _e('You have selected the <strong>%s</strong> membership level.', 'pmpro_custom' ); ?></p>
    <hr />

    <p class="submit">
    <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
    </p>

</form>
<?php
    echo '</div>';
}

