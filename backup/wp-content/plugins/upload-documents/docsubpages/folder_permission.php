<?php
    global $wpdb;
    $foldersName = $wpdb->get_results( 
    	"SELECT DISTINCT `folder_name` FROM `wp_documents`"
    );

    if( isset($_GET['foldel']) ):
        $foldel = base64_decode($_GET['foldel']);
        if(isset($foldel)):
            $DocsId = $wpdb->get_results( "SELECT `id` FROM `wp_documents` WHERE `folder_name` = '$foldel'", ARRAY_A );
            if( isset($DocsId) ):
                foreach ($DocsId as $DocId):
                    $DocId_doc[] = $DocId['id'];
                    $DocId_docs = implode(",",$DocId_doc);
                endforeach;     
                $wpdb->query( "DELETE FROM `wp_documents` WHERE `id` IN ($DocId_docs)" );
                $wpdb->query( "DELETE FROM `wp_user_documents` WHERE `docid` IN ($DocId_docs)" );

                $uploads = wp_upload_dir();
                $FolderPath = $uploads['basedir'].'/documents/'.$foldel;
                delete_directory($FolderPath);
                $folder_listingpage = admin_url().'admin.php?page=folder_permission';
                header("Location: $folder_listingpage");
            endif;    
        endif;

    endif;

?>
<div class="wrap">
<h1>Folders Listing</h1>
<p class="search-box doc-search-box">
	<label class="screen-reader-text" for="post-search-input">Search Folder:</label>
	<input id="folsearchinput" name="s" value="" type="search">
	<input id="folsearchsubmit" class="button" value="Search Folder" type="submit" onclick="folderlisting();">
</p>
<table class="wp-list-table widefat fixed striped" id="tsearch">
	<thead>
        <tr>
            <th scope="id" >ID</th><th scope="title" >TITLE</th><th scope="delete" >ACTION</th>	
        </tr>
	</thead>
	<tbody id="the-list">
    	<?php
    	$count = 1;
        if(isset($foldersName) AND !empty($foldersName)):
        	foreach($foldersName as $folderName){
		echo '<tr>';
				echo '<th scope="row">'.$count.'</th>';
				echo '<td class="title column-title directory-title" data-colname="Title">'.$folderName->folder_name.'</td>';
                $delete_folder_name = base64_encode($folderName->folder_name);
				echo '<td><a class="directory-delete" href="#" onclick="fdid(\''.$delete_folder_name.'\')"><i class="fa fa-trash"></i> DELETE</a><a href="#ex1" data-f-name="'.$folderName->folder_name.'" class="fd" rel="modal:open"> <i class="fa fa-pencil"></i>  Edit</a></td>';
			
         echo '</tr>';
         $count++;
            }
         else:
         	echo '<tr>';
				echo '<th scope="row">No Document Found.</th>';
         	echo '</tr>';
         endif;
		 ?>       
	</tbody>
	<tfoot>
		<tr>
            <th scope="id" >ID</th><th scope="title" >TITLE</th><th scope="delete" >ACTION</th>	
        </tr>
	</tfoot>
</table>
</div>

<div id="ex1" style="display:none;">

    <p class="pt"></p>
</div>
<script>
(function($){

$('.fd').click(function(){

var folname = $(this).attr('data-f-name');
//alert(folname);
//jQuery('.pt').html('');
//jQuery('.pt').append(data);
				$.ajax({
					type : 'get',
					url : '<?php echo admin_url( 'admin-ajax.php' ); ?>',
					data : {
								action : 'folder_per',
								foldername : folname,
							},
					success : function(data){
						$('.pt').html('');
						$('.pt').append(data);
					},

				});

});


$(document.body).on('submit', '#change_per', function(event){
            $.ajax({
                url: "<?php echo admin_url( 'admin-ajax.php'); ?>", 
                type: "POST",             
                data: new FormData(this), 
                contentType: false,       
                cache: false,            
                dataType: 'json',
                processData:false,        
                success: function(data)
                {
                    console.log(data);
                    $('.message').html('');
					$('.message').append(data.success);  
					if(data.newfoldername){
                    	$('#oldfoldername').val(data.newfoldername);
                    	$('#newfoldername').val(data.newfoldername);
                	}
                }
            });
            return false;

});

 })(jQuery)
</script>
