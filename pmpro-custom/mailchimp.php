<?php
/***
 *  Removce the default action for Mailchimp Plugin. Add a custom look.
 * 
 * 
 */
remove_action ( 'pmpro_checkout_after_tos_fields', 'pmpromc_additional_lists_on_checkout' );

function pmp_mailchimp_add() {
    global $pmpro_review;

	$options = get_option( 'pmpromc_options' );

	// Get API and bail if we can't set it.
	$api = pmpromc_getAPI();
	if ( empty( $api ) ) {
		return;
	}

	// Are there additional lists?
	if ( ! empty( $options['additional_lists'] ) ) {
		$additional_lists = $options['additional_lists'];
	} else {
		return;
	}

	global $current_user;
	pmpromc_check_additional_audiences_for_user( $current_user->ID );

	// Okay get through API.
	$lists = $api->get_all_lists();

	// No lists?
	if ( empty( $lists ) ) {
		return;
	}

	$additional_lists_array = array();
	foreach ( $lists as $list ) {
		if ( ! empty( $additional_lists ) ) {
			foreach ( $additional_lists as $additional_list ) {
				if ( $list->id == $additional_list ) {
					$additional_lists_array[] = $list;
					break;
				}
			}
		}
	}

	// No lists? do nothing.
	if ( empty( $additional_lists_array ) ) {
		return;
	}

	$display_modifier = empty( $pmpro_review ) ? '' : 'style="display: none;"';
	?>

    <div id="pmpro_mailing_lists" class="pmpro_checkout top1em" width="100%" cellpadding="0" cellspacing="0" border="0">
		<h3>Join our mailing list.</h3>
	
		<div class="col-12 p-2 ml-5">
        <?php
				global $current_user;
				if ( isset( $_REQUEST['additional_lists'] ) ) {
					$additional_lists_selected = $_REQUEST['additional_lists'];
				} elseif ( isset( $_SESSION['additional_lists'] ) ) {
					$additional_lists_selected = $_SESSION['additional_lists'];
				} elseif ( ! empty( $current_user->ID ) ) {
					$additional_lists_selected = get_user_meta( $current_user->ID, 'pmpromc_additional_lists', true );
				} else {
					$additional_lists_selected = array();
				}
				$count = 0;
				foreach ( $additional_lists_array as $key => $additional_list ) {
					$count++;
					?>
					<input type="checkbox" class="form-check-input" id="additional_lists_<?php echo( $count ); ?>" name="additional_lists[]" value="<?php echo( $additional_list->id ); ?>" 
							<?php
							if ( is_array( $additional_lists_selected ) ) {
								checked( in_array( $additional_list->id, $additional_lists_selected ) );
							};
							?>
							/>
					<label class="form-check-label" for="additional_lists_<?php echo( $count ); ?>" class="pmpromc-checkbox-label"><?php echo( $additional_list->name ); ?></label><br/>
					<?php
				}
				?>
            </div>
	</div>
 <?php
}
add_action( 'pmpro_checkout_after_tos_fields', 'pmp_mailchimp_add' );


function pmp_change_mailchimp( $translated_text ) {
	if ( $translated_text == 'Opt-in Mailchimp Mailing Lists' ) {
		$translated_text = 'Opt-in for our Mailing Lists';
	}
	return $translated_text;
}
add_filter( 'gettext', 'pmp_change_mailchimp', 20 );

