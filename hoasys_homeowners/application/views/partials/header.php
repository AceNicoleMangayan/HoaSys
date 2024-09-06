<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8" />
    <title><?php echo $title . ' | HOMEOWNER '; ?>
    </title>
    <meta name="description" content="Initialized via remote ajax json data">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv='cache-control' content='no-cache'>
    <meta http-equiv='expires' content='0'>
    <meta http-equiv='pragma' content='no-cache'>
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
    <link rel="apple-touch-icon" sizes="57x57"
        href="<?php echo base_url(); ?>assets/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60"
        href="<?php echo base_url(); ?>assets/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72"
        href="<?php echo base_url(); ?>assets/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76"
        href="<?php echo base_url(); ?>assets/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114"
        href="<?php echo base_url(); ?>assets/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120"
        href="<?php echo base_url(); ?>assets/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144"
        href="<?php echo base_url(); ?>assets/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152"
        href="<?php echo base_url(); ?>assets/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180"
        href="<?php echo base_url(); ?>assets/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"
        href="<?php echo base_url(); ?>assets/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32"
        href="<?php echo base_url(); ?>assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96"
        href="<?php echo base_url(); ?>assets/images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16"
        href="<?php echo base_url(); ?>assets/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?php echo base_url(); ?>assets/images/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <script src="<?php echo base_url(); ?>assets/js/app.js" type="text/javascript"></script>
    <script>
    var sys_env = "<?php echo ENVIRONMENT; ?>";
    </script>
    <script>
    var protocol = window.location.protocol;
    var pathName = window.location.pathname;
    var host = window.location.host;
    var match = host.match(/\d{1,3}/g);
    var ipHost = match != null ? match : 0;
    var base_url = "";
    console.log(host);
    console.log(protocol);
    console.log(pathName)
    console.log(pathArray)
    // if (host == "localhost" || ipHost.length == 4) {
    //     var pathArray = pathName.split("/");
    //     base_url = protocol + "//" + host + "/" + pathArray[1];
    // } else {
    base_url = protocol + "//" + host;
    // }
    console.log(base_url);
    </script>
    <script
        src="<?php echo base_url(); ?>assets/src/custom/plugins/highcharts/code/highcharts.js?v=<?php echo time(); ?>">
    </script>
    <script src="<?php echo base_url(); ?>assets/src/custom/plugins/moment/moment-timezone.js">
    </script>
    <script src="<?php echo base_url(); ?>assets/src/custom/plugins/moment/moment-timezone-with-data.js">
    </script>
</head>
<body
    class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
    <div class="m-grid m-grid--hor m-grid--root m-page">
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
