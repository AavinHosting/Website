<?php
/**
 * @package 
 * @version 
 */
/*
Plugin Name: Document Listing
Plugin URI: 
Description: Administrative should be able to upload documents, then specify which users have access to specific documents and/or folders.
Author: 
Version: 
Author URI: 
*/

define( 'UD_DOCROOT', dirname( __FILE__ ) );
define( 'UD_PLUGURL', plugin_dir_url( __FILE__ ) );
define( 'UD_WEBROOT', str_replace( getcwd(), home_url(), dirname( __FILE__ ) ) );

add_action( 'admin_menu', 'documents' );

function scripts_with_jquery() {
	// Register the script like this for a plugin:
	wp_register_script( 'jquery.modal', plugins_url( '/js/jquery.modal.min.js', __FILE__ ), array( 'jquery' ) );
	wp_enqueue_script( 'jquery.modal' );

	wp_register_script( 'jquery-min', plugins_url( '/js/jquery.min.js', __FILE__ ), array( 'jquery' ) );
	//wp_enqueue_script( 'jquery-min' );

	wp_register_script( 'custom-script', plugins_url( '/js/custom-script.js', __FILE__ ), array( 'jquery' ) );
	wp_enqueue_script( 'custom-script' );

	// Register the style like this for a plugin:
	wp_register_style( 'custom-style', plugins_url( '/css/custom-style.css', __FILE__ ) );
	wp_enqueue_style( 'custom-style' );

	wp_register_style( 'font-awesome', plugins_url( '/css/font-awesome.min.css', __FILE__ ) );
	wp_enqueue_style( 'font-awesome' );

	wp_register_style( 'filetype-icons', plugins_url( '/css/filetype-icons.css', __FILE__ ) );
	wp_enqueue_style( 'filetype-icons' );

	wp_register_style( 'jquery.modal', plugins_url( '/css/jquery.modal.min.css', __FILE__ ) );
	wp_enqueue_style( 'jquery.modal' );

}
add_action( 'admin_enqueue_scripts', 'scripts_with_jquery' );
add_action( 'wp_enqueue_scripts', 'scripts_with_jquery' );


function scripts_with_bootstrap() {
	wp_register_script( 'bootstrap-min-js', plugins_url( '/js/bootstrap.min.js', __FILE__ ), array( 'jquery' ) );
	wp_enqueue_script( 'bootstrap-min-js' );

	wp_register_style( 'bootstrap-min-css', plugins_url( '/css/bootstrap.min.css', __FILE__ ) );
	wp_enqueue_style( 'bootstrap-min-css' );
}
add_action( 'wp_enqueue_scripts', 'scripts_with_bootstrap' );

// Shortcode to view documents
include( UD_DOCROOT . '/docsubpages/shortcode.php' );

function documents() {
	add_menu_page( 'documents', 'Documents', 'administrator', 'documents', 'document_listing' );
	add_submenu_page( 'documents', 'upload documents', 'Upload Documents', 'manage_options', 'upload_documents', 'upload_documents' );
	add_submenu_page( 'documents', 'folder permission', 'Folder Permission', 'manage_options', 'folder_permission', 'folder_permission' );
}

function upload_documents() {
	include( UD_DOCROOT . '/docsubpages/multiple-uploads.php' );
}

function document_listing() {
	include( UD_DOCROOT . '/docsubpages/document_listing.php' );
}

function folder_permission() {
	include( UD_DOCROOT . '/docsubpages/folder_permission.php' );
}

ob_start();

// Create Tables
global $wpdb;

$table_name = "wp_documents";

if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
	$documents = "CREATE TABLE IF NOT EXISTS `wp_documents` (
		`id` int(100) NOT NULL AUTO_INCREMENT,
		`document_title` text NOT NULL,
		`document_url` text NOT NULL,
		`folder_name` text NOT NULL,
		`publish_date` timestamp NOT NULL,
		`last_modification_date` timestamp NOT NULL, 
		`document_date` DATE NULL DEFAULT NULL, 
		PRIMARY KEY (`id`)
	);";

	$wpdb->query( $documents );
}

$table_name1 = "wp_user_documents";

if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name1'" ) != $table_name1 ) {
	$user_documents = "CREATE TABLE IF NOT EXISTS `wp_user_documents` (
		`id` int(100) NOT NULL AUTO_INCREMENT,
		`userid` int(100) NOT NULL,
		`docid` int(100) NOT NULL,
		PRIMARY KEY (`id`)
	);";

	$wpdb->query( $user_documents );
}


register_activation_hook( __FILE__, 'my_plugin_install_function' );

