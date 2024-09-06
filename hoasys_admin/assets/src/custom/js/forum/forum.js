$(function () {
	loadMoreContent();
});

$("#loadMore_actual_btn").on("click", function () {
	loadMoreContent();
});
function loadMoreContent() {
	var offset = $(".forum-card").length;
	var limit = 5;

	$.ajax({
		url: `${base_url}/forum/load_forum_data`,
		type: "POST",
		dataType: "json",
		data: {
			offset: offset,
			limit: limit,
			search: $("#search_Forum").val(),
		},
		success: function (data) {
			if (data.forum_data.length > 0) {
				// Append new forum cards
				$.each(data.forum_data, function (index, forum) {
					let role = forum.publisher_role;
					let pub_id = "";
					let display_css = "";
					if (role == "admin") {
						pub_id = forum.id_admin;
					} else {
						pub_id = forum.id_ho;
					}
					// display of update and delete
					if (session_id == pub_id) {
						// meaning, this forum is owned by the one who logged in
						display_css = "";
					} else {
						display_css = 'style="display:none"';
					}

					var forumCardHtml = `
                        <div class="card forum-card mt-4">
                            <div class="card-body forum-post p-4">
							<div class="d-flex justify-content-between align-items-center">
							<h4 class="mb-0">${forum.title_forum}</h4>
							<div class="d-flex">
							<button type="button" data-id="${
								forum.id_forum
							}" class="btn btn-link edit-icon-btn mr-2 update_forum" ${display_css}>
								<!-- Edit Icon (Font Awesome example) -->
								<i class="fa fa-edit"></i>
							</button>
							<button type="button" data-id="${
								forum.id_forum
							}" class="btn btn-link delete-icon-btn delete_forum" ${display_css}>
								<!-- Delete Icon (Font Awesome example) -->
								<i class="fa fa-trash"></i>
							</button>
								</div>
							</div>
                                <p class="mt-4">${forum.desc_forum}</p>
                                <div class="publisher-info text-primary mt-2" style="font-size:smaller">
                                    <span class="publisher-name text-capitalize">By ${
																			forum.published_by
																		}</span>
                                    <span class="publish-date">- ${formatReadableDate(
																			forum.datetime_post
																		)}</span>
                                </div>
                                <!-- Add other forum card content here -->
                                <!-- ... -->
                                <div class="comments-section comments-section-num${
																	forum.id_forum
																}">
                                    <!-- Add comments section here if needed -->
                                    <!-- ... -->
                                </div>
                                <button type="button" data-id="${
																	forum.id_forum
																}" class="btn btn-link reply-comments-design mt-2">Reply Forum</button>
                                
																<button type="button" data-id="${
																	forum.id_forum
																}" class="btn btn-link see-forum-design see-forum-comments mt-2 see-forum-comments-num${
						forum.id_forum
					}">See Forum Comments</button>
					<button type="button" data-id="${
						forum.id_forum
					}" class="btn btn-link see-forum-design mt-2 show-less-forum"> Show less</button>

                            </div>
                        </div>
                    `;
					$(".list_forums").append(forumCardHtml);
				});

				// Check if all records are loaded
				if (data.total_forums <= offset + limit) {
					$("#loadMoreBtn").hide();
				}
			} else {
				// No more records, hide the load more button
				$("#loadMoreBtn").hide();
				let norecords = '<div class="text-center mt-5"><h3 style="color: #ada9e7;">No Records To Load</h3></div>';
				$(".list_forums").append(norecords);
			}
		},
		error: function (xhr, status, error) {
			console.error(xhr.responseText);
		},
	});
}

function formatReadableDate(dateString) {
	const options = {
		year: "numeric",
		month: "long",
		day: "numeric",
		hour: "numeric",
		minute: "numeric",
		second: "numeric",
		timeZoneName: "short",
	};
	const formattedDate = new Date(dateString).toLocaleDateString(
		"en-US",
		options
	);
	return formattedDate;
}

