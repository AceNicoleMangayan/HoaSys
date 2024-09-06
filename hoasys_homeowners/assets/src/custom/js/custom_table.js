var table_ajax_req = [];
$.extend({
	table_scroll: function (table_id, on_sort, end_scroll) {
		let sort_type = "none";
		let sort_col = null;
		let sort_data = [];
		let col_width = [];
		$("#" + table_id).data("sorttype", "none");
		$("#" + table_id).data("sortcol", null);
		$("#" + table_id).on("click", ".col_sort", function () {
			if (sort_type == "none" && sort_col == null) {
				sort_type = "asc";
				$(this).removeClass("fa-unsorted");
				$(this).addClass("fa-sort-asc");
				sort_col = $(this).attr("id");
			} else {
				if ($(this).attr("id") == sort_col) {
					if (sort_type == "asc") {
						sort_type = "desc";
						$(this).removeClass("fa-sort-asc");
						$(this).addClass("fa-sort-desc");
					} else if (sort_type == "desc") {
						sort_type = "none";
						$(this).removeClass("fa-sort-desc");
						$(this).addClass("fa-unsorted");
					} else {
						sort_type = "asc";
						$(this).removeClass("fa-unsorted");
						$(this).addClass("fa-sort-asc");
					}
				} else {
					$("table").find(".col_sort").removeClass("fa-sort-asc");
					$("table").find(".col_sort").removeClass("fa-sort-desc");
					$("table").find(".col_sort").addClass("fa-unsorted");
					sort_type = "asc";
					$(this).removeClass("fa-unsorted");
					$(this).addClass("fa-sort-asc");
					sort_col = $(this).attr("id");
				}
			}
			sort_data["type"] = sort_type;
			sort_data["col"] = sort_col;
			$("#" + table_id).data("sorttype", sort_type);
			$("#" + table_id).data("sortcol", sort_col);
			on_sort(sort_data);
		});
		// COLUMN WIDTH
		col_w = "";
		$($("#" + table_id).find("thead"))
			.find("th")
			.map(function () {
				col_w = $(this).attr("class");
				if (col_w != "") {
					col_class = col_w.split("-");
					$(this).css("width", col_class[1] + "%");
				}
			});
		$("#" + table_id)
			.find("table")
			.css("table-layout", "fixed");
		$($("#" + table_id).find("tbody"))
			.find("th")
			.map(function () {
				col_w = $(this).attr("class");
				if (col_w != "") {
					col_class = col_w.split("-");
					$(this).css("width", col_class[1] + "%");
				}
			});
		// TABLE SCROLL
		$("#" + table_id).scroll(function () {
			scroll = $(this).scrollTop() + $(this).innerHeight();
			scroll_height = $(this)[0].scrollHeight;
			if (
				$(this).scrollTop() + $(this).innerHeight() >=
				$(this)[0].scrollHeight
			) {
				end_scroll(scroll_height);
			}
		});
		// ALL CHECK CHECKBOX
		$("#" + table_id)
			.find("." + table_id + "_all_check")
			.on("click", function () {
				let checkboxes = [];
				let checked_items = [];
				checkboxes = $("#" + table_id).find("." + table_id + "_row_check");
				if (
					$("#" + table_id)
						.find("." + table_id + "_all_check")
						.is(":checked")
				) {
					$("#" + table_id)
						.find("." + table_id + "_row_check")
						.prop("checked", true);
					// console.log($('#'+table_id).find('.'+table_id+'_row_check'));
					// $('#'+table_id).find('.'+table_id+'_row_check').map(function () {
					//     return $(this).prop('checked', true);
					// });
					// console.log(checked_items);
					$($("#" + table_id).find("tbody"))
						.find("tr")
						.css("background-color", "#f0f0f0");
				} else {
					$("#" + table_id)
						.find("." + table_id + "_row_check")
						.prop("checked", false);
					// console.log($('#'+table_id).find('.'+table_id+'_row_check'));
					// $('#'+table_id).find('.'+table_id+'_row_check').map(function () {
					//     return $(this).prop('checked', false);
					// });
					$($("#" + table_id).find("tbody"))
						.find("tr")
						.removeAttr("style");
				}
			});
		$("#" + table_id)
			.find("tbody")
			.on("click", "input", function () {
				// console.log($(this).is(':checked'));
				let checked_items = [];
				if ($(this).is(":checked")) {
					$(this).parents("tr").css("background-color", "#f0f0f0");
				} else {
					$(this).parents("tr").removeAttr("style");
					$($("#" + table_id).find("table"))
						.find("." + table_id + "_all_check")
						.prop("checked", false);
				}
				checked_items = $($("#" + table_id).find("tbody"))
					.find("input:checked")
					.map(function () {
						return $(this);
					})
					.get();
				if (checked_items.length < 1) {
					$($("#" + table_id).find("tbody"))
						.find("tr")
						.removeAttr("style");
					$($("#" + table_id).find("table"))
						.find("." + table_id + "_all_check")
						.prop("checked", false);
				}
			});
	},
});
const no_table_record_display = (table_id) => {
	$("#" + table_id)
		.find("table")
		.hide();
	$("#" + table_id)
		.find("#no_" + table_id)
		.fadeIn();
};
const custom_table_ajax = (table_id, first_init_bool = 1, callback) => {
	let current_table = $("#" + table_id).data("tableoptions");
	let checked_rows = $("#" + table_id).data("check_rows");
	let limit_increment = 0;
	if (!first_init_bool) {
		limit_increment =
			parseInt($("#" + table_id + "_items").data("limit")) +
			current_table.row_limit;
	}
	$("#" + table_id + "_items").data("limit", limit_increment);
	current_table.ajax_params.limit = $("#" + table_id + "_items").data("limit");
	let table_body_elem = $("#" + table_id).find("body");
	// if (table_ajax_req[table_id] != null){
	//     table_ajax_req[table_id].abort();
	//     mApp.unblock('#'+table_id);
	// }
	// table_ajax_req[table_id] = $.ajax({
	$.ajax({
		type: "POST",
		url: current_table.ajax_url,
		data: current_table.ajax_params,
		cache: false,
		dataType: "json",
		beforeSend: function () {
			mApp.block("#" + table_id, {
				overlayColor: "#000000",
				type: "loader",
				state: "success",
				size: "lg",
				centerY: false,
				centerX: false,
				css: {
					position: "absolute",
					margin: "auto",
				},
			});
		},
		success: function (data) {
			mApp.unblock("#" + table_id);
			record_rows = [];
			record_rows = data;
			let current_list = [];
			if (record_rows.length > 0) {
				$("#" + table_id)
					.find("#no_" + table_id)
					.hide();
				$("#" + table_id)
					.find("table")
					.show();
				let tr_row = "";
				$.each(record_rows, function (index, row) {
					let row_id = row[current_table.table_options.row_id_field];
					let checked_stat = "";
					let row_style = "";
					if (current_table.checkbox_field == true) {
						if (checked_rows.includes(row_id)) {
							row_style = 'style="background-color:#f0f0f0;"';
						}
					}
					tr_row += ' <tr data-rowid="' + row_id + '" ' + row_style + ">";
					tr_col = "";
					if (current_table.checkbox_field == true) {
						if (checked_rows.includes(row_id)) {
							checked_stat = "checked";
						}
						tr_col +=
							'<th scope="row" style="vertical-align: middle !important;">' +
							'<label class="m-checkbox m-checkbox--check-bold">' +
							'<input class="' +
							table_id +
							'_row_check" type="checkbox" value="' +
							row_id +
							'" ' +
							checked_stat +
							">" +
							"<span></span>" +
							"</label>" +
							"</th>";
					}

					$.each(
						current_table.table_options.headers,
						function (header_index, header_value) {
							let col_text_truncate = header_value.text_truncate
								? "text-truncate"
								: "";
							let col_content = row[header_value.field];
							let text_align =
								header_value.col_text_align != undefined &&
								header_value.col_text_align != ""
									? header_value.col_text_align
									: "center";
							if (row.columns != undefined && row.columns != "") {
								let column_option =
									row.columns[header_value.id] != undefined &&
									row.columns[header_value.id] != ""
										? row.columns[header_value.id]
										: false;
								if (column_option != false) {
									let col_template = column_option["template"];
									let col_text_align = column_option["text_align"];
									col_content =
										col_template != undefined && col_template != ""
											? col_template
											: row[header_value.field];
									text_align =
										col_text_align != undefined && col_text_align != ""
											? col_text_align
											: text_align;
								}
							}
							tr_col +=
								'<td class="' +
								col_text_truncate +
								" " +
								header_value.id +
								'" style="text-align: ' +
								text_align +
								';vertical-align: middle !important;">' +
								col_content +
								"</td>";
						}
					);
					tr_row += tr_col + "</tr>";
				});
				if (first_init_bool) {
					$($("#" + table_id).find("#" + table_id + "_items")).html(tr_row);
				} else {
					$($("#" + table_id).find("#" + table_id + "_items")).append(tr_row);
				}
				$(this).parents("tr").css("background-color", "#f0f0f0");
			} else {
				if (!first_init_bool) {
					let current_list = $(
						$("#" + table_id).find("#" + table_id + "_items")
					).find("tr");
					if (current_list.length < 1) {
						no_table_record_display(table_id);
					}
				} else {
					no_table_record_display(table_id);
				}
			}
			callback(data);
		},
		error: function (xhr) {
			// if error occured
			// alert("Error occured.please try again");
			console.log(xhr.statusText + xhr.responseText);
			// $(placeholder).removeClass('loading');
		},
		complete: function () {
			mApp.unblock("#" + table_id + "_items");
		},
	});
};
const update_params = (table_id, first_init_bool = 1, callback) => {
	let ajax_table_params = {};
	let current_table = $("#" + table_id).data("tableoptions");
	if (current_table.ajax_params != undefined) {
		ajax_table_params = current_table.ajax_params;
	}
	ajax_table_params.limiter = current_table.row_limit;
	ajax_table_params.search = $("#" + current_table.search).val();
	ajax_table_params.order_type = $("#" + current_table.id).data("sorttype");
	ajax_table_params.order_by = $("#" + current_table.id).data("sortcol");
	current_table.ajax_params = ajax_table_params;
	$("#" + table_id).data("tableoptions", current_table);
	custom_table_ajax(table_id, first_init_bool, function (data) {
		callback(data);
	});
};
$.extend({
	table_scroll_2: function (
		options,
		on_sort,
		end_scroll,
		after_render,
		on_search,
		on_row_check
	) {
		let sort_type = "none";
		let sort_col = null;
		let sort_data = [];
		let check_rows = [];
		//options
		let table_id = options.id;
		$("#" + table_id).data("tableoptions", options);
		let table_min_width =
			options.min_width > 0 ? options.min_width + "rem" : "60rem";
		table_ajax_url = options.ajax_url;
		table_headers = options.table_options.headers;
		let th = "";
		checkbox_all_bool = options.checkbox_all;
		let check_box_all_header_col = "";
		("</label>");
		if (checkbox_all_bool != undefined && checkbox_all_bool == true) {
			check_box_all_header_col =
				'<label class="m-checkbox m-checkbox--check-bold">' +
				'<input class="' +
				table_id +
				'_all_check" type="checkbox">' +
				"<span></span>" +
				"</label>";
		}
		let checkbox_all_width =
			options.checkbox_all_width != undefined &&
			options.checkbox_all_width != "" &&
			options.checkbox_all_width > 0
				? options.checkbox_all_width + "%"
				: "5%";
		if (options.checkbox_field != undefined && options.checkbox_field == true) {
			th =
				'<th style="width: ' +
				checkbox_all_width +
				' !important">' +
				check_box_all_header_col +
				"</th>";
		}
		$.each(table_headers, function (index, value) {
			let sort = "";
			if (value.sortable) {
				sort =
					'<i class="fa fa-unsorted float-right col_sort" id="' +
					value.field +
					'"></i>';
			}
			let table_header_text_align =
				value.header_text_align != undefined && value.header_text_align != ""
					? value.header_text_align
					: "left";
			let table_col_width =
				value.width != undefined && value.width != "" && value.width > 0
					? value.width + "%"
					: "10%";
			th +=
				'<th style="width: ' +
				table_col_width +
				";text-align: " +
				table_header_text_align +
				'">' +
				value.text +
				sort +
				"</th>";
		});
		let max_table_height =
			options.max_height != undefined && options.max_height != ""
				? options.max_height + "rem"
				: "5rem";
		style =
			"max-height: " +
			max_table_height +
			";height: " +
			max_table_height +
			"; position: relative; overflow-y: scroll;";
		$("#" + table_id).prop("style", style);
		let table_content =
			'<div class="col-12" style="height:25rem;display:none;" id="no_' +
			table_id +
			'">' +
			'<div class="col-12 px-0 pb-4">' +
			'<div class="col-12 py-4 no_records_div">' +
			"<span style='font-size: 2rem;'>No Records Found</span>" +
			"</div>" +
			"</div>" +
			"</div>" +
			'<table id="' +
			table_id +
			'_scroll_table" class="table" style="min-width: ' +
			table_min_width +
			';width: 100%;overflow-x:scroll;table-layout: fixed;">' +
			"<thead><tr>" +
			th +
			"</thead></tr>" +
			'<tbody class="text-center" id="' +
			table_id +
			'_items" data-checkbox="0">' +
			"</tbody>" +
			"</table>";
		$("#" + table_id).data("limit", 0);
		$("#" + table_id).data("sorttype", "none");
		$("#" + table_id).data("sortcol", null);
		$("#" + table_id).data("check_rows", check_rows);
		$("#" + table_id).html(table_content);
		update_params(table_id, 1, function (data) {
			after_render(data);
		});
		$("#" + table_id).on("click", ".col_sort", function () {
			if (sort_type == "none" && sort_col == null) {
				sort_type = "asc";
				$(this).removeClass("fa-unsorted");
				$(this).addClass("fa-sort-asc");
				sort_col = $(this).attr("id");
			} else {
				if ($(this).attr("id") == sort_col) {
					if (sort_type == "asc") {
						sort_type = "desc";
						$(this).removeClass("fa-sort-asc");
						$(this).addClass("fa-sort-desc");
					} else if (sort_type == "desc") {
						sort_type = "none";
						$(this).removeClass("fa-sort-desc");
						$(this).addClass("fa-unsorted");
					} else {
						sort_type = "asc";
						$(this).removeClass("fa-unsorted");
						$(this).addClass("fa-sort-asc");
					}
				} else {
					$("table").find(".col_sort").removeClass("fa-sort-asc");
					$("table").find(".col_sort").removeClass("fa-sort-desc");
					$("table").find(".col_sort").addClass("fa-unsorted");
					sort_type = "asc";
					$(this).removeClass("fa-unsorted");
					$(this).addClass("fa-sort-asc");
					sort_col = $(this).attr("id");
				}
			}
			sort_data["type"] = sort_type;
			sort_data["col"] = sort_col;
			$("#" + table_id).data("sorttype", sort_type);
			$("#" + table_id).data("sortcol", sort_col);
			update_params(table_id, 1, function (data) {
				on_sort(sort_data);
			});
		});
		let lastScrollLeft = 0;
		$("#" + table_id).scroll(function () {
			var documentScrollLeft = $(this).scrollLeft();
			if (lastScrollLeft == documentScrollLeft) {
				let top_height = $(this).scrollTop() + $(this).innerHeight();
				if (
					$(this).scrollTop() + $(this).innerHeight() + 0.6666641235352 >=
					$(this)[0].scrollHeight
				) {
					update_params(table_id, 0, function (data) {
						end_scroll($(this)[0].scrollHeight);
					});
				}
			} else {
				lastScrollLeft = documentScrollLeft;
			}
		});
		// ALL CHECK CHECKBOX
		$("#" + table_id)
			.find("." + table_id + "_all_check")
			.on("click", function () {
				let checkboxes = [];
				let checked_rows = [];
				checkboxes = $("#" + table_id).find("." + table_id + "_row_check");
				if (
					$("#" + table_id)
						.find("." + table_id + "_all_check")
						.is(":checked")
				) {
					$("#" + table_id)
						.find("." + table_id + "_row_check")
						.prop("checked", true);

					$($("#" + table_id).find("tbody"))
						.find("tr")
						.css("background-color", "#f0f0f0");
					$("#" + table_id).data("check_all_bool", true);
				} else {
					$("#" + table_id)
						.find("." + table_id + "_row_check")
						.prop("checked", false);
					$($("#" + table_id).find("tbody"))
						.find("tr")
						.removeAttr("style");
					$("#" + table_id).data("check_all_bool", false);
					$("#" + table_id).data("check_rows", checked_rows);
				}
			});
		$("#" + table_id)
			.find("tbody")
			.on("click", "input", function () {
				let checked_rows = $("#" + table_id).data("check_rows");
				// let checkbox_all = 0;
				// let checkbox_under = [];
				let check_box_data = [];
				// let current_checkbox_stat = false;
				// let checked_items = [];
				if ($(this).is(":checked")) {
					$(this).parents("tr").css("background-color", "#f0f0f0");
					if (!checked_rows.includes($(this).val())) {
						checked_rows.push($(this).val());
					}
				} else {
					$(this).parents("tr").removeAttr("style");
					$($("#" + table_id).find("table"))
						.find("." + table_id + "_all_check")
						.prop("checked", false);
					checked_rows.splice($.inArray($(this).val(), checked_rows));
				}
				checked_items = $($("#" + table_id).find("tbody"))
					.find("input:checked")
					.map(function () {
						return $(this);
					})
					.get();
				if (checked_items.length < 1) {
					$($("#" + table_id).find("tbody"))
						.find("tr")
						.removeAttr("style");
					$($("#" + table_id).find("table"))
						.find("." + table_id + "_all_check")
						.prop("checked", false);
				}
				check_box_data["current_checked_box_value"] = $(this).val();
				$("#" + table_id).data("check_rows", checked_rows);
				on_row_check(check_box_data);
			});
		$("#" + options.search).on("keyup", function () {
			update_params(table_id, 1, function (data) {
				on_search(data);
			});
		});
	},
});