function my_plugin_install_function() {
	//if ( ! get_option( 'sk_testsubmit_installed' ) ):
	$documentlistingpage = get_option( 'documentlistingpage' );
	if ( !isset( $documentlistingpage )AND $documentlistingpage == '' ):
		$userdocumentlistingview = '[VIEW_DOCUMENTS]';
	$userdocumentlist = array(
		'post_date' => date( 'Y-m-d H:i:s' ),
		'post_name' => 'Document Listing',
		'post_status' => 'publish',
		'post_title' => 'Document Listing',
		'post_type' => 'page',
		'post_content' => $userdocumentlistingview,
	);
	$documentlisting = wp_insert_post( $userdocumentlist, false );
	//save the registration page id in the database
	update_option( 'documentlistingpage', $documentlisting );
	endif;

	//registration page
	$registrationview = registration_form_fields();
	$postRegistration = array(
		'post_date' => date( 'Y-m-d H:i:s' ),
		'post_name' => 'Registration',
		'post_status' => 'publish',
		'post_title' => 'Registration',
		'post_type' => 'page',
		'post_content' => $registrationview,
	);
	//$registration = wp_insert_post( $postRegistration, false );
	//save the registration page id in the database
	//update_option( 'registrationpage', $registration );


	//post status and options
	$lostpassword = '[custom-password-lost-form]';
	$postForgetPassword = array(
		'post_date' => date( 'Y-m-d H:i:s' ),
		'post_name' => 'Forget Password',
		'post_status' => 'publish',
		'post_title' => 'Forget Password',
		'post_type' => 'page',
		'post_content' => $lostpassword,
	);
	//insert page and save the id
	//$forgetpassword = wp_insert_post( $postForgetPassword, false );
	//save the id in the database
	//update_option( 'forgetpasswordpage', $forgetpassword );


	update_option( 'sk_testsubmit_installed', true );
	//endif;
}

// Get administrators userid
function admin_user_ids() {
	global $wpdb;
	$wp_user_search = $wpdb->get_results( "SELECT ID, display_name FROM $wpdb->users ORDER BY ID" );

	$adminArray = array();

	//Loop through all users
	foreach ( $wp_user_search as $userid ) {
		$curID = $userid->ID;
		$curuser = get_userdata( $curID );
		$user_level = $curuser->user_level;
		//Only look for admins
		if ( $user_level >= 8 ) { //levels 8, 9 and 10 are admin
			$adminArray[] = $curID;
		}
	}
	return $adminArray;
}

add_action( 'wp_ajax_check_folder', 'check_folder_callback' );
add_action( 'wp_ajax_nopriv_check_folder', 'check_folder_callback' );

function check_folder_callback() {
	$existfoldername = $_POST[ 'existfoldername' ];
	$img = $_FILES[ 'img' ];

	$uploads = wp_upload_dir();
	$upload_path = $uploads[ 'basedir' ];

	if ( $existfoldername == 'other' ) {
		$foldername = $_POST[ 'foldername' ];
	} else {
		$foldername = $existfoldername;
	}

	if ( !empty( $img ) ) {
		$img_desc = upreArrayFiles( $img );

		//$foldername = date('YmHis',time()).mt_rand(0, 3);
		$full_directory_path = $upload_path . '/documents/' . $foldername;
		if ( !file_exists( $full_directory_path ) ) {
			mkdir( $full_directory_path, 0777, true );
		}

		$success_message = $error_message = '';

		foreach ( $img_desc as $val ) {
			$name = $val[ 'name' ];
			$x = explode( '.', $name );
			$ext = end( $x );
			$fileName = str_replace( '.' . $ext, "", $name );

			move_uploaded_file( $val[ 'tmp_name' ], $full_directory_path . '/' . $name );
			$document_url = '/documents/' . $foldername . '/' . $name;
			$publish_date = date( 'Y-m-d H:i:s' );

			global $wpdb;
			$docInsert = $wpdb->insert(
				'wp_documents',
				array(
					'document_title' => "$fileName",
					'document_url' => "$document_url",
					'folder_name' => "$foldername",
					'publish_date' => "$publish_date",
					'last_modification_date' => "$publish_date",
					'document_date' => "$publish_date"
				)
			);
			if ( $docInsert ):
				$success_mes .= $fileName . ' successfully inserted.';
			else :
				$error_message .= $fileName . ' not inserted.';
			endif;
		}
		if ( $docInsert ):
			if ( $success_mes )$success_message = __( 'Successfully Inserted.' );
		if ( $error_message )$error_message .= __( 'Not Inserted.' );
		$json_rsp[ 'success' ] = $success_message;
		$json_rsp[ 'error' ] = $error_message;
		if ( $existfoldername == 'other' ):
			$json_rsp[ 'addfoldername' ] = $_POST[ 'foldername' ];
		endif;
		endif;
	} else {
		$error_message = __( 'Please Select the File.' );
		$json_rsp[ 'selectfile' ] = $error_message;
		//echo $error_message;
	}
	echo json_encode( $json_rsp );
	die;
}

