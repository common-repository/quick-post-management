<?php
/* 
 * Plugin Name:   Quick Post Management
 * Version:       0.0.6
 * Plugin URI:    http://name.ly/plugins/
 * Description:   QPM adds handy front-end pages/posts management links for one-click actions as drafting, setting private/public, opening/closing comments/trackbacks, trashing/deleting, etc
 * Author:        Name.ly
 * Author URI:    http://namely.pro/
 */



if ( ! defined ( 'ABSPATH' ) ) { exit (); }



define ( 'QPM_VERSION', '0.0.1' ); // settings' version



load_plugin_textdomain ( 'QPM', false, basename ( dirname ( __FILE__ ) ) . '/languages' );



global $qpm_options;
qpm_load_and_check_settings ();

function qpm_load_and_check_settings () {
	global $qpm_options;

	$qpm_options = get_option ( 'qpm_options' );

	// here, in the future, we can check for previous versions, and if such, upgrade accordingly

	if ( ! $qpm_options || ! is_array ( $qpm_options ) || ! isset ( $qpm_options [ 'version' ] ) ) {
		$qpm_options = array ();
		$qpm_options [ "version" ] = QPM_VERSION;
		$qpm_options [ "add_to_the_top" ] = "no";
		$qpm_options [ "add_to_the_bottom" ] = "yes";
		$qpm_options [ "handle_the_content" ] = "yes";
		$qpm_options [ "priority_the_content" ] = 999999;
		$qpm_options [ "handle_the_excerpt" ] = "yes";
		$qpm_options [ "priority_the_excerpt" ] = 999999;
		$qpm_options [ "add_set_as_draft" ] = "yes";
		$qpm_options [ "add_set_as_private" ] = "yes";
		$qpm_options [ "add_close_comments" ] = "yes";
		$qpm_options [ "add_close_trackbacks" ] = "no";
		$qpm_options [ "add_move2trash" ] = "yes";
		$qpm_options [ "add_forcedelete" ] = "no";
		$qpm_options [ "add_convert" ] = "no";
		$qpm_options [ "add_custom_links" ] = "no";
		$qpm_options [ "custom_links" ] = '<a href="%SITE_URL%/wp-admin/post.php?post=%POST_ID%&action=edit">' . __ ( "Edit", "QPM" ) . '</a> | <a href="%SITE_URL%/wp-admin/edit.php?post_type=%POST_TYPE%">' . __ ( "Manage", "QPM" ) . '</a>';
		$qpm_options [ "add_qpm_admin" ] = "yes";
		$qpm_options [ "mail_post_author" ] = "no";
		$qpm_options [ "only_superadmins" ] = "no";
		add_option ( "qpm_options", $qpm_options );
	}
}



if ( is_admin () ) {
  require_once ( "qpm_admin.php" );
}



//register_activation_hook ( __FILE__, 'qpm_activate' );
//register_deactivation_hook ( __FILE__, 'qpm_deactivate' ); // N.B. this will clear the settings and the table in the database



function qpm_activate () {
	// done automatically
}
	
function qpm_deactivate () {
	// done automatically
//	delete_option ( 'qpm_options' );
}



if ( ! is_admin () && ( ! is_multisite () || is_multisite () && ( "yes" != $qpm_options [ "only_superadmins" ] || is_super_admin () ) ) ) {
	if ( "yes" == $qpm_options [ "handle_the_content" ] ) {
		add_filter ( "the_content", "qpm_the_content_filter", (int) $qpm_options [ "priority_the_content" ], 1 );
	}
	if ( "yes" == $qpm_options [ "handle_the_excerpt" ] ) {
		add_filter ( "the_excerpt", "qpm_the_content_filter", (int) $qpm_options [ "priority_the_excerpt" ], 1 );
	}
}



function qpm_the_content_filter ( $content ) {

	global $qpm_options;
	global $post;

	$qpm_links = qpm_get_links ( $post->ID );

	if ( $qpm_links ) {

		if ( "yes" == $qpm_options [ "add_to_the_top" ] ) {
			$content = $qpm_links . $content;
		}

		if ( "yes" == $qpm_options [ "add_to_the_bottom" ] ) {
			$content .= $qpm_links;
		}

	}

	return $content;

}



