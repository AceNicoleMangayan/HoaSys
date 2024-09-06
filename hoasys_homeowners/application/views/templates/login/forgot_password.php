<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?php echo $title . ' | HOASYS'; ?></title>
    <meta name="description" content="Initialized via remote ajax json data">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv='cache-control' content='no-cache'>
    <meta http-equiv='expires' content='0'>
    <meta http-equiv='pragma' content='no-cache'>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Your existing stylesheets -->
    <link href="<?php echo base_url(); ?>assets/css/styles.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/src/custom/css/fonts/poppins/poppins.css" rel="stylesheet"
        type="text/css" />
    <link href="<?php echo base_url(); ?>assets/src/custom/css/fonts/roboto/roboto.css" rel="stylesheet"
        type="text/css" />
    <link href="<?php echo base_url(); ?>assets/src/custom/css/fonts/pacifico/pacifico.css" rel="stylesheet"
        type="text/css" />
    <link href="<?php echo base_url(); ?>assets/src/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>assets/src/demo/default/base/style.bundle.css" rel="stylesheet"
        type="text/css">

    <!-- FAVICON -->
    <link rel="apple-touch-icon" sizes="180x180"
        href="<?php echo base_url(); ?>assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32"
        href="<?php echo base_url(); ?>assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16"
        href="<?php echo base_url(); ?>assets/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?php echo base_url(); ?>assets/images/favicon/site.webmanifest">

    <!-- Your existing scripts -->
    <script src="<?php echo base_url(); ?>assets/js/app.js" type="text/javascript"></script>
    <script>
    var sys_env = "<?php echo ENVIRONMENT; ?>";
    </script>
    <script src="<?php echo base_url(); ?>assets/src/custom/plugins/moment/moment-timezone.js"></script>
    <script src="<?php echo base_url(); ?>assets/src/custom/plugins/moment/moment-timezone-with-data.js"></script>

    <!-- Bootstrap styles -->
    <style>
    html {
        background: black;
    }

    body {
        background-color: white !important;
        margin: 0;
    }

    .m-grid__item.m-grid__item--fluid.m-login__wrapper {
        margin: auto !important;
        flex: none !important;
        padding: 0 !important;
    }

    .padding-Box {
        padding-left: 50px;
        padding-right: 50px;
        padding-top: 45px;
        padding-bottom: 40px;
    }

    .signinDes {
        background: rgba(193, 193, 193, 0.36);
        width: 100%;
        max-width: 40em;
        margin: 10% auto;
        /* padding: 10px; */
        max-height: 80vh;
        /* Adjusted max-height */
        border-radius: 1em;
    }

    #myVideo {
        position: fixed;
        object-fit: cover;
        height: 100vh;
        width: 100vw;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
    }

    .content {
        z-index: 1;
    }

    .top_logo {
        margin-top: 85px;
    }

    .top_logo img {
        width: 25%;
        /* Default size for larger screens */
        max-width: 100%;
        /* Make image responsive */
        height: auto;
        /* Maintain aspect ratio */
    }

    @media (max-width: 767px) {

        /* Media query for screens smaller than 768 pixels wide */
        .top_logo img {
            width: 100%;
            /* Full width for smaller screens */
        }

        /* Media query for screens smaller than 768 pixels wide */
        .m-login__form-action {
            text-align: center;
            /* Center align on smaller screens */
        }

        .m-login__form-action .btn {
            margin-top: 10px;
            /* Add margin between elements on smaller screens */
        }
    }

    .small-btn {
        padding: 1rem 0.5rem !important;
        /* Adjust padding as needed */
        font-size: inherit !important;
        /* Maintain the default font size */
    }

    .custom-select {
        /* Add your custom styles here */
        border-radius: 17px;
        padding: 8px;
        /* border: 1px solid #ccc; */
        background: #f7f6f9;
        /* width: 200px; */
        /* Set the width according to your design */
    }

    .custom-select option {
        /* Style for select options */
        background-color: #fff;
        color: #000;
    }
    </style>
</head>

<body
    class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="text-center top_logo">
                    <img class="mx-auto d-block" src="<?php echo base_url("/assets/images/password_recover.png") ?>" />
                </div>
                <div class="m-grid__item m-grid__item--fluid m-login m-login--signin m-login--2 m-login-2--skin-2"
                    id="m_login">
                    <div class="m-grid__item m-grid__item--fluid m-login__wrapper">
                        <div class="m-login__container signinDes">
                            <div class="m-login__signin">
                                <div id="showError" style="display:none">
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert"
                                        style="font-size:small;">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        </button>
                                        <strong>Oh snap!</strong> Username and Password mismatched!
                                    </div>
                                </div>
                                <div class="m-login__form m-form padding-Box">
                                    <div class="form-group">
                                        <label for="recipient-name" class="m--font-bolder">Recover Option</label>
                                        <select class="form-select custom-select" title="Recover Option" title="" id="recover_option"
                                            required>
                                            <option value="1">Username</option>
                                            <option value="0">Email</option>
                                        </select>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <input class="form-control m-input" type="text" placeholder="Type Username OR Email here..."
                                            name="forgot_creds" id="forgot_creds" autocomplete="off">
                                    </div>
                                    <!-- <div class="form-group m-form__group">
                                        <input class="form-control m-input" type="text" placeholder="Type Username OR Email here..."
                                            name="forgot_pass_fname" id="forgot_pass_fname" autocomplete="off">
                                    </div> -->
                                    <!-- <div class="form-group m-form__group">
                                        <input class="form-control m-input" type="text" placeholder="Last Name"
                                            name="forgot_pass_lname" id="forgot_pass_lname" autocomplete="off">
                                    </div> -->
                                    <div class="m-login__form-action">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <button id="send_ver_btn"
                                                    class="btn btn-primary small-btn btn-block btn-sm m-btn m-btn--pill m-btn--custom m-btn--air"><i
                                                        class="fa fa-send"></i>&nbsp;&nbsp;Send Verification to
                                                    Email</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- Your existing scripts -->
    <script src="<?php echo base_url(); ?>assets/src/vendors/base/vendors.bundle.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/src/demo/default/base/scripts.bundle.js" type="text/javascript">
    </script>
</body>

</html>
<script type="text/javascript">
$("#recover_option").on("change", function(){
    $("#forgot_creds").val("");
});
$("#send_ver_btn").on("click", function() {
    // let fname = $("#forgot_pass_fname").val();
    // let lname = $("#forgot_pass_lname").val();
    let creds = $("#forgot_creds").val();
    let rec = $("#recover_option").val();

    // let trimmed_fname = fname.trim();
    // let trimmed_lname = lname.trim();
    let trimmed_creds = creds.trim();
    // if (trimmed_fname === "" || trimmed_lname === "") {
    if (trimmed_creds === "") {
        swal("OOps!", `Kindly fill all the form fields!`, "error");
    } else {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>login/send_verification",
            dataType: "JSON",
            data: {
                // fname,
                // lname
                creds,
                rec
            },
            success: function(data) {
                let res = JSON.parse(data);
                if (res == "1") {
                    swal("Success!",
                        `We have successfully sent account recovery details to your email!`,
                        "success");
                } else {
                    swal("OOps!",
                        `This user is inactive or not available in our system. Kindly contact the administrator.`,
                        "error");
                }
            }
        })
    }
});
</script>