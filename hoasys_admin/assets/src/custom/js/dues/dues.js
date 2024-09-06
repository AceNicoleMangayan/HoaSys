$(function () {
	initpayment();
	// monthyear_opt(1);
	monthyear_opt(2);
	$(".m_selectpicker").selectpicker();

	$(
		"#year_select,#year_select_choose, #year_select_ho,#year_select_send,#year_select_all_bill"
	).select2({
		placeholder: "Select Year",
		width: "100%",
	});
	$(
		"#month_select,#month_select_choose, #month_select_ho,#month_select_send"
	).select2({
		placeholder: "Select Month",
		width: "100%",
	});
	// Initialize block_num dropdown
	initializeDropdown_block("#block_num");
	// Initialize lot_num dropdown
	initializeDropdown_lot("#lot_num");
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
								searchField: $("#search_Field_prize").val(),
								month: $("#month_select_choose").val(),
								year: $("#year_select_choose").val(),
								stat: $("#status_pay").val(),
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
					field: "fullname",
					title: "Full Name",
					width: 130,
					selector: false,
					sortable: "asc",
					// textAlign: "left",
					template: function (row, index, datatable) {
						var html = "";
						html =
							'<span class="text-success show_ledger_details" data-name="' +
							row.fullname +
							'" data-id="' +
							row.id_ho +
							'" style="text-decoration: underline;cursor:pointer">' +
							row.fullname +
							"<span>";
						return html;
					},
				},
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
// function monthyear_opt(type) {
// 	var YearsOption;
// 	var MonthOption;
// 	var YearsOption2;
// 	var MonthOption2;
// 	var YearsOptionSend;
// 	var MonthOptionSend;
// 	const monthNames = [
// 		"January",
// 		"February",
// 		"March",
// 		"April",
// 		"May",
// 		"June",
// 		"July",
// 		"August",
// 		"September",
// 		"October",
// 		"November",
// 		"December",
// 	];

// 	if (type == 1) {
// 		YearsOption = $("#year_select");
// 		MonthOption = $("#month_select");
// 		YearsOption2 = $("#year_select_ho");
// 		MonthOption2 = $("#month_select_ho");
// 		YearsOptionSend = $("#year_select_send");
// 		MonthOptionSend = $("#month_select_send");
// 	} else {
// 		YearsOption = $("#year_select_choose");
// 		MonthOption = $("#month_select_choose");
// 		YearsOption2 = $("#year_select_ho");
// 		MonthOption2 = $("#month_select_ho");
// 		YearsOptionSend = $("#year_select_send");
// 		MonthOptionSend = $("#month_select_send");

// 		// Clear existing options
// 		YearsOption.empty();
// 		MonthOption.empty();

// 		// Add "All" option
// 		var optionAll_month = $("<option />");
// 		optionAll_month.html("All Months");
// 		optionAll_month.val("All");

// 		// Add "All" option
// 		var optionAll_year = $("<option />");
// 		optionAll_year.html("All Years");
// 		optionAll_year.val("All");

// 		YearsOption.append(optionAll_year);

// 		// Clone and append to YearsOption2
// 		YearsOption2.empty();
// 		YearsOption2.append(optionAll_year.clone());

// 		MonthOption.append(optionAll_month.clone());

// 		// Clone and append to MonthOption2
// 		MonthOption2.empty();
// 		MonthOption2.append(optionAll_month.clone());

// 		// Clone and append to YearsOptionSend
// 		YearsOptionSend.empty();
// 		YearsOptionSend.append(optionAll_year.clone());

// 		// Clone and append to MonthOptionSend
// 		MonthOptionSend.empty();
// 		MonthOptionSend.append(optionAll_month.clone());
// 	}

// 	// Determine the Current Year.
// 	var currentYear = new Date().getFullYear();

// 	// Loop and add the Year values to DropDownList.
// 	for (var i = currentYear; i >= 2000; i--) {
// 		var option = $("<option />");
// 		option.html(i);
// 		option.val(i);
// 		YearsOption.append(option);

// 		// Clone and append to YearsOption2
// 		YearsOption2.append(option.clone());

// 		// Clone and append to YearsOptionSend
// 		YearsOptionSend.append(option.clone());
// 	}

// 	// MONTH OPTION
// 	for (var m = 0; m < 12; m++) {
// 		let month = monthNames[m];

// 		// Create separate option elements for each set
// 		let monthElem = $("<option />");
// 		monthElem.val(month);
// 		monthElem.text(month);

// 		MonthOption.append(monthElem);

// 		// Clone and append to MonthOption2
// 		let monthElem2 = monthElem.clone();
// 		MonthOption2.append(monthElem2);

// 		// Clone and append to MonthOptionSend
// 		let monthElemSend = monthElem.clone();
// 		MonthOptionSend.append(monthElemSend);
// 	}
// }
function monthyear_opt(type) {
	var YearsOption;
	var MonthOption;
	var YearsOption2;
	var MonthOption2;
	var YearsOptionSend;
	var MonthOptionSend;
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
		YearsOption2 = $("#year_select_ho");
		MonthOption2 = $("#month_select_ho");
		YearsOptionSend = $("#year_select_send");
		MonthOptionSend = $("#month_select_send");
	} else {
		YearsOption = $("#year_select_choose");
		MonthOption = $("#month_select_choose");
		YearsOption2 = $("#year_select_ho");
		MonthOption2 = $("#month_select_ho");
		YearsOptionSend = $("#year_select_send");
		MonthOptionSend = $("#month_select_send");

		// Clear existing options
		YearsOption.empty();
		MonthOption.empty();

		// Add "All" option for years
		var optionAllYear = $("<option />");
		optionAllYear.html("All Years");
		optionAllYear.val("All");

		YearsOption.append(optionAllYear);
		YearsOption2.append(optionAllYear.clone());
		YearsOptionSend.append(optionAllYear.clone());

		// Add "All" option for months
		var optionAllMonth = $("<option />");
		optionAllMonth.html("All Months");
		optionAllMonth.val("All");

		MonthOption.append(optionAllMonth);
		MonthOption2.append(optionAllMonth.clone());
		MonthOptionSend.append(optionAllMonth.clone());
	}

	// Add new year select with id "year_select_all_bill"
	var YearsOptionAllBill = $("#year_select_all_bill");
	YearsOptionAllBill.empty();

	// Determine the Current Year.
	var currentYear = new Date().getFullYear();

	// Loop and add the Year values to DropDownList.
	for (var i = currentYear; i >= 2000; i--) {
		var option = $("<option />");
		option.html(i);
		option.val(i);
		YearsOption.append(option);

		// Clone and append to YearsOption2
		YearsOption2.append(option.clone());

		// Clone and append to YearsOptionSend
		YearsOptionSend.append(option.clone());

		// Append to YearsOptionAllBill
		YearsOptionAllBill.append(option.clone());
	}

	// MONTH OPTION
	for (var m = 0; m < 12; m++) {
		let month = monthNames[m];

		// Create separate option elements for each set
		let monthElem = $("<option />");
		monthElem.val(month);
		monthElem.text(month);

		MonthOption.append(monthElem);

		// Clone and append to MonthOption2
		let monthElem2 = monthElem.clone();
		MonthOption2.append(monthElem2);

		// Clone and append to MonthOptionSend
		let monthElemSend = monthElem.clone();
		MonthOptionSend.append(monthElemSend);
	}
}

