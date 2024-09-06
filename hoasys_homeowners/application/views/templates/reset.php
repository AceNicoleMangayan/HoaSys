<!DOCTYPE html>
<html lang="en">

<head>
	<title>Successful Reset</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Bootstrap CSS CDN -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
	<!-- jQuery CDN -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!-- Bootstrap JS CDN -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
	<div class="container">
		<div class="row text-center">
			<div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
				<img src="<?php echo base_url("/assets/images/reset.png") ?>" class="img-responsive center-block" alt="Centralized Image">
				<input type="text" style="display: none;" id="needed_id" value="<?php echo $id_ho; ?>">
				<h3>Please input your OTP provided through email:</h3>
				<div class="form-group">
					<input class="form-control" type="text" placeholder="Input OTP" name="recov_code" id="recov_code" autocomplete="off">
				</div>
				<button id="reset_pass_btn" class="btn btn-primary btn-block btn-sm m-btn m-btn--pill m-btn--custom m-btn--air"><i class="fa fa-check"></i>&nbsp;&nbsp;Verify</button>
			</div>
		</div>
		<br>
		<div class="row mt-3" id="show_creds" style="display:none;">
			<div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
				<h4 class="text-success">SUCCESS! Here's your new credentials:</h4><br>
				<span><b>Username: </b><span id="new_username"></span></span><br>
				<span><b>Password: </b><span id="new_password"></span></span><br>
				<h5>Please DO NOT share this to anyone. Thanks!</h5>
			</div>
		</div>
		<div class="row" id="wrong_rc" style="display: none;">
			<div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
				<h5 class="text-danger">Oops wrong OTP or you have used this OTP to recover your account! Kindly process
					'forgot password' again to provide you with new OTP.</h5>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$("#reset_pass_btn").on("click", function() {
			let code = $("#recov_code").val();
			let id_ho = $("#needed_id").val();
			let trimmed_code = code.trim();

			if (trimmed_code === "") {
				swal("OOps!", `Kindly fill the recovery field!`, "error");
			} else {
				$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>login/recovery_code_verification",
					dataType: "JSON",
					data: {
						code,
						id_ho
					},
					success: function(data) {
						if (data.status == "1") {
							// swal("Success!", `You have successfully reset your account username and password!`, "success");
							$("#new_password").text(data.creds[0].password);
							$("#new_username").text(data.creds[0].username);
							$("#show_creds").show();
							$("#wrong_rc").hide();
						} else {
							// swal("OOps!", `Wrong recovery code!.`, "error");
							console.log("error");
							$("#wrong_rc").show();
						}
					}
				})
			}
		});
	</script>
</body>

</html>