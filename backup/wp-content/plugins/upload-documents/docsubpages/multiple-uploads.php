<div class="wrap">
	<h1>Upload Documents</h1>
	<div class="upload_documents">

		<form action="" method="post" enctype="multipart/form-data" class="upload" id="upload-doc">
			<div class="">
				<p><label>Select Folder Name:</label>
				</p>
				<?php
				$uploads = wp_upload_dir();
				$upload_path = $uploads[ 'basedir' ];
				$path = $upload_path . '/documents';

				if ( file_exists( $path ) ) {
					$dirs = array();
					$dir = dir( $path );

					while ( false !== ( $entry = $dir->read() ) ) {
						if ( $entry != '.' && $entry != '..' ) {
							if ( is_dir( $path . '/' . $entry ) ) {
								$dirs[] = $entry;
							}
						}
					}
					
					sort($dirs);
				} else {
					mkdir( $upload_path . '/documents', 0777 );
					$dirs = array();
				}

				echo '<select name="existfoldername" id="existsfolname" required>';
				echo '<option value="">Select Folder</option>';
				foreach ( $dirs as $dir ) {
					echo '<option value="' . $dir . '">' . $dir . '</option>';
				}
				echo '<option value="other">Other</option>';
				echo '</select>';

				?>
			</div>

			<div class="add_f" id="addfolder">
				<p><label>Enter Folder Name:</label>
				</p>
				<p><input type="text" name="foldername" id="foldername" autocomplete="off"/>
				</p>
				<p class="error" id="pid1"></p>
			</div>

			<div>
				<p><label>Upload Files:</label>
				</p>
				<p><input type="file" name="img[]" id="file_ak" required="required" multiple>
				</p>
				<p class="error" id="pid2"></p>
			</div>
			<input type="hidden" name="action" value="check_folder">
			<input type="submit" name="submit" class="button button-primary button-large" id="savedoc" value="Upload Documents">
		</form>
		<div id="progress-wrp">
			<div class="progress-bar"></div>
			<div class="status">0%</div>
		</div>
		<p id="success"></p>

		<?php

		$uploads = wp_upload_dir();
		$upload_path = $uploads[ 'basedir' ];
		if ( !file_exists( $upload_path . '/documents' ) ) {
			mkdir( $upload_path . '/documents', 0777 );
		}

		?>
	</div>
</div>



<script type="text/javascript">
	( function ( $ ) {
		var isValid = true;
		jQuery( 'input[name=foldername]' ).keyup( function ( event ) {
			isValid = true;
			var curr = $( this );
			$( '#existsfolname' ).find( 'option' ).each( function () {
				//console.log($(this).val());
				if ( curr.val() == $( this ).val() ) {
					isValid = false;
					jQuery( "#pid1" ).text( "Folder already exists." );
					jQuery( "#success" ).text( "" );
				}
				if ( isValid == true ) {
					jQuery( "#pid1" ).text( "" );
				}

			} )

		} )

		jQuery( document.body ).on( 'submit', '#upload-doc', function ( event ) {

			if ( isValid ) {
				console.log( isValid );
				//jQuery("#progress-wrp").css("display", "block");
				jQuery.ajax( {
					url: "<?php echo admin_url('admin-ajax.php'); ?>", // Url to which the request is send
					type: "POST", // Type of request to be send, called as method
					data: new FormData( this ), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
					contentType: false, // The content type used when sending data to the server.
					cache: false, // To unable request pages to be cached
					dataType: 'json',
					processData: false, // To send DOMDocument or non processed data file it is set to false
					//async : false,
					xhr: function () {
						//upload Progress
						var xhr = jQuery.ajaxSettings.xhr();
						if ( xhr.upload ) {
							xhr.upload.addEventListener( 'progress', function ( event ) {
								var percent = 0;
								var position = event.loaded || event.position;
								var total = event.total;
								if ( event.lengthComputable ) {
									percent = Math.ceil( position / total * 100 );
								}
								console.log( 'percent: ' + percent );
								//update progressbar
								jQuery( "#progress-wrp" ).css( "display", "block" );
								jQuery( ".progress-bar" ).css( "width", +percent + "%" );
								jQuery( ".status" ).text( percent + "%" );
							}, true );
						}
						return xhr;
					},
					success: function ( data ) // A function to be called if request succeeds
						{
							console.log( data.success );
							if ( data.msg ) {
								jQuery( "#pid1" ).text( data.msg );
								jQuery( '#foldername' ).val( "" );
								jQuery( "#success" ).text( "" );
							}
							if ( data.selectfile ) {
								jQuery( "#pid1" ).text( "" );
								jQuery( "#pid2" ).text( data.selectfile );
							}
							if ( data.success ) {
								jQuery( "#pid1" ).text( "" );
								jQuery( "#pid2" ).text( "" );
								jQuery( '#foldername' ).val( "" );
								jQuery( '#file_ak' ).val( "" );
								jQuery( '#existsfolname' ).val( "" );
								jQuery( '#addfolder' ).hide();
								jQuery( "#success" ).text( data.success ).show();
								jQuery( "#success" ).delay( 9000 ).fadeOut( 'slow' );
								setTimeout( function () {
									jQuery( "#progress-wrp" ).css( "display", "none" );
								}, 5000 );
								if ( data.addfoldername ) {
									var addfoldername = data.addfoldername;
									jQuery( '#existsfolname option' ).eq( -2 ).after( '<option value="' + addfoldername + '" >' + addfoldername + '</option>' );
								}
							}

						}
				} );


			}
			return false;
		} );

		$( document ).ready( function () {
			$( '#addfolder' ).hide();
			$( '#existsfolname' ).on( 'change', function () {
				if ( this.value == 'other' ) {
					$( '#addfolder' ).hide();
					$( "#addfolder" ).show();
				} else {
					$( "#addfolder" ).hide();
				}
			} );
		} );
	} )( jQuery );
</script>