$("#create_billing_btn").on("click", function () {
	// $("#billing_title").text("CREATE BILLING");
	$("#create_billing_options").modal("show");
	// $("#create_billing_modal").data("type", 1);
});

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
			let total = 0;
			$("#view_payment_records_modal").data("ho", id);
			$("#view_payment_records_modal").data("rec", record_id);
			if(receipt == null){
				$("#receipt_label_updated").text('- No Receipt Number -');
			}else{
				$("#receipt_label_updated").text(result[0].receipt_num);
			}
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
				$("#details_penalty").text("- No Penalty Added -");
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
				$("#send_billing_btn").hide();
				$("#revert_to_pending").show();
				$("#delete_record").hide();
				$("#penalty_record").hide();
				$("#pay_record").hide();
			} else {
				$("#set_to_paid").show();
				$("#send_billing_btn").show();
				$("#revert_to_pending").hide();
				$("#delete_record").show();
				$("#penalty_record").show();
				$("#pay_record").show();
			}
		},
	});
}
$(
	"#year_select_choose,#month_select_choose,#status_pay,#block_num,#lot_num"
).on("change", function () {
	initpayment();
});
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
	monthyear_opt(1);
});
$("#create_per_year_bill").on("click", function () {
	$("#create_billing_options").modal("hide");
	$("#create_billing_year_modal").modal("show");
});
$("#per_homeowner_billing_btn").on("click", function () {
	$("#create_billing_options").modal("hide");
	$("#create_billing_modal_ho").modal("show");
	// get_homeowners();
	// monthyear_opt(1);
	$("#sel_ho").hide();
	$("#create_billing_modal_ho").data("save", 0);
	$("#create_billing_modal_ho").data("exist", 0);
	$("#create_billing_modal_ho").data("bid", 0);
});

