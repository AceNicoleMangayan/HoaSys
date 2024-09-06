var displayData = (function () {
	var ann = function (searchVal = "") {
		var options = {
			data: {
				type: "remote",
				source: {
					read: {
						method: "POST",
						url: `${base_url}/election/get_voters`,
						params: {
							query: {
								searchField: $("#search_voter").val(),
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
					field: "fname",
					title: "Voter's Name",
					width: 150,
					selector: false,
					sortable: "asc",
					textAlign: "left",
					template: function (row, index, datatable) {
						var html = row.fname+" "+row.lname;
						return html;
					},
				},
				{
					field: "election_title",
					width: 150,
					title: "Election Title",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = row.election_title;
						return html;
					},
				},
				{
					field: "datetime_voted",
					width: 150,
					title: "Date Time Voted",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						html = row.datetime_voted;
						return html;
					},
				},
			],
		};
		var datatable = $("#datatable_voters").mDatatable(options);
	};
	return {
		init: function (searchVal) {
			ann(searchVal);
		},
	};
})();

function initvoter() {
	$("#datatable_voters").mDatatable("destroy");
	displayData.init();
}
$(function () {
	initvoter();
	$(".m_selectpicker").selectpicker();
});

$("#search_voter").on("change", function () {
	initvoter();
});
