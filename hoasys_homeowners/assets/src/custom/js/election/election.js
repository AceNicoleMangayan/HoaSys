var positionCheckedCounts_global = {}; // Object to store checked counts for each position
var checkedValues_global = []; // Array to store values of checked checkboxes
$(function () {
	get_election_ongoing_details_list();
	get_election_published_details_list();
});
$("#open_test").on("click", function () {});

function show_Votes_Details(id, name) {
	$("#result-container").html("");
	$.ajax({
		type: "POST",
		url: `${base_url}/election/fetch_elect_cand`,
		dataType: "json",
		data: {
			id,
		},
		success: function (data) {
			$("#election_title_showing").text(name);
			$("#election_details_ongoing").modal("show");
			$.each(data, function (position, candidates) {
				var positionHtml = `<hr><div class="row m-row--no-padding m-row--col-separator-xl"><div class="col-12 m-2 p-3"><h4 class="text-success"><strong>${position}</strong></h4></div>`;
				if (candidates.length > 0) {
					$.each(candidates, function (index, candidate) {
						if (candidate.fname == null) {
							positionHtml += `<div class="text-center mt-5 p-3"><h5 style="color: #ada9e7;">No Candidates</h5></div>`;
						} else {
							var candidateInfo = `${candidate.fname} ${candidate.mname} ${candidate.lname}`;
							var candidateVotes = `Total Votes: ${candidate.total_votes}`;
							var candidateScore = `Total Score: ${candidate.total_score}`;
							let percentage = 0;
							if (parseInt(candidate.total_votes) != 0) {
								// percentage =
								// 	(parseInt(candidate.total_score) /
								// 		parseInt(candidate.total_votes)) *
								// 	100;
								percentage =
									(parseInt(candidate.total_score) /
										parseInt(candidate.total_votes)) *
									100;
								// Format the percentage result to have 2 decimal places
								percentage = percentage.toFixed(2);
								// Convert the formattedPercentage back to a number if needed
								percentage = parseFloat(percentage);
							}
							positionHtml += `
                            <div class="col-12">
                                <div class="m-widget24">
                                    <div class="m-widget24__item">
                                        <h4 class="m-widget24__title">
                                            ${candidateInfo}
                                        </h4><br>
                                        <span class="m-widget24__desc">
                                            ${candidate.desc_candidate}
                                        </span>
                                        <span class="m-widget24__stats m--font-success">
                                            ${candidate.total_score} / ${candidate.total_votes} votes
                                        </span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div class="progress-bar m--bg-success" role="progressbar"
                                                style="width: ${percentage}%;" aria-valuenow="${percentage}" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                        <span class="m-widget24__change">
                                            Percentage
                                        </span>
                                        <span class="m-widget24__number">
                                            ${percentage}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        `;
						}
					});

					positionHtml += "</div>";
				} else {
					positionHtml += `<div class="text-center mt-5 p-3"><h5 style="color: #ada9e7;">No Positions and Candidates</h5></div>`;
				}

				$("#result-container").append(positionHtml);
			});
		},
		error: function (xhr, status, error) {
			console.error(xhr.responseText);
		},
	});
}

