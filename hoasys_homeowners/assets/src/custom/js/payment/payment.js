$(function () {
	initpayment();
	monthyear_opt(1);
	monthyear_opt(2);
	$(".m_selectpicker").selectpicker();

    $("#year_select,#year_select_choose").select2({
        placeholder: "Select Year",
        width: "100%",
    });
    $("#month_select,#month_select_choose").select2({
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
						url: `${base_url}/payment/get_payment`,
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
					field: "contact_num",
					title: "Contact Number",
					width: 80,
					selector: false,
					sortable: "asc",
					textAlign: "left",
				},
				{
					field: "email_add",
					title: "Email Address",
					width: 100,
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
					title: "Payment Records",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						html =
							'<button data-email="'+row.email_add+'" data-id="' +
							row.homeownerID +
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
var displayPayment_records = (function () {
	var payment = function (homeownerid, callback) {
		var options = {
			data: {
				type: "remote",
				source: {
					read: {
						method: "POST",
						url: `${base_url}/payment/get_payment_records`,
						params: {
							query: {
								searchField: $("#search_Field_prize").val(),
								id: homeownerid,
								month: $("#month_select_choose").val(),
								year: $("#year_select_choose").val(),
								stat: $("#status_pay").val()
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
					field: "month",
					title: "Month",
					width: 80,
					selector: false,
					sortable: "asc",
					textAlign: "left",
					template: function (row, index, datatable) {
						var html = "";
						html = row.month;
						return html;
					}
				},
				{
					field: "year",
					title: "Year",
					width: 80,
					selector: false,
					sortable: "asc",
					textAlign: "left",
					template: function (row, index, datatable) {
						var html = "";
						html = row.year;
						return html;
					}
				},
				{
					field: "payment",
					title: "Amount",
					width: 80,
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
					template: function (row, index, datatable) {
						var html = "";
						var cl = "";
						var st = row.status;
						if(st == "unpaid"){
							cl = "color:red";
						}else{
							cl = "color:green";
						}
						html = "<span class='' style='"+cl+"'>"+st+"</span>";
						return html;
					}
				},
				{
					field: "Actions",
					width: 200,
					title: "Actions",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						var st = row.status;
						if(st == "unpaid"){
							html =
							'<button data-id="' +
							row.bhomeID +
							'" data-payment="'+row.payment+'" data-month="'+row.month+'" data-year="'+row.year+'" type="button" class="btn btn-primary btn-sm send_reminder_email">Email </button>&nbsp;&nbsp;<button data-id="' +
							row.bhomeID +
							'" type="button" class="btn btn-success btn-sm confirm_payment"> Confirm</button>&nbsp;&nbsp;<button data-id="' +
							row.bhomeID+
							'" type="button" class="btn btn-outline-danger btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--outline-2x m-btn--pill m-btn--air remove_billing_home"><i class="fa fa-remove"></i></button>';
						}else{
							html = "<span>No available action</span>"
						}
						
						return html;
					},
				},
			],
		};
		var datatable = $("#datatable_view_records").mDatatable(options);
	};
	return {
		init: function (homeownerid, callback) {
			payment(homeownerid);
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
function monthyear_opt(type){
    var YearsOption = "";
    var MonthOption = "";
    const monthNames = ["January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
    ];
    if(type == 1){
        var YearsOption = $("#year_select");
        var MonthOption = $("#month_select");
    }else{
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
    var currentYear = (new Date()).getFullYear();
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
$("#create_billing_btn").on("click",function(){
	$("#billing_title").text("CREATE BILLING");
    $("#create_billing_modal").modal("show");
	$("#create_billing_modal").data("type", 1);
});
$("#create_specific_billing_btn").on("click", function(){
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
		url: `${base_url}/payment/create_billing`,
		data: {
			month: $("#month_select").val(),
            year: $("#year_select").val(),
			id: $("#create_billing_modal").data("id"),
			type: $("#create_billing_modal").data("type")
		},
		cache: false,
		success: function (data) {
			let bill = JSON.parse(data);
            if(bill == "1"){
                toastr.success("Successfully created billing for " + $("#month_select").val()+" "+ $("#year_select").val());
                $("#create_billing_modal").modal("hide");
            }else if(bill == "2"){
                toastr.warning("You cannot add " + $("#month_select").val()+" "+ $("#year_select").val() + " billing for each employee! There's no active homeowners detected.");
            }else{
                toastr.error("You cannot add " + $("#month_select").val()+" "+ $("#year_select").val() + " billing! this billing was already created.");
            }
		},
	});
});
$("#refresh_payment").on("click", ".view_records_class", function(){
	initpayment_records($(this).data("id"));
	$("#view_payment_records_modal").data("id",$(this).data("id"));
	$("#view_payment_records_modal").modal("show");
	$("#hidden_email").val($(this).data("email"));
});
$("#year_select_choose,#month_select_choose,#status_pay").on("change", function(){
	var homeownerid = $("#view_payment_records_modal").data("id");
	initpayment_records(homeownerid);
});
$("#refresh_view_records").on("click", ".remove_billing_home", function(){
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

$("#refresh_view_records").on("click", ".confirm_payment", function(){
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
function confirm_payment_record(bhomeid){
	$.ajax({
		type: "POST",
		url: `${base_url}/payment/confirm_billing`,
		data: {
			id: bhomeid
		},
		cache: false,
		success: function (data) {
                toastr.success("Billing PAID");
				var homeownerid = $("#view_payment_records_modal").data("id");
				initpayment_records(homeownerid);
		},
	});
}
function delete_record(bhomeid){
	$.ajax({
		type: "POST",
		url: `${base_url}/payment/delete_billing`,
		data: {
			id: bhomeid
		},
		cache: false,
		success: function (data) {
                toastr.success("Successfully Removed Billing");
				var homeownerid = $("#view_payment_records_modal").data("id");
				initpayment_records(homeownerid);
		},
	});
}
$("#refresh_view_records").on("click", ".send_reminder_email", function(){
	toastr.success("Successfully Sent Billing Reminders via Email.");
$.ajax({
	type: "POST",
	url: `${base_url}/payment/email_sending_reminder`,
	data: {
		email:$("#hidden_email").val(),
		payment:$(this).data("payment"),
		month:$(this).data("month"),
		year:$(this).data("year"),
	},
	cache: false,
	success: function (data) {
			// success
	},
});
})
$("#search_Field_prize").on("change", function(){
	initpayment();
});