function get_homeowners_options(ho = []) {
	let m = $("#month_select_ho").val();
	let y = $("#year_select_ho").val();
	if (y == "All" || m == "All") {
		toastr.error("Please select Specific Year and Month.");
	} else {
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
			// toastr.success("Successfully set record to PAID");
			// get_details_dues_per_homeowner(ho_id, record_id);
			$("#receipt_modal").modal("show");
			$("#receipt_modal").data("ho", ho_id);
			$("#receipt_modal").data("rec", record_id);
			$("#receipt_modal").data("stat", "paid");
			// update_status_record(ho_id, record_id, "paid");
			// $("#set_to_paid").hide();
			// $("#send_billing_btn").hide();
			// $("#revert_to_pending").show();
		} else if (result.dismiss === "cancel") {
			toastr.error("Action Cancelled.");
		}
	});
});
$("#save_receipt").on("click", function () {
	let ho_id = $("#receipt_modal").data("ho");
	let record_id = $("#receipt_modal").data("rec");
	let stat = $("#receipt_modal").data("stat");
	let receipt = $("#receipt_input").val();
	if (receipt.trim() !== "") {
		// Receipt is not empty, proceed with further actions
		update_status_record(ho_id, record_id, stat, receipt);
		$("#set_to_paid").hide();
		$("#send_billing_btn").hide();
		$("#revert_to_pending").show();
		$("#receipt_modal").modal("hide");
		toastr.success("Successfully set record to PAID");
	} else {
		// Receipt is empty
		toastr.error("Kindly input the receipt number.");
	}
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
			delete_record_dues(record_id, ho_id);
		} else if (result.dismiss === "cancel") {
			toastr.success("Action Cancelled.");
		}
	});
});

function delete_record_dues(record_id, ho_id) {
	$("#loading-overlay").show();
	$.ajax({
		type: "POST",
		url: `${base_url}/dues/delete_record`,
		data: {
			record_id,
			ho_id,
		},
		cache: false,
		success: function (data) {
			initpayment();
		},
		complete: function () {
			// Hide loading overlay after the AJAX call is completed
			$("#loading-overlay").hide();
		},
	});
}

