$(function () {
	initpayment();
	monthyear_opt(1);
	monthyear_opt(2);
	$(".m_selectpicker").selectpicker();

	$("#year_select,#year_select_choose, #year_select_ho").select2({
		placeholder: "Select Year",
		width: "100%",
	});
	$("#month_select,#month_select_choose, #month_select_ho").select2({
		placeholder: "Select Month",
		width: "100%",
	});
});
var displayPayment = (function () {
	var payment = function (searchVal = "") {
		var options = {
			data: {
				type: "remote",
				source: {
					read: {
						method: "POST",
						url: `${base_url}/dues/get_dues`,
						params: {
							query: {
								// searchField: $("#search_Field_prize").val(),
								month: $("#month_select_choose").val(),
								year: $("#year_select_choose").val(),
								stat: $("#status_pay").val(),
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
				// {
				// 	field: "fullname",
				// 	title: "Full Name",
				// 	width: 120,
				// 	selector: false,
				// 	sortable: "asc",
				// 	textAlign: "left",
				// 	template: function (row, index, datatable) {
				// 		var html = "";
				// 		html = row.fullname;
				// 		return html;
				// 	},
				// },
				{
					field: "month_record",
					title: "Month",
					width: 80,
					selector: false,
					sortable: "asc",
					textAlign: "left",
				},
				{
					field: "year_record",
					title: "Year",
					width: 80,
					selector: false,
					sortable: "asc",
					textAlign: "left",
				},
				{
					field: "paid_amount",
					width: 100,
					title: "Amount",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						html =
							"<span style='color:#11af43;'><strong>Php " +
							row.paid_amount +
							"</strong></span>";
						return html;
					},
				},
				{
					field: "penalty",
					width: 100,
					title: "Penalty",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						var pen = row.penalty;
						if (pen != null) {
							html =
								"<span class='text-danger'><strong>Php " +
								pen +
								"</strong></span>";
						} else {
							html = "<span style='font-style:italic'>No Penalties</span>";
						}
						return html;
					},
				},
				{
					field: "duedate_record",
					width: 100,
					title: "Due Date",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						var duedate = row.duedate_record;
						var month = row.month_record;
						var year = row.year_record;
						html = month + " " + duedate + " , " + year;
						return html;
					},
				},
				{
					field: "status_record",
					width: 80,
					title: "Status",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						if (row.status_record == "pending") {
							html =
								"<span style='font-weight:500' class='text-info text-capitalize'>" +
								row.status_record +
								"</span>";
						} else {
							html =
								"<span style='font-weight:500' class='text-success text-capitalize'>" +
								row.status_record +
								"</span>";
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
							'<button data-email="' +
							row.email_add +
							'" data-record="' +
							row.id_record +
							'" data-id="' +
							row.id_ho +
							'" type="button" class="btn btn-primary view_records_class"> View </button>';
						return html;
					},
				},
			],
		};
		var datatable = $("#datatable_payment").mDatatable(options);
	};
	return {
		init: function (searchVal) {
			payment(searchVal);
		},
	};
})();

function initpayment() {
	$("#datatable_payment").mDatatable("destroy");
	displayPayment.init();
}
function initpayment_records(homeownerID) {
	$("#datatable_view_records").mDatatable("destroy");
	displayPayment_records.init(homeownerID);
}
function monthyear_opt_old(type) {
	var YearsOption = "";
	var MonthOption = "";
	var YearsOption2 = "";
	var MonthOption2 = "";
	const monthNames = [
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
	if (type == 1) {
		var YearsOption = $("#year_select");
		var MonthOption = $("#month_select");
	} else if (type == 3) {
		var YearsOption2 = $("#year_select_ho");
		var MonthOption2 = $("#month_select_ho");
	} else {
		var YearsOption = $("#year_select_choose");
		var MonthOption = $("#month_select_choose");
		var optionAll = $("<option />");
		optionAll.html("All");
		optionAll.val("All");
		var optionAll2 = $("<option />");
		optionAll2.html("All");
		optionAll2.val("All");
		YearsOption.append(optionAll);
		MonthOption.append(optionAll2);
	}
	//Determine the Current Year.
	var currentYear = new Date().getFullYear();
	//Loop and add the Year values to DropDownList.

	for (var i = currentYear; i >= 2000; i--) {
		var option = $("<option />");
		option.html(i);
		option.val(i);
		YearsOption.append(option);
	}
	// MONTH OPTION
	for (var m = 0; m < 12; m++) {
		let month = monthNames[m];
		let monthElem = document.createElement("option");
		monthElem.value = month;
		monthElem.textContent = month;
		MonthOption.append(monthElem);
	}
}
function monthyear_opt(type) {
	var YearsOption;
	var MonthOption;
	const monthNames = [
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

	if (type == 1) {
		YearsOption = $("#year_select");
		MonthOption = $("#month_select");
		var YearsOption2 = $("#year_select_ho");
		var MonthOption2 = $("#month_select_ho");
	} else {
		YearsOption = $("#year_select_choose");
		MonthOption = $("#month_select_choose");
		var optionAll = $("<option />");
		optionAll.html("All");
		optionAll.val("All");
		var optionAll2 = $("<option />");
		optionAll2.html("All");
		optionAll2.val("All");
		YearsOption.append(optionAll);
		MonthOption.append(optionAll2);
		YearsOption2 = $("#year_select_ho"); // Add this line to initialize YearsOption2
		MonthOption2 = $("#month_select_ho"); // Add this line to initialize MonthOption2
		var optionAll3 = $("<option />");
		optionAll3.html("All");
		optionAll3.val("All");
		YearsOption2.append(optionAll3);
		var optionAll4 = $("<option />");
		optionAll4.html("All");
		optionAll4.val("All");
		MonthOption2.append(optionAll4);
	}

	// Determine the Current Year.
	var currentYear = new Date().getFullYear();

	// Loop and add the Year values to DropDownList.
	for (var i = currentYear; i >= 2000; i--) {
		var option = $("<option />");
		option.html(i);
		option.val(i);
		YearsOption.append(option);
		YearsOption2.append(option.clone()); // Add this line to clone and append to YearsOption2
	}

	// MONTH OPTION
	for (var m = 0; m < 12; m++) {
		let month = monthNames[m];
		let monthElem = document.createElement("option");
		monthElem.value = month;
		monthElem.textContent = month;
		MonthOption.append(monthElem);
		MonthOption2.append(monthElem.cloneNode(true)); // Add this line to clone and append to MonthOption2
	}
}
// $("#create_billing_btn").on("click", function () {
// 	// $("#billing_title").text("CREATE BILLING");
// 	$("#create_billing_options").modal("show");
// 	// $("#create_billing_modal").data("type", 1);
// });

$("#create_specific_billing_btn").on("click", function () {
	$("#billing_title").text("CREATE SPECIFIC BILLING");
	$("#view_payment_records_modal").modal("hide");
	$("#create_billing_modal").data("type", 2);
	var homeownerid = $("#view_payment_records_modal").data("id");
	$("#create_billing_modal").data("id", homeownerid);
	$("#create_billing_modal").modal("show");
});
$("#submit_created_billing").on("submit", (e) => {
	e.preventDefault();
	$.ajax({
		type: "POST",
		url: `${base_url}/dues/create_billing_all`,
		data: {
			month: $("#month_select").val(),
			year: $("#year_select").val(),
		},
		cache: false,
		success: function (data) {
			let bill = JSON.parse(data);
			if (bill == "1" || bill == "4") {
				toastr.success(
					"Successfully created billing for " +
						$("#month_select").val() +
						" " +
						$("#year_select").val()
				);
				$("#create_billing_modal").modal("hide");
				initpayment();
			} else if (bill == "3") {
				toastr.warning(
					"You cannot add " +
						$("#month_select").val() +
						" " +
						$("#year_select").val() +
						" billing! There's no active homeowners detected without this billing record."
				);
			} else if (bill == "2") {
				toastr.success(
					"Successfully created billing for " +
						$("#month_select").val() +
						" " +
						$("#year_select").val() +
						" for detected homeowners without this billing record."
				);
				initpayment();
				$("#create_billing_modal").modal("hide");
			}
		},
	});
});
$("#submit_created_billing_ho").on("submit", (e) => {
	e.preventDefault();
	let ho_names = $("#select_ho_name").val();
	if (ho_names.length == 0) {
		toastr.error("Oops! please select homeowner names!");
	} else {
		$.ajax({
			type: "POST",
			url: `${base_url}/dues/create_billing_ho`,
			data: {
				month: $("#month_select_ho").val(),
				year: $("#year_select_ho").val(),
				homeowners: $("#select_ho_name").val(),
			},
			cache: false,
			success: function (data) {
				toastr.success(
					"Successfully created billing for " +
						$("#month_select_ho").val() +
						" " +
						$("#year_select_ho").val() +
						" for selected homeowners."
				);
				initpayment();
				$("#create_billing_modal_ho").modal("hide");
			},
		});
	}
});
$("#refresh_payment").on("click", ".view_records_class", function () {
	// initpayment_records($(this).data("id"));
	get_details_dues_per_homeowner($(this).data("id"), $(this).data("record"));
	$("#view_payment_records_modal").data("id", $(this).data("id"));
	$("#view_payment_records_modal").modal("show");
	$("#hidden_email").val($(this).data("email"));
});
function get_details_dues_per_homeowner(id, record_id) {
	$.ajax({
		type: "POST",
		url: baseUrl + "/dues/get_details_dues_per_homeowner",
		cache: false,
		data: {
			id: id,
			record_id,
		},
		success: function (res) {
			var result = JSON.parse(res);
			var penalty = result[0].penalty;
			var updated = result[0].date_updated;
			var receipt = result[0].receipt_num;
			if(receipt == null){
				$("#receipt_label_updated").text('- No Receipt Number -');
			}else{
				$("#receipt_label_updated").text(result[0].receipt_num);
			}
			let total = 0;
			$("#view_payment_records_modal").data("ho", id);
			$("#view_payment_records_modal").data("rec", record_id);
			$("#month_year_display").text(
				result[0].month_record + " " + result[0].year_record
			);
			$("#details_status").text(result[0].status_record);
			$("#details_name").text(
				result[0].fname + " " + result[0].mname + " " + result[0].lname
			);
			if (updated == null) {
					$("#details_date_updated").text("- No Date -");
			} else {
				var dateString = updated;
				var formattedDate = new Date(dateString).toLocaleString("en-US", {
					month: "long",
					day: "numeric",
					year: "numeric",
					hour: "numeric",
					minute: "numeric",
					hour12: true,
				});
				$("#details_date_updated").text(formattedDate);
			}
			$("#details_address").text(
				"Block " +
					result[0].block +
					", " +
					"Lot " +
					result[0].lot +
					",  " +
					result[0].village
			);
			$("#details_amount").text(result[0].paid_amount);
			if (penalty == null) {
				$("#details_penalty").text("NO PENALTY ADDED");
				$("#details_total").text(result[0].paid_amount);
				total = result[0].paid_amount;
			} else {
				$("#details_penalty").text(penalty);
				total = parseFloat(penalty) + parseFloat(result[0].paid_amount);
				total = total.toFixed(2);
				$("#details_total").text(total);
			}
			$("#view_payment_records_modal").data("total", total);
			// status
			if (result[0].status_record == "paid") {
				$("#set_to_paid").hide();
				$("#revert_to_pending").show();
				$("#delete_record").hide();
				$("#penalty_record").hide();
			} else {
				$("#set_to_paid").show();
				$("#revert_to_pending").hide();
				$("#delete_record").show();
				$("#penalty_record").show();
			}
		},
	});
}
$("#year_select_choose,#month_select_choose,#status_pay").on(
	"change",
	function () {
		initpayment();
	}
);
$("#refresh_view_records").on("click", ".remove_billing_home", function () {
	// $("#create_billing_modal").data("id")
	swal({
		title: `Are you sure you want to remove this payment record?`,
		text: "Action cannot be undone",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			delete_record($(this).data("id"));
		} else if (result.dismiss === "cancel") {
		}
	});
});

$("#refresh_view_records").on("click", ".confirm_payment", function () {
	swal({
		title: `Are you sure you want to CONFIRM this payment record as PAID?`,
		text: "Action cannot be undone",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			confirm_payment_record($(this).data("id"));
		} else if (result.dismiss === "cancel") {
		}
	});
});
function confirm_payment_record(bhomeid) {
	$.ajax({
		type: "POST",
		url: `${base_url}/payment/confirm_billing`,
		data: {
			id: bhomeid,
		},
		cache: false,
		success: function (data) {
			toastr.success("Billing PAID");
			var homeownerid = $("#view_payment_records_modal").data("id");
			initpayment_records(homeownerid);
		},
	});
}
function delete_record(bhomeid) {
	$.ajax({
		type: "POST",
		url: `${base_url}/payment/delete_billing`,
		data: {
			id: bhomeid,
		},
		cache: false,
		success: function (data) {
			toastr.success("Successfully Removed Billing");
			var homeownerid = $("#view_payment_records_modal").data("id");
			initpayment_records(homeownerid);
		},
	});
}
$("#refresh_view_records").on("click", ".send_reminder_email", function () {
	toastr.success("Successfully Sent Billing Reminders via Email.");
	$.ajax({
		type: "POST",
		url: `${base_url}/payment/email_sending_reminder`,
		data: {
			email: $("#hidden_email").val(),
			payment: $(this).data("payment"),
			month: $(this).data("month"),
			year: $(this).data("year"),
		},
		cache: false,
		success: function (data) {
			// success
		},
	});
});
$("#search_Field_prize").on("change", function () {
	initpayment();
});
$("#per_year_billing_btn").on("click", function () {
	$("#create_billing_options").modal("hide");
	$("#create_billing_modal").modal("show");
});
$("#per_homeowner_billing_btn").on("click", function () {
	$("#create_billing_options").modal("hide");
	$("#create_billing_modal_ho").modal("show");
	// get_homeowners();
	monthyear_opt(1);
	$("#sel_ho").hide();
	$("#create_billing_modal_ho").data("save", 0);
	$("#create_billing_modal_ho").data("exist", 0);
	$("#create_billing_modal_ho").data("bid", 0);
});
function get_homeowners_options(ho = []) {
	$.ajax({
		type: "POST",
		url: baseUrl + "/dues/fetch_homeowners_options",
		cache: false,
		data: {
			month: $("#month_select_ho").val(),
			year: $("#year_select_ho").val(),
		},
		success: function (res) {
			var result = JSON.parse(res);
			var stringx = "";
			if (result.return == 1) {
				$("#select_ho_name").prop("disabled", false);
				$.each(result.opt, function (key, data) {
					let selected = "";
					if (ho.length > 0) {
						selected = accounts.includes(data.id) ? "selected" : "";
					}
					stringx +=
						"<option value=" +
						data.id +
						" " +
						selected +
						">" +
						data.text +
						"</option>";
				});
			} else {
				$("#select_ho_name").prop("disabled", true);
				toastr.warning(
					"All Active homeowners where already added in this billing period."
				);
			}
			if (result.created == 1) {
				// assign billing id
				$("#create_billing_modal_ho").data("exist", 1);
				$("#create_billing_modal_ho").data("bid", result.bid);
			}
			$("#select_ho_name").html(stringx);
			$("#select_ho_name").trigger("change");
			$(".selectpicker").selectpicker("refresh");
		},
	});
}
$("#get_ho_options_btn").on("click", function () {
	get_homeowners_options();
	$("#sel_ho").show();
	$("#create_billing_modal_ho").data("save", 1);
});

$(".ho-select").on("change", function () {
	updateSelectHo();
});

function updateSelectHo() {
	$("#select_ho_name").trigger("change");
	$(".selectpicker").selectpicker("refresh");
	$("#select_ho_name").empty();
	// $("#select_ho_name").prop("disabled", true);
	$("#sel_ho").hide();
	$("#create_billing_modal_ho").data("save", 0);
}
$("#set_to_paid").on("click", function () {
	let record_id = $("#view_payment_records_modal").data("rec");
	let ho_id = $("#view_payment_records_modal").data("ho");
	let total = $("#view_payment_records_modal").data("total");
	swal({
		title: "Are you sure you this homeowner paid " + total + " ?",
		text: "this record will be marked as PAID",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			toastr.success("Successfully set record to PAID");
			// get_details_dues_per_homeowner(ho_id, record_id);
			update_status_record(ho_id, record_id, "paid");
			$("#set_to_paid").hide();
			$("#revert_to_pending").show();
		} else if (result.dismiss === "cancel") {
			toastr.error("Action Cancelled.");
		}
	});
});
$("#revert_to_pending").on("click", function () {
	let record_id = $("#view_payment_records_modal").data("rec");
	let ho_id = $("#view_payment_records_modal").data("ho");
	let total = $("#view_payment_records_modal").data("total");
	swal({
		title: "Are you sure you want to revert the " + total + " payment?",
		text: "this will marked again as PENDING",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			toastr.success("Successfully set record to PENDING");
			// get_details_dues_per_homeowner(ho_id, record_id);
			update_status_record(ho_id, record_id, "pending");
			$("#set_to_paid").show();
			$("#revert_to_pending").hide();
		} else if (result.dismiss === "cancel") {
			toastr.success("Action Cancelled.");
		}
	});
});
$("#delete_record").on("click", function () {
	let record_id = $("#view_payment_records_modal").data("rec");
	let ho_id = $("#view_payment_records_modal").data("ho");

	swal({
		title: "PAY ATTENTION PLEASE!",
		text: "Are you sure you want to delete this record? Kindly review as this cannot be undone again.",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			toastr.success("Successfully deleted record");
			$("#view_payment_records_modal").modal("hide");
			delete_record(record_id);
		} else if (result.dismiss === "cancel") {
			toastr.success("Action Cancelled.");
		}
	});
});
function delete_record(record_id) {
	$.ajax({
		type: "POST",
		url: `${base_url}/dues/delete_record`,
		data: {
			record_id,
		},
		cache: false,
		success: function (data) {
			initpayment();
		},
	});
}
function update_status_record(ho_id, record_id, status) {
	$.ajax({
		type: "POST",
		url: `${base_url}/dues/update_record_status`,
		data: {
			record_id,
			status,
		},
		cache: false,
		success: function (data) {
			get_details_dues_per_homeowner(ho_id, record_id);
			initpayment();
		},
	});
}
$("#penalty_record").on("click", function () {
	$("#penalty_modal").modal("show");
});
$("#save_penalty").on("click", function () {
	let record_id = $("#view_payment_records_modal").data("rec");
	let ho_id = $("#view_payment_records_modal").data("ho");
	let penalty = $("#penalty_input").val();
	// Check the input against the pattern
	if (!/^\d+(\.\d{0,2})?$/.test(penalty)) {
		toastr.error("Oops! type money equivalent numbers");
	} else {
		$.ajax({
			type: "POST",
			url: `${base_url}/dues/update_penalty`,
			data: {
				record_id,
				penalty: $("#penalty_input").val(),
			},
			cache: false,
			success: function (data) {
				toastr.success("Successfully added penalty");
				$("#penalty_modal").modal("hide");
				get_details_dues_per_homeowner(ho_id, record_id);
				initpayment();
			},
		});
	}
});
$("#view_ledger").on("click", function(){
     $.ajax({
            type: "POST",
            url: `${base_url}/dues/get_ledger_details`,
            cache: false,
            success: function (res) {
                var result = JSON.parse(res);
                displayLedger(result);
                $("#view_ledger_modal").modal("show");
            },
        });
})
function displayLedger(ledgerData) {
        var tableBody = $('#ledgerBody');
        var grandTotal = 0;

        // Clear existing data
        tableBody.empty();

        // Iterate through each record and append a new row to the table
        ledgerData.forEach(function (record) {
            // Handle null penalty by converting it to 0
            var penalty = parseFloat(record.penalty) || 0;

            var total = parseFloat(record.paid_amount) + penalty;
            grandTotal += total;

            // Append a new row to the table body
            tableBody.append(
                '<tr>' +
                '<td>' + record.month_record + ' ' + record.year_record + '</td>' +
                '<td>' + record.month_record + ' ' + record.duedate_record + '</td>' +
                '<td>' + parseFloat(record.paid_amount) + '</td>' +
                '<td>' + penalty + '</td>' +
                '<td>' + total + '</td>' +
                '</tr>'
            );
        });

        // Display grand total below the table
        $('#grandTotal').text('Total Balance: ' + grandTotal.toFixed(2));
    }