$(".list_forums").on("click", ".see-forum-comments", function () {
	let forum_id = $(this).data("id");
	let offset = $(
		".comments-section-num" + forum_id + " .comment_design"
	).length;
	$(".comments-section-num" + forum_id).show();
	forum_replies(forum_id, offset);
});
function forum_replies(forum_id, offset = 0, isNewComment = false) {
	// If it's a new comment, don't clear the entire content
	if (!isNewComment && offset === 0) {
		// Empty the existing content only if it's not a new comment and the offset is 0
		$(".comments-section-num" + forum_id).empty();
	}

	$.ajax({
		url: `${base_url}/forum/load_forum_replies`,
		type: "POST",
		dataType: "json",
		data: { forum_id: forum_id, limit: 5, offset: offset },
		success: function (data) {
			if (data.forum_replies.length > 0) {
				// Append new forum replies
				$.each(data.forum_replies, function (index, reply) {
					var replyHtml = `
                        <div class="comment mt-3 comment_design">
                            <p><span class="text-primary">@${reply.commented_by}</span>&nbsp;&nbsp;<span>${reply.forum_rep}</span></p>
							<div class="publisher-info text-info mt-2" style="font-size:smaller">
							<span class="publish-date">${formatReadableDate(
																	reply.datetime_rep
																)}</span>
							</div>
							<button type="button" data-commentby="${reply.commented_by}" data-forumid="${reply.id_forum}" data-replyid="${reply.id_forum_rep}" class="btn btn-link reply_comments">Reply</button>
                            <button type="button" data-id="${reply.id_forum_rep}" class="btn btn-link view_sub_comments view_sub_comments_num${reply.id_forum_rep}">Comments</button>
                            <button type="button" data-id="${reply.id_forum_rep}" data-forumid="${reply.id_forum}" class="btn btn-link text-danger delete_forum_comment">Delete</button>
							<button type="button" data-id="${reply.id_forum_rep}" class="btn btn-link show-less-subcomments">Show less</button>
                            <div class="comment-section-inside${reply.id_forum_rep}">
                            </div>
                        </div>
                    `;

					// Append new comment without duplicating
					$(".comments-section-num" + forum_id).append(replyHtml);
				});

				// Check if there are more comments to load
				if (data.forum_replies.length >= 5) {
					// Update "See Forum Comments" button
					$(".see-forum-comments-num" + forum_id).text(
						"See More Forum Comments..."
					);
				} else {
					// No more comments, hide the see more button
					$(".see-forum-comments-num" + forum_id).hide();
				}
			} else {
				// No more comments, hide the see more button
				$(".see-forum-comments-num" + forum_id).hide();
			}
		},
		error: function (xhr, status, error) {
			console.error(xhr.responseText);
		},
	});
}

$(".m-content").on("click", ".view_sub_comments", function () {
	let forum_rep_id = $(this).data("id");
	$(".comment-section-inside" + forum_rep_id).show();
	forum_sub_comments(forum_rep_id);
});
let fetchedForumRepIds = [];

