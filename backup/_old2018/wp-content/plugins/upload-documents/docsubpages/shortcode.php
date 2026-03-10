<?php

function is_user_log_in() {
	$view = FALSE;
	if ( is_user_logged_in() ) {
		$current_user = wp_get_current_user();
		$user_login = $current_user->user_login;
		$ID = $current_user->ID;

		$administrator_ids = admin_user_ids();


		$view .= '<script> var ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '"</script>';
		$view .= '<h3>Welcome, ' . $current_user->display_name . '</h3>';
		$view .= '<a href="' . wp_logout_url( home_url() ) . '">' . __( "Log Out" ) . '</a>';
		$view .= '<h1>Document Listing</h1>';
		$view .= '<div class="search-box doc-search-box">';
		$view .= '<form method="post" name="" id="front_end_dsearch_form" class="form-inline">';
		$view .= '<h2 id="front_end_search_form_title">Search:</h2>';

		$view .= '<select name="type" id="front_end_searchby" class="form-control">';
		$view .= '<option value="">Select Search For:</option>';
		$view .= '<option value="folder">Folder Name</option>';
		$view .= '<option value="document">Document Name</option>';
		$view .= '</select>';

		$view .= '<input name="title" placeholder="Search keyword" type="text" id="front_end_searchtitle" class="form-control">';

		$view .= '<select name="sortby" id="front_end_search_sort_by" class="form-control">';
		$view .= '<option value="">Select Search Order:</option>';
		$view .= '<option value="name">Title</option>';
		$view .= '<option value="document_date">Document Date</option>';
		$view .= '</select>';

		$view .= '<select name="orderby" id="front_end_search_order_by" class="form-control">';
		$view .= '<option value="">Select Order By:</option>';
		$view .= '<option value="asc">Asc</option>';
		$view .= '<option value="desc">Desc</option>';
		$view .= '</select>';

		$view .= '<input value="Search" type="submit" name="search" id="front_end_searchfunctionality" class="btn btn-default">';

		$view .= '</form>';
		$view .= '</div>';
		$view .= '<div id="front_end_main_listing">';

		global $wpdb;
		$dc_ids = $wpdb->get_results( "SELECT docid FROM `wp_user_documents` WHERE `userid` = $ID", ARRAY_A );
		if ( $dc_ids ):
			foreach ( $dc_ids as $dc_id ): $doc_ids[] = $dc_id[ 'docid' ];
		endforeach;
		else :
			$doc_ids = '';
		endif;

		if ( $ID && is_super_admin( $ID ) ) {
			$foldersName = $wpdb->get_results( "SELECT DISTINCT `folder_name` FROM `wp_documents`" );
		} else {
			if ( $doc_ids != '' ):
				$doc_ids_string = implode( ",", $doc_ids );
			$foldersName = $wpdb->get_results( "SELECT DISTINCT `folder_name` FROM `wp_documents` WHERE `id` IN ($doc_ids_string)" );
			endif;
		}

		$view .= '<table id="frontend-folder-listing">';
		$view .= '<tbody id="">';
		if ( isset( $foldersName )AND!empty( $foldersName ) ):
			foreach ( $foldersName as $folderName ):
				$foldername = $folderName->folder_name;
		if ( $doc_ids != '' ):
			$documents = $wpdb->get_results( "SELECT * FROM `wp_documents` where `folder_name` = '$foldername' AND `id` IN ($doc_ids_string) ORDER BY `document_date` DESC" );
		else :
			$documents = $wpdb->get_results( "SELECT * FROM `wp_documents` where `folder_name` = '$foldername' ORDER BY `document_date` DESC" );
		endif;
		$count = 1;
		$view .= '<tr class="mycrousal">';
		$view .= '<td colspan="4"><i class="vk_crousal fa fa-folder" aria-hidden="true"></i> ' . $foldername . '</td>';
		$view .= '</tr>';

		$view .= '<tr class="fol-doc-list"><td colspan="4">';
		if ( isset( $documents )AND!empty( $documents ) ):
			$view .= '<table class="wp-list-table widefat fixed striped folder-doc-listing" id="frontend-doc-listing">';
		$view .= '<thead>';
		$view .= '<tr>';
		$view .= '<th scope="title" >TITLE</th><th scope="title" >DOCUMENT DATE</th>';
		$view .= '</tr>';
		$view .= '</thead>';
		foreach ( $documents as $document ):

			$view .= '<tr>';
		$view .= '<td class="title column-title directory-title"><a class="link-icon" href="' . site_url() . '/wp-content/uploads' . $document->document_url . '" target="_blank">' . $document->document_title . '</a></td>';
		$view .= '<td class="title column-modification-date directory-last-modification-date">' . $document->document_date . '</td>';
		$view .= '</tr>';
		$count++;
		endforeach;
		$view .= '</table>';
		else :
			$view .= '<table>';
		$view .= '<tr>';
		$view .= '<th scope="row">No Document found.</th>';
		$view .= '</tr>';
		$view .= '</table>';
		endif;
		$view .= '</td></tr>';

		endforeach;
		else :
			$view .= '<tr>';
		$view .= '<th scope="row">No Folder Found.</th>';
		$view .= '</tr>';
		endif;
		$view .= '</tbody>';
		$view .= '</table>';
		$view .= '</div>';


		// $doc_ids = FALSE;
		// if($dc_ids):
		// 	$view.='<ul class="document listing">';
		// 	foreach($dc_ids as $docKey => $docVal):	$doc_ids[] = $docVal['docid'];  endforeach;
		// 	foreach($doc_ids as $doc_id):	
		// 		$document = $wpdb->get_row( "SELECT * FROM `wp_documents` WHERE `id` = $doc_id" );
		// 		$uploads = wp_upload_dir();
		// 		$documentpath = $uploads['baseurl'].$document->document_url;			
		// 		$view.='<li><a href="'.$documentpath.'" target="_blank">'.$document->document_title.'</a></li>';
		// 	endforeach;
		// 	$view.='</ul>';
		// else:
		// 	$view.='<h4>You have no access to view any document</h4>';
		// endif;

	} else {
		$documentlistingpageid = get_option( 'documentlistingpage' );
		$registration_pageid = get_option( 'registrationpage' );
		$registration_page_id = get_permalink( $registration_pageid );

		$forget_password_pageid = get_option( 'forgetpasswordpage' );
		$forget_password_page_id = get_permalink( $forget_password_pageid );


		$view = '<ul class="nav nav-tabs" role="tablist">';
		$view .= '  <li class="nav-item">';
		$view .= '    <a class="nav-link active" data-toggle="tab" href="#login" role="tab">Login</a>';
		$view .= '  </li>';
		//$view.='  <li class="nav-item">';
		//$view.='    <a class="nav-link" data-toggle="tab" href="#registration-form" role="tab">Registration</a>';
		//$view.=' </li>';
		$view .= '  <li class="nav-item">';
		$view .= '    <a class="nav-link" data-toggle="tab" href="#forgetpassword-form" role="tab">Forget Password</a>';
		$view .= '  </li>';
		$view .= '</ul>';

		$view .= '<div class="tab-content">';
		$view .= '<div class="tab-pane active" id="login" role="tabpanel">';
		$view .= '<div class="login-form">';
		$view .= '<h4>Please login first to view the documents.</h3>';
		$args = array(
			'redirect' => get_permalink( 4144 ),
			'id_username' => 'user',
			'id_password' => 'pass',
			'echo' => false,
		);
		$view .= wp_login_form( $args );
		$view .= '</div>';
		$view .= '</div>';

		//$view.='<div class="tab-pane" id="registration-form" role="tabpanel">';
		//	$view.='<div class="rg-form">';
		//	$view.= registration_form_fields();
		//	$view.='</div>';
		//$view.='</div>';

		$view .= '<div class="tab-pane" id="forgetpassword-form" role="tabpanel">';
		$view .= '<div class="fp-form">';
		global $wpdb;
		$error = '';
		$success = '';

		// check if we're in reset form
		if ( isset( $_POST[ 'action' ] ) && 'reset' == $_POST[ 'action' ] ) {
			$email = trim( $_POST[ 'user_login' ] );

			if ( empty( $email ) ) {
				$error = 'Enter a username or e-mail address..';
			} else if ( !is_email( $email ) ) {
				$error = 'Invalid username or e-mail address.';
			} else if ( !email_exists( $email ) ) {
				$error = 'There is no user registered with that email address.';
			} else {

				$random_password = wp_generate_password( 12, false );
				$user = get_user_by( 'email', $email );

				$update_user = wp_update_user( array(
					'ID' => $user->ID,
					'user_pass' => $random_password
				) );

				// if  update user return true then lets send user an email containing the new password
				if ( $update_user ) {
					$to = $email;
					$subject = 'Your new password';
					$sender = get_option( 'name' );

					$message = 'Your new password is: ' . $random_password;

					$headers[] = 'MIME-Version: 1.0' . "\r\n";
					$headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers[] = "X-Mailer: PHP \r\n";
					$headers[] = 'From: ' . $sender . ' < ' . $email . '>' . "\r\n";

					$mail = wp_mail( $to, $subject, $message, $headers );
					if ( $mail )
						$success = 'Check your email address for you new password.';

				} else {
					$error = 'Oops something went wrong updaing your account.';
				}

			}

			if ( !empty( $error ) )
				echo '<div class="message"><p class="error"><strong>ERROR:</strong> ' . $error . '</p></div>';

			if ( !empty( $success ) )
				echo '<div class="error_login"><p class="success">' . $success . '</p></div>';
		}

		$view .= '<form method="post">';
		$view .= '<fieldset>';
		$view .= '<p>Please enter your username or email address. You will receive a link to create a new password via email.</p>';
		$view .= '<p><label for="user_login">Username or E-mail:</label>';
		$user_login = isset( $_POST[ 'user_login' ] ) ? $_POST[ 'user_login' ] : '';
		$view .= '<input type="text" name="user_login" id="user_login" value="' . $user_login . '" /></p>';
		$view .= '<p>';
		$view .= '<input type="hidden" name="action" value="reset" />';
		$view .= '<input type="submit" value="Get New Password" class="button" id="submit" />';
		$view .= '</p>';
		$view .= '</fieldset> ';
		$view .= '</form>';


		$view .= '</div>';
		$view .= '</div>';
		$view .= '</div>';

	}
	return $view;
}
add_shortcode( 'VIEW_DOCUMENTS', 'is_user_log_in' );
?>