function show_Balot_Details(id) {
	$("#result-container-balot").html("");
	var positionCheckedCounts = {}; // Object to store checked counts for each position
	var checkedValues = []; // Array to store values of checked checkboxes

	$.ajax({
		type: "POST",
		url: `${base_url}/election/fetch_elect_cand`,
		dataType: "json",
		data: {
			id,
		},
		success: function (data) {
			if (data.allowed == 0) {
				toastr.error("You are done voting and cannot vote again.");
			}
			$("#election_details_ongoing_balot").modal("show");

			$.each(data, function (position, candidates) {
				// Initialize checked count for the position along with other details
				positionCheckedCounts[position] = {
					count: 0,
					position_election_added_id: null,
					election_pos_id: null,
				};

				var allowedVotes =
					candidates.length > 0 ? parseInt(candidates[0].winner) : 0;

				var positionHtml = `<hr><div class="col-12 m-2 p-3"><h4 class="text-success"><strong>${position}</strong></h4><span>( Kindly check ${allowedVotes} candidate/s )</span></div><div class="m-widget2">`;

				if (candidates.length > 0) {
					$.each(candidates, function (index, candidate) {
						var candidateInfo = `${candidate.fname} ${candidate.mname || ""} ${
							candidate.lname
						}`;

						// Add position_election_added_id and election_pos_id to candidateData
						var candidateData = {
							fname: candidate.fname,
							mname: candidate.mname,
							lname: candidate.lname,
							desc_candidate: candidate.desc_candidate,
							total_votes: candidate.total_votes,
							total_score: candidate.total_score,
							winner: parseInt(candidate.winner),
							candidate_id: candidate.candidate_id,
							position_election_added_id: candidate.position_election_added_id,
							election_pos_id: candidate.election_pos_id,
						};

						// Define checkboxHtml outside the loop
						var checkboxHtml = `<input type="checkbox" value="${
							candidate.candidate_id
						}" data-winner="${parseInt(
							candidate.winner
						)}" data-position="${position}" data-position-election-added-id="${
							candidate.position_election_added_id
						}" data-election-pos-id="${candidate.election_pos_id}"`;

						if (
							positionCheckedCounts[position].count >=
							parseInt(candidate.winner)
						) {
							// Disable unchecked checkboxes if checked count exceeds the winners count for the position
							checkboxHtml += " disabled";
						}
						checkboxHtml += ">";

						positionHtml += `
                            <div class="m-widget2__item m-widget2__item--primary">
                                <div class="m-widget2__checkbox">
                                    <label class="m-checkbox m-checkbox--solid m-checkbox--single m-checkbox--brand">
                                        ${checkboxHtml}
                                        <span></span>
                                    </label>
                                </div>
                                <div class="m-widget2__desc">
                                    <span class="m-widget2__text">
                                        <strong>${candidateInfo}</strong><br>
                                        <span>${candidate.desc_candidate}</span><br>
                                    </span>
                                </div>
                            </div>`;

						// Increment checked count for the position if the checkbox is checked
						if ($(checkboxHtml).is(":checked")) {
							positionCheckedCounts[position].count++;
							checkedValues.push(candidate.candidate_id); // Add the value to the array
						}

						// Update additional IDs in the positionCheckedCounts object
						positionCheckedCounts[position].position_election_added_id =
							candidate.position_election_added_id;
						positionCheckedCounts[position].election_pos_id =
							candidate.election_pos_id;
					});

					positionHtml += "</div>";
				} else {
					positionHtml += `<div class="text-center mt-5 p-3"><h5 style="color: #ada9e7;">No Positions and Candidates</h5></div>`;
				}

				$("#result-container-balot").append(positionHtml);
			});

			// Add event listener for checkbox changes
			$("#result-container-balot").on("change", ":checkbox", function () {
				var position = $(this).attr("data-position");

				if ($(this).is(":checked")) {
					// Increment checked count for the position if the checkbox is checked
					positionCheckedCounts[position].count++;
					checkedValues.push($(this).val()); // Add the value to the array
				} else {
					// Decrement checked count for the position if the checkbox is unchecked
					positionCheckedCounts[position].count--;
					checkedValues = checkedValues.filter(
						(value) => value !== $(this).val()
					); // Remove the value from the array
				}

				$(
					"#result-container-balot :checkbox[data-position='" + position + "']"
				).each(function () {
					if (
						!$(this).is(":checked") &&
						(parseInt($(this).attr("data-winner")) <= 0 ||
							positionCheckedCounts[position].count >=
							parseInt($(this).attr("data-winner")))
					) {
						// Disable unchecked checkboxes if checked count exceeds the winners count for the position
						$(this).prop("disabled", true);
					} else {
						// Enable all checkboxes for the position
						$(this).prop("disabled", false);
					}
				});

				checkedValues_global = checkedValues;
				positionCheckedCounts_global = positionCheckedCounts;

				// Log the values of checked checkboxes and position counts
				console.log("Checked Values: ", checkedValues_global);
				console.log("Position Checked Counts: ", positionCheckedCounts_global);
			});
		},
		error: function (xhr, status, error) {
			console.error(xhr.responseText);
		},
	});
}

$("#submit_balot_btn").on("click", function () {
	swal({
		title: `Are you sure you want to send this ballot?`,
		text: "Kindly double check and finalize your votes.",
		type: "question",
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: `No`,
	}).then((result) => {
		if (result.value) {
			save_balot();
		} else if (result.dismiss === "cancel") {}
	});
});