function upreArrayFiles( $file ) {
	$file_ary = array();
	$file_count = count( $file[ 'name' ] );
	$file_key = array_keys( $file );

	for ( $i = 0; $i < $file_count; $i++ ) {
		foreach ( $file_key as $val ) {
			$file_ary[ $i ][ $val ] = $file[ $val ][ $i ];
		}
	}
	return $file_ary;
}


// Ajax function: Modal data to change user permission for folder
add_action( 'wp_ajax_folder_per', 'folder_per_callback' );
add_action( 'wp_ajax_nopriv_folder_per', 'folder_per_callback' );

function folder_per_callback() {
	$adminIdArray = admin_user_ids();

	$foldername = $_GET[ 'foldername' ];
	global $wpdb;
	$dirIds = $wpdb->get_results( "SELECT id FROM `wp_documents` WHERE `folder_name` = '$foldername'", ARRAY_A );
	if ( $dirIds ):

		foreach ( $dirIds as $dirId ):
			$doc_ids[] = $dirId[ 'id' ];
	endforeach;
	$countDids = count( $doc_ids );
	$Dids = implode( ',', $doc_ids );



	$users = get_users( array( 'exclude' => $adminIdArray ) );
	$setpermissions .= '<form method="post" action="" name="" id="change_per">';
	$setpermissions .= '<h4>Change Folder Name :</h4>';
	$setpermissions .= '<input type="text" name="newfoldername" value="' . $foldername . '" id="newfoldername">';
	$setpermissions .= '<p id="errormessage"></p>';
	$setpermissions .= '<input type="hidden" name="oldfoldername" value="' . $foldername . '" id="oldfoldername">';
	// $uploads = wp_upload_dir();
	// $upload_path = $uploads['basedir'];
	// $path = $upload_path.'/documents';

	// $dirs = array();
	// $dir = dir($path);

	// while (false !== ($entry = $dir->read())) {
	//     if ($entry != '.' && $entry != '..') {
	//        if (is_dir($path . '/' .$entry)) {
	//             $dirs[] = $entry; 
	//        }
	//     }
	// }
	// $setpermissions.='<select name="alreadyexistfol" id="alreadyexistfol" required>';
	// $setpermissions.='<option value="">Select Folder</option>';
	// foreach ($dirs as $dir) {
	//     $setpermissions.='<option value="'.$dir.'">'.$dir.'</option>';
	// }
	// $setpermissions.='<option value="other">Other</option>';
	// $setpermissions.='</select>';

	if ( $users ):
		$setpermissions .= '<h4>Set Users Permission For ' . $foldername . ' :</h4>';

	$setpermissions .= '<input type="hidden" name="Dids" value="' . $Dids . '">';
	foreach ( $users as $user ) {
		$userid = $user->ID;
		$userdoc = $wpdb->get_results( "SELECT * FROM `wp_user_documents` WHERE `userid` = '$userid' AND `docid` IN ($Dids)", ARRAY_A );
		$countUserdoc = count( $userdoc );

		if ( $countDids == $countUserdoc ):
			$setpermissions .= '<input type="checkbox" class="olduserids" name="olduserids[]" value="' . $user->ID . '" checked >';
		$setpermissions .= '<input type="checkbox" name="userids[]" value="' . $user->ID . '" checked >' . $user->display_name . ', ' . $user->user_email . '<br>';
		else :
			$setpermissions .= '<input type="checkbox" name="userids[]" value="' . $user->ID . '"  >' . $user->display_name . ', ' . $user->user_email . '<br>';
		endif;
	}
	$setpermissions .= '<input type="hidden" name="action" value="change_permission">';
	$setpermissions .= '<input type="submit" name="save_user_permission" id="save_user_permission" value="Save">';

	else :$setpermissions .= '<p>No User exists.</p>';

	endif;
	$setpermissions .= '</form>';
	$setpermissions .= '<p class="message"></p>';

	echo $setpermissions;

	endif;
	die;
}


//Ajax function: Change user permission for folder
add_action( 'wp_ajax_change_permission', 'change_permission_callback' );
add_action( 'wp_ajax_nopriv_change_permission', 'change_permission_callback' );