function forum_sub_comments(forum_rep_id) {
	// Check if forum_rep_id is already fetched
	if (fetchedForumRepIds.includes(forum_rep_id)) {
		return; // Already fetched, exit the function
	}

	$.ajax({
		url: `${base_url}/forum/load_forum_sub_comments`,
		type: "POST",
		dataType: "json",
		data: { forum_rep_id: forum_rep_id },
		success: function (data) {
			if (data.forum_sub_comments.length > 0) {
				// Append new sub-comments
				$.each(data.forum_sub_comments, function (index, subComment) {
					var subCommentHtml = `
                        <div class="comment mt-3 sub_comments_design">
                            <p><span class="text-primary">@${subComment.commented_by}</span>&nbsp;&nbsp;<span>${subComment.forum_rep}</span></p>
                            <div class="publisher-info text-info mt-2" style="font-size:smaller">
							<span class="publish-date">${formatReadableDate(
																	subComment.datetime_rep
																)}</span>
							</div>
							<button type="button" data-id="${subComment.id_forum_rep}" data-forumid="${subComment.id_forum}" data-replyid="${subComment.reference}" class="btn btn-link text-danger delete_sub_comment">Delete</button>
                        </div>
                    `;
					// Append sub-comment to the correct container
					$(".view_sub_comments_num" + forum_rep_id)
						.siblings(".comment-section-inside" + forum_rep_id)
						.append(subCommentHtml);
				});

				// Add forum_rep_id to the fetched IDs list
				fetchedForumRepIds.push(forum_rep_id);

				// Check if there are more sub-comments to load
				// if (data.forum_sub_comments.length >= 5) {
				// 	var seeMoreHtml = `<div data-id="${forum_rep_id}" class="see-more-sub-comments mt-2"><i class="fa fa-commenting-o"></i>&nbsp;&nbsp;<span>See More Sub-Comments...</span></div>`;
				// 	$(".view_sub_comments_num" + forum_rep_id)
				// 		.siblings(".comment-section-inside" + forum_rep_id)
				// 		.append(seeMoreHtml);
				// } else {
				// 	$(".see-more-sub-comments-num" + forum_rep_id).hide();
				// }
			} else {
				// No more sub-comments, hide the see more button
				$(".see-more-sub-comments-num" + forum_rep_id).hide();
			}
		},
		error: function (xhr, status, error) {
			console.error(xhr.responseText);
		},
	});
}

// Save forum
$("#save_new_forum").on("click", function () {
	// Get the values of forum title and forum description
	let forum_title = $("#forum_title").val();
	let forum_desc = $("#forum_desc").val();

	// Trim whitespace from the values
	let trimmed_forum_title = forum_title.trim();
	let trimmed_forum_desc = forum_desc.trim();

	// Check if both title and description are empty or contain only white spaces
	if (trimmed_forum_title === "" || trimmed_forum_desc === "") {
		// Display an alert or perform any other action for empty or whitespace-only values
		toastr.error(
			"Forum title and description cannot be empty or contain only white spaces."
		);
	} else {
		// Proceed with your logic if the values are valid
		// For example, you can submit the form or perform other actions
		save_forum();
	}
});
function save_forum() {
	let forum_title = $("#forum_title").val();
	let forum_desc = $("#forum_desc").val();
	$(".list_forums").html("");
	$.ajax({
		url: `${base_url}/forum/save_forum`,
		type: "POST",
		dataType: "json",
		data: {
			forum_title,
			forum_desc,
			visibility: $("#visibilitySelect").val(),
			role: "admin",
		},
		success: function () {
			$(".list_forums").html(""); // Clear existing forums
			loadMoreContent(); // Load latest forum data
			$("#forum_title").val("");
			$("#forum_desc").val("");
			toastr.success("Successfully Added Forum");
		},
		error: function (xhr, status, error) {
			console.error(xhr.responseText);
		},
	});
}
$(".list_forums").on("click", ".reply-comments-design", function () {
	let forum_id = $(this).data("id");
	$("#submit_forum_reply_modal").data("id", forum_id);
	$("#submit_forum_reply_modal").modal("show");
	$("#form_reply_Label").text("Forum Comment");
	$("#submit_forum_reply_modal").data("type", 1);
});