function save_balot() {
	$.ajax({
		type: "POST",
		url: `${base_url}/election/save_balot`,
		data: {
			checkedValues_global,
			positionCheckedCounts_global,
			election_id: $("#election_details_ongoing_balot").data("id"),
		},
		cache: false,
		success: function (data) {
			$("#election_details_ongoing_balot").modal("hide");
			toastr.success("Successfully Submitted Your Balot.");
		},
	});
}
function get_election_ongoing_details_list() {
	$.ajax({
		type: "POST",
		url: `${base_url}/election/get_election_ongoing_published_details_list`,
		data: {
			type: "ongoing",
		},
		cache: false,
		success: function (data) {
			let election = JSON.parse(data);
			// Clear existing content before appending new data
			$("#ongoing_elections_list").empty();

			if (election.length > 0) {
				election.forEach(function (election) {
					// Create HTML for each record
					let html = `
                        <div class="card forum-card mt-4 col-12" style="background:#ebdff3;">
                            <div class="card-body forum-post p-4">
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                        <span class="mb-0 font-weight-bold">${election.election_title}</span>
                                    </div>
                                    <div class="col-xl-3 col-md-3 col-sm-12 mt-2 mt-md-0">
                                    </div>
									<div class="col-xl-3 col-md-3 col-sm-12 mt-2 mt-md-0">
									<div class="d-flex flex-column flex-md-row">
										<button type="button" data-id="${election.id_elect}"
										class="btn btn-warning btn-sm btn-icon edit-icon-btn cast_vote btn-block">
										<span><i class="fa fa-pencil"></i> &nbsp;Cast Your Vote</span>
										</button>
									</div>
								</div>
                                </div>
                            </div>
                        </div>`;

					// Append the HTML to the container
					$("#ongoing_elections_list").append(html);
				});
			} else {
				let norecords =
					'<div class="text-center mt-5 p-3"><h5 style="color: #ada9e7;">No Ongoing Elections</h5></div>';
				$("#ongoing_elections_list").append(norecords);
			}
		},
	});
}

function get_election_published_details_list() {
    $.ajax({
        type: "POST",
        url: `${base_url}/election/get_election_ongoing_published_details_list`,
        data: {
            type: "active",
        },
        cache: false,
        success: function (data) {
            let election = JSON.parse(data);
            // Clear existing content before appending new data
            $("#published_elections_list").empty();

            if (election.length > 0) {
                election.forEach(function (election) {
                    // Create HTML for each record
                    let html = `
                        <div class="card forum-card mt-4 col-12" style="background:#d3f0f3;">
                            <div class="card-body forum-post p-4">
                                <div class="row">
                                    <div class="col-xl-9 col-md-9 col-sm-12">
                                        <span class="mb-0 font-weight-bold">${election.election_title}</span>
                                    </div>
                                    <div class="col-xl-3 col-md-3 col-sm-12 mt-2 mt-md-0">
                                        <div class="d-flex">
                                            <button type="button" data-name="${election.election_title}" data-id="${election.id_elect}"
                                                class="btn btn-info btn-sm btn-icon edit-icon-btn votes_list btn-block">
                                                <span><i class="fa fa-star"></i> &nbsp;View Results</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;

                    // Append the HTML to the container
                    $("#published_elections_list").append(html);
                });
            } else {
                let norecords =
                    '<div class="text-center mt-5 p-3"><h5 style="color: #ada9e7;">No Published Elections</h5></div>';
                $("#published_elections_list").append(norecords);
            }
        },
    });
}

$("#ongoing_elections_list,#published_elections_list").on(
	"click",
	".votes_list",
	function () {
		let id = $(this).data("id");
		let name = $(this).data("name");
		show_Votes_Details(id, name);
	}
);

$("#ongoing_elections_list").on("click", ".cast_vote", function () {
	let id = $(this).data("id");
	$("#election_details_ongoing_balot").data("id", id);
	$.ajax({
		type: "POST",
		url: `${base_url}/election/check_if_allowed_voter`,
		dataType: "json",
		data: {
			id
		},
		cache: false,
		success: function (data) {
			if (data == 1) {
				show_Balot_Details(id);
			} else if (data == 2) {
				toastr.warning("You cannot vote! You are a renter with less than one year of residency. Please contact administrator if this is a wrong tagging.");
			} else if (data == 3){
				toastr.warning("You cannot vote! You are not allowed to vote by settings.");
			} else if (data == 4){
			    toastr.warning("You cannot vote! You are tagged as a noncompliant payer. Kindly pay your dues accordingly.");
			} else if (data == 5){
			    toastr.warning("You cannot vote! You are tagged as a noncompliant payer and not allowed to vote by settings. Kindly pay your dues accordingly.");
			} else {
				toastr.warning("You have already voted for this election. You cannot vote again unless this election will be reset.");
			}
		},
	});

});