function change_permission_callback() {
	$newfoldername = $_POST[ 'newfoldername' ];
	$oldfoldername = $_POST[ 'oldfoldername' ];

	//    $uploads = wp_upload_dir();
	//    $upload_path = $uploads['basedir'];
	//    $full_dir_path = $upload_path.'/documents/'.$newfoldername;
	//    if (file_exists($full_dir_path)) { 
	//                    	$json_rsp['msg'] = 'Folder already exists.';
	//                        echo json_encode($json_rsp);
	//    					die;
	//                    }

	$userids = $_POST[ 'userids' ];
	$olduserids = $_POST[ 'olduserids' ];

	$dids = $_POST[ 'Dids' ];
	$Doids = explode( ',', $dids );

	if ( $olduserids ):
		if ( $userids ):
			$commonuserids = array_intersect( $userids, $olduserids );
	$adduserids = array_diff( $userids, $commonuserids );
	$deleteuserids = array_diff( $olduserids, $commonuserids );
	else :
		$deleteuserids = $olduserids;
	endif;
	else :
		$adduserids = $userids;
	endif;

	if ( $deleteuserids ):
		global $wpdb;
	foreach ( $deleteuserids as $deleteuserid ) {
		foreach ( $Doids as $Doid ) {
			$wpdb->query( "DELETE FROM `wp_user_documents` WHERE docid = $Doid AND userid = $deleteuserid" );
		}
	}
	endif;

	if ( $adduserids ):
		global $wpdb;
	foreach ( $adduserids as $adduserid ) {
		foreach ( $Doids as $Doid ) {

			$userup = $wpdb->get_row( "SELECT * FROM `wp_user_documents` WHERE `docid` = $Doid AND `userid` = $adduserid" );

			if ( !$userup ):
				$userupdate = $wpdb->insert(
					'wp_user_documents',
					array(
						'docid' => "$Doid",
						'userid' => "$adduserid",
					)
				);
			endif;
		}
	}
	endif;

	if ( $newfoldername != $oldfoldername ):
		$json_suc_rsp[ 'newfoldername' ] = $newfoldername;
	global $wpdb;
	$folder_documents = $wpdb->get_results( "SELECT * FROM `wp_documents` WHERE `folder_name` = '$oldfoldername'", ARRAY_A );
	foreach ( $folder_documents as $folder_document ):
		$doc_fol_name = $folder_document[ 'folder_name' ];
	$doc_fol_path = $folder_document[ 'document_url' ];
	$doc_fol_id = $folder_document[ 'id' ];
	$new_doc_fol_path = str_replace( '/' . $oldfoldername . '/', '/' . $newfoldername . '/', $doc_fol_path );

	$wpdb->update(
		'wp_documents',
		array(
			'document_url' => "$new_doc_fol_path",
			'folder_name' => "$newfoldername",
		),
		array( 'id' => "$doc_fol_id" )
	);

	endforeach;
	endif;


	$success_update_mes = __( 'Successfully Updated.' );
	$json_suc_rsp[ 'success' ] = $success_update_mes;
	echo json_encode( $json_suc_rsp );
	die;
}


