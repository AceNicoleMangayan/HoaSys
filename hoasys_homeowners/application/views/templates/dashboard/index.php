<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-subheader" style="padding-bottom: 10rem;background-color: #f0f1f7;">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title text-capitalize" style="color: #073A4B;">Hello, <?php echo $fullname;?>!</h3>
            </div>
              <div class="">
                <h3 class="m-subheader__title text-capitalize <?php echo $color;?>"  style=""><?php echo $payer;?></h3>
            </div>
            <br>
        </div>
    </div>
    <div class="m-content row" style="margin-top: -11rem !important;">
        <div class="col-xl-12 col-sm-12 col-md-12" id="announcement_port" style="display: none;">
            <div class="m-portlet content_portlet">
                <div class="m-portlet__head" style="padding: 1rem 1rem 1rem 1.5rem !important;background:#073A4B;">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text" style="color:white !important;">Latest Announcements</h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-widget3">
                        <div id="announcement_latest">

                        </div>
                        <!-- See more block -->
                        <div class="m-widget3__item m-widget3__see-more">
                            <button class="btn btn-secondary btn-block" id="see_more_btn">
                                See more announcements
                            </button>
                        </div>
                        <!-- End See more block -->

                    </div>
                </div>

            </div>
        </div>
        <div class="col-xl-6 col-sm-12 col-md-12">
            <div class="m-portlet content_portlet">
                <div class="m-portlet__head" style="padding: 1rem 1rem 1rem 1.5rem !important;background:#073A4B;">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text" style="color:white !important;">Counts</h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-form m-form--label-align-right m--margin-buttom-20" style="margin-bottom: 20px">
                        <div class="row align-items-center">
                            <div class="col-xl-12">
                                <!--begin:: Widgets/Activity-->
                                <div class="m-portlet m-portlet--bordered-semi m-portlet--widget-fit m-portlet--full-height m-portlet--skin-light  m-portlet--rounded-force" style="
">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <h3 class="m-portlet__head-text m--font-light" style="color:white !important;">
                                                    One View Counts
                                                </h3>
                                            </div>
                                        </div>
                                        <!--<div class="m-portlet__head-tools">-->
                                        <!--    <ul class="m-portlet__nav">-->
                                        <!--        <li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">-->
                                                    <!--<a href="#" class="m-portlet__nav-link m-portlet__nav-link--icon m-portlet__nav-link--icon-xl">-->
                                                    <!--    <i class="fa fa-genderless m--font-light"></i>-->
                                                    <!--</a>-->
                                        <!--            <div class="m-dropdown__wrapper">-->
                                        <!--                <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" style="left: auto; right: 21.2847px;"></span>-->
                                        <!--                <div class="m-dropdown__inner">-->
                                        <!--                    <div class="m-dropdown__body">-->
                                        <!--                        <div class="m-dropdown__content">-->
                                        <!--                            <ul class="m-nav">-->
                                        <!--                                <li class="m-nav__section m-nav__section--first">-->
                                        <!--                                    <span class="m-nav__section-text">Quick-->
                                        <!--                                        Actions</span>-->
                                        <!--                                </li>-->
                                        <!--                                <li class="m-nav__item">-->
                                        <!--                                    <a href="#" class="m-nav__link">-->
                                        <!--                                        <i class="m-nav__link-icon flaticon-share"></i>-->
                                        <!--                                        <span class="m-nav__link-text">Activity</span>-->
                                        <!--                                    </a>-->
                                        <!--                                </li>-->
                                        <!--                                <li class="m-nav__item">-->
                                        <!--                                    <a href="#" class="m-nav__link">-->
                                        <!--                                        <i class="m-nav__link-icon flaticon-chat-1"></i>-->
                                        <!--                                        <span class="m-nav__link-text">Messages</span>-->
                                        <!--                                    </a>-->
                                        <!--                                </li>-->
                                        <!--                                <li class="m-nav__item">-->
                                        <!--                                    <a href="#" class="m-nav__link">-->
                                        <!--                                        <i class="m-nav__link-icon flaticon-info"></i>-->
                                        <!--                                        <span class="m-nav__link-text">FAQ</span>-->
                                        <!--                                    </a>-->
                                        <!--                                </li>-->
                                        <!--                                <li class="m-nav__item">-->
                                        <!--                                    <a href="#" class="m-nav__link">-->
                                        <!--                                        <i class="m-nav__link-icon flaticon-lifebuoy"></i>-->
                                        <!--                                        <span class="m-nav__link-text">Support</span>-->
                                        <!--                                    </a>-->
                                        <!--                                </li>-->
                                        <!--                                <li class="m-nav__separator m-nav__separator--fit">-->
                                        <!--                                </li>-->
                                        <!--                                <li class="m-nav__item">-->
                                        <!--                                    <a href="#" class="btn btn-outline-danger m-btn m-btn--pill m-btn--wide btn-sm">Cancel</a>-->
                                        <!--                                </li>-->
                                        <!--                            </ul>-->
                                        <!--                        </div>-->
                                        <!--                    </div>-->
                                        <!--                </div>-->
                                        <!--            </div>-->
                                        <!--        </li>-->
                                        <!--    </ul>-->
                                        <!--</div>-->
                                    </div>
                                    <div class="m-portlet__body" style="padding-bottom: 0.9em;">
                                        <div class="m-widget17">
                                            <div class="m-widget17__visual m-widget17__visual--chart m-portlet-fit--top m-portlet-fit--sides m--bg-danger" style="
