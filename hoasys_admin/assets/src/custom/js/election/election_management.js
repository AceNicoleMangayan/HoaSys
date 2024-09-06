var displayData = (function () {
	var ann = function (searchVal = "") {
		var options = {
			data: {
				type: "remote",
				source: {
					read: {
						method: "POST",
						url: `${base_url}/election/get_election_list`,
						params: {
							query: {
								searchField: $("#search_election").val(),
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
					field: "election_title",
					title: "Election Title",
					width: 150,
					selector: false,
					sortable: "asc",
					textAlign: "left",
					template: function (row, index, datatable) {
						var html = row.election_title;
						return html;
					},
				},
				{
					field: "election_desc",
					width: 150,
					title: "Description",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						if (row.election_desc == null || row.election_desc == "") {
							html = "<span style='font-style: italic;'>No Description</span>";
						} else {
							html = row.election_desc;
						}
						return html;
					},
				},
				// {
				// 	field: "datecreated_elect",
				// 	width: 150,
				// 	title: "Date Time Added",
				// 	sortable: false,
				// 	overflow: "visible",
				// 	textAlign: "center",
				// 	template: function (row, index, datatable) {
				// 		var html = "";
				// 		html = row.datecreated_elect;
				// 		return html;
				// 	},
				// },
				{
					field: "datetimeStart",
					title: "Start - End",
					width: 150,
					selector: false,
					sortable: "asc",
					textAlign: "left",
					template: function (row, index, datatable) {
						var html = "";
						html = formatReadableDate(row.datetimeStart)+" - "+formatReadableDate(row.datetimeEnd);
						return html;
					},
				},	
				{
					field: "election_status",
					title: "Election Status",
					width: 120,
					selector: false,
					sortable: "asc",
					textAlign: "center",
					template: function (row, index, datatable) {
						let stat = row.election_status;
						let html = "";
						if (stat == "active") {
							html =
								"<strong><span class='badge bg-primary me-1'> </span><span class='text-primary text-capitalize'> " +
								stat +
								"</span></strong>";
						} else if (stat == "pending") {
							html =
								"<strong><span class='badge bg-warning me-1'> </span><span class='text-warning text-capitalize'> " +
								stat +
								"</span></strong>";
						} else if (stat == "ongoing") {
							html =
								"<strong><span class='badge bg-info me-1'> </span><span class='text-info text-capitalize'> " +
								stat +
								"</span></strong>";
						} else if (stat == "inactive") {
							html =
								"<strong><span class='badge bg-danger me-1'> </span><span class='text-danger text-capitalize'> " +
								stat +
								"</span></strong>";
						} else {
							html =
								"<strong><span class='badge bg-success me-1'> </span><span class='text-success text-capitalize'> " +
								stat +
								"</span></strong>";
						}
						return html;
					},
				},
				{
					field: "lname",
					width: 150,
					title: "Created By",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						html =
							"<span class='text-capitalize'>" +
							row.fname +
							" " +
							row.lname +
							"</span>";
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

						if (
							row.election_status == "pending" ||
							row.election_status == "inactive"
						) {
							html =
								'<button data-id="' +
								row.id_elect +
								'" type="button" class="btn btn-primary election_details">Edit</button>&nbsp;&nbsp;<button data-id="' +
								row.id_elect +
								'" type="button" data-title="' +
								row.election_title +
								'" class="btn btn-info election_settings"><i class="fa fa-cog"></i></button>';
						} else {
							html =
								'<button data-id="' +
								row.id_elect +
								'" type="button" class="btn btn-primary election_details">Edit</button>';
						}
						return html;
					},
				},
			],
		};
		var datatable = $("#datatable_election").mDatatable(options);
	};
	return {
		init: function (searchVal) {
			ann(searchVal);
		},
	};
})();

function initelection() {
	$("#datatable_election").mDatatable("destroy");
	displayData.init();
}
$(function () {
	initelection();
	$(".m_selectpicker").selectpicker();
});
$("#search_election").on("change", function () {
	initelection();
});

$("#add_election_btn").on("click", function () {
	$("#election_form").modal("show");
	get_positions_options();
});

function get_positions_options(ho = []) {
	$.ajax({
		type: "POST",
		url: baseUrl + "/election/fetch_positions_options",
		cache: false,
		success: function (res) {
			var result = JSON.parse(res);
			var stringx = "";
			$.each(result.opt, function (key, data) {
				let selected = "";
				// if (ho.length > 0) {
				// 	selected = accounts.includes(data.id) ? "selected" : "";
				// }
				stringx +=
					"<option value=" +
					data.id +
					" " +
					selected +
					">" +
					data.text +
					"</option>";
			});
			$("#select_positions").html(stringx);
			$("#select_positions").trigger("change");
			$(".selectpicker").selectpicker("refresh");
		},
	});
}
$("#submit_election_details").on("submit", (e) => {
	e.preventDefault();
	// Show loading overlay
	let positions = $("#select_positions").val();
	let election_name = $("#election_name").val();
	let trimmed_election_name = election_name.trim();

	// Datetime filter
	let datetimeStart = $("#date_Start").val();
	let datetimeEnd = $("#date_End").val();

	if (positions.length == 0 || trimmed_election_name === "") {
		toastr.error(
			"Please input election name and select at least (1) position."
		);
	} else if (datetimeStart == "" || datetimeEnd == "") {
		toastr.error("Please select Start and End date time.");
	} else {
		$("#loading-overlay").show();
		save_election();
	}
});
function save_election() {
	$.ajax({
		type: "POST",
		url: `${base_url}/election/save_election`,
		data: {
			election_title: $("#election_name").val(),
			election_desc: $("#election_description").val(),
			positions: $("#select_positions").val(),
			start: $("#date_Start").val(),
			end: $("#date_End").val(),
		},
		cache: false,
		success: function (data) {
			$("#datatable_election").mDatatable("reload");
			$("#election_form").modal("hide");
			$("#election_name").val("");
			$("#election_description").val("");
			$("#select_positions").trigger("change");
			$(".selectpicker").selectpicker("refresh");
			$("#select_positions").empty();
			toastr.success(
				"Successfully Added New (Pending) Election! Next, kindly add candidates."
			);
		},
		complete: function () {
			// Hide loading overlay after the AJAX call is completed
			$("#loading-overlay").hide();
		},
	});
}
$("#refresh_election").on("click", ".election_settings", function () {
	let id = $(this).data("id");
	let title_elect = $(this).data("title");
	$("#elect_title_display_pos").text(title_elect);
	$("#election_settings_display").modal("show");
	$("#election_settings_display").data("id", id);
	get_election_positions_lists(id);
});
function get_election_positions_lists(id) {
	$.ajax({
		type: "POST",
		url: `${base_url}/election/get_election_position_list`,
		data: {
			id,
		},
		cache: false,
		success: function (data) {
			let positions = JSON.parse(data);
			positions_specific_options();
			// Clear existing content before appending new data
			$("#election_positions_list").empty();

			if (positions.length > 0) {
				positions.forEach(function (position) {
					// Create HTML for each record
					let html = `
						<div class="card forum-card mt-4 col-12" style="background:#e7e7e7;">
							<div class="card-body forum-post p-4">
								<div class="d-flex justify-content-between align-items-center">
									<span class="mb-0 font-weight-bold" style="flex-grow: 2;">${position.position_name}</span>
									<div class="d-flex">
										<button type="button" data-winner="${position.candidates_winner}" data-name="${position.position_name}" data-elect="${position.id_elect}" data-pos="${position.election_pos_id}" data-id="${position.election_pos_add_id}"
											class="btn btn-info btn-sm btn-icon edit-icon-btn mr-2 candidate_list">
											<span><i class="fa fa-cog"></i> &nbsp;Candidate Settings</span>
										</button>
										<button type="button" data-elect="${position.id_elect}" data-id="${position.election_pos_add_id}"
											class="btn text-danger btn-link delete-icon-btn delete_position_elect">
											<i class="fa fa-trash"></i>
										</button>
									</div>
								</div>
							</div>
						</div>`;

					// Append the HTML to the container
					$("#election_positions_list").append(html);
				});
			} else {
				let norecords =
					'<div class="text-center mt-5 p-3"><h5 style="color: #ada9e7;">No Positions</h5></div>';
				$("#election_positions_list").append(norecords);
			}
		},
	});
}
$("#refresh_election").on("click", ".election_details", function () {
	let id = $(this).data("id");
	$.ajax({
		type: "POST",
		url: `${base_url}/election/get_election_details`,
		data: {
			id,
		},
		cache: false,
		success: function (data) {
			let el = JSON.parse(data);
			let stat = el[0].election_status;
			let btns = "";
			$("#election_name_update").val(el[0].election_title);
			$("#election_description_update").val(el[0].election_desc);
			$("#election_form_update").data("id", el[0].id_elect);
			$("#date_Start_update").val(el[0].datetimeStart);
			$("#date_End_update").val(el[0].datetimeEnd);
			$("#election_form_update").modal("show");
			// buttons showing
			if (stat == "pending") {
				btns =
					'<button type="button" data-stat="ongoing" id="start_voting_btn" class="btn btn-info"><i class="fa fa-play"></i>&nbsp;Start Voting</button>';
				$("#update_save_changes_voting_btn").show();
				$("#election_name_update").prop("disabled", false);
				$("#election_description_update").prop("disabled", false);
				$("#date_Start_update").prop("disabled", false);
				$("#date_End_update").prop("disabled", false);
			} else if (stat == "ongoing") {
				btns =
					'<button type="button" data-stat="done" id="end_voting_btn" class="btn btn-danger"><i class="fa fa-stop"></i>&nbsp; End Voting</button>';
				$("#update_save_changes_voting_btn").hide();
				$("#election_name_update").prop("disabled", true);
				$("#election_description_update").prop("disabled", true);
			} else if (stat == "done") {
				btns =
					'<button type="button" data-stat="active" id="publish_voting_btn" class="btn btn-primary"><i class="fa fa-check"></i>&nbsp; Publish</button>&nbsp;<button type="button" data-stat="ongoing" id="resume_voting_btn" class="btn btn-info"><i class="fa fa-stop"></i>&nbsp; Resume</button>&nbsp;<button type="button" data-stat="pending" id="reset_voting_btn" class="btn btn-warning"><i class="fa fa-fast-backward"></i>&nbsp; Reset</button>';
				$("#update_save_changes_voting_btn").hide();
				$("#election_name_update").prop("disabled", true);
				$("#election_description_update").prop("disabled", true);
				$("#date_Start_update").prop("disabled", true);
				$("#date_End_update").prop("disabled", true);
			} else if (stat == "active") {
				btns =
					'<button type="button" data-stat="inactive" id="unpublish_voting_btn" class="btn btn-danger"><i class="fa fa-remove"></i>&nbsp; Unpublish</button>&nbsp;<button type="button" data-stat="ongoing" id="resume_voting_btn" class="btn btn-info"><i class="fa fa-stop"></i>&nbsp; Resume</button>&nbsp;<button type="button" data-stat="pending" id="reset_voting_btn" class="btn btn-warning"><i class="fa fa-fast-backward"></i>&nbsp; Reset</button>';
				$("#update_save_changes_voting_btn").hide();
				$("#election_name_update").prop("disabled", true);
				$("#election_description_update").prop("disabled", true);
				$("#date_Start_update").prop("disabled", true);
				$("#date_End_update").prop("disabled", true);
			} else if (stat == "inactive") {
				btns =
					'<button type="button" data-stat="active" id="publish_voting_btn" class="btn btn-primary"><i class="fa fa-check"></i>&nbsp; Publish</button>&nbsp;<button type="button" data-stat="ongoing" id="resume_voting_btn" class="btn btn-info"><i class="fa fa-stop"></i>&nbsp; Resume</button>&nbsp;<button type="button" data-stat="pending" id="reset_voting_btn" class="btn btn-warning"><i class="fa fa-fast-backward"></i>&nbsp; Reset</button>';
				$("#update_save_changes_voting_btn").show();
				$("#election_name_update").prop("disabled", false);
				$("#election_description_update").prop("disabled", false);
				$("#date_Start_update").prop("disabled", false);
				$("#date_End_update").prop("disabled", false);
			}
			$("#buttons_statuses").html(btns);
		},
	});
});
$("#election_positions_list").on("click", ".candidate_list", function () {
	let pos_added_id = $(this).data("id");
	let pos_id = $(this).data("pos");
	let election_id = $(this).data("elect");
	$("#election_candidate").modal("show");
	$("#election_settings_display").modal("hide");
	$("#election_name_cand").text($(this).data("name"));
	$("#winner_candidate").val($(this).data("winner"));
	initcandidate(pos_added_id);
	$("#election_candidate").data("id", pos_added_id);
	$("#election_candidate").data("pos", pos_id);
	$("#election_candidate").data("elect", election_id);
	candidate_options();
});
$("#election_positions_list").on(
	"click",
	".delete_position_elect",
	function () {
		let pos_added_id = $(this).data("id");
		let election_id = $(this).data("elect");
		swal({
			title: `Are you sure you want to remove this positions?`,
			text: "Candidates and other settings under this position will also be removed. Action cannot be undone",
			type: "question",
			showCancelButton: true,
			confirmButtonText: "Yes",
			cancelButtonText: `No`,
		}).then((result) => {
			if (result.value) {
				delete_position(pos_added_id, election_id);
			} else if (result.dismiss === "cancel") {
			}
		});
	}
);
function delete_position(pos_added_id, election_id) {
	$.ajax({
		type: "POST",
		url: `${base_url}/election/delete_position`,
		data: {
			pos_added_id,
			election_id,
		},
		cache: false,
		success: function (data) {
			var result = JSON.parse(data);
			if (result == 0) {
				toastr.error(
					"You only have one position left for this Election. You cannot remove."
				);
			} else {
				get_election_positions_lists(election_id);
				toastr.success("Successfully deleted position.");
			}
		},
	});
}

$("#candidate_close_btn").on("click", function () {
	$("#election_settings_display").modal("show");
});
$("#submit_election_details_update").on("submit", (e) => {
	e.preventDefault();
	let election_name = $("#election_name_update").val();
	let trimmed_election_name = election_name.trim();
	// Datetime filter
	let datetimeStart = $("#date_Start_update").val();
	let datetimeEnd = $("#date_End_update").val();

	// Parse the datetime strings into Date objects
	var start = new Date(datetimeStart);
	var end = new Date(datetimeEnd);

	// Check if datetimeStart is later than datetimeEnd
	var isLater = start > end;

	// Check if datetimeStart and datetimeEnd have the same date and time
	var isSameDateTime = start.getTime() === end.getTime();

	if (trimmed_election_name === "") {
		toastr.error(
			"Please input election name and select at least (1) position."
		);
	} else if (datetimeStart == "" || datetimeEnd == "") {
		toastr.error("Please select Start and End date time.");
	} else if (isLater) {
		// Don't save
		toastr.error("Please input start and end dates and time correctly.");
	} else if (isSameDateTime) {
		toastr.error("Oops!You have the same start and end date time!");
	} else {
		update_election();
	}
});
function update_election() {
	let id = $("#election_form_update").data("id");
	$.ajax({
		type: "POST",
		url: `${base_url}/election/update_election`,
		data: {
			id,
			election_title: $("#election_name_update").val(),
			election_desc: $("#election_description_update").val(),
			start: $("#date_Start_update").val(),
			end: $("#date_End_update").val(),
		},
		cache: false,
		success: function (data) {
			$("#datatable_election").mDatatable("reload");
			$("#election_form_update").modal("hide");
			toastr.success("Successfully Updated Election details.");
		},
	});
}

// candidates
var displayData_candidates = (function () {
	var ann = function (pos_can_add_id, searchVal = "") {
		var options = {
			data: {
				type: "remote",
				source: {
					read: {
						method: "POST",
						url: `${base_url}/election/get_candidates_list`,
						params: {
							query: {
								searchField: $("#search_candidates").val(),
								pos_can_add_id: pos_can_add_id,
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
					field: "fname",
					title: "Full Name",
					width: 150,
					selector: false,
					sortable: "asc",
					textAlign: "left",
					template: function (row, index, datatable) {
						var html = row.fname + " " + row.lname;
						return html;
					},
				},
				{
					field: "candidate_description",
					width: 100,
					title: "Description",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						html = row.candidate_description;
						return html;
					},
				},
				{
					field: "Is Elected",
					width: 80,
					title: "Elected",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						if (row.is_elected == "1") {
							html = "<span class='text-success'>Yes</span>";
						} else {
							html = "<span class='text-warning'>Not Yet</span>";
						}
						return html;
					},
				},
				{
					field: "action",
					width: 150,
					title: "Actions",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						html =
							'<button data-id="' +
							row.id_elect_cand +
							'" type="button" data-title="' +
							row.id_elect_cand +
							'" class="btn btn-danger delete_candidate_per_pos"><i class="fa fa-trash"></i></button>';
						return html;
					},
				},
			],
		};
		var datatable = $("#datatable_candidates").mDatatable(options);
	};
	return {
		init: function (pos_can_add_id, searchVal) {
			ann(pos_can_add_id, searchVal);
		},
	};
})();
function initcandidate(pos_can_add_id) {
	$("#datatable_candidates").mDatatable("destroy");
	displayData_candidates.init(pos_can_add_id);
}
$("#search_candidates").on("change", function () {
	let pos_added_id = $("#election_candidate").data("id");
	initcandidate(pos_added_id);
});

function candidate_options() {
	$("#election_candidate_options").select2({
		ajax: {
			url: baseUrl + "/election/election_candidate_select",
			dataType: "json",
			type: "POST",
			delay: 250,
			data: function (params) {
				var query = {
					election: params.term,
				};
				// Query parameters will be ?search=[term]&page=[page]
				return query;
			},
			// Additional AJAX parameters go here; see the end of this chapter for the full code of this example
		},
		width: "100%",
		placeholder: "Select Homeowner",
	});
}

$("#add_new_candidate").on("click", function () {
	let options_candidate = $("#election_candidate_options").val();
	let candidate_desc = $("#candidate_desc").val();
	let trimmed_candidate_desc = candidate_desc.trim();

	if (options_candidate.length == 0 || trimmed_candidate_desc === "") {
		toastr.error("Please select candidate and input candidate description.");
	} else {
		save_candidate();
	}
});
function save_candidate() {
	let pos_added_id = $("#election_candidate").data("id");
	let elect_id = $("#election_candidate").data("elect");
	let pos_id = $("#election_candidate").data("pos");
	$.ajax({
		type: "POST",
		url: `${base_url}/election/save_candidate`,
		data: {
			pos_added_id,
			elect_id,
			pos_id,
			candidate: $("#election_candidate_options").val(),
			desc: $("#candidate_desc").val(),
		},
		cache: false,
		success: function (data) {
			var result = JSON.parse(data);
			if (result.success == 0) {
				toastr.error("This candidate was already added for this position.");
			} else if(result.success == 2){
			    toastr.error("This candidate was already added for other position of this Election.");
			}else{
				toastr.success("Successfully added a candidate.");
				$("#datatable_candidates").mDatatable("reload");
				$("#candidate_desc").val("");
			}
		},
	});
}
$("#winner_save_btn").on("click", function () {
	let pos_added_id = $("#election_candidate").data("id");
	$.ajax({
		type: "POST",
		url: `${base_url}/election/save_number_of_winners`,
		data: {
			pos_added_id,
			winner: $("#winner_candidate").val(),
		},
		cache: false,
		success: function (data) {
			$("#election_candidate").modal("hide");
			toastr.success("Successfully set number of winners.");
		},
	});
});

function positions_specific_options() {
	$("#select_positions_specific").select2({
		ajax: {
			url: baseUrl + "/election/positions_options_specific",
			dataType: "json",
			type: "POST",
			delay: 250,
			data: function (params) {
				var query = {
					election: params.term,
				};
				// Query parameters will be ?search=[term]&page=[page]
				return query;
			},
			// Additional AJAX parameters go here; see the end of this chapter for the full code of this example
		},
		width: "100%",
		placeholder: "Select Position",
	});
}
$("#add_new_pos_btn").on("click", function () {
	let select_positions_specific = $("#select_positions_specific").val();
	if (select_positions_specific.length == 0) {
		toastr.success("Please select position to add.");
	} else {
		save_additional_position();
	}
});
function save_additional_position() {
	let election_id = $("#election_settings_display").data("id");
	let select_positions_specific = $("#select_positions_specific").val();
	$.ajax({
		type: "POST",
		url: `${base_url}/election/save_additional_position`,
		data: {
			election_id,
			pos_id: select_positions_specific,
		},
		cache: false,
		success: function (data) {
			var result = JSON.parse(data);
			if (result == 0) {
				toastr.error(
					"You have added this position in this election already. You cannot add twice. Kindly select another."
				);
			} else {
				get_election_positions_lists(election_id);
				toastr.success("Successfully added new position.");
			}
		},
	});
}
$("#refresh_candidates").on("click", ".delete_candidate_per_pos", function () {
	let candidate_id = $(this).data("id");
	swal({
		title: `Are you sure you want to remove this candidate?`,
		text: "Action cannot be undone",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			delete_candidate(candidate_id);
		} else if (result.dismiss === "cancel") {
		}
	});
});
function delete_candidate(candidate_id) {
	$.ajax({
		type: "POST",
		url: `${base_url}/election/delete_candidate`,
		data: {
			candidate_id,
		},
		cache: false,
		success: function (data) {
			$("#datatable_candidates").mDatatable("reload");
			toastr.success("Successfully removed candidate.");
		},
	});
}
// Buttons statuses
$("#buttons_statuses").on("click", "#start_voting_btn", function () {
	let stat = $(this).data("stat");
	let election_id = $("#election_form_update").data("id");
	swal({
		title: `Are you sure you want to start the voting?`,
		text: "Once started, homeowners can now start vote for their favored candidates.Note: You cannot update details of Election while it's on going.",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			update_election_statuses(stat, election_id);
			toastr.success("Voting Starts Now!");
		} else if (result.dismiss === "cancel") {
		}
	});
});
$("#buttons_statuses").on("click", "#end_voting_btn", function () {
	let stat = $(this).data("stat");
	let election_id = $("#election_form_update").data("id");
	swal({
		title: `Are you sure you want to end the voting?`,
		text: "Homeowners cannot continue voting after this.",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			update_election_statuses(stat, election_id);
			toastr.warning("The voting has ended for this Election.");
		} else if (result.dismiss === "cancel") {
		}
	});
});

