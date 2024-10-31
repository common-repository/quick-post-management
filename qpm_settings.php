<?php



if ( ! defined ( 'ABSPATH' ) ) { exit (); }



function qpm_settings_page () {

	global $qpm_options;

	if ( wp_verify_nonce ( $_POST [ 'qpmnonce' ], 'qpm' ) ) {
		if ( isset ( $_POST [ 'qpm_save' ] ) ) {
			//
			$qpm_options [ "version" ] = QPM_VERSION;
			$qpm_options [ "add_to_the_top" ] = $_POST [ "qpm_settings_add_to_the_top" ] ? "yes" : "no" ;
			$qpm_options [ "add_to_the_bottom" ] = $_POST [ "qpm_settings_add_to_the_bottom" ] ? "yes" : "no" ;
			$qpm_options [ "handle_the_content" ] = $_POST [ "qpm_settings_handle_the_content" ] ? "yes" : "no" ;
			$qpm_options [ "priority_the_content" ] = (int) $_POST [ "qpm_settings_priority_the_content" ];
			if ( $qpm_options [ "priority_the_content" ] < 1 ) {
				$qpm_options [ "priority_the_content" ] = 999999;
			}
			$qpm_options [ "handle_the_excerpt" ] = $_POST [ "qpm_settings_handle_the_excerpt" ] ? "yes" : "no" ;
			$qpm_options [ "priority_the_excerpt" ] = (int) $_POST [ "qpm_settings_priority_the_excerpt" ];
			if ( $qpm_options [ "priority_the_excerpt" ] < 1 ) {
				$qpm_options [ "priority_the_excerpt" ] = 999999;
			}
			$qpm_options [ "add_set_as_draft" ] = $_POST [ "qpm_settings_add_set_as_draft" ] ? "yes" : "no" ;
			$qpm_options [ "add_set_as_private" ] = $_POST [ "qpm_settings_add_set_as_private" ] ? "yes" : "no" ;
			$qpm_options [ "add_close_comments" ] = $_POST [ "qpm_settings_add_close_comments" ] ? "yes" : "no" ;
			$qpm_options [ "add_close_trackbacks" ] = $_POST [ "qpm_settings_add_close_trackbacks" ] ? "yes" : "no" ;
			$qpm_options [ "add_move2trash" ] = $_POST [ "qpm_settings_add_move2trash" ] ? "yes" : "no" ;
			$qpm_options [ "add_forcedelete" ] = $_POST [ "qpm_settings_add_forcedelete" ] ? "yes" : "no" ;
			$qpm_options [ "add_convert" ] = $_POST [ "qpm_settings_add_convert" ] ? "yes" : "no" ;
			$qpm_options [ "add_custom_links" ] = $_POST [ "qpm_settings_add_custom_links" ] ? "yes" : "no" ;
			if ( isset ( $_POST [ "qpm_settings_custom_links" ] ) ) {
				$qpm_options [ "custom_links" ] = stripslashes ( $_POST [ "qpm_settings_custom_links" ] );
				if ( "" == $qpm_options [ "custom_links" ] ) {
					$qpm_options [ "add_custom_links" ] = "no";
				}
			}
			$qpm_options [ "add_qpm_admin" ] = $_POST [ "qpm_settings_add_qpm_admin" ] ? "yes" : "no" ;
			$qpm_options [ "mail_post_author" ] = $_POST [ "qpm_settings_mail_post_author" ] ? "yes" : "no" ;
//			$qpm_options [ "" ] = $_POST [ "qpm_settings_" ] ? "yes" : "no" ;
			//
			if ( is_multisite () && is_super_admin () ) {
				$qpm_options [ "only_superadmins" ] = $_POST [ "qpm_settings_only_superadmins" ] ? "yes" : "no" ;
			} // end of if ( is_multisite () && is_super_admin () )
			update_option ( 'qpm_options', $qpm_options );

			echo '<div id="message" class="updated fade"><p>' . __ ( "Settings saved.", "QPM" ) . '</p></div>';
		}
		elseif ( isset ( $_POST [ 'qpm_reset' ] ) ) {
			delete_option ( 'qpm_options' );
			qpm_load_and_check_settings ();
			echo '<div id="message" class="updated fade"><p>' . __ ( "You are done reinstalling!", "QPM" ) . '</p></div>';
		}
	}
?>	
	<div class="wrapper">
	<h2><?php _e ( "Quick Post Management", "QPM" ); ?> - <?php _e ( "Settings", "QPM" ); ?> (<?php echo ( is_super_admin () ? sprintf ( __ ( "saved version: %s", "QPM" ), QPM_VERSION ) : "" ); ?>)</h2>
	<form method='post'>
	<?php wp_nonce_field ( "qpm", "qpmnonce" ); ?>

	<p><h4><?php _e ( "Where to add the quick link(s)", "QPM" ); ?></h4></p>
	<p><input type="checkbox" value="1" name="qpm_settings_add_to_the_top" id="qpm_settings_add_to_the_top" <?php echo "yes" == $qpm_options [ "add_to_the_top" ] ? 'checked="yes"' : ''; ?>/><label for="qpm_settings_add_to_the_top"> <?php _e ( 'Add to the top', "QPM" ); ?></label></p>
	<p><input type="checkbox" value="1" name="qpm_settings_add_to_the_bottom" id="qpm_settings_add_to_the_bottom" <?php echo "yes" == $qpm_options [ "add_to_the_bottom" ] ? 'checked="yes"' : ''; ?>/><label for="qpm_settings_add_to_the_bottom"> <?php _e ( 'Add to the bottom', "QPM" ); ?></label></p>
	<p>and</p>
	<p><input type="checkbox" value="1" name="qpm_settings_handle_the_content" id="qpm_settings_handle_the_content" <?php echo "yes" == $qpm_options [ "handle_the_content" ] ? 'checked="yes"' : ''; ?>/><label for="qpm_settings_handle_the_content"> <?php _e ( 'Handle <code>the_content</code> cycle(s) with priority', "QPM" ); ?></label> <input type="text" name="qpm_settings_priority_the_content" id="qpm_settings_priority_the_content" value="<?php echo $qpm_options [ "priority_the_content" ]; ?>" size="7" ></p>
	<p><input type="checkbox" value="1" name="qpm_settings_handle_the_excerpt" id="qpm_settings_handle_the_excerpt" <?php echo "yes" == $qpm_options [ "handle_the_excerpt" ] ? 'checked="yes"' : ''; ?>/><label for="qpm_settings_handle_the_excerpt"> <?php _e ( 'Handle <code>the_excerpt</code> cycle(s) with priority', "QPM" ); ?></label> <input type="text" name="qpm_settings_priority_the_excerpt" id="qpm_settings_priority_the_excerpt" value="<?php echo $qpm_options [ "priority_the_excerpt" ]; ?>" size="7" ></p>
	<p><input type="submit" class="button-primary" value="<?php _e ( "Save", "QPM" ); ?>" name="qpm_save"></p>

	<p><h4><?php _e ( "What quick link(s) to add [whatever is applicable]", "QPM" ); ?></h4></p>
	<p><input type="checkbox" value="1" name="qpm_settings_add_set_as_draft" id="qpm_settings_add_set_as_draft" <?php echo "yes" == $qpm_options [ "add_set_as_draft" ] ? 'checked="yes"' : ''; ?>/><label for="qpm_settings_add_set_as_draft"> <?php _e ( 'Set as draft', "QPM" ); ?></label></p>
	<p><input type="checkbox" value="1" name="qpm_settings_add_set_as_private" id="qpm_settings_add_set_as_private" <?php echo "yes" == $qpm_options [ "add_set_as_private" ] ? 'checked="yes"' : ''; ?>/><label for="qpm_settings_add_set_as_private"> <?php _e ( 'Set as private/public', "QPM" ); ?></label></p>
	<p><input type="checkbox" value="1" name="qpm_settings_add_close_comments" id="qpm_settings_add_close_comments" <?php echo "yes" == $qpm_options [ "add_close_comments" ] ? 'checked="yes"' : ''; ?>/><label for="qpm_settings_add_close_comments"> <?php _e ( 'Close/open comments', "QPM" ); ?></label></p>
	<p><input type="checkbox" value="1" name="qpm_settings_add_close_trackbacks" id="qpm_settings_add_close_trackbacks" <?php echo "yes" == $qpm_options [ "add_close_trackbacks" ] ? 'checked="yes"' : ''; ?>/><label for="qpm_settings_add_close_trackbacks"> <?php _e ( 'Close/open trackbacks', "QPM" ); ?></label></p>
	<p><input type="checkbox" value="1" name="qpm_settings_add_move2trash" id="qpm_settings_add_move2trash" <?php echo "yes" == $qpm_options [ "add_move2trash" ] ? 'checked="yes"' : ''; ?>/><label for="qpm_settings_add_move2trash"> <?php _e ( 'Move to trash', "QPM" ); ?></label></p>
	<p><input type="checkbox" value="1" name="qpm_settings_add_forcedelete" id="qpm_settings_add_forcedelete" <?php echo "yes" == $qpm_options [ "add_forcedelete" ] ? 'checked="yes"' : ''; ?>/><label for="qpm_settings_add_forcedelete"> <?php _e ( 'Force delete', "QPM" ); ?></label></p>
	<p><input type="checkbox" value="1" name="qpm_settings_add_convert" id="qpm_settings_add_convert" <?php echo "yes" == $qpm_options [ "add_convert" ] ? 'checked="yes"' : ''; ?>/><label for="qpm_settings_add_convert"> <?php _e ( 'Convert into page/post', "QPM" ); ?></label></p>
	<p><input type="checkbox" value="1" name="qpm_settings_add_custom_links" id="qpm_settings_add_custom_links" <?php echo "yes" == $qpm_options [ "add_custom_links" ] ? 'checked="yes"' : ''; ?> onclick="document.getElementById('qpm_settings_custom_links').readOnly=!this.checked;"/><label for="qpm_settings_add_custom_links"> <?php _e ( 'Own links:', "QPM" ); ?></label></p>
	<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<textarea name="qpm_settings_custom_links" rows="3" cols="80" id="qpm_settings_custom_links" <?php echo "yes" == $qpm_options [ "add_custom_links" ] ? '' : 'readonly'; ?>><?php echo esc_textarea ( $qpm_options [ "custom_links" ] ); ?></textarea></p>
	<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>One can use the following shortcodes:<code>%SITE_URL%</code>, <code>%POST_ID%</code>, <code>%POST_TYPE%</code>, and <code>%AUTHOR_ID%</code></i></p>
	<p><input type="checkbox" value="1" name="qpm_settings_add_qpm_admin" id="qpm_settings_add_qpm_admin" <?php echo "yes" == $qpm_options [ "add_qpm_admin" ] ? 'checked="yes"' : ''; ?>/><label for="qpm_settings_add_qpm_admin"> <?php _e ( 'Link to this admin page', "QPM" ); ?></label></p>
	<p><input type="submit" class="button-primary" value="<?php _e ( "Save", "QPM" ); ?>" name="qpm_save"></p>

	<p><h4><?php _e ( "Extra action(s) to take", "QPM" ); ?></h4></p>
	<p><input disabled type="checkbox" value="1" name="qpm_settings_mail_post_author" id="qpm_settings_mail_post_author" <?php echo "yes" == $qpm_options [ "mail_post_author" ] ? 'checked="yes"' : ''; ?>/><label for="qpm_settings_mail_post_author"> <?php _e ( 'Notify the post author about the changes via email', "QPM" ); ?></label></p>
	<p><input type="submit" class="button-primary" value="<?php _e ( "Save", "QPM" ); ?>" name="qpm_save"></p>

<?php if ( is_multisite () && is_super_admin () ) { ?>
	<p><h4><?php _e ( "Misc (Super Admins)", "QPM" ); ?></h4></p>
	<p><input type="checkbox" value="1" name="qpm_settings_only_superadmins" id="qpm_settings_only_superadmins" <?php echo "yes" == $qpm_options [ "only_superadmins" ] ? 'checked="yes"' : ''; ?>/><label for="qpm_settings_only_superadmins"> <?php _e ( "Show Quick Post Management quick link(s) and the admin section only to the super admins", "QPM" ); ?></label></p>
<?php } // end of if ( is_multisite () && is_super_admin () ) ?>

	<p>&nbsp;</p>
	<p><h4><?php _e ( "Reinstall", "QPM" ); ?></h4></p>
	<p style='font-size:12px;'><?php _e ( 'If you want to reinstall Quick Post Management, use the button below. N.B. it will ERASE all saved QPM settings.', "QPM" ); ?></p>
	<p><input type="submit" onclick="return confirm('<?php _e ( "All settings will be lost! ARE YOU SURE?", "QPM" ); ?>')" class="button" value="<?php _e ( "Reset all settings", "QPM" ); ?>" name="qpm_reset"></p>
	</form>

	<p>&nbsp;</p>
	<p><h4><?php _e ( "Name.ly/Plugins", "QPM" ); ?></h4></p>
	<p><?php _e ( 'This plugin is proundly presented to you by <a href="http://name.ly/plugins/" target="_blank"><i>Name.ly/Plugins</i></a>.', "QPM" ); ?></p>
	<p><?php _e ( '<i>Name.ly</i> offers WordPress blogs and many other services allowing to consolidate multiple sites, pages and profiles.', "QPM" ); ?></p>
	<p><?php _e ( 'All on catchy domain names, like many.at, brief.ly, sincere.ly, links2.me, thatis.me, of-cour.se, ... and hundreds more.', "QPM" ); ?></p>
	<p><?php _e ( '<i>Name.ly/PRO</i> platform allows domain name owners to run similar sites under their own brand.', "QPM" ); ?></p>
	<p><?php _e ( '<a href="http://namely.pro/" target="_blank"><i>Name.ly/PRO</i></a> is most known for being first WordPress driven product allowing reselling emails and sub-domains.', "QPM" ); ?></p>

	</div>

<?php

}



?>