//Ajax function: Search Functionality For Backend
function docsearchfunction_callback() {

	$searchSortBy = $_POST[ 'searchSortBy' ];
	$searchOrderBy = $_POST[ 'searchOrderBy' ];
	$searchby = $_POST[ 'searchby' ];
	$searchtitle = $_POST[ 'searchtitle' ];

	if ( $searchOrderBy == 'desc' ):
		$orderBy = 'DESC';
	else :
		$orderBy = 'ASC';
	endif;
	
	if ( $searchSortBy == 'publish_date' ) {
		$sortBy = 'publish_date';
	} else if ( $searchSortBy == 'last_modification_date' ) {
		$sortBy = 'last_modification_date';
	} else if ( $searchSortBy == 'document_date' ) {
		$sortBy = 'document_date';
	} else {
		if ( $searchby == 'folder' ):
			$sortBy = 'folder_name';
		else :
			//$sortBy = 'document_title';
			$sortBy = 'document_date';
			$orderBy = 'DESC';
		endif;
	}


	if ( !isset( $searchtitle ) || empty( $searchtitle ) ) {
		$query = "select * from `wp_documents` order by `" . $sortBy . "` " . $orderBy;
		$folder_query = "select DISTINCT `folder_name` from `wp_documents` order by `" . $sortBy . "` " . $orderBy;
	} else {
		if ( $searchby == 'folder' ):
			$folder_query = "select DISTINCT `folder_name` from `wp_documents` where `folder_name` LIKE '%" . $searchtitle . "%' order by `" . $sortBy . "` " . $orderBy;
		$query = "select * from `wp_documents` where `folder_name` LIKE '%" . $searchtitle . "%' order by `" . $sortBy . "` " . $orderBy;
		else :
			$folder_query = "select DISTINCT `folder_name` from `wp_documents` where `document_title` LIKE '%" . $searchtitle . "%' order by `" . $sortBy . "` " . $orderBy;
		$query = "select * from `wp_documents` where `document_title` LIKE '%" . $searchtitle . "%' order by `" . $sortBy . "` " . $orderBy;
		endif;
	}

	if ( $searchby == 'folder' ):
		global $wpdb;
	$foldersName = $wpdb->get_results( $folder_query );

	$html = '<table class="wp-list-table widefat fixed striped" id="tsearch">';
	$html .= '<thead>';
	$html .= '<tr>';
	$html .= '<th colspan="4">FOLDERS NAME</th>';
	$html .= '</tr>';
	$html .= '</thead>';
	$html .= '<tbody id="the-list">';
	if ( isset( $foldersName )AND!empty( $foldersName ) ):
		foreach ( $foldersName as $folderName ):
			$foldername = $folderName->folder_name;
	$documents = $wpdb->get_results( "SELECT * FROM `wp_documents` where `folder_name` = '$foldername'" );
	$count = 1;
	$html .= '<tr class="mycrousal">';
	$html .= '<td colspan="4"><i class="vk_crousal fa fa-folder" aria-hidden="true"></i>  ' . $foldername . '</td>';
	$html .= '</tr>';

	$html .= '<tr class="fol-doc-list"><td colspan="4">';
	if ( isset( $documents )AND!empty( $documents ) ):
		$html .= '<table class="wp-list-table widefat fixed striped">';
	$html .= '<thead>';
	$html .= '<tr>';
	$html .= '<th scope="id" >ID</th><th scope="title" >TITLE</th><th scope="title" >LAST MODIFICATION DATE</th><th scope="title" >DOCUMENT DATE</th><th scope="delete" >ACTION</th>';
	$html .= '</tr>';
	$html .= '</thead>';
	foreach ( $documents as $document ):

		$html .= '<tr>';
	$html .= '<th scope="row">' . $count . '</th>';
	$html .= '<td class="title column-title directory-title" data-colname="Title"><a class="link-icon" href="' . site_url() . '/wp-content/uploads' . $document->document_url . '" target="_blank">' . $document->document_title . '</a></td>';
	$html .= '<td class="title column-modification-date directory-last-modification-date" data-colname="Title">' . $document->last_modification_date . '</td>';
	$html .= '<td class="title column-document-date directory-document-date" data-colname="Title">' . $document->document_date . '</td>';
	$html .= '<td><a class="directory-edit" href="' . admin_url() . 'admin.php?page=documents&eid=' . $document->id . '"><i class="fa fa-pencil"></i> EDIT</a><a  class="directory-delete" href="javascript:did(' . $document->id . ')"><span class="deletebtn"></span><i class="fa fa-trash"></i> DELETE</a></td>';
	$html .= '</tr>';
	$count++;
	endforeach;
	$html .= '</table>';
	else :
		$html .= '<table>';
	$html .= '<tr>';
	$html .= '<th scope="row">No Document found.</th>';
	$html .= '</tr>';
	$html .= '</table>';
	endif;
	$html .= '</td></tr>';

	endforeach;
	else :
		$html .= '<tr>';
	$html .= '<th scope="row">No Folder Found.</th>';
	$html .= '</tr>';
	endif;
	$html .= '</tbody>';
	$html .= '<tfoot>';
	$html .= '<tr>';
	$html .= '<th colspan="4" >FOLDERS NAME</th>';
	$html .= '</tr>';
	$html .= '</tfoot>';
	$html .= '</table>';
	else :
		global $wpdb;
	$documents = $wpdb->get_results( $query );
	$count = 1;
	if ( isset( $documents )AND!empty( $documents ) ):
		$html = '<table class="wp-list-table widefat fixed striped">';
	$html .= '<thead>';
	$html .= '<tr>';
	$html .= '<th scope="id" >ID</th><th scope="title" >TITLE</th><th scope="title" >LAST MODIFICATION DATE</th><th scope="title" >DOCUMENT DATE</th><th scope="delete" >ACTION</th>';
	$html .= '</tr>';
	$html .= '</thead>';
	foreach ( $documents as $document ):

		$html .= '<tr>';
	$html .= '<th scope="row">' . $count . '</th>';
	$html .= '<td class="title column-title directory-title" data-colname="Title"><a class="link-icon" href="' . site_url() . '/wp-content/uploads' . $document->document_url . '" target="_blank">' . $document->document_title . '</a></td>';
	$html .= '<td class="title column-modification-date directory-last-modification-date" data-colname="Title">' . $document->last_modification_date . '</td>';
	$html .= '<td class="title column-document-date directory-document-date" data-colname="Title">' . $document->document_date . '</td>';
	$html .= '<td><a class="directory-edit" href="' . admin_url() . 'admin.php?page=documents&eid=' . $document->id . '"><i class="fa fa-pencil"></i> EDIT</a><a  class="directory-delete" href="javascript:did(' . $document->id . ')"><span class="deletebtn"></span><i class="fa fa-trash"></i> DELETE</a></td>';
	$html .= '</tr>';
	$count++;
	endforeach;
	$html .= '</table>';
	else :
		$html .= '<table>';
	$html .= '<tr>';
	$html .= '<th scope="row">No Document found.</th>';
	$html .= '</tr>';
	$html .= '</table>';
	endif;
	endif;
	echo $html;

	die();
}
add_action( 'wp_ajax_docsearchfunction', 'docsearchfunction_callback' );
add_action( 'wp_ajax_nopriv_docsearchfunction', 'docsearchfunction_callback' );


