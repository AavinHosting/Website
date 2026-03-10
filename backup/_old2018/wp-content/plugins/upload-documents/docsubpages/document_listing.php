<?php
global $wpdb;
$foldersName = $wpdb->get_results( 
	"SELECT DISTINCT `folder_name` FROM `wp_documents`"
);

if(isset($_GET['eid'])):
	include 'update_document.php';
else:
	if(isset($_GET['did'])):
		$did = $_GET['did'];
		$dDoc = $wpdb->get_row( 
		"SELECT * FROM `wp_documents`
		WHERE id = $did"
		);
		$uploads = wp_upload_dir();
		$Full_upload_file_path = $uploads['basedir'].$dDoc->document_url;
		
	    $myArray = explode('/', $dDoc->document_url);
	    $docParentFolder = $myArray[2];
		$docParentFolderpath = $uploads['basedir'].'/documents/'.$docParentFolder;
		if (file_exists($docParentFolderpath)) :	
				$filecount = count(glob($docParentFolderpath . "*"));
				$delfile = unlink($Full_upload_file_path);
				if($filecount == 1): rmdir($docParentFolderpath); endif;
		endif;
		
		$deldoc = $wpdb->delete( 'wp_documents', array( 'id' => $did ) );
		$deluserdoc = $wpdb->delete( 'wp_user_documents', array( 'docid' => $did ) );
		if($deldoc || $deluserdoc || $delfile) {
			$listingpage = admin_url().'admin.php?page=documents';
			header("Location: $listingpage");
		}
	endif;
?>
<div class="wrap">

<h1>Document Listing</h1>
<div class="search-box doc-search-box">
<form method="post" name="" id="dsearch_form">
	<h2 id="search_form_title">Search:</h2>

	<!-- <input type="hidden" name="action" value="searchFunctionality"> -->

	<select name="type" id="searchby">
		<option value="">--- Select Search For ---</option>
		<option value="folder">Folder Name</option>
		<option value="document">Document Name</option>
		<option value="document_date">Document Date</option>
	</select>

	<input name="title" placeholder="Search keyword" type="text" id="searchtitle">

	<select name="sortby" id="search_sort_by">
		<option value="">--- Select Search Order ---</option>
		<option value="name">Title</option>
		<!-- <option value="publish_date">Publish Date</option> -->
		<option value="document_date">Document Date</option>
	</select>

	<select name="orderby" id="search_order_by">
		<option value="">--- Select Order By ---</option>
		<option value="asc">Asc</option>
		<option value="desc">Desc</option>
	</select>

	<input value="Search" type="submit" name="search" id="searchfunctionality">