">
                                                <div class="m-widget17__chart" style="height:118px;background: #5867dd;">
                                                    <div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                                                        <div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                                            <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0">
                                                            </div>
                                                        </div>
                                                        <div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                                            <div style="position:absolute;width:200%;height:200%;left:0; top:0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <canvas id="m_chart_activities" width="875" height="216" style="display: block; width: 875px; height: 216px;" class="chartjs-render-monitor"></canvas>
                                                </div>
                                            </div>
                                            <div class="m-widget17__stats">
                                                <div class="m-widget17__items m-widget17__items-col1">
                                                    <div class="m-widget17__item unsolved_click" style=" cursor: pointer;">
                                                        <span class="m-widget17__icon">
                                                            <i class="flaticon-paper-plane m--font-brand"></i>
                                                        </span>
                                                        <span class="m-widget17__subtitle">
                                                            <strong id="unsolved_num">0</strong> Unsolved
                                                        </span>
                                                        <span class="m-widget17__desc">
                                                            Unsolved Concerns
                                                        </span>
                                                    </div>
                                                    <div class="m-widget17__item active_click" style=" cursor: pointer;">
                                                        <span class="m-widget17__icon">
                                                            <i class="flaticon-coins m--font-info"></i>
                                                        </span>
                                                        <span class="m-widget17__subtitle">
                                                            <strong id="active_num">0</strong> Paid
                                                        </span>
                                                        <span class="m-widget17__desc">
                                                            Your Paid Dues
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="m-widget17__items m-widget17__items-col2">
                                                    <div class="m-widget17__item ongoing_click" style=" cursor: pointer;">
                                                        <span class="m-widget17__icon">
                                                            <i class="flaticon-pie-chart m--font-success"></i>
                                                        </span>
                                                        <span class="m-widget17__subtitle">
                                                            <strong id="ongoing_num">0</strong> Ongoing
                                                        </span>
                                                        <span class="m-widget17__desc">
                                                            Ongoing Election
                                                        </span>
                                                    </div>
                                                    <div class="m-widget17__item unpaid_click" style=" cursor: pointer;">
                                                        <span class="m-widget17__icon">
                                                            <i class="flaticon-coins m--font-danger"></i>
                                                        </span>
                                                        <span class="m-widget17__subtitle">
                                                            <strong id="unpaid_num">0</strong> Unpaid
                                                        </span>
                                                        <span class="m-widget17__desc">
                                                            Your Unpaid Dues
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end:: Widgets/Activity-->
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-xl-6 col-sm-12 col-md-12">
            <div class="m-portlet content_portlet">
                <div class="m-portlet__head" style="padding: 1rem 1rem 1rem 1.5rem !important;background:#073A4B;">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text" style="color:white !important;">Dues Status</h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-form m-form--label-align-right m--margin-buttom-20" style="margin-bottom: 20px">
                        <div class="row align-items-center">
                            <!-- test here  -->
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <select class="form-control" id="year_chart" required>
                                        </select>
                                    </div>
                                    <div class="col-md-6 offset-md-3">
                                        <!-- <h2>Circular Graph</h2> -->
                                        <div id="chart-container"></div>
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
<form id="" method="post">
    <div class="modal fade" id="announcement_details_dashboard" data-id="0" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div style="margin: 0 20px">
                        <div class="form-group m-form__group row">
                            <div style="margin: 20px auto;">
                                <h3 style="text-align: center!important;" id="announcement_title_dash"></h3>
                                <h5 style="text-align: center!important;" class="text-muted" id="announcement_date_dash">sjdhsjgf</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <!-- <label class="m--font-bolder">Concern Description: </label> -->
                                <p id="announcement_desc_dash">Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-metal" data-dismiss="modal">Close</button>&nbsp;
                </div>
            </div>
        </div>
    </div>
