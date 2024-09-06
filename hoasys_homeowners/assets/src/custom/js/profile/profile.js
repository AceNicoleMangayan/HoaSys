$(function () {
	fetch_homeowner_details();
});

function fetch_homeowner_details() {
	$.ajax({
		type: "POST",
		url: `${base_url}/profile/get_homeowner_details`,
		cache: false,
		success: function (data) {
			let home = JSON.parse(data);
			$("#profile_fullname").text(home[0].fname + " " + home[0].lname);
			$("#profile_email_display").text(home[0].email_add);
			$("#profile_block_display").text(home[0].block);
			$("#profile_lot_display").text(home[0].lot);
			$("#profile_village_display").text(home[0].village);

			$("#profile_username").val(home[0].username);
			$("#profile_email").val(home[0].email_add);
			$("#profile_contact_number").val(home[0].contact_num);
			$("#profile_fname").val(home[0].fname);
			$("#profile_mname").val(home[0].mname);
			$("#profile_lname").val(home[0].lname);
			$("#profile_block").val(home[0].block);
			$("#profile_lot").val(home[0].lot);
			$("#profile_village").val(home[0].village);

			$("#profile_monthly").val(home[0].monthly);
			$("#profile_duedate").val("Every " + home[0].duedate + " of the month.");
		},
	});
}

$("#save_account_details_btn").on("click", function () {
	let username = $("#profile_username").val();
	let email = $("#profile_email").val();
	let contact = $("#profile_contact_number").val();

	if (username == "" || email == "" || contact == "") {
		toastr.error("Cannot Update if either of the username, email and contact is empty.");
	} else {
		swal({
			title: `Are you sure you to update your account details?`,
			text: "Kindly review",
			type: "question",
			showCancelButton: true,
			confirmButtonText: "Yes",
			cancelButtonText: `No`,
		}).then((result) => {
			if (result.value) {
				update_account_status();
			} else if (result.dismiss === "cancel") {}
		});
	}
});

function update_account_status() {
	let username = $("#profile_username").val();
	let email = $("#profile_email").val();
	let contact = $("#profile_contact_number").val();
	$.ajax({
		type: "POST",
		url: `${base_url}/profile/save_profile_changes`,
		data: {
			username,
			// email,
			contact
		},
		cache: false,
		success: function (data) {
		    var result = JSON.parse(data);
		     if (result === 1) {
		         toastr.success("Successfully Updated Your Account Details.");
			     fetch_homeowner_details();
		     }else{
		        toastr.error("This username is already in use!.");   
		     }
		},
	});
}
$("#change_password_btn").on("click", function () {
	let pass = $("#profile_password").val();
	if (pass != ""){
		$("#otp_sending_confirm_password").data("password", pass);
		$("#loading-overlay").show();
		$.ajax({
			type: "POST",
			url: `${base_url}/profile/confirm_pass_changes`,
			// data: {
			// 	pass
			// },
			cache: false,
			success: function (data) {
				$("#otp_sending_confirm_password").modal("show");
			},
			complete: function () {
				// Hide loading overlay after the AJAX call is completed
				$("#loading-overlay").hide();
			},
		});
	}else{
		toastr.error("Please input password.");
	}
});

$("#submit_otp_sending_confirm_password").on("submit", (e) => {
	e.preventDefault();
	// Show loading overlay
	$("#loading-overlay").show();
	let pass = $("#otp_sending_confirm_password").data("password");
	let otp = $("#input_otp").val();
	$.ajax({
		type: "POST",
		url: `${base_url}/profile/final_password_confirmation`,
		dataType: "json",
		data: {
			pass,
			otp
		},
		cache: false,
		success: function (data) {
			if(data == 1){
				toastr.success("Succefully Updated Your Password.");
				fetch_homeowner_details();
				$("#otp_sending_confirm_password").modal("hide");
				$("#otp_sending_confirm_password").data("password",0);
				$("#profile_password").val("");
				$("#input_otp").val("");
			}else{
				toastr.error("Wrong OTP.");
				$("#input_otp").val("");
				$("#profile_password").val("");
			}
		},
		complete: function () {
			// Hide loading overlay after the AJAX call is completed
			$("#loading-overlay").hide();
		},
	});

});
