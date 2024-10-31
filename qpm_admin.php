<?php



if ( ! defined ( 'ABSPATH' ) ) { exit (); }



global $qpm_options;



if ( is_admin () && ( ! is_multisite () || is_multisite () && ( "yes" != $qpm_options [ "only_superadmins" ] || is_super_admin () ) ) ) {

  require_once "qpm_settings.php";

  add_action ( 'init', 'qpm_handle_quick_actions', 1 );
  add_action ( 'admin_menu', 'qpm_add_menu_pages' );
  add_filter ( 'plugin_action_links', 'qpm_plugin_action_links_filter', 10, 2 );

}



function qpm_plugin_action_links_filter ( $links, $file ) {
	if ( $file == plugin_basename ( dirname ( __FILE__ ) . '/index.php' ) ) {
		$links [] = '<a href="options-general.php?page=qpm">' . __ ( 'Settings' ) . '</a>';
	}
	return $links;
}




function qpm_handle_quick_actions () {
	global $qpm_options;

	if ( function_exists ( "current_user_can" ) ) {
		if ( ! current_user_can ( "editor" ) && ! current_user_can ( "edit_post", $_GET [ 'post_id' ] ) ) {
			return false;
		}
	} else {
		return false;
	}

	if ( isset ( $_GET [ 'qpmnonce' ] ) && wp_verify_nonce ( $_GET [ 'qpmnonce' ], 'qpm_nonce' ) ) {

		$new_post = array ();
		$new_post [ 'ID' ] = $_GET [ 'post_id' ];

		$final_url = "";

		$old_post = get_post ( $_GET [ 'post_id' ] );
		if ( "yes" == $qpm_options [ "mail_post_author" ] ) {
			$author_user_info = get_userdata ( $old_post->post_author );
		}

		switch ( $_GET [ 'postaction' ] ) {

			case "set_as_draft":
				$new_post [ 'post_status' ] = "draft";
				wp_update_post ( $new_post );
				$final_url = get_option ( "home" ) . "/wp-admin/post.php?post=" . $_GET [ 'post_id' ] . "&action=edit";
				break;

			case "set_as_private":
				$new_post [ 'post_status' ] = "private";
				wp_update_post ( $new_post );
				$final_url = get_option ( "home" ) . "/wp-admin/post.php?post=" . $_GET [ 'post_id' ] . "&action=edit";
				break;

			case "set_as_public":
				$new_post [ 'post_status' ] = "publish";
				wp_update_post ( $new_post );
				$final_url = get_option ( "home" ) . "/wp-admin/post.php?post=" . $_GET [ 'post_id' ] . "&action=edit";
				break;

			case "close_comments":
				$new_post [ 'comment_status' ] = "closed";
				wp_update_post ( $new_post );
				$final_url = get_option ( "home" ) . "/wp-admin/post.php?post=" . $_GET [ 'post_id' ] . "&action=edit";
				break;

			case "open_comments":
				$new_post [ 'comment_status' ] = "open";
				wp_update_post ( $new_post );
				$final_url = get_option ( "home" ) . "/wp-admin/post.php?post=" . $_GET [ 'post_id' ] . "&action=edit";
				break;

			case "close_trackbacks":
				$new_post [ 'ping_status' ] = "closed";
				wp_update_post ( $new_post );
				$final_url = get_option ( "home" ) . "/wp-admin/post.php?post=" . $_GET [ 'post_id' ] . "&action=edit";
				break;

			case "open_trackbacks":
				$new_post [ 'ping_status' ] = "open";
				wp_update_post ( $new_post );
				$final_url = get_option ( "home" ) . "/wp-admin/post.php?post=" . $_GET [ 'post_id' ] . "&action=edit";
				break;

			case "move2trash":
	 			wp_delete_post ( $_GET [ 'post_id' ], false ); // http://codex.wordpress.org/Function_Reference/wp_delete_post
				$final_url = get_option ( "home" ) . "/wp-admin/edit.php?post_status=trash&post_type=" . $old_post->post_type;
				break;

			case "forcedelete":
	 			wp_delete_post ( $_GET [ 'post_id' ], true ); // http://codex.wordpress.org/Function_Reference/wp_delete_post
				$final_url = get_option ( "home" ) . "/wp-admin/edit.php?post_type=" . $old_post->post_type;
				break;

			case "convert2page":
				$new_post [ 'post_type' ] = "page";
				wp_update_post ( $new_post );
				$final_url = get_option ( "home" ) . "/wp-admin/post.php?post=" . $_GET [ 'post_id' ] . "&action=edit";
				break;

			case "convert2post":
				$new_post [ 'post_type' ] = "post";
				wp_update_post ( $new_post );
				$final_url = get_option ( "home" ) . "/wp-admin/post.php?post=" . $_GET [ 'post_id' ] . "&action=edit";
				break;

			default:
				break;


		}

		if ( "yes" == $qpm_options [ "mail_post_author" ] ) {
			// wp_mail ( $author_user_info->user_email, "Subject", "Message" ); // http://codex.wordpress.org/Function_Reference/wp_mail
		}

		if ( $final_url ) {
			wp_redirect ( $final_url );
		}

	}
}



function qpm_add_menu_pages () {
	add_options_page ( __ ( "Quick Post Management", "QPM" ), __ ( "QPM", "QPM" ), 'manage_options', 'qpm', 'qpm_settings_page' );
}



?>