function update_status_record(ho_id, record_id, status, receipt = null) {
	$("#loading-overlay").show();
	$.ajax({
		type: "POST",
		url: `${base_url}/dues/update_record_status`,
		data: {
			record_id,
			ho_id,
			status,
			receipt,
		},
		cache: false,
		success: function (data) {
			$("#receipt_input").val("");
			get_details_dues_per_homeowner(ho_id, record_id);
			initpayment();
		},
		complete: function () {
			// Hide loading overlay after the AJAX call is completed
			$("#loading-overlay").hide();
		},
	});
}
$("#penalty_record").on("click", function () {
	$("#penalty_modal").modal("show");
});
$("#pay_record").on("click", function () {
	let amount = $("#details_amount").text();
	$("#pay_input").val(amount);
	$("#pay_modal").modal("show");
});
$("#save_penalty").on("click", function () {
	let record_id = $("#view_payment_records_modal").data("rec");
	let ho_id = $("#view_payment_records_modal").data("ho");
	let penalty = $("#penalty_input").val();
	// Check the input against the pattern
	if (!/^\d+(\.\d{0,2})?$/.test(penalty)) {
		toastr.error("Oops! type money equivalent numbers");
	} else {
		$("#loading-overlay").show();
		$.ajax({
			type: "POST",
			url: `${base_url}/dues/update_penalty`,
			data: {
				record_id,
				ho_id,
				penalty: $("#penalty_input").val(),
			},
			cache: false,
			success: function (data) {
				toastr.success("Successfully added penalty");
				$("#penalty_modal").modal("hide");
				get_details_dues_per_homeowner(ho_id, record_id);
				initpayment();
			},
			complete: function () {
				// Hide loading overlay after the AJAX call is completed
				$("#loading-overlay").hide();
			},
		});
	}
});
$("#save_pay").on("click", function () {
	let record_id = $("#view_payment_records_modal").data("rec");
	let ho_id = $("#view_payment_records_modal").data("ho");
	let amount = $("#pay_input").val();
	// Check the input against the pattern
	if (!/^\d+(\.\d{0,2})?$/.test(amount)) {
		toastr.error("Oops! type money equivalent numbers");
	} else {
		swal({
			title: "Are you sure you want to change the amount of this billing?",
			text: "The homeowner will receive via email of your action.",
			type: "question",
			showCancelButton: true,
			confirmButtonText: "Yes",
			cancelButtonText: `No`,
		}).then((result) => {
			if (result.value) {
				$("#loading-overlay").show();
				$.ajax({
					type: "POST",
					url: `${base_url}/dues/update_amount`,
					data: {
						record_id,
						ho_id,
						amount,
					},
					cache: false,
					success: function (data) {
						toastr.success(
							"Successfully changed the amount value of this billing record."
						);
						$("#pay_modal").modal("hide");
						get_details_dues_per_homeowner(ho_id, record_id);
						initpayment();
					},
					complete: function () {
						// Hide loading overlay after the AJAX call is completed
						$("#loading-overlay").hide();
					},
				});
			} else if (result.dismiss === "cancel") {
			}
		});
	}
});
$("#send_billing_btn").on("click", function () {
	let record_id = $("#view_payment_records_modal").data("rec");
	let ho_id = $("#view_payment_records_modal").data("ho");
	swal({
		title: "Are you sure you want to send your billing reminder?",
		text: "The homeowner will receive via email.",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			send_billing_dues(record_id, ho_id);
			$("#view_payment_records_modal").modal("hide");
			toastr.success("Successfully sent billing reminder to the homeowner.");
		} else if (result.dismiss === "cancel") {
		}
	});
});

