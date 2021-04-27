<?php
/****
 * Canada Info Level Shortcode
 * 
 * return true
 */

function pmp_get_latest() { 

    global $wpdb, $pmpro_msg, $pmpro_msgt, $current_user;

    $pmpro_levels = pmpro_getAllLevels(false, true);
    $pmpro_level_order = pmpro_getOption('level_order');

    if(!empty($pmpro_level_order))
    {
        $order = explode(',',$pmpro_level_order);

        //reorder array
        $reordered_levels = array();
        foreach($order as $level_id) {
            foreach($pmpro_levels as $key=>$level) {
                if($level_id == $level->id)
                    $reordered_levels[] = $pmpro_levels[$key];
            }
        }

        $pmpro_levels = $reordered_levels;
    }

        $pmpro_levels = apply_filters("pmpro_levels_array", $pmpro_levels);

        ob_start();

        
    ?>
    <div id="pmpro_levels_table" class="<?php echo pmpro_get_element_class( 'pmpro_table pmpro_checkout', 'pmpro_levels_table' ); ?>  col-sm-5">
        <ul class="issues">
        <?php	
        $count = 0;
        foreach($pmpro_levels as $level)
        {
        if(isset($current_user->membership_level->ID))
            $current_level = ($current_user->membership_level->ID == $level->id);
        else
            $current_level = false;
        ?>
        <?php 
        if($count++ % 2 == 0) { 
            $levelClass='odd';
        } else { 
            $levelClass='even';
        } 
        ?>
        <li class="<?=$levelClass;?><?php if($current_level == $level) { ?> <?php } ?>">

        <ul class="latest-issue">
            <li>
            <?php 
            
            //Show the Title Name 
            echo $current_level ? "{$level->name}" : $level->name; ?>

            </li>
            <?php if(empty($current_user->membership_level->ID)) { ?>
            
                        <li><a class="<?php echo pmpro_get_element_class( 'pmpro_btn pmpro_btn-select', 'pmpro_btn-select' ); ?> button-get" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php _e('Select', 'paid-memberships-pro' );?></a></li>
                    <?php } elseif ( !$current_level ) { ?>  
                                        
                        <li><a class="<?php echo pmpro_get_element_class( 'pmpro_btn pmpro_btn-select', 'pmpro_btn-select' ); ?> button-get" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php _e('Select', 'paid-memberships-pro' );?></a></li>
                    <?php } elseif($current_level) { ?>
                
                <?php
                    //if it's a one-time-payment level, offer a link to renew				
                    if( pmpro_isLevelExpiringSoon( $current_user->membership_level) && $current_user->membership_level->allow_signups ) {
                        ?>
                            <li><a class="<?php echo pmpro_get_element_class( 'pmpro_btn pmpro_btn-select', 'pmpro_btn-select' ); ?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php _e('Renew', 'paid-memberships-pro' );?></a></li>
                        <?php
                    } else {
                        ?>
                        
                            <li><a class="<?php echo pmpro_get_element_class( 'pmpro_btn disabled', 'pmpro_btn' ); ?>" href="<?php echo pmpro_url("account")?>"><?php _e('✓', 'paid-memberships-pro' );?></a></li>
                            <li><h3><a href="<?php echo get_site_url(); ?>/canada-info-guide-issue-1/">You have access, see it now!&nbsp;<i class="fas fa-arrow-circle-right"></i></a></h3></li>
                        </ul>
                        <?php
                    }
                ?>
                
        <?php } ?>
            </li> 
            </ul>
            </div>
        <?php } 

    return ob_get_clean();
}
add_shortcode ( 'pmp-levels', 'pmp_get_latest' );