$("#buttons_statuses").on("click", "#publish_voting_btn", function () {
	let stat = $(this).data("stat");
	let election_id = $("#election_form_update").data("id");
	swal({
		title: `Are you sure you want to publish this election?`,
		text: "The list of winners will be publish on the election board",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			update_election_statuses(stat, election_id);
			toastr.success("Successfully posted Winners for this Election!");
		} else if (result.dismiss === "cancel") {
		}
	});
});
$("#buttons_statuses").on("click", "#unpublish_voting_btn", function () {
	let stat = $(this).data("stat");
	let election_id = $("#election_form_update").data("id");
	swal({
		title: `Are you sure you want to unpublish this election?`,
		text: "The list of winners will be REMOVED from the election board.",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			update_election_statuses(stat, election_id);
			toastr.success(
				"Successfully Unpublished the Officers List in the Election Board!"
			);
		} else if (result.dismiss === "cancel") {
		}
	});
});
$("#buttons_statuses").on("click", "#resume_voting_btn", function () {
	let stat = $(this).data("stat");
	let election_id = $("#election_form_update").data("id");
	swal({
		title: `Are you sure you want to RESUME this election?`,
		text: "The voting will continue once you proceed.",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			update_election_statuses(stat, election_id);
			toastr.success("Voting has resumed!");
		} else if (result.dismiss === "cancel") {
		}
	});
});

