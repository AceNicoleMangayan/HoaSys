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
					field: "name",
					title: "Full Name",
					width: 120,
					selector: false,
					sortable: "asc",
					textAlign: "left",
					template: function (row, index, datatable) {
						var html = "";
						html = row.firstname+" "+row.lastname;
						return html;
					}
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
					field: "contact_num",
					title: "Contact Number",
					width: 100,
					selector: false,
					sortable: "asc",
					textAlign: "left",
				},
				{
					field: "email_add",
					title: "Email Address",
					width: 120,
					selector: false,
					sortable: "asc",
					textAlign: "left",
				},
				{
					field: "status",
					title: "Status",
					width: 80,
					selector: false,
					sortable: "asc",
					textAlign: "left",
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
							row.homeownerID +
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
// Major
var displayData_major = (function () {
	var prize = function (searchVal = "") {
		var options = {
			data: {
				type: "remote",
				source: {
					read: {
						method: "POST",
						url: `${base_url}/homeowners/get_prizes_major`,
						params: {
							query: {
								searchField: $("#search_Field_prize_major").val(),
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
					title: "Prize",
					width: 120,
					selector: false,
					sortable: "asc",
					textAlign: "left",
				},
				{
					field: "winners",
					title: "Winners",
					width: 80,
					selector: false,
					sortable: "asc",
					textAlign: "left",
				},
				{
					field: "sequence",
					title: "Sequence",
					width: 80,
					selector: false,
					sortable: "asc",
					textAlign: "left",
				},
				{
					field: "isActive",
					title: "Active",
					width: 100,
					selector: false,
					sortable: "asc",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						var checked = "";
						var disabled = "";
						if (row.isActive == 1) {
							checked = "checked";
						}
						if (row.dateTimeSpin != null) {
							disabled = "disabled";
						} else {
							disabled = "";
						}
						html =
							'<div class="col-3"><span class="m-switch m-switch--outline m-switch--icon m-switch--success"><label><input class="tog_active" data-id="' +
							row.prize_ID +
							'" data-major="' +
							row.major +
							'" type="checkbox" ' +
							disabled +
							' name="" ' +
							checked +
							"><span></span></label></span></div>";
						return html;
					},
				},
				{
					field: "dateTimeSpin",
					title: "Date-time Spinned",
					width: 100,
					selector: false,
					sortable: "asc",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						if (row.dateTimeSpin == null) {
							html = "<span style='font-style:italic;'>Not Yet Spinned</span>";
						} else {
							html =
								'<span style="font-weight:bold">' +
								row.dateTimeSpin +
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
							'<button data-id="' +
							row.prize_ID +
							'" type="button" class="btn btn-outline-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--outline-2x m-btn--pill m-btn--air update_homeowners"><i class="fa fa-pencil"></i></button>';
						return html;
					},
				},
			],
		};
		var datatable = $("#datatable_prizes_major").mDatatable(options);
	};
	return {
		init: function (searchVal) {
			prize(searchVal);
		},
	};
})();
function initprizes() {
	$("#datatable_homeowners").mDatatable("destroy");
	displayData.init();
}
function initprizes_major() {
	$("#datatable_prizes_major").mDatatable("destroy");
	displayData_major.init();
}

$(function () {
	initprizes();
	$(".m_selectpicker").selectpicker();
});
// Button triggers
$("#MajorP_btn").on("click", function () {
	initprizes_major();
});
$("#MinorP_btn").on("click", function () {
	initprizes();
});
$("#select_sequence,#select_sequence_update").select2({
	placeholder: "Select Sequence.",
	width: "100%",
});
$("#search_Field_prize").on("change", function () {
	initprizes();
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
	$.ajax({
		type: "POST",
		url: `${base_url}/homeowners/save_homeowner`,
		data: {
			fname: $("#first_name").val(),
			lname: $("#last_name").val(),
			block: $("#block").val(),
			lot: $("#lot").val(),
			contact_number: $("#contact_number").val(),
			email_address: $("#email_address").val(),
			password: $("#pass").val(),
			payment: $("#monthly_payment").val(),
			duedate: $("#due_date_payment").val()
		},
		cache: false,
		success: function (data) {
			var major = $("#Major_prize").val();
			$("#datatable_homeowners").mDatatable("reload");
			toastr.success("Successfully added " + $("#first_name").val()+" "+ $("#last_name").val());
			$("#raffle_modal").modal("hide");
		},
	});
});

$("#refresh_homeowners").on("click",".update_Homeowners_info", function(){
let homeowner_id = $(this).data("id");
$("#homeowner_ID_update").val(homeowner_id);
$.ajax({
	type: "POST",
	url: `${base_url}/homeowners/get_homeowner_details`,
	data: {
		id: homeowner_id
	},
	cache: false,
	success: function (data) {
		let homeowner = JSON.parse(data);
		$("#homeowner_update_modal").modal("show");
		$("#first_name_up").val(homeowner[0].firstname);
		$("#last_name_up").val(homeowner[0].lastname);
		$("#block_up").val(homeowner[0].block);
		$("#lot_up").val(homeowner[0].lot);
		$("#contact_number_up").val(homeowner[0].contact_num);
		$("#email_address_up").val(homeowner[0].email_add);
		$("#pass_up").val(homeowner[0].password);
		$('#status_up').selectpicker('val', homeowner[0].status);
		$("#monthly_payment_up").val(homeowner[0].payment);
		$("#due_date_payment_up").val(homeowner[0].duedate);
	},
});
});

$("#submit_homeowner_update").on("submit", (e) => {
	e.preventDefault();
	let homeownerid = $("#homeowner_ID_update").val();
	$.ajax({
		type: "POST",
		url: `${base_url}/homeowners/update_homeowner`,
		data: {
			id: homeownerid,
			status: $("#status_up").val(),
			fname: $("#first_name_up").val(),
			lname: $("#last_name_up").val(),
			block: $("#block_up").val(),
			lot: $("#lot_up").val(),
			contact_number: $("#contact_number_up").val(),
			email_address: $("#email_address_up").val(),
			password: $("#pass_up").val(),
			payment: $("#monthly_payment_up").val(),
			due: $("#due_date_payment_up").val()
		},
		cache: false,
		success: function (data) {
			$("#datatable_homeowners").mDatatable("reload");
			toastr.success("Successfully updated " + $("#first_name_up").val()+" "+ $("#last_name_up").val());
			$("#homeowner_update_modal").modal("hide");
		},
	});
});