<?php

function UserID( $editid ) {
	global $wpdb;
	$dc_userids = $wpdb->get_results( "SELECT userid FROM `wp_user_documents` WHERE `docid` = '$editid'", ARRAY_A );
	$doc_userids = FALSE;
	if ( $dc_userids ):
		foreach ( $dc_userids as $useKey => $usrVal ): $doc_userids[] = $usrVal[ 'userid' ];
	endforeach;
	else :
		$doc_userids = "";
	endif;

	$ucheckbox = FALSE;
	$adminIdArray = admin_user_ids();


	if ( $doc_userids ): $dIds = $doc_userids;
	else :$dIds = ' ';
	endif;
	$users_include = get_users( array( 'include' => $dIds, 'exclude' => $adminIdArray ) );
	if ( $users_include ):
		foreach ( $users_include as $user_include ) {
			$ucheckbox .= '<input type="checkbox" name="userids[]" value="' . $user_include->ID . '" checked >' . $user_include->display_name . ', ' . $user_include->user_email . '<br>';
		}
	endif;
	if ( $dIds != ' ' ):
		$totaladminIdArray = array_merge( $adminIdArray, $dIds );
	else :
		$totaladminIdArray = $adminIdArray;
	endif;
	$users_exclude = get_users( array( 'exclude' => $totaladminIdArray ) );
	if ( $users_exclude ):
		foreach ( $users_exclude as $user_exclude ) {
			$ucheckbox .= '<input type="checkbox" name="userids[]" value="' . $user_exclude->ID . '" >' . $user_exclude->display_name . ', ' . $user_exclude->user_email . '<br>';
		}
	endif;
	return $ucheckbox;
}

function editdocument( $editd ) {
	global $wpdb;
	$uDoc = $wpdb->get_row( "SELECT document_url FROM `wp_documents` WHERE id = $editd" );
	$uploads = wp_upload_dir();
	$documentpath = $uploads[ 'baseurl' ] . $uDoc->document_url;
	$html = '<div class="fullfilepath">';
	$html .= '<div class="doc-container"><a href="' . $documentpath . '" target="_blank">' . $documentpath . '</a></div>';
	$html .= '<div class="doc-modify-button"><a href="#" class="changebutton page-title-action">MODIFY DOCUMENT</a></div>';
	$html .= '</div>';

	$html .= '<div class="uploaddoc">';
	$html .= '<div class="doc-container"><label>Upload Document:</label></div>';
	$html .= '<div class="doc-container"><input type="file" name="document" /></div>';
	$html .= '<div class="doc-modify-button"><a href="#" class="notchangebutton page-title-action">RESET</a></div>';
	$html .= '</div>';
	return $html;
}

function edittitle( $editd ) {
	global $wpdb;
	$TDoc = $wpdb->get_row( "SELECT document_title FROM `wp_documents` WHERE id = $editd" );
	$title = '<input name="udocname" type="text" value="' . $TDoc->document_title . '" class="regular-text" required/>';
	return $title;
}

function editdocumentdate( $editd ) {
	global $wpdb;
	$TDoc = $wpdb->get_row( "SELECT document_date FROM `wp_documents` WHERE id = $editd" );
	$title = '<input name="udocument_date" class="custom_date" type="text" value="' . $TDoc->document_date . '" class="regular-text" required/>';
	return $title;
}

if ( $_GET[ 'eid' ] ):
	$eid = $_GET[ 'eid' ];

wp_enqueue_script( 'jquery-ui-datepicker' );
wp_register_style( 'jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css' );
wp_enqueue_style( 'jquery-ui' );
?>
<script type="text/javascript">
	jQuery( document ).ready( function ( $ ) {
		$( '.custom_date' ).datepicker( {
			dateFormat: 'yy-mm-dd'
		} );
	} );
</script>
<div class="wrap">
	<h1>Update Document</h1>
	<form method="post" action="" name="" enctype="multipart/form-data">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label>Document Name</label>
					</th>
					<td>
						<?php echo edittitle($eid); ?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label>Document Filepath</label>
					</th>
					<td>
						<?php echo editdocument($eid); ?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label>Document Date</label>
					</th>
					<td>
						<?php echo editdocumentdate($eid); ?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label>User Access</label>
					</th>
					<td>
						<?php echo UserID($eid); ?>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><input type="submit" name="update" id="submit" class="button button-primary" value="Save Changes">
		</p>
	</form>
</div>
<?php endif; ?>

<?php
if ( isset( $_REQUEST[ 'update' ] ) ) {

	$userids = $_POST[ 'userids' ];
	$udocname = $_POST[ 'udocname' ];
	$udoc = $_FILES[ 'document' ];
	$udocument_date = $_POST[ 'udocument_date' ];

	$modification_date = date( 'Y-m-d H:i:s' );


	if ( isset( $udoc )OR isset( $udocname )OR isset( $userids ) ) {
		global $wpdb;

		$deluser = $wpdb->delete( 'wp_user_documents', array( 'docid' => "$eid" ) );
		if ( $userids ):
			foreach ( $userids as $userid ):
				$userupdate = $wpdb->insert(
					'wp_user_documents',
					array(
						'docid' => "$eid",
						'userid' => "$userid",
					)
				);
		endforeach;
		endif;

		$udDoc = $wpdb->get_row(
			"SELECT * FROM `wp_documents`
		WHERE id = $eid"
		);
		$uploads = wp_upload_dir();
		$Full_upload_file_path = $uploads[ 'basedir' ] . $udDoc->document_url;

		$myArray = explode( '/', $udDoc->document_url );
		$docParentFolder = $myArray[ 2 ];
		$docParentFolderpath = $uploads[ 'basedir' ] . '/documents/' . $docParentFolder;

		if ( !empty( $udoc[ 'name' ] ) ):
			// Delete old file
			if ( file_exists( $docParentFolderpath ) ):
				$delfile = unlink( $Full_upload_file_path );
		endif;
		// Insert updated  file
		move_uploaded_file( $udoc[ 'tmp_name' ], $docParentFolderpath . '/' . $udoc[ 'name' ] );
		$document_url = '/documents/' . $docParentFolder . '/' . $udoc[ 'name' ];
		else :
			$document_url = '/documents/' . $docParentFolder . '/' . $myArray[ 3 ];
		endif;

		if ( !empty( $udocname )AND!empty( $document_url ) ):

			$documentupdate = $wpdb->update(
				'wp_documents',
				array(
					'document_url' => "$document_url",
					'document_title' => "$udocname",
					'last_modification_date' => "$modification_date",
					'document_date' => "$udocument_date"
				),
				array( 'id' => "$eid" ),
				array(
					'%s',
					'%s',
					'%s',
				)
			);
		endif;

		if ( isset( $userupdate ) || isset( $documentupdate ) ):
			$url = admin_url( 'admin.php?page=documents&eid=' . $eid );
		wp_redirect( $url );
		$success_message = __( 'Successfully Updated' );
		echo "<p><strong>{$success_message}</strong></p>";
		endif;

	}

}

?>