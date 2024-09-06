var displayData = (function () {
	var ann = function (searchVal = "") {
		var options = {
			data: {
				type: "remote",
				source: {
					read: {
						method: "POST",
						url: `${base_url}/concerns/get_concerns`,
						params: {
							query: {
								searchField: $("#search_Field").val(),
								status: $("#status_concern").val(),
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
					field: "id_concern",
					width: 80,
					title: "Concern #",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						html = "00000000" + row.id_concern;
						html = html.slice(-8);
						return html;
					},
				},
				{
					field: "title_concern",
					title: "Concern Title",
					width: 120,
					selector: false,
					sortable: "asc",
					textAlign: "left",
					template: function (row, index, datatable) {
						var html = "";
						var class_decor = "";
						if (row.title_concern == "unpublished") {
							class_decor = 'style="text-decoration: line-through;"';
						} else {
							class_decor = "";
						}

						html = "<span " + class_decor + ">" + row.title_concern + "</span>";
						return html;
					},
				},
				{
					field: "datesent_concern",
					title: "Date Sent",
					width: 100,
					selector: false,
					sortable: "asc",
					textAlign: "left",
				},
				{
					field: "lname",
					width: 150,
					title: "Sender",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						html = row.fname + " " + row.lname;
						return html;
					},
				},
				{
					field: "status_concern",
					title: "Status",
					width: 100,
					selector: false,
					sortable: "asc",
					textAlign: "left",
					template: function (row, index, datatable) {
						let stat = row.status_concern;
						let html = "";
						if (stat == "solved") {
							html =
								"<strong><span class='badge bg-success me-1'> </span><span class='text-success text- capitalize'> " +
								stat +
								"</span></strong>";
						} else {
							html =
								"<strong><span class='badge bg-danger me-1'> </span><span class='text-danger text- capitalize'> " +
								stat +
								"</span></strong>";
						}
						return html;
					},
				},
				{
					field: "isReceivedEmail",
					width: 80,
					title: "Sent Email",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						var isReceived = row.isReceivedEmail;
						if (isReceived == 1) {
							html = "<span class='text-success'>YES</span>";
						} else {
							html = "<span class='text-danger'>NO</span>";
						}
						return html;
					},
				},
				{
					field: "action",
					width: 200,
					title: "Actions",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						html =
							'<button data-id="' +
							row.id_concern +
							'" type="button" class="btn btn-primary view_email_concern"> View </button>';
						return html;
					},
				},
			],
		};
		var datatable = $("#datatable_concerns").mDatatable(options);
	};
	return {
		init: function (searchVal) {
			ann(searchVal);
		},
	};
})();
function initconcerns() {
	$("#datatable_concerns").mDatatable("destroy");
	displayData.init();
}

$(function () {
	initconcerns();
	$(".m_selectpicker").selectpicker();
});
$("#select_sequence,#select_sequence_update").select2({
	placeholder: "Select Sequence.",
	width: "100%",
});
$("#search_Field,#status_concern").on("change", function () {
	initconcerns();
});

$("#refresh_concerns").on("click", ".send_email_concern", function () {
	let c_id = $(this).data("id");
	$("#concern_send_details").modal("show");
	$("#send_to").val($(this).data("email"));
	$("#concern_send_details").data("id", c_id);
	// $("#announcement_id_update").val(ann_id);
	// $.ajax({
	// 	type: "POST",
	// 	url: `${base_url}/announcement/get_announcement_details`,
	// 	data: {
	// 		id: ann_id,
	// 	},
	// 	cache: false,
	// 	success: function (data) {
	// 		let ann = JSON.parse(data);
	// 		$("#announcement_update_modal").modal("show");
	// 		$("#ann_title_update").val(ann[0].title);
	// 		$("#ann_description_update").val(ann[0].description);
	// 		if (ann[0].status_anmnt == "unpublished") {
	// 			$("#publish_btn").show();
	// 			$("#unpublish_btn").hide();
	// 		} else {
	// 			$("#unpublish_btn").show();
	// 			$("#publish_btn").hide();
	// 		}
	// 	},
	// });
});

$("#refresh_concerns").on("click", ".view_email_concern", function () {
	let c_id = $(this).data("id");
	$("#concern_details").data("id", c_id);
	$.ajax({
		type: "POST",
		url: `${base_url}/concerns/get_concern_details`,
		data: {
			id: c_id,
		},
		cache: false,
		success: function (data) {
			let con = JSON.parse(data);
			let stat = con[0].status_concern;
			$("#concern_details").modal("show");
			$("#concern_title_display").text(con[0].title_concern);
			$("#concern_date_display").text(con[0].datesent_concern);
			$("#concern_sender_display").text(con[0].fname+" "+con[0].lname);
			$("#concern_status_display").text(stat);
			$("#concern_desc_display").text(con[0].desc_concern);
			let id_con = "00000000" + con[0].id_concern;
			id_con = id_con.slice(-8);
			$("#concern_id_display").text(id_con);
			// if(stat == "unresolved"){
			// 	$("#solve_btn").show();
			// 	$("#unsolve_btn").hide();
			// }else{
			// 	$("#unsolve_btn").show();
			// 	$("#solve_btn").hide();
			// }
		},
	});
});
$("#solve_btn").on("click", function(){
	swal({
		title: `Are you sure you to mark this concern as SOLVED ?`,
		text: "Kindly review",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			change_concern_status("solved");
		} else if (result.dismiss === "cancel") {
		}
	});
});
$("#unsolve_btn").on("click", function(){
	swal({
		title: `Are you sure you to mark this concern back as UNRESOLVED ?`,
		text: "Kindly review",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			change_concern_status("unresolved");
		} else if (result.dismiss === "cancel") {
		}
	});
});
function change_concern_status(status){
	let id = $("#concern_details").data("id");
	$.ajax({
		type: "POST",
		url: `${base_url}/concerns/change_concern_status`,
		data: {
			id,
			status
		},
		cache: false,
		success: function (data) {
			$("#datatable_concerns").mDatatable("reload");
			toastr.success("Successfully updated status to "+status);
			$("#concern_details").modal("hide");
		},
	});
}
$("#submit_concern_email_add").on("submit", (e) => {
	e.preventDefault();
	// Show loading overlay
	$("#loading-overlay").show();
	let title = $("#concern_title").val();
	let description = $("#concern_content").val();
	if(title == "" || description == ""){
		toastr.error("Please fill the title and description fields.");
	}else{
		$.ajax({
			type: "POST",
			url: `${base_url}/concerns/send_concern`,
			data: {
				title,
				description
			},
			cache: false,
			success: function (data) {
				$("#datatable_concerns").mDatatable("reload");
				$("#concern_send_details_add").modal("hide");
				toastr.success("Successfully Sent Concern to Admin");
			},
			complete: function () {
				// Hide loading overlay after the AJAX call is completed
				$("#loading-overlay").hide();
			},
		});
	}
});
//New codes
$("#send_concern_btn").on("click", function(){
	$("#concern_send_details_add").modal("show");
})