$("#submit_forum_reply").on("submit", (e) => {
	e.preventDefault();
	let saving_type = $("#submit_forum_reply_modal").data("type");
	let forum_comment = $("#forum_comment").val();
	// Trim whitespace from the values
	let trimmed_forum_comment = forum_comment.trim();

	// Check if both title and description are empty or contain only white spaces
	if (trimmed_forum_comment === "") {
		// Display an alert or perform any other action for empty or whitespace-only values
		toastr.error("Forum comment cannot be empty or contain only white spaces.");
	} else {
		if (saving_type == 1) {
			save_forum_comment();
		} else {
			save_comment_reply();
		}
	}
});
function save_forum_comment() {
	let forum_id = $("#submit_forum_reply_modal").data("id");
	let forum_comment = $("#forum_comment").val();
	$(".comments-section-num" + forum_id).empty();

	$.ajax({
		url: `${base_url}/forum/save_forum_comment`,
		type: "POST",
		dataType: "json",
		data: {
			forum_id,
			forum_comment,
		},
		success: function () {
			$("#submit_forum_reply_modal").modal("hide");
			$(".see-forum-comments-num" + forum_id).show();
			$("#forum_comment").val("");

			// Append the new comment directly to the comments section
			forum_replies(forum_id, 0, true); // Pass true to indicate it's a new comment
			toastr.success("Successfully Added Comment to Forum");
		},
		error: function (xhr, status, error) {
			console.error(xhr.responseText);
		},
	});
}

$(".list_forums").on("click", ".reply_comments", function () {
	let forum_id = $(this).data("forumid");
	let reply_id = $(this).data("replyid");
	let commentby = $(this).data("commentby");
	$("#form_reply_Label").text("Reply to @" + commentby);
	$("#submit_forum_reply_modal").data("id", forum_id);
	$("#submit_forum_reply_modal").data("reply", reply_id);
	$("#submit_forum_reply_modal").modal("show");
	$("#submit_forum_reply_modal").data("type", 2);
});

function save_comment_reply() {
	let forum_id = $("#submit_forum_reply_modal").data("id");
	let reply_id = $("#submit_forum_reply_modal").data("reply");
	let forum_comment = $("#forum_comment").val();
	$(".comment-section-inside" + reply_id).empty();
	fetchedForumRepIds = [];
	$.ajax({
		url: `${base_url}/forum/save_forum_comment_reply`,
		type: "POST",
		dataType: "json",
		data: {
			forum_id,
			forum_comment,
			reply_id,
		},
		success: function () {
			$("#submit_forum_reply_modal").modal("hide");
			$("#forum_comment").val("");

			// Append the new comment directly to the comments section
			forum_sub_comments(reply_id);
			toastr.success("Successfully Added Reply to Comment");
		},
		error: function (xhr, status, error) {
			console.error(xhr.responseText);
		},
	});
}

$(".list_forums").on("click", ".show-less-forum", function () {
	let forum_id = $(this).data("id");
	$(".comments-section-num" + forum_id).hide();
	$(".see-forum-comments-num" + forum_id).show();
});
$(".list_forums").on("click", ".show-less-subcomments", function () {
	let reply_id = $(this).data("id");
	$(".comment-section-inside" + reply_id).hide();
});
$(".list_forums").on("click", ".update_forum", function () {
	let forum_id = $(this).data("id");
	fetch_forum_details(forum_id);
});
$(".list_forums").on("click", ".delete_forum", function () {
	let forum_id = $(this).data("id");
	swal({
		title: `Are you sure you want to remove this forum? Once removed, comments and sub-comments will also be removed.`,
		text: "Action cannot be undone",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			delete_forum(forum_id);
		} else if (result.dismiss === "cancel") {
		}
	});
});
$(".list_forums").on("click", ".delete_forum_comment", function () {
	let forum_comment_id = $(this).data("id");
	let forum_id = $(this).data("forumid");
	swal({
		title: `Are you sure you want to remove this forum comment? Once removed, sub-comments will also be removed.`,
		text: "Action cannot be undone",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			delete_forum_comment(forum_comment_id, forum_id);
		} else if (result.dismiss === "cancel") {
		}
	});
});
$(".list_forums").on("click", ".delete_sub_comment", function () {
	let forum_comment_id = $(this).data("id");
	let forum_id = $(this).data("forumid");
	let forum_reference = $(this).data("replyid");
	swal({
		title: `Are you sure you want to remove this sub-comment? Once removed, this will no longer appear here.`,
		text: "Action cannot be undone",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			delete_forum_sub_comment(forum_comment_id, forum_id, forum_reference);
		} else if (result.dismiss === "cancel") {
		}
	});
});
function fetch_forum_details(forum_id) {
	$.ajax({
		type: "POST",
		url: `${base_url}/forum/fetch_forum_details`,
		data: {
			forum_id,
		},
		cache: false,
		success: function (data) {
			let forum = JSON.parse(data);
			$("#forum_title_update").val(forum[0].title_forum);
			$("#forum_desc_update").val(forum[0].desc_forum);
			$("#submit_forum_update_modal").modal("show");
			$("#submit_forum_update_modal").data("id", forum_id);
		},
	});
}
$("#submit_forum_update").on("submit", (e) => {
	e.preventDefault();
	let forum_id = $("#submit_forum_update_modal").data("id");
	let title = $("#forum_title_update").val();
	let desc = $("#forum_desc_update").val();
	// Trim whitespace from the values
	let trimmed_title = title.trim();
	let trimmed_desc = desc.trim();
	// Check if both title and description are empty or contain only white spaces
	if (trimmed_title === "" || trimmed_desc === "") {
		// Display an alert or perform any other action for empty or whitespace-only values
		toastr.error(
			"Forum title and Description cannot be empty or contain only white spaces."
		);
	} else {
		save_forum_updates(forum_id);
	}
});