function qpm_get_links ( $postid ) {

	global $qpm_options;

	$post = get_post ( $postid );

	if ( function_exists ( "current_user_can" ) && ( current_user_can ( "editor" ) || current_user_can ( "edit_post", $postid ) ) ) { // on some international installations, current_user_can ( "editor" ) returns 0 somehow, hence the extra check
		$qpm_links = "";
		$qpm_links_n = 0;
		$link_separator = __ ( " | ", "QPM" );
		//
		$qpmadminurl = get_option ( "home" ) . "/wp-admin/options-general.php?page=qpm";
		$baseactionurl = $qpmadminurl . "&qpmnonce=" . wp_create_nonce ( "qpm_nonce" ); // http://codex.wordpress.org/Function_Reference/wp_create_nonce
		if ( "yes" == $qpm_options [ "add_set_as_draft" ] ) {
			$qpm_links .= ( $qpm_links ? $link_separator : "" ) . '<a href="' . $baseactionurl . "&postaction=set_as_draft&post_id=" . $postid . '">' . __ ( 'Set as draft', "QPM" ) . '</a>'; 
			$qpm_links_n++;
		}
		if ( "yes" == $qpm_options [ "add_set_as_private" ] ) {
			if ( "publish" == $post->post_status ) {
				$qpm_links .= ( $qpm_links ? $link_separator : "" ) . '<a href="' . $baseactionurl . "&postaction=set_as_private&post_id=" . $postid . '">' . __ ( 'Set as private', "QPM" ) . '</a>'; 
				$qpm_links_n++;
			} elseif ( "private" == $post->post_status ) {
				$qpm_links .= ( $qpm_links ? $link_separator : "" ) . '<a href="' . $baseactionurl . "&postaction=set_as_public&post_id=" . $postid . '">' . __ ( 'Set as public', "QPM" ) . '</a>'; 
				$qpm_links_n++;
			} else {
				// skip other cases
			}
		}
		if ( "yes" == $qpm_options [ "add_close_comments" ] ) {
			if ( "open" == $post->comment_status ) {
				$qpm_links .= ( $qpm_links ? $link_separator : "" ) . '<a href="' . $baseactionurl . "&postaction=close_comments&post_id=" . $postid . '">' . __ ( 'Close comments', "QPM" ) . '</a>'; 
				$qpm_links_n++;
			} elseif ( "closed" == $post->comment_status ) {
				$qpm_links .= ( $qpm_links ? $link_separator : "" ) . '<a href="' . $baseactionurl . "&postaction=open_comments&post_id=" . $postid . '">' . __ ( 'Open comments', "QPM" ) . '</a>'; 
				$qpm_links_n++;
			} else {
				// skip other cases
			}
		}
		if ( "yes" == $qpm_options [ "add_close_trackbacks" ] ) {
			if ( "open" == $post->ping_status ) {
				$qpm_links .= ( $qpm_links ? $link_separator : "" ) . '<a href="' . $baseactionurl . "&postaction=close_trackbacks&post_id=" . $postid . '">' . __ ( 'Close trackbacks', "QPM" ) . '</a>'; 
				$qpm_links_n++;
			} elseif ( "closed" == $post->ping_status ) {
				$qpm_links .= ( $qpm_links ? $link_separator : "" ) . '<a href="' . $baseactionurl . "&postaction=open_trackbacks&post_id=" . $postid . '">' . __ ( 'Open trackbacks', "QPM" ) . '</a>'; 
				$qpm_links_n++;
			} else {
				// skip other cases
			}
		}
		if ( "yes" == $qpm_options [ "add_move2trash" ] ) {
			$qpm_links .= ( $qpm_links ? $link_separator : "" ) . '<a href="' . $baseactionurl . "&postaction=move2trash&post_id=" . $postid . '">' . __ ( 'Move to trash', "QPM" ) . '</a>'; 
			$qpm_links_n++;
		}
		if ( "yes" == $qpm_options [ "add_forcedelete" ] ) {
			$qpm_links .= ( $qpm_links ? $link_separator : "" ) . '<a href="' . $baseactionurl . "&postaction=forcedelete&post_id=" . $postid . '">' . __ ( 'Force delete', "QPM" ) . '</a>'; 
			$qpm_links_n++;
		}
		if ( "yes" == $qpm_options [ "add_convert" ] ) {
			if ( "page" == $post->post_type ) {
				$qpm_links .= ( $qpm_links ? $link_separator : "" ) . '<a href="' . $baseactionurl . "&postaction=convert2post&post_id=" . $postid . '">' . __ ( 'Convert into post', "QPM" ) . '</a>'; 
				$qpm_links_n++;
			} elseif ( "post" == $post->post_type ) {
				$qpm_links .= ( $qpm_links ? $link_separator : "" ) . '<a href="' . $baseactionurl . "&postaction=convert2page&post_id=" . $postid . '">' . __ ( 'Convert into page', "QPM" ) . '</a>'; 
				$qpm_links_n++;
			} else {
				// skip other cases
			}
		}
		if ( "yes" == $qpm_options [ "add_custom_links" ] && $qpm_options [ "custom_links" ] ) {
			$qpm_links .= ( $qpm_links ? $link_separator : "" ) . str_replace ( array ( "%SITE_URL%", "%POST_ID%", "%POST_TYPE%", "%AUTHOR_ID%" ), array ( get_option ( 'home' ), $postid, $post->post_type, $post->post_author ), $qpm_options [ "custom_links" ] ); 
			$qpm_links_n++;
		}
		if ( "yes" == $qpm_options [ "add_qpm_admin" ] ) {
			$qpm_links .= ( $qpm_links ? $link_separator : "" ) . '<a href="' . $qpmadminurl . '">' . __ ( 'QPM settings', "QPM" ) . '</a>'; 
			$qpm_links_n++;
		}
		//
		if ( $qpm_links ) {
			$qpm_links = '<div class="qpm">' . ( $qpm_links_n > 1 ? __ ( 'QPM actions: ', "QPM" ) : __ ( 'QPM: ', "QPM" ) ) . $qpm_links . '</div>';
		}
	} else {
		$qpm_links = false;
	}
	return $qpm_links;
}



add_shortcode ( 'QuickPostManagement', 'qpm_shortcode' );

function qpm_shortcode () {
	global $post;

	$qpm_links = qpm_get_links ( $post->ID );

	if ( $qpm_links ) {
		return $qpm_links;
	} else {
		return '';
	}

}



?>