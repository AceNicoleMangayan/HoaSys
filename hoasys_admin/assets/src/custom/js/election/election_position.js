var displayData = (function () {
	var ann = function (searchVal = "") {
		var options = {
			data: {
				type: "remote",
				source: {
					read: {
						method: "POST",
						url: `${base_url}/election/get_positions`,
						params: {
							query: {
								searchField: $("#search_election_position").val(),
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
			columns: [{
					field: "position_name",
					title: "Position Name",
					width: 150,
					selector: false,
					sortable: "asc",
					textAlign: "left",
					template: function (row, index, datatable) {
						var html = row.position_name;
						return html;
					},
				},
				{
					field: "position_description",
					width: 150,
					title: "Position Description",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						if (row.position_description == null || row.position_description == "") {
							html = "<span style='font-style: italic;'>No Description</span>";
						} else {
							html = row.position_description;
						}
						return html;
					},
				},
				{
					field: "datetime_added",
					width: 150,
					title: "Date Time Added",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						html = row.datetime_added;
						return html;
					},
				},
				{
					field: "position_status",
					title: "Position Status",
					width: 120,
					selector: false,
					sortable: "asc",
					textAlign: "center",
					template: function (row, index, datatable) {
						let stat = row.position_status;
						let html = "";
						if (stat == "active") {
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
					field: "action",
					width: 200,
					title: "Actions",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						var status_label = "";
						var status_color = "";
						var status_id = "";
						if (row.position_status == "active") {
							status_label = "Deactivate";
							status_color = "btn-danger";
							status_id = "inactive";
						} else {
							status_label = "Activate";
							status_color = "btn-success";
							status_id = "active";
						}
						html =
							'<button data-id="' +
							row.election_pos_id +
							'" type="button" class="btn btn-primary edit_position"> Edit </button>&nbsp;&nbsp;<button data-id="' +
							row.election_pos_id +
							'" type="button" data-stat="' + status_id + '" class="btn change_status ' + status_color + '">' + status_label + '</button>';
						return html;
					},
				},
			],
		};
		var datatable = $("#datatable_positions").mDatatable(options);
	};
	return {
		init: function (searchVal) {
			ann(searchVal);
		},
	};
})();

function initposition() {
	$("#datatable_positions").mDatatable("destroy");
	displayData.init();
}
$(function () {
	initposition();
	$(".m_selectpicker").selectpicker();
});

$("#search_election_position").on("change", function () {
	initposition();
});
$("#add_position_btn").on("click", function () {
	$("#position_modal_form").modal("show");
});



$("#submit_position").on("submit", (e) => {
	e.preventDefault();
	// Show loading overlay
	$("#loading-overlay").show();

	$.ajax({
		type: "POST",
		url: `${base_url}/election/save_position`,
		data: {
			title: $("#position_title_field").val(),
			desc: $("#position_desc_field").val(),
		},
		cache: false,
		success: function (data) {
			$("#datatable_positions").mDatatable("reload");
			$("#position_modal_form").modal("hide");
			toastr.success("Successfully Added New Position");
			$("#loading-overlay").hide();
			$("#position_title_field").val("");
			$("#position_desc_field").val("");
		},
		complete: function () {
			// Hide loading overlay after the AJAX call is completed
			$("#loading-overlay").hide();
		},
	});
});

$("#refresh_positions").on("click", ".change_status", function () {
	let id = $(this).data("id");
	let stat = $(this).data("stat");
	swal({
		title: 'Are you sure you want to set position to ' + stat + " ?",
		text: "",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			change_position_status(id, stat);
		} else if (result.dismiss === "cancel") {

		}
	});
});
$("#refresh_positions").on("click", ".edit_position", function () {
	let id = $(this).data("id");
	$.ajax({
		type: "POST",
		url: `${base_url}/election/get_position_details`,
		data: {
			id,
		},
		cache: false,
		success: function (data) {
			let pos = JSON.parse(data);
			$("#position_title_field_update").val(pos[0].position_name);
			$("#position_desc_field_update").val(pos[0].position_description);
			$("#position_modal_form_update").data("id",pos[0].election_pos_id);
			$("#position_modal_form_update").modal("show");
		},
	});
});

function change_position_status(id, stat) {
	$.ajax({
		type: "POST",
		url: `${base_url}/election/change_position_status`,
		data: {
			id,
			stat
		},
		cache: false,
		success: function (data) {
			$("#datatable_positions").mDatatable("reload");
			$("#position_modal_form").modal("hide");
			toastr.success("Successfully Added New Position");
			$("#loading-overlay").hide();
			$("#position_title_field").val("");
			$("#position_desc_field").val("");
		},
		complete: function () {
			// Hide loading overlay after the AJAX call is completed
			$("#loading-overlay").hide();
		},
	});
}

$("#submit_position_update").on("submit", (e) => {
	e.preventDefault();
	swal({
		title: 'Are you sure you want to update position details?',
		text: "Note that it will affect other records.",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			change_position_details();
		} else if (result.dismiss === "cancel") {

		}
	});
});
function change_position_details(){
	let pos_id = $("#position_modal_form_update").data("id");
	$.ajax({
		type: "POST",
		url: `${base_url}/election/update_position`,
		data: {
			pos_id,
			title: $("#position_title_field_update").val(),
			desc: $("#position_desc_field_update").val(),
		},
		cache: false,
		success: function (data) {
			$("#datatable_positions").mDatatable("reload");
			$("#position_modal_form_update").modal("hide");
			toastr.success("Successfully Updated Position Details.");
			$("#loading-overlay").hide();
		},
		complete: function () {
			// Hide loading overlay after the AJAX call is completed
			$("#loading-overlay").hide();
		},
	});
}
// -- no used 