//Ajax function: Search Functionality For Frontend
function frontenddocsearchfunction_callback() {
	global $wpdb;
	$searchSortBy = $_POST[ 'searchSortBy' ];
	$searchOrderBy = $_POST[ 'searchOrderBy' ];
	$searchby = $_POST[ 'searchby' ];
	$searchtitle = $_POST[ 'searchtitle' ];

	$userId = get_current_user_id();
	$administrator_ids = admin_user_ids();
	if ( in_array( $userId, $administrator_ids ) ):
		$user_doc_ids = $wpdb->get_results( 'select `id` from `wp_documents`', ARRAY_A );
	else :
		$user_doc_ids = $wpdb->get_results( 'select `docid` from `wp_user_documents` where `userid` = ' . $userId, ARRAY_A );
	endif;

	if ( $user_doc_ids ):
		foreach ( $user_doc_ids as $user_doc_id ):
			if ( in_array( $userId, $administrator_ids ) ):
				$user_doc[] = $user_doc_id[ 'id' ];
			else :
				$user_doc[] = $user_doc_id[ 'docid' ];
	endif;
	$user_docs = implode( ",", $user_doc );
	endforeach;


	if ( $searchOrderBy == 'desc' ):
		$orderBy = 'DESC';
	else :
		$orderBy = 'ASC';
	endif;

	if ( $searchSortBy == 'publish_date' ) {
		$sortBy = 'publish_date';
	} else if ( $searchSortBy == 'last_modification_date' ) {
		$sortBy = 'last_modification_date';
	} else if ( $searchSortBy == 'document_date' ) {
		$sortBy = 'document_date';
	} else {
		if ( $searchby == 'folder' ):
			$sortBy = 'folder_name';
		else :
			//$sortBy = 'document_title';
			$sortBy = 'document_date';
			$orderBy = 'DESC';
		endif;
	}

	if ( !isset( $searchtitle ) || empty( $searchtitle ) ) {
		$query = "select * from `wp_documents` where id IN(" . $user_docs . ") order by `" . $sortBy . "` " . $orderBy;
		$folder_query = "select DISTINCT `folder_name` from `wp_documents` where id IN(" . $user_docs . ") order by `" . $sortBy . "` " . $orderBy;
	} else {
		if ( $searchby == 'folder' ):
			$folder_query = "select DISTINCT `folder_name` from `wp_documents` where `folder_name` LIKE '%" . $searchtitle . "%' AND id IN(" . $user_docs . ") order by `" . $sortBy . "` " . $orderBy;
		$query = "select * from `wp_documents` where `folder_name` LIKE '%" . $searchtitle . "%' AND id IN(" . $user_docs . ") order by `" . $sortBy . "` " . $orderBy;
		else :
			$folder_query = "select DISTINCT `folder_name` from `wp_documents` where `document_title` LIKE '%" . $searchtitle . "%' AND id IN(" . $user_docs . ") order by `" . $sortBy . "` " . $orderBy;
		$query = "select * from `wp_documents` where `document_title` LIKE '%" . $searchtitle . "%' AND id IN(" . $user_docs . ") order by `" . $sortBy . "` " . $orderBy;
		endif;
	}
	if ( $searchby == 'folder' ):
		global $wpdb;
	$foldersName = $wpdb->get_results( $folder_query );

	$html = '<table class="wp-list-table widefat fixed striped" id="tsearch">';
	$html .= '<tbody id="the-list">';
	if ( isset( $foldersName )AND!empty( $foldersName ) ):
		foreach ( $foldersName as $folderName ):
			$foldername = $folderName->folder_name;
	$documents = $wpdb->get_results( "SELECT * FROM `wp_documents` where `folder_name` = '$foldername' AND id IN($user_docs)" );
	$count = 1;
	$html .= '<tr class="mycrousal">';
	$html .= '<td colspan="4"><i class="vk_crousal fa fa-folder" aria-hidden="true"></i>  ' . $foldername . '</td>';
	$html .= '</tr>';

	$html .= '<tr class="fol-doc-list"><td colspan="4">';
	if ( isset( $documents )AND!empty( $documents ) ):
		$html .= '<table class="wp-list-table widefat fixed striped folder-doc-listing">';
	$html .= '<thead>';
	$html .= '<tr>';
	$html .= '<th scope="title" >TITLE</th><th scope="title" >DOCUMENT DATE</th>';
	$html .= '</tr>';
	$html .= '</thead>';
	foreach ( $documents as $document ):

		$html .= '<tr>';
	$html .= '<td class="title column-title directory-title" data-colname="Title"><a class="link-icon" href="' . site_url() . '/wp-content/uploads' . $document->document_url . '" target="_blank">' . $document->document_title . '</a></td>';
	$html .= '<td class="title column-modification-date directory-last-modification-date" data-colname="Title">' . $document->document_date . '</td>';
	$html .= '</tr>';
	$count++;
	endforeach;
	$html .= '</table>';
	else :
		$html .= '<table>';
	$html .= '<tr>';
	$html .= '<th scope="row">No Document found.</th>';
	$html .= '</tr>';
	$html .= '</table>';
	endif;
	$html .= '</td></tr>';

	endforeach;
	else :
		$html .= '<tr>';
	$html .= '<th scope="row">No Folder Found.</th>';
	$html .= '</tr>';
	endif;
	$html .= '</tbody>';
	$html .= '</table>';
	else :
		global $wpdb;
	$documents = $wpdb->get_results( $query );
	$count = 1;
	if ( isset( $documents )AND!empty( $documents ) ):
		$html = '<table class="wp-list-table widefat fixed striped folder-doc-listing">';
	$html .= '<thead>';
	$html .= '<tr>';
	$html .= '<th scope="title" >TITLE</th><th scope="title" >DOCUMENT DATE</th>';
	$html .= '</tr>';
	$html .= '</thead>';
	foreach ( $documents as $document ):

		$html .= '<tr>';
	$html .= '<td class="title column-title directory-title" data-colname="Title"><a class="link-icon" href="' . site_url() . '/wp-content/uploads' . $document->document_url . '" target="_blank">' . $document->document_title . '</a></td>';
	$html .= '<td class="title column-modification-date directory-last-modification-date" data-colname="Title">' . $document->document_date . '</td>';
	$html .= '</tr>';
	$count++;
	endforeach;
	$html .= '</table>';
	else :
		$html .= '<table>';
	$html .= '<tr>';
	$html .= '<th scope="row">No Document found.</th>';
	$html .= '</tr>';
	$html .= '</table>';
	endif;
	endif;
	else :
		$html .= '<h3>No Document found.</h3>';
	endif;
	echo $html;

	die();
}
add_action( 'wp_ajax_frontenddocsearchfunction', 'frontenddocsearchfunction_callback' );
add_action( 'wp_ajax_nopriv_frontenddocsearchfunction', 'frontenddocsearchfunction_callback' );



