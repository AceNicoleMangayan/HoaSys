var displayData = (function () {
	var prize = function (searchVal = "") {
		var options = {
			data: {
				type: "remote",
				source: {
					read: {
						method: "POST",
						url: `${base_url}/homeowners/get_homeowners`,
						params: {
							query: {
								searchField: $("#search_Field_ho").val(),
								block: $("#block_num").val(),
								lot: $("#lot_num").val(),
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
					field: "name",
					title: "Full Name",
					width: 120,
					selector: false,
					sortable: "asc",
					textAlign: "left",
					template: function (row, index, datatable) {
						var html = "";
						html = row.fname + " " + row.lname;
						return html;
					},
				},
				{
					field: "block",
					title: "Block",
					width: 80,
					selector: false,
					sortable: "asc",
					textAlign: "left",
				},
				{
					field: "lot",
					title: "Lot",
					width: 80,
					selector: false,
					sortable: "asc",
					textAlign: "left",
				},
				{
					field: "village",
					title: "Village",
					width: 80,
					selector: false,
					sortable: "asc",
					textAlign: "left",
				},
				{
					field: "contact_num",
					title: "Contact Number",
					width: 100,
					selector: false,
					sortable: "asc",
					textAlign: "left",
				},
				{
                    field: "good_payer",
                    title: "Pay Status",
                    width: 100,
                    selector: false,
                    sortable: "asc",
                    textAlign: "left",
                    template: function (row, index, datatable) {
                        let stat = row.good_payer;
                        let html = "";
                        if (stat === "1") {
                            html = "Good Payer";
                        } else {
                            html = "Not Good";
                        }
                        return html;
                    },
                },
				{
					field: "status",
					title: "Status",
					width: 100,
					selector: false,
					sortable: "asc",
					textAlign: "left",
					template: function (row, index, datatable) {
						let stat = row.status;
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
					field: "Actions",
					width: 70,
					title: "Actions",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						html =
							'<button data-id="' +
							row.id_ho +
							'" type="button" class="btn btn-outline-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--outline-2x m-btn--pill m-btn--air update_Homeowners_info"><i class="fa fa-pencil"></i></button>';
						return html;
					},
				},
			],
		};
		var datatable = $("#datatable_homeowners").mDatatable(options);
	};
	return {
		init: function (searchVal) {
			prize(searchVal);
		},
	};
})();

function initho() {
	$("#datatable_homeowners").mDatatable("destroy");
	displayData.init();
}

$(function () {
	initho();
	year_picker();
	$(".m_selectpicker").selectpicker();
	// Initialize block_num dropdown
	initializeDropdown_block("#block_num");
	// Initialize lot_num dropdown
	initializeDropdown_lot("#lot_num");
});
// Button triggers
$("#MinorP_btn").on("click", function () {
	initho();
});
$("#select_sequence,#select_sequence_update").select2({
	placeholder: "Select Sequence.",
	width: "100%",
});
$("#search_Field_ho,#block_num,#lot_num").on("change", function () {
	initho();
});
$("#addprize_btn").on("click", function () {
	$("#raffle_modal").modal("show");
	$("#Major_prize").val("default").selectpicker("refresh");
	$("#select_sequence").empty();
	$("#select_sequence").prop("disabled", true);
	$("#Winners_number").prop("disabled", false);
	$("#winnerNotif").hide();
	// sequences
	// sequence_select(0, 0, 1);
});
$("#submit_homeowner").on("submit", (e) => {
	e.preventDefault();
	// Show loading overlay
	$("#loading-overlay").show();
	let month_opt = $("#month_options_add").val();
	if(month_opt == ""){
	   swal({
            title: "Hold On!",
            text: "It looks like you haven't filled out all the fields in the form. Please double-check and complete all required fields.",
            icon: "error",
        });
	}else{
	    $.ajax({
		type: "POST",
		url: `${base_url}/homeowners/save_homeowner`,
		data: {
			fname: $("#first_name").val(),
			lname: $("#last_name").val(),
			mname: $("#mid_name").val(),
			block: $("#block").val(),
			lot: $("#lot").val(),
			village: $("#village").val(),
			contact_number: $("#contact_number").val(),
			email_address: $("#email_address").val(),
			username: $("#username").val(),
			// password: $("#pass").val(),
			payment: $("#monthly_payment").val(),
			duedate: $("#due_date_payment").val(),
			owner: $("#type_of_owner").val(),
			movein: $("#year_options_add").val(),
			moveinmonth: $("#month_options_add").val(),
			vote: $("#allowed_to_vote").val(),
			run: $("#allowed_to_run").val(),
			payer: $("#good_payer").val(),
		},
		cache: false,
		success: function (data) {
			let datareturned = JSON.parse(data);
			var major = $("#Major_prize").val();
			if (datareturned.success == 2) {
				swal({
					title: "Existing Username!",
					text: "Please provide another username.",
					icon: "error",
				});
			} else if (datareturned.success == 3) {
				swal({
					title: "Existing Email Address!",
					text: "Please provide another email address.",
					icon: "error",
				});
			} else {
				$("#datatable_homeowners").mDatatable("reload");
				toastr.success(
					"Successfully added " +
						$("#first_name").val() +
						" " +
						$("#last_name").val()
				);
				$("#submit_homeowner")[0].reset();
				$("#raffle_modal").modal("hide");
			}
		},
		complete: function () {
			// Hide loading overlay after the AJAX call is completed
			$("#loading-overlay").hide();
		},
	});
	}
});

$("#refresh_homeowners").on("click", ".update_Homeowners_info", function () {
	let homeowner_id = $(this).data("id");
	$("#homeowner_ID_update").val(homeowner_id);
	$.ajax({
		type: "POST",
		url: `${base_url}/homeowners/get_homeowner_details`,
		data: {
			id: homeowner_id,
		},
		cache: false,
		success: function (data) {
			let homeowner = JSON.parse(data);
			$("#homeowner_update_modal").modal("show");
			$("#first_name_up").val(homeowner[0].fname);
			$("#last_name_up").val(homeowner[0].lname);
			$("#mid_name_up").val(homeowner[0].mname);
			$("#block_up").val(homeowner[0].block);
			$("#lot_up").val(homeowner[0].lot);
			$("#contact_number_up").val(homeowner[0].contact_num);
			$("#email_address_up").val(homeowner[0].email_add);
			$("#username_up").val(homeowner[0].username);
			// $("#pass_up").val(homeowner[0].password);
			$("#village_up").val(homeowner[0].village);
			$("#status_up").selectpicker("val", homeowner[0].status);
			$("#allowed_to_vote_up").selectpicker("val", homeowner[0].can_vote);
			$("#allowed_to_run_up").selectpicker("val", homeowner[0].can_run);
			$("#good_payer_up").selectpicker("val", homeowner[0].good_payer);
			$("#type_of_owner_up").selectpicker("val", homeowner[0].owner_type);
			$("#month_options_add_up").selectpicker(
				"val",
				homeowner[0].move_in_month
			);
			$("#year_options_add_up").selectpicker("val", homeowner[0].move_in_year);
			$("#monthly_payment_up").val(homeowner[0].monthly);
			// $("#due_date_payment_up").val(homeowner[0].duedate);
			$("#due_date_payment_up").selectpicker("val", homeowner[0].duedate);
		},
	});
});

$("#submit_homeowner_update").on("submit", (e) => {
	e.preventDefault();
	let homeownerid = $("#homeowner_ID_update").val();
	swal({
		title: `Are you sure you want to update the owner details?`,
		text: "Action cannot be undone",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			$.ajax({
				type: "POST",
				url: `${base_url}/homeowners/update_homeowner`,
				data: {
					id: homeownerid,
					status: $("#status_up").val(),
					fname: $("#first_name_up").val(),
					lname: $("#last_name_up").val(),
					mname: $("#mid_name_up").val(),
					block: $("#block_up").val(),
					lot: $("#lot_up").val(),
					village: $("#village_up").val(),
					contact_number: $("#contact_number_up").val(),
					email_address: $("#email_address_up").val(),
					// password: $("#pass_up").val(),
					username: $("#username_up").val(),
					monthly: $("#monthly_payment_up").val(),
					due: $("#due_date_payment_up").val(),
					year: $("#year_options_add_up").val(),
					month: $("#month_options_add_up").val(),
					vote: $("#allowed_to_vote_up").val(),
					run: $("#allowed_to_run_up").val(),
					payer: $("#good_payer_up").val(),
					owner: $("#type_of_owner_up").val(),
				},
				cache: false,
				success: function (data) {
					let datareturned = JSON.parse(data);
					if (datareturned.success == 2) {
						swal({
							title: "Existing Username!",
							text: "Please provide another username.",
							icon: "error",
						});
					} else if (datareturned.success == 3) {
						swal({
							title: "Existing Email Address!",
							text: "Please provide another email address.",
							icon: "error",
						});
					} else {
						$("#datatable_homeowners").mDatatable("reload");
						toastr.success(
							"Successfully updated " +
								$("#first_name_up").val() +
								" " +
								$("#last_name_up").val()
						);
						$("#homeowner_update_modal").modal("hide");
					}
				},
			});
		} else if (result.dismiss === "cancel") {
		}
	});
});
$("#download_homeowners_report").on("click", function () {
	var todate = moment().format("DD-MM-YYYY HH:mm:ss");
	$.ajax({
		url: `${base_url}/homeowners/download_homeowners_report`,
		method: "POST",
		data: {
			searchField: $("#search_Field_ho").val(),
			block: $("#block_num").val(),
			lot: $("#lot_num").val(),
		},
		beforeSend: function () {
			mApp.block("body", {
				overlayColor: "#000000",
				type: "loader",
				state: "brand",
				size: "lg",
				message: "Downloading...",
			});
		},
		xhrFields: {
			responseType: "blob",
		},
		success: function (data) {
			// console.log(data);
			var a = document.createElement("a");
			var url = window.URL.createObjectURL(data);
			a.href = url;
			a.download = "Hoasys-homeowners-members-" + todate + ".xlsx";
			a.click();
			window.URL.revokeObjectURL(url);
			mApp.unblock("body");
		},
		error: function (data) {
			$.notify(
				{
					message: "No record to export / Error in exporting excel",
				},
				{
					type: "danger",
					timer: 1000,
				}
			);
			mApp.unblock("body");
		},
	});
});

function initializeDropdown_block(selector) {
	// Add "ALL" option
	$(selector).append('<option value="All">All Blocks</option>');

	// Add numbers 1 to 100
	for (let i = 1; i <= 100; i++) {
		$(selector).append('<option value="' + i + '">' + i + "</option>");
	}
}

function initializeDropdown_lot(selector) {
	// Add "ALL" option
	$(selector).append('<option value="All">All Lots</option>');

	// Add numbers 1 to 100
	for (let i = 1; i <= 100; i++) {
		$(selector).append('<option value="' + i + '">' + i + "</option>");
	}
}

function year_picker() {
	var currentYear = new Date().getFullYear();
	// Populate the year dropdown with options from 1980 to the current year
	for (var year = 1980; year <= currentYear; year++) {
		$("#year_options_add,#year_options_add_up").append(
			$("<option>", {
				value: year,
				text: year,
			})
		);
	}
	// Set the default selected year (you can change this as needed)
	var defaultYear = currentYear;
	$("#year_options_add,#year_options_add_up").val(defaultYear);

	// Month
	// Array of months
	var months = [
		"January",
		"February",
		"March",
		"April",
		"May",
		"June",
		"July",
		"August",
		"September",
		"October",
		"November",
		"December",
	];

	// Populate select options with month names
	var select = $("#month_options_add,#month_options_add_up");
	$.each(months, function (index, value) {
		select.append($("<option>").text(value).attr("value", value));
	});

	// Initialize Bootstrap-select
	select.selectpicker();
}