function save_forum_updates(forum_id) {
	$.ajax({
		url: `${base_url}/forum/update_forum`,
		type: "POST",
		dataType: "json",
		data: {
			forum_id,
			forum_title: $("#forum_title_update").val(),
			forum_desc: $("#forum_desc_update").val(),
		},
		success: function () {
			$("#submit_forum_update_modal").modal("hide");
			toastr.success("Successfully Updated Forum");
			$(".list_forums").html(""); // Clear existing forums
			loadMoreContent(); // Load latest forum data
		},
		error: function (xhr, status, error) {
			console.error(xhr.responseText);
		},
	});
}
function delete_forum(forum_id) {
	$.ajax({
		url: `${base_url}/forum/delete_forum`,
		type: "POST",
		dataType: "json",
		data: {
			forum_id,
		},
		success: function () {
			toastr.success("Successfully Deleted Forum");
			$(".list_forums").html(""); // Clear existing forums
			loadMoreContent(); // Load latest forum data
		},
		error: function (xhr, status, error) {
			console.error(xhr.responseText);
		},
	});
}
function delete_forum_comment(forum_comment_id, forum_id) {
	$.ajax({
		url: `${base_url}/forum/delete_forum_comment`,
		type: "POST",
		dataType: "json",
		data: {
			forum_comment_id,
		},
		success: function () {
			toastr.success("Successfully Deleted Forum Comment");
			$(".comments-section-num" + forum_id).text("");
			forum_replies(forum_id, 0, true); // Pass true to indicate it's a new comment
		},
		error: function (xhr, status, error) {
			console.error(xhr.responseText);
		},
	});
}
function delete_forum_sub_comment(forum_comment_id, forum_id, forum_reference) {
	$.ajax({
		url: `${base_url}/forum/delete_forum_sub_comment`,
		type: "POST",
		dataType: "json",
		data: {
			forum_comment_id,
			forum_reference,
		},
		success: function () {
			toastr.success("Successfully Deleted Forum Sub-Comment");
			$(".list_forums").html(""); // Clear existing forums
			loadMoreContent(); // Load latest forum data
			// $(".comment-section-inside"+forum_reference).text("");
			// forum_sub_comments(forum_reference);
		},
		error: function (xhr, status, error) {
			console.error(xhr.responseText);
		},
	});
}
$("#search_Forum").on("change", function () {
	$(".list_forums").html(""); // Clear existing forums
	loadMoreContent(); // Load latest forum data
});
