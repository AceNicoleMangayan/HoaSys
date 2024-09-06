var displayData = (function () {
	var ann = function (searchVal = "") {
		var options = {
			data: {
				type: "remote",
				source: {
					read: {
						method: "POST",
						url: `${base_url}/concerns/get_concerns`,
						params: {
							query: {
								searchField: $("#search_Field").val(),
								status: $("#status_concern").val(),
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
					field: "id_concern",
					width: 80,
					title: "Concern #",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						html = "00000000" + row.id_concern;
						html = html.slice(-8);
						return html;
					},
				},
				{
					field: "title_concern",
					title: "Concern Title",
					width: 120,
					selector: false,
					sortable: "asc",
					textAlign: "left",
					template: function (row, index, datatable) {
						var html = "";
						var class_decor = "";
						if (row.title_concern == "unpublished") {
							class_decor = 'style="text-decoration: line-through;"';
						} else {
							class_decor = "";
						}

						html = "<span " + class_decor + ">" + row.title_concern + "</span>";
						return html;
					},
				},
					{
					field: "datesent_concern",
					title: "Date Sent",
					width: 100,
					selector: false,
					sortable: "asc",
					textAlign: "left",
					template: function (row, index, datatable) {
						var html = "";
						html = formatReadableDate(row.datesent_concern);
						return html;
					},
				},
		    	{
                    field: "ad_lname",
                    width: 150,
                    title: "Solved By",
                    sortable: false,
                    overflow: "visible",
                    textAlign: "center",
                    template: function (row, index, datatable) {
                        var html = "";
                        
                        // Check if ad_fname and ad_lname are null
                        if (row.ad_fname === null && row.ad_lname === null) {
                            html = "-";
                        } else {
                            // Display the name if not null
                            html = (row.ad_fname ? row.ad_fname : "") + " " + (row.ad_lname ? row.ad_lname : "");
                        }
                        
                        return html;
                    },
                },
				{
					field: "status_concern",
					title: "Status",
					width: 100,
					selector: false,
					sortable: "asc",
					textAlign: "left",
					template: function (row, index, datatable) {
						let stat = row.status_concern;
						let html = "";
						if (stat == "solved") {
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
					field: "isReceivedEmail",
					width: 80,
					title: "Sent Email",
					sortable: false,
					overflow: "visible",
					textAlign: "center",
					template: function (row, index, datatable) {
						var html = "";
						var isReceived = row.isReceivedEmail;
						if (isReceived == 1) {
							html = "<span class='text-success'>YES</span>";
						} else {
							html = "<span class='text-danger'>NO</span>";
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
						html =
							'<button data-id="' +
							row.id_concern +
							'" type="button" class="btn btn-primary view_email_concern"> View </button>';
						return html;
					},
				},
			],
		};
		var datatable = $("#datatable_concerns").mDatatable(options);
	};
	return {
		init: function (searchVal) {
			ann(searchVal);
		},
	};
})();

function initconcerns() {
	$("#datatable_concerns").mDatatable("destroy");
	displayData.init();
}

$(function () {
	initconcerns();
	$(".m_selectpicker").selectpicker();
});
$("#select_sequence,#select_sequence_update").select2({
	placeholder: "Select Sequence.",
	width: "100%",
});
$("#search_Field,#status_concern").on("change", function () {
	initconcerns();
});

$("#refresh_concerns").on("click", ".send_email_concern", function () {
	let c_id = $(this).data("id");
	$("#concern_send_details").modal("show");
	$("#send_to").val($(this).data("email"));
	$("#concern_send_details").data("id", c_id);
});

$("#refresh_concerns").on("click", ".view_email_concern", function () {
	let c_id = $(this).data("id");
	$("#concern_details").data("id", c_id);

	$.ajax({
		type: "POST",
		url: `${base_url}/concerns/get_concern_details`,
		data: {
			id: c_id,
		},
		cache: false,
		success: function (data) {
			let con = JSON.parse(data);
			let stat = con.ann_info[0].status_concern;

			// Display concern details
			$("#concern_details").modal("show");
			$("#concern_title_display").text(con.ann_info[0].title_concern);
			$("#concern_date_display").text(formatReadableDate(con.ann_info[0].datesent_concern));
			$("#concern_sender_display").text(con.ann_info[0].fname + " " + con.ann_info[0].lname);
			$("#concern_status_display").text(stat);
			$("#concern_desc_display").text(con.ann_info[0].desc_concern);
			
			if(con.ann_info[0].date_solved ==  null){
			$("#concern_date_solved_display").text('-No Date Yet-');
			}else{
				$("#concern_date_solved_display").text(formatReadableDate(con.ann_info[0].date_solved));
			}

			if(con.ann_info[0].email_reply_content ==  null){
				$("#concern_reply_disp").text('-No Reply Yet-');	
			}else{
				$("#concern_reply_disp").text(con.ann_info[0].email_reply_content);	
			}

			// Display files
			let filesHtml = '';
			if (con.attach.length == 0) {
				$("#display_files_uploaded").html("<span><i>No attachments</i></span>");
			} else {
				con.attach.forEach(function (file) {
					let fileName = file.file_link.split('/').pop(); // Extracting the file name from the path
					filesHtml += `<a href="${file.file_link}" target="_blank" download="${fileName}">${fileName}</a><br>`;
				});
				$("#display_files_uploaded").html(filesHtml);
			}
			// Format and display concern ID
			let id_con = "00000000" + con.ann_info[0].id_concern;
			id_con = id_con.slice(-8);
			$("#concern_id_display").text(id_con);
		},
	});
});
$("#solve_btn").on("click", function () {
	swal({
		title: `Are you sure you to mark this concern as SOLVED ?`,
		text: "Kindly review",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			change_concern_status("solved");
		} else if (result.dismiss === "cancel") {}
	});
});
$("#unsolve_btn").on("click", function () {
	swal({
		title: `Are you sure you to mark this concern back as UNRESOLVED ?`,
		text: "Kindly review",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			change_concern_status("unresolved");
		} else if (result.dismiss === "cancel") {}
	});
});

function change_concern_status(status) {
	let id = $("#concern_details").data("id");
	$.ajax({
		type: "POST",
		url: `${base_url}/concerns/change_concern_status`,
		data: {
			id,
			status
		},
		cache: false,
		success: function (data) {
			$("#datatable_concerns").mDatatable("reload");
			toastr.success("Successfully updated status to " + status);
			$("#concern_details").modal("hide");
		},
	});
}
// $("#submit_concern_email_add").on("submit", (e) => {
// 	e.preventDefault();
// 	// Show loading overlay
// 	$("#loading-overlay").show();
// 	let title = $("#concern_title").val();
// 	let description = $("#concern_content").val();
// 	if(title == "" || description == ""){
// 		toastr.error("Please fill the title and description fields.");
// 	}else{
// 		$.ajax({
// 			type: "POST",
// 			url: `${base_url}/concerns/send_concern`,
// 			data: {
// 				title,
// 				description
// 			},
// 			cache: false,
// 			success: function (data) {
// 				$("#datatable_concerns").mDatatable("reload");
// 				$("#concern_send_details_add").modal("hide");
// 				toastr.success("Successfully Sent Concern to Admin");
// 			},
// 			complete: function () {
// 				// Hide loading overlay after the AJAX call is completed
// 				$("#loading-overlay").hide();
// 			},
// 		});
// 	}
// });
// $("#btn_send_concern").on("click", function() {
//     // Disable the "Send" button
//     $(this).prop('disabled', true);
// });
// Bind the form submission to the submit event of the form
// $("#submit_concern_email_add").off("submit").on("submit", function (e) {
//     e.preventDefault();

//     // Call the function to submit the form
//     submitConcernForm();
// });
// Bind the form submission to the submit event of the form

$("#btn_send_concern").on("click", function () {
	submitConcernForm();
});

function submitConcernForm() {
	let title = $("#concern_title").val();
	let description = $("#concern_content").val();

	if (title == "" || description == "") {
		toastr.error("Please fill the title and description fields.");
	} else {
		// Disable the submit button to prevent multiple clicks
		$('#btn_send_concern').prop('disabled', true);

		// Show loading overlay
		$("#loading-overlay").show();
		let formData = new FormData($("#submit_concern_email_add")[0]);

		// Manually append title and description to FormData
		formData.append("title", title);
		formData.append("description", description);

		$.ajax({
			type: "POST",
			url: `${base_url}/concerns/send_concern`,
			data: formData,
			contentType: false,
			processData: false,
			cache: false,
			success: function (data) {
				$("#datatable_concerns").mDatatable("reload");
				$("#concern_send_details_add").modal("hide");
				toastr.success("Successfully Sent Concern to Admin");
				// Reset the form
				$("#submit_concern_email_add")[0].reset();
				// Assuming you have a file input with the id 'upload_iRequest_files_fileReq'
				$('#upload_iRequest_files_fileReq').val('');
				$("#files_select_label_fileReq").text("");
				$("#fileList_fileReq").text("");
			},
			complete: function () {
				// Enable the submit button and hide loading overlay after the AJAX call is completed
				$('#btn_send_concern').prop('disabled', false);
				$("#loading-overlay").hide();
			},
		});
	}
}

// Bind the button click to trigger the form submission
// Bind the button click to trigger the form submission
$("#send_concern_btn").on("click", function () {
	$("#concern_send_details_add").modal("show");
	// Reset the form
	$("#submit_concern_email_add")[0].reset();
	$('#btn_send_concern').prop('disabled', false);
	// Assuming you have a file input with the id 'upload_iRequest_files_fileReq'
	$('#upload_iRequest_files_fileReq').val('');
	$("#files_select_label_fileReq").text("");
	$("#fileList_fileReq").text("");
});

$("#upload_iRequest_files_fileReq").on("change", function () {
	var input = $(this)[0];
	var ul = $("#fileList_fileReq");
	var maxsize = 15000000;
	var exceed = 0;
	var fileCounter = 0;

	$("#files_select_label_fileReq").text("Files Selected");
	$("#display_div_file_fileReq").show();
	ul.empty();

	for (var i = 0; i < input.files.length; i++) {
		var file = input.files[i];
		var li = $("<li>").text(file.name);
		var file_size = file.size;
		var file_ext = file.name.split('.').pop();

		if (isValidFile(file_ext) && file_size <= maxsize && fileCounter < 2) {
			ul.append(li);
			$("#fileList_fileReq li").removeClass("included");
			fileCounter++;
			$('#btn_send_concern').prop('disabled', false);
		} else {
			ul.append(li);
			exceed = 1;
			$("#fileList_fileReq li").addClass("not_included");

			if (file_size > maxsize) {
				$("#files_select_label_fileReq").html("<span class='text-danger'>File/s exceeded 15MB. Please upload another.</span>");
				$('#btn_send_concern').prop('disabled', true);
			} else if (fileCounter >= 2) {
				$("#files_select_label_fileReq").html("<span class='text-danger'>Only 2 files are allowed. Please remove extra files.</span>");
				$('#btn_send_concern').prop('disabled', true);

			} else {
				$("#files_select_label_fileReq").html("<span class='text-danger'>Invalid file type. Please reupload allowed file types.</span>");
				$('#btn_send_concern').prop('disabled', true);

			}
		}
	}

	// Display message if no files selected
	if (input.files.length === 0) {
		var li = $("<li>").text('No Files Selected');
		ul.append(li);
		$("#files_select_label_fileReq").text("");
		$('#btn_send_concern').prop('disabled', true);
	}

	// Disable or enable submit button based on file size exceeding or exceeding 2 files
	// $('#submit_concern_email_add').formValidation('disableSubmitButtons', exceed !== 0 || fileCounter < 1 || fileCounter > 2);

});

// Function to check if the file extension is valid
function isValidFile(file_ext) {
	// var allowedExtensions = ["doc", "docx", "pdf", "txt", "xml", "xlsx", "xls", "xlsm", "ppt", "pptx", "jpeg", "JPEG", "PNG", "jpg", "JPG", "png", "mp4", "mov", "3gp", "m4v", "wmv", "avi", "mpeg", "mp3", "wav", "csv", "xlxx"];
	var allowedExtensions = ["jpeg", "JPEG", "PNG", "jpg", "JPG", "png"];

	return allowedExtensions.indexOf(file_ext.toLowerCase()) !== -1;
}
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
    ).replace('at', ' ');
    return formattedDate;
}