// user registration login form
function registration_form() {

	// only show the registration form to non-logged-in members
	if ( !is_user_logged_in() ) {

		// check to make sure user registration is enabled
		$registration_enabled = get_option( 'users_can_register' );

		// only show the registration form if allowed
		if ( $registration_enabled ) {
			$output = registration_form_fields();
		} else {
			$output = __( 'User registration is not enabled' );
		}
		return $output;
	}
}
add_shortcode( 'register_form', 'registration_form' );

// registration form fields
function registration_form_fields() {

	ob_start();
	?>
	<h3 class="header">
		<?php _e('Register Your Account'); ?>
	</h3>

	<?php 
		// show any error messages after form submission
		show_error_messages(); 
		?>

	<form id="registration_form" class="form" action="" method="POST">
		<p>
			<label for="user_Login">
				<?php _e('Username'); ?>
			</label>
			<input name="user_login" id="user_login" class="required" type="text"/>
		</p>
		<p>
			<label for="user_email">
				<?php _e('Email'); ?>
			</label>
			<input name="user_email" id="user_email" class="required" type="email"/>
		</p>
		<p>
			<label for="user_first">
				<?php _e('First Name'); ?>
			</label>
			<input name="user_first" id="user_first" type="text"/>
		</p>
		<p>
			<label for="user_last">
				<?php _e('Last Name'); ?>
			</label>
			<input name="user_last" id="user_last" type="text"/>
		</p>
		<p>
			<label for="password">
				<?php _e('Password'); ?>
			</label>
			<input name="user_pass" id="password" class="required" type="password"/>
		</p>
		<p>
			<label for="password_again">
				<?php _e('Password Again'); ?>
			</label>
			<input name="user_pass_confirm" id="password_again" class="required" type="password"/>
		</p>
		<p>
			<input type="hidden" name="register_nonce" value="<?php echo wp_create_nonce('register-nonce'); ?>"/>
			<input type="submit" name="registration_submit" value="<?php _e('Register Your Account'); ?>"/>
		</p>
	</form>
	<?php
	return ob_get_clean();
}

// used for tracking error messages
function errors() {
	static $wp_error; // Will hold global variable safely
	return isset( $wp_error ) ? $wp_error : ( $wp_error = new WP_Error( null, null, null ) );
}

