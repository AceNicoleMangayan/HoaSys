var displayData = (function () {
	var ann = function (searchVal = "") {
		var options = {
			data: {
				type: "remote",
				source: {
					read: {
						method: "POST",
						url: `${base_url}/activity/get_activity`,
						params: {
							query: {
								searchField: $("#search_Field").val(),
								// status: $("#status_concern").val(),
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
					field: "id_log",
					width: 80,
					title: "Log #",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						html = "00000000" + row.id_log;
						html = html.slice(-8);
						return html;
					},
				},
				{
					field: "activity_description",
					title: "Activity Log",
					width: 300,
					selector: false,
					sortable: "asc",
					textAlign: "left",
					template: function (row, index, datatable) {
						var html = row.activity_description;
						return html;
					},
				},
				{
					field: "module",
					width: 90,
					title: "Module Type",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						html = row.module;
						return html;
					},
				},
				{
					field: "datetime_transaction",
					title: "Date of transaction",
					width: 200,
					selector: false,
					sortable: "asc",
					textAlign: "left",
					template: function (row, index, datatable) {
						var html = "";
						html = formatReadableDate(row.datetime_transaction);
						return html;
					},
				},
			],
		};
		var datatable = $("#datatable_activity").mDatatable(options);
	};
	return {
		init: function (searchVal) {
			ann(searchVal);
		},
	};
})();

function initactivity() {
	$("#datatable_activity").mDatatable("destroy");
	displayData.init();
}

$(function () {
	initactivity();
	$(".m_selectpicker").selectpicker();
});
$("#select_sequence,#select_sequence_update").select2({
	placeholder: "Select Sequence.",
	width: "100%",
});
$("#search_Field,#status_concern").on("change", function () {
	initactivity();
});

function formatReadableDate(dateString) {
	const options = {
		year: "numeric",
		month: "long",
		day: "numeric",
		hour: "numeric",
		minute: "numeric",
		// second: "numeric",
		// timeZoneName: "short",
	};
	const formattedDate = new Date(dateString).toLocaleDateString(
		"en-US",
		options
	);
	return formattedDate;
}