$("#buttons_statuses").on("click", "#reset_voting_btn", function () {
	let stat = $(this).data("stat");
	let election_id = $("#election_form_update").data("id");
	swal({
		title: `PLEASE READ, ATTENTION! THIS IS A CRITICAL ACTION`,
		text: "Are you sure you want to reset this election? If Yes, it will removed all the votes recorded in this election and will not retrieve them again.",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			update_election_statuses(stat, election_id);
			toastr.success(
				"This Election Successfully Reset! You can modify this election and can start voting all over again."
			);
		} else if (result.dismiss === "cancel") {
		}
	});
});
function update_election_statuses(stat, election_id) {
	$("#loading-overlay").show();
	$.ajax({
		type: "POST",
		url: `${base_url}/election/update_election_statuses`,
		data: {
			election_id,
			stat,
		},
		cache: false,
		success: function () {
			$("#datatable_election").mDatatable("reload");
			$("#election_form_update").modal("hide");
		},
		complete: function () {
			// Hide loading overlay after the AJAX call is completed
			$("#loading-overlay").hide();
		},
	});
}
function formatReadableDate(dateString) {
    const options = {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "numeric",
        minute: "numeric",
    };
    const formattedDate = new Date(dateString).toLocaleDateString(
        "en-US",
        options
    ).replace(' at', '');
    return formattedDate;
}
