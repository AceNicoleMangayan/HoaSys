var displayData = (function () {
	var ann = function (searchVal = "") {
		var options = {
			data: {
				type: "remote",
				source: {
					read: {
						method: "POST",
						url: `${base_url}/announcement/get_announcement`,
						params: {
							query: {
								searchField: $("#search_Field_prize").val(),
							},
						},
					},
				},
				saveState: {
					cookie: false,
					webstorage: false,
				},
				pageSize: 10,
				serverPaging: true,
				serverFiltering: true,
				serverSorting: true,
			},
			layout: {
				theme: "default",
				class: "",
				scroll: false,
				minHeight: 20,
				footer: false,
			},
			sortable: true,
			pagination: true,
			toolbar: {
				placement: ["bottom"],
				items: {
					pagination: {
						pageSizeSelect: [10, 20, 30, 50, 100],
					},
				},
			},
			columns: [
				{
					field: "title_anmnt",
					title: "Announcement Title",
					width: 200,
					selector: false,
					sortable: "asc",
					textAlign: "left",
					template: function (row, index, datatable) {
						var html = "";
						var class_decor = "";
						if(row.status_anmnt == "unpublished"){
							class_decor = 'style="text-decoration: line-through;"';
						}else{
							class_decor = "";
						}

						html = '<span ' + class_decor + '>' + row.title_anmnt + '</span>';
						return html;
					},
				},
				{
					field: "datecreated_anmnt",
					title: "Date Created",
					width: 150,
					selector: false,
					sortable: "asc",
					textAlign: "left",
				},
				// {
				// 	field: "status",
				// 	title: "Status",
				// 	width: 120,
				// 	selector: false,
				// 	sortable: "asc",
				// 	textAlign: "left",
				// 	template: function (row, index, datatable) {
				// 		let stat = row.status_anmnt;
				// 		let html = "";
				// 		if (stat == "published") {
				// 			html =
				// 				"<strong><span class='badge bg-success me-1'> </span><span class='text-success text- capitalize'> " +
				// 				stat +
				// 				"</span></strong>";
				// 		} else {
				// 			html =
				// 				"<strong><span class='badge bg-danger me-1'> </span><span class='text-danger text- capitalize'> " +
				// 				stat +
				// 				"</span></strong>";
				// 		}
				// 		return html;
				// 	},
				// },
				// {
				// 	field: "Actions",
				// 	width: 70,
				// 	title: "Actions",
				// 	sortable: false,
				// 	overflow: "visible",
				// 	textAlign: "center",
				// 	template: function (row, index, datatable) {
				// 		var html = "";
				// 		html =
				// 			'<button data-id="' +
				// 			row.id_anmnt +
				// 			'" type="button" class="btn btn-outline-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--outline-2x m-btn--pill m-btn--air update_announcement_info"><i class="fa fa-pencil"></i></button>';
				// 		return html;
				// 	},
				// },
				{
					field: "Actions",
					width: 70,
					title: "Action",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						html =
							'<button data-id="' +
							row.id_anmnt+
							'" type="button" class="btn btn-primary view_announcement_modal"> View </button>';
						return html;
					},
				},
			],
		};
		var datatable = $("#datatable_announcement").mDatatable(options);
	};
	return {
		init: function (searchVal) {
			ann(searchVal);
		},
	};
})();
function initannouncement() {
	$("#datatable_announcement").mDatatable("destroy");
	displayData.init();
}

$(function () {
	initannouncement();
	$(".m_selectpicker").selectpicker();
});
// Button triggers
$("#MinorP_btn").on("click", function () {
	initannouncement();
});
$("#select_sequence,#select_sequence_update").select2({
	placeholder: "Select Sequence.",
	width: "100%",
});
$("#search_Field_prize").on("change", function () {
	initannouncement();
});
$("#add_announcement_btn").on("click", function () {
	$("#announcement_modal").modal("show");
	$("#Major_prize").val("default").selectpicker("refresh");
	$("#select_sequence").empty();
	$("#select_sequence").prop("disabled", true);
	$("#Winners_number").prop("disabled", false);
	$("#winnerNotif").hide();
	// sequences
	// sequence_select(0, 0, 1);
});
$("#submit_announcement").on("submit", (e) => {
	e.preventDefault();
	// Show loading overlay
	$("#loading-overlay").show();

	$.ajax({
		type: "POST",
		url: `${base_url}/announcement/save_announcement`,
		data: {
			title: $("#ann_title").val(),
			desc: $("#ann_description").val(),
		},
		cache: false,
		success: function (data) {
			$("#datatable_announcement").mDatatable("reload");
			$("#announcement_modal").modal("hide");
			toastr.success("Successfully added announcement");
			$("#loading-overlay").hide();
		},
		complete: function () {
			// Hide loading overlay after the AJAX call is completed
			$("#loading-overlay").hide();
		},
	});
});