const custom_table_method = {
	reload: function (element_id, method, specific_option, data, callback) {
		let current_table = $("#" + element_id).data("tableoptions");
		switch (method) {
			case "update":
				current_table[specific_option] = data;
				$("#" + element_id).data("tableoptions", current_table);
			default:
				break;
		}
		update_params(element_id, 1, function (data) {
			callback(data);
		});
	},
	update_ajax_params: function (element_id, data) {
		let current_table = $("#" + element_id).data("tableoptions");
		current_table.ajax_params = data;
		$("#" + element_id).data("tableoptions", current_table);
	},
	recheck_list: function (element_id) {
		let current_table = $("#" + element_id).data("tableoptions");
		let current_list = $(
			$("#" + element_id).find("#" + element_id + "_items")
		).find("tr");
		if (current_list.length < 1) {
			no_table_record_display(current_table.id);
		}
	},
	clear_selected: function (element_id) {
		let current_table = $("#" + element_id).data("tableoptions");
		$("#" + current_table.id)
			.find("." + current_table.id + "_row_check")
			.prop("checked", false);
		$($("#" + current_table.id).find("tbody"))
			.find("tr")
			.removeAttr("style");
		$($("#" + current_table.id).find("table"))
			.find("." + current_table.id + "_all_check")
			.prop("checked", false);
		$("#" + current_table.id).data("check_rows", []);
	},
};