</form>
<!-- javascript -->
<!-- Replace the local inclusion with the Chart.js CDN link -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
<script type="text/javascript">
    $(function() {

        var currentYear = new Date().getFullYear();
        var selectYear = $("#year_chart");

        // Add options for the last 10 years, you can modify the loop as needed
        for (var i = 0; i < 10; i++) {
            var optionYear = currentYear - i;
            selectYear.append("<option value='" + optionYear + "'>" + optionYear + "</option>");
        }

        // Set the current year as the default selected option
        selectYear.val(currentYear.toString());
        get_chart_dues();
        get_count_charts();
    });

    function get_count_charts() {
        $.ajax({
            type: "POST",
            url: "<?= base_url(); ?>dashboard/get_dashboard_counts",
            dataType: "json",
            cache: false,
            success: function(data) {
                console.log(data.concerns[0].concerns);
                $("#unsolved_num").text(data.concerns[0].concerns);
                $("#ongoing_num").text(data.election[0].election);
                $("#active_num").text(data.paid[0].paid);
                $("#unpaid_num").text(data.unpaid[0].unpaid);

                if (data.announcement.length > 0) {
                    // Iterate through the data and generate HTML for each announcement
                    $("#announcement_port").show();
                    $.each(data.announcement, function(index, announcement) {
                        var formattedDate = new Date(announcement.datecreated_anmnt).toLocaleDateString(
                            'en-US', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            });
                        var announcementHTML =
                            '<div class="m-widget3__item">' +
                            '<div class="m-widget3__header">' +
                            '<div class="m-widget3__user-img">' +
                            '<i class="fa fa-bullhorn" style="font-size:x-large;color:#073A4B"></i>' +
                            '</div>' +
                            '<div class="m-widget3__info">' +
                            '<span class="m-widget3__username">' + announcement.title_anmnt +
                            '</span><br>' +
                            '<span class="m-widget3__time">' + formattedDate + '</span>' +
                            '</div>' +
                            '<span data-id="' + announcement.id_anmnt +
                            '" style=" cursor: pointer;" class="m-widget3__status m--font-info view_announcement_modal">View</span>' +
                            '</div>' +
                            '<div class="m-widget3__body">' +
                            '<p class="m-widget3__text text-truncate">' + announcement.desc_anmnt + '</p>' +
                            '</div>' +
                            '</div>';

                        // Append the generated HTML to the #announcement_latest element
                        $('#announcement_latest').append(announcementHTML);
                    });
                } else {
                    $("#announcement_port").hide();
                }
            },
        });
    }

    function get_chart_dues() {
        $.ajax({
            url: "<?= base_url(); ?>dashboard/pie_chart",
            type: "POST",
            dataType: "json",
            data: {
                year: $("#year_chart").val()
            },
            success: function(data) {
                // Create pie chart
                Highcharts.chart('chart-container', {
                    chart: {
                        type: 'pie',
                    },
                    title: {
                        text: 'Homeowners Dues Status'
                    },
                    series: [{
                        name: 'Data',
                        data: data
                    }]
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching data:", error);
            }
        });
    }
    $("#year_chart").on("change", function() {
        get_chart_dues();
    });
    $(".unsolved_click").on("click", function() {
        window.location.href = "<?= base_url('concerns'); ?>";
    });
    $(".ongoing_click").on("click", function() {
        window.location.href = "<?= base_url('election'); ?>";
    });
    $(".active_click").on("click", function() {
        window.location.href = "<?= base_url('dues'); ?>";
    });
    $(".unpaid_click").on("click", function() {
        window.location.href = "<?= base_url('dues'); ?>";
    });
    $("#see_more_btn").on("click", function() {
        window.location.href = "<?= base_url('announcement'); ?>";
    });
    $("#announcement_latest").on("click", ".view_announcement_modal", function() {
        let id = $(this).data("id");
        $.ajax({
            type: "POST",
            url: "<?= base_url(); ?>dashboard/get_specific_announcement_dashboard",
            data: {
                id
            },
            dataType: "json",
            cache: false,
            success: function(data) {
                var formattedDate = new Date(data[0].datecreated_anmnt).toLocaleDateString(
                    'en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                $("#announcement_title_dash").text(data[0].title_anmnt);
                $("#announcement_desc_dash").text(data[0].desc_anmnt);
                $("#announcement_date_dash").text(formattedDate);

            },
        });
        $("#announcement_details_dashboard").modal("show");
    });
</script>