function send_billing_dues(record_id, ho_id) {
	$("#loading-overlay").show();
	$.ajax({
		type: "POST",
		url: `${base_url}/dues/send_billing_dues`,
		data: {
			record_id,
			ho_id,
		},
		cache: false,
		success: function (data) {
			// $("#view_payment_records_modal").modal("hide");
		},
		complete: function () {
			// Hide loading overlay after the AJAX call is completed
			$("#loading-overlay").hide();
		},
	});
}
$("#download_dues_report").on("click", function () {
	var todate = moment().format("DD-MM-YYYY HH:mm:ss");
	var month = $("#month_select_choose").val();
	var year = $("#year_select_choose").val();
	var status = $("#status_pay").val();
	var search = $("#search_Field_prize").val();
	var block = $("#block_num").val();
	var lot = $("#lot_num").val();

	if (year == "All") {
		toastr.warning("Please select Year when exporting Dues info.");
	} else {
		$.ajax({
			url: `${base_url}/dues/download_dues_report`,
			method: "POST",
			data: {
				month,
				year,
				status,
				search,
				block,
				lot,
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
				a.download =
					"Hoasys-dues-report-" + todate + "-" + month + "-" + year + ".xlsx";
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
	}
});

$("#send_billing_dues").on("click", function () {
	$("#send_notif_modal").modal("show");
	$("#create_billing_options").modal("hide");
	// monthyear_opt(3);
});

$("#submit_send_notif").on("submit", (e) => {
	e.preventDefault();
	let month = $("#month_select_send").val();
	let year = $("#year_select_send").val();
	if (month == "All" || year == "All") {
		toastr.error("Oops! please select specific Year and Month!");
	} else {
		$("#loading-overlay").show();
		$.ajax({
			type: "POST",
			url: `${base_url}/dues/send_batch_emails`,
			dataType: "json",
			data: {
				month,
				year,
			},
			cache: false,
			success: function (data) {
				// console.log(data);
				if (data == "1") {
					toastr.success(
						"Successfully sent billing reminders to homeowners for " +
							month +
							" " +
							year +
							"."
					);
					$("#send_notif_modal").modal("hide");
				} else {
					toastr.error(
						"Cannot send due reminders since NO homeowners are created billing for " +
							month +
							" " +
							year +
							". Please create record billing first for homeowners. "
					);
				}
			},
			complete: function () {
				// Hide loading overlay after the AJAX call is completed
				$("#loading-overlay").hide();
			},
		});
	}
});
$("#submit_created_year_billing").on("submit", (e) => {
	e.preventDefault();
	let year = $("#year_select_all_bill").val();
	$("#loading-overlay").show();
	$.ajax({
		type: "POST",
		url: `${base_url}/dues/create_billing_all_year`,
		dataType: "json",
		data: {
			year,
		},
		cache: false,
		success: function (data) {},
		complete: function () {
			// Hide loading overlay after the AJAX call is completed
			$("#loading-overlay").hide();
			initpayment();
			toastr.success(
				"Successfully created billings to all homeowners for " + year + "."
			);
			$("#create_billing_year_modal").modal("hide");
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
$("#refresh_payment").on("click", ".show_ledger_details", function () {
	// initpayment_records($(this).data("id"));
	let id = $(this).data("id");
	let fullname = $(this).data("name");
	$.ajax({
		type: "POST",
		url: `${base_url}/dues/get_ledger_details`,
		cache: false,
		data: {
			id,
		},
		success: function (res) {
			var result = JSON.parse(res);
			$("#name_ledger").text(fullname);
			displayLedger(result);
			$("#view_ledger_modal").modal("show");
		},
	});
});
function displayLedger(ledgerData) {
	var tableBody = $("#ledgerBody");
	var grandTotal = 0;

	// Clear existing data
	tableBody.empty();

	// Iterate through each record and append a new row to the table
	ledgerData.forEach(function (record) {
		// Handle null penalty by converting it to 0
		var penalty = parseFloat(record.penalty) || 0;

		var total = parseFloat(record.paid_amount) + penalty;
		var formattedTotal = total.toFixed(2); // Formatting total to two decimal places
		grandTotal += total;

		// Append a new row to the table body
		tableBody.append(
			"<tr>" +
				"<td>" +
				record.month_record +
				" " +
				record.year_record +
				"</td>" +
				"<td>" +
				record.month_record +
				" " +
				record.duedate_record +
				"</td>" +
				"<td>" +
				parseFloat(record.paid_amount) +
				"</td>" +
				"<td>" +
				penalty +
				"</td>" +
				"<td>" +
				formattedTotal +
				"</td>" +
				"</tr>"
		);
	});

	// Display grand total below the table
	$("#grandTotal").text("Total Balance: " + grandTotal.toFixed(2));
}