</form>
</div>
<div id="main_listing">
<table class="wp-list-table widefat fixed striped" id="tsearch">
	<thead>
        <tr>
            <th colspan="4" >FOLDERS NAME</th>	
        </tr>
	</thead>
	<tbody id="the-list">
    	<?php
    	if(isset($foldersName) AND !empty($foldersName)):
    		foreach($foldersName as $folderName):
    			$foldername = $folderName->folder_name;
    			$documents = $wpdb->get_results( "SELECT * FROM `wp_documents` where `folder_name` = '$foldername' ORDER BY `document_date` DESC" );
    			$count =1;
    			echo '<tr class="mycrousal">';
    			echo '<td colspan="4"><i class="vk_crousal fa fa-folder" aria-hidden="true"></i>  '.$foldername.'</td>';
    			echo '</tr>';
    			
    			echo '<tr class="fol-doc-list"><td colspan="4">';
			        if(isset($documents) AND !empty($documents)):
			        	echo '<table class="wp-list-table widefat fixed striped">';
			        	echo '<thead>';
					    	echo '<tr>';
					            echo '<th scope="id" >ID</th><th scope="title" >TITLE</th><th scope="title" >LAST MODIFICATION DATE</th><th scope="title" >DOCUMENT DATE</th><th scope="delete" >ACTION</th>';	
					        echo '</tr>';
						echo '</thead>';
			        	foreach($documents as $document):
				        
						echo '<tr>';
								echo '<th scope="row">'.$count.'</th>';
								echo '<td class="title column-title directory-title" data-colname="Title"><a class="link-icon" href="'.site_url().'/wp-content/uploads'.$document->document_url.'" target="_blank">'.$document->document_title.'</a></td>';
								echo '<td class="title column-modification-date directory-last-modification-date" data-colname="Title">'.$document->last_modification_date.'</td>';
								echo '<td class="title column-document-date directory-document-date" data-colname="Title">' . $document->document_date . '</td>';
								echo '<td><a class="directory-edit" href="'.admin_url().'admin.php?page=documents&eid='.$document->id.'"><i class="fa fa-pencil"></i> EDIT</a><a  class="directory-delete" href="javascript:did('.$document->id.')"><span class="deletebtn"></span><i class="fa fa-trash"></i> DELETE</a></td>';
				         echo '</tr>';
				         $count++;
			         	 endforeach;
			         	 echo '</table>';
			         else:
				         echo '<table>';
				         	echo '<tr>';
								echo '<th scope="row">No Document found.</th>';
				         	echo '</tr>';
				         echo '</table>';
			         endif;
			    echo '</td></tr>';
			    
		    endforeach;
		else:    
			echo '<tr>';
				echo '<th scope="row">No Folder Found.</th>';
		    echo '</tr>';
		endif;        	
		 ?>       
	</tbody>
	<tfoot>
		<tr>
            <th colspan="4" >FOLDERS NAME</th>	
        </tr>
	</tfoot>
</table>
</div>
</div>
<?php
endif;
?>

 <script type="text/javascript">
 (function($){

 	$(document).ready(function(){

 		$('#searchby').on('change', function() {
  			 var searchby = this.value;
  			 if( searchby == 'document'){
  			 	$('#search_form_title').text('Search Documents');
  			 	$('#search_sort_by option').eq(-1).after('<option value="last_modification_date" >Last Modification Date</option>');
  			 	$('#search_sort_by option').eq(-2).after('<option value="publish_date">Publish Date</option>');
  			 	$('#search_sort_by option').eq(-3).after('<option value="document_date">Document Date</option>');
  			 } else {
  			 	$('#search_form_title').text('Search Folders');
  			 	$('#search_sort_by').find("option[value='last_modification_date']:not(:selected)").remove();
  			 	$('#search_sort_by').find("option[value='publish_date']:not(:selected)").remove();
  			 	$('#search_sort_by').find("option[value='document_date']:not(:selected)").remove();
  			 }
		});


 	})

 	$('body').on('submit', '#dsearch_form', function(event){
 			var search_sort_by = $('#search_sort_by').val();
 			var search_order_by = $('#search_order_by').val();
 			var searchby = $('#searchby').val();
 			var searchtitle = $('#searchtitle').val();

		  	$.ajax({
		      type : 'post',
		      url  : "<?php echo admin_url('admin-ajax.php'); ?>",
		      data: {
		              action: 'docsearchfunction',
		              searchSortBy : search_sort_by,
		              searchOrderBy : search_order_by,
		              searchby : searchby,
		              searchtitle : searchtitle,
		            },
		      success: function(data)
		            {
		                   console.log(data);
		                   $("#main_listing").html('');
						   $("#main_listing").append(data);
						   
						   $('.mycrousal').click(function(){
					 				$(this).next('tr').toggle();
					 				var curr = $(this).find('.vk_crousal');
					 				if( curr.hasClass('fa-folder') ){
					 				
					 				curr.removeClass('fa-folder').addClass('fa-folder-open'); 

					 			}
					 				else{
					 		  	curr.removeClass('fa-folder-open').addClass('fa-folder');
					             }

					 		})
		            }
		    });
           
    	return false;
	})   

 })(jQuery)
 </script>