$("#refresh_announcement").on(
	"click",
	".update_announcement_info",
	function () {
		let ann_id = $(this).data("id");
		$("#announcement_id_update").val(ann_id);
		$.ajax({
			type: "POST",
			url: `${base_url}/announcement/get_announcement_details`,
			data: {
				id: ann_id,
			},
			cache: false,
			success: function (data) {
				let ann = JSON.parse(data);
				$("#announcement_update_modal").modal("show");
				$("#ann_title_update").val(ann[0].title);
				$("#ann_description_update").val(ann[0].description);
				if (ann[0].status_anmnt == "unpublished") {
					$("#publish_btn").show();
					$("#unpublish_btn").hide();
				} else {
					$("#unpublish_btn").show();
					$("#publish_btn").hide();
				}
			},
		});
	}
);

$("#refresh_announcement").on(
	"click",
	".delete_announcement",
	function () {
		let ann_id = $(this).data("id");
		$("#announcement_id_update").val(ann_id);
		swal({
			title: `Are you sure you want to remove this Announcement?`,
			text: "Action cannot be undone",
			type: "question",
			showCancelButton: true,
			confirmButtonText: "Yes",
			cancelButtonText: `No`,
		}).then((result) => {
			if (result.value) {
				remove_announcement(ann_id);
			} else if (result.dismiss === "cancel") {
			}
		});
	}
);
function remove_announcement(id){
	let tit = $("#ann_title_update").val();
	$.ajax({
		type: "POST",
		url: `${base_url}/announcement/remove_announcement`,
		data: {
			id
		},
		cache: false,
		success: function (data) {
			$("#datatable_announcement").mDatatable("reload");
			toastr.success("Successfully removed " + tit);
			$("#announcement_update_modal").modal("hide");
		},
	});
}

$("#publish_btn").on("click", function () {
	let tit = $("#ann_title_update").val();
	let annid = $("#announcement_id_update").val();
	$.ajax({
		type: "POST",
		url: `${base_url}/announcement/update_announcement_publish`,
		data: {
			id: annid,
			pub: "published",
		},
		cache: false,
		success: function (data) {
			$("#datatable_announcement").mDatatable("reload");
			toastr.success("Successfully published " + tit);
			$("#announcement_update_modal").modal("hide");
		},
	});
});
$("#unpublish_btn").on("click", function () {
	let tit = $("#ann_title_update").val();
	let annid = $("#announcement_id_update").val();
	$.ajax({
		type: "POST",
		url: `${base_url}/announcement/update_announcement_publish`,
		data: {
			id: annid,
			pub: "unpublished",
		},
		cache: false,
		success: function (data) {
			$("#datatable_announcement").mDatatable("reload");
			toastr.success("Successfully unpublished " + tit);
			$("#announcement_update_modal").modal("hide");
		},
	});
});

$("#submit_update_announcement").on("submit", (e) => {
	e.preventDefault();
	let tit = $("#ann_title_update").val();
	let annid = $("#announcement_id_update").val();
	$.ajax({
		type: "POST",
		url: `${base_url}/announcement/update_announcement`,
		data: {
			id: annid,
			title: tit,
			desc: $("#ann_description_update").val(),
		},
		cache: false,
		success: function (data) {
			$("#datatable_announcement").mDatatable("reload");
			toastr.success("Successfully updated " + tit);
			$("#announcement_update_modal").modal("hide");
		},
	});
});
$("#refresh_announcement").on("click", ".view_announcement_modal", function() {
    let id = $(this).data("id");
    $.ajax({
        type: "POST",
		url: `${base_url}/dashboard/get_specific_announcement_dashboard`,
        data: {
            id
        },
        dataType: "json",
        cache: false,
        success: function(data) {
            var formattedDate = new Date(data[0].datecreated_anmnt).toLocaleDateString(
                'en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            $("#announcement_title_dash").text(data[0].title_anmnt);
            $("#announcement_desc_dash").text(data[0].desc_anmnt);
            $("#announcement_date_dash").text(formattedDate);

        },
    });
    $("#announcement_details_dashboard").modal("show");
});
