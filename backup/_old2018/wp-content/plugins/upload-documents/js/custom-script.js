// JavaScript Document
function did(id) {
	if (confirm('Sure To Remove This Record ?')) {
		window.location.href = 'admin.php?page=documents&did=' + id;
	}
}

function fdid(fid) {
	if (confirm('Sure To Remove This Record ?')) {
		window.location.href = 'admin.php?page=folder_permission&foldel=' + fid;
	}
}

function searchlisting() {
	var input = jQuery('#docsearchinput').val();
	jQuery.each(jQuery("#tsearch tbody tr"), function () {
		if (jQuery(this).text().toLowerCase().indexOf(input.toLowerCase()) === -1)
			jQuery(this).hide();
		else
			jQuery(this).show();
	});
}

jQuery(document).ready(function () {
	jQuery("#docsearchinput").keyup(function () {
		if (jQuery(this).val() == "") {
			jQuery("#tsearch tbody tr").show();
		}
	});


	jQuery(".changebutton").click(function () {
		jQuery(".uploaddoc").css('display', 'block');
		jQuery(".fullfilepath").css('display', 'none');
	});
	jQuery(".notchangebutton").click(function () {
		jQuery(".fullfilepath").css('display', 'block');
		jQuery(".uploaddoc").css('display', 'none');
	});


	jQuery('.mycrousal').click(function () {
		jQuery(this).next('tr').toggle();
		var curr = jQuery(this).find('.vk_crousal');
		if (curr.hasClass('fa-folder')) {

			curr.removeClass('fa-folder').addClass('fa-folder-open');

		} else {
			curr.removeClass('fa-folder-open').addClass('fa-folder');
		}

	})



	jQuery('#front_end_searchby').on('change', function () {
		var searchby = this.value;
		if (searchby == 'document') {
			jQuery('#front_end_search_form_title').text('Search Documents');
			jQuery('#front_end_search_sort_by option').eq(-1).after('<option value="last_modification_date" >Last Modification Date</option>');
			jQuery('#front_end_search_sort_by option').eq(-2).after('<option value="publish_date">Publish Date</option>');
			jQuery('#front_end_search_sort_by option').eq(-2).after('<option value="document_date">Document Date</option>');
		} else {
			jQuery('#front_end_search_form_title').text('Search Folders');
			//jQuery('#front_end_search_sort_by').find("option[value='last_modification_date']:not(:selected)").remove();
			//jQuery('#front_end_search_sort_by').find("option[value='publish_date']:not(:selected)").remove();
			jQuery('#front_end_search_sort_by').find("option[value='last_modification_date']").remove();
			jQuery('#front_end_search_sort_by').find("option[value='publish_date']").remove();
			jQuery('#front_end_search_sort_by').find("option[value='document_date']").remove();
		}
	});


	jQuery('body').on('submit', '#front_end_dsearch_form', function (event) {
		var search_sort_by = jQuery('#front_end_search_sort_by').val();
		var search_order_by = jQuery('#front_end_search_order_by').val();
		var searchby = jQuery('#front_end_searchby').val();
		var searchtitle = jQuery('#front_end_searchtitle').val();

		jQuery.ajax({
			type: 'post',
			url: ajaxurl,
			data: {
				action: 'frontenddocsearchfunction',
				searchSortBy: search_sort_by,
				searchOrderBy: search_order_by,
				searchby: searchby,
				searchtitle: searchtitle,
			},
			success: function (data) {
				console.log(data);
				jQuery("#front_end_main_listing").html('');
				jQuery("#front_end_main_listing").append(data);

				jQuery('.mycrousal').click(function () {
					jQuery(this).next('tr').toggle();
					var curr = jQuery(this).find('.vk_crousal');
					if (curr.hasClass('fa-folder')) {

						curr.removeClass('fa-folder').addClass('fa-folder-open');

					} else {
						curr.removeClass('fa-folder-open').addClass('fa-folder');
					}

				})
			}
		});

		return false;
	})


});