// register a new user
function add_new_member() {
	if ( isset( $_POST[ "user_login" ] ) && wp_verify_nonce( $_POST[ 'register_nonce' ], 'register-nonce' ) ) {
		$user_login = $_POST[ "user_login" ];
		$user_email = $_POST[ "user_email" ];
		$user_first = $_POST[ "user_first" ];
		$user_last = $_POST[ "user_last" ];
		$user_pass = $_POST[ "user_pass" ];
		$pass_confirm = $_POST[ "user_pass_confirm" ];


		// this is required for username checks
		//require_once(ABSPATH . WPINC . '/registration.php');

		$errors = new WP_Error();

		if ( username_exists( $user_login ) ) {
			// Username already registered
			errors()->add( 'username_unavailable', __( 'Username already taken' ) );
		}
		if ( !validate_username( $user_login ) ) {
			// invalid username
			errors()->add( 'username_invalid', __( 'Invalid username' ) );
		}
		if ( $user_login == '' ) {
			// empty username
			errors()->add( 'username_empty', __( 'Please enter a username' ) );
		}
		if ( !is_email( $user_email ) ) {
			//invalid email
			errors()->add( 'email_invalid', __( 'Invalid email' ) );
		}
		if ( email_exists( $user_email ) ) {
			//Email address already registered
			errors()->add( 'email_used', __( 'Email already registered' ) );
		}
		if ( $user_pass == '' ) {
			// passwords do not match
			errors()->add( 'password_empty', __( 'Please enter a password' ) );
		}
		if ( $user_pass != $pass_confirm ) {
			// passwords do not match
			errors()->add( 'password_mismatch', __( 'Passwords do not match' ) );
		}

		$errors = errors()->get_error_messages();

		// only create the user in if there are no errors
		if ( empty( $errors ) ) {

			$new_user_id = wp_insert_user( array(
				'user_login' => $user_login,
				'user_pass' => $user_pass,
				'user_email' => $user_email,
				'first_name' => $user_first,
				'last_name' => $user_last,
				'user_registered' => date( 'Y-m-d H:i:s' ),
				'role' => 'subscriber'
			) );
			if ( $new_user_id ) {
				// send an email to the admin alerting them of the registration
				wp_new_user_notification( $new_user_id );

				// log the new user in
				wp_setcookie( $user_login, $user_pass, true );
				wp_set_current_user( $new_user_id, $user_login );
				do_action( 'wp_login', $user_login );

				$documentlistingpageid = get_option( 'documentlistingpage' );

				// send the newly created user to the home page after logging them in
				wp_redirect( get_permalink( $documentlistingpageid ) );
				exit;
			}
		}
	}
}
add_action( 'init', 'add_new_member' );


// displays error messages from form submissions
function show_error_messages() {
	if ( $codes = errors()->get_error_codes() ) {
		echo '<div class="errors">';
		// Loop error codes and display errors
		foreach ( $codes as $code ) {
			$message = errors()->get_error_message( $code );
			echo '<span class="error"><strong>' . __( 'Error' ) . '</strong>: ' . $message . '</span><br/>';
		}
		echo '</div>';
	}
}


// Shortcode for lost password form
add_shortcode( 'custom-password-lost-form', 'render_password_lost_form' );

function render_password_lost_form() {

	if ( is_user_logged_in() ) {
		return __( 'You are already signed in.', 'personalize-login' );
	} else {
		$documentlistingpageid = get_option( 'documentlistingpage' );
		$doc_list_pageid = get_permalink( $documentlistingpageid );
		$html = '<div id="password-lost-form" class="widecolumn">';
		$html .= '<h3>' . _e( "Forgot Your Password?", "personalize-login" ) . '</h3>';
		$html .= '<p>' . _e( "Enter your email address and we'll send you a link you can use to pick a new password.", "personalize_login" ) . '</p>';
		$html .= '<form id="lostpasswordform" action="' . wp_lostpassword_url( $doc_list_pageid ) . '" method="post">';
		$html .= '<p class="form-row">';
		$html .= '<label for="user_login">' . _e( "Email", "personalize-login" ) . '</label>';
		$html .= '<input type="text" name="user_login" id="user_login">';
		$html .= '</p>';

		$html .= '<p class="lostpassword-submit">';
		$html .= '<input type="submit" name="submit" class="lostpassword-button" value="Reset Password"/>';
		$html .= '</p>';
		$html .= '</form>';
		$html .= '</div>';

		return $html;
	}
}

// Function to delete complete directory
function delete_directory( $dirname ) {
	if ( is_dir( $dirname ) )
		$dir_handle = opendir( $dirname );
	if ( !$dir_handle )
		return false;
	while ( $file = readdir( $dir_handle ) ) {
		if ( $file != "." && $file != ".." ) {
			if ( !is_dir( $dirname . "/" . $file ) )
				unlink( $dirname . "/" . $file );
			else
				delete_directory( $dirname . '/' . $file );
		}
	}
	closedir( $dir_handle );
	rmdir( $dirname );
	return true;
}

// add_filter( 'wp_nav_menu_items', 'wti_loginout_menu_link', 10, 2 );

// function wti_loginout_menu_link( $items, $args ) {
//    if ($args->theme_location == 'primary') {
//       if (is_user_logged_in()) {
//          $items .= '<li class="right"><a href="'. wp_logout_url( home_url() ) .'">'. __("Log Out") .'</a></li>';
//       }
//    }
//    return $items;
// }