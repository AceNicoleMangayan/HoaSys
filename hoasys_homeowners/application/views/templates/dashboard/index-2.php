<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-subheader" style="padding-bottom: 10rem;background-color: #f0f1f7;">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title" style="color: #073A4B;"> Dashboard</h3>
            </div>
            <br>
        </div>
    </div>
    <div class="m-content" style="margin-top: -11rem !important;">
        <div class="m-portlet content_portlet">
            <div class="m-portlet__head" style="padding: 1rem 1rem 1rem 1.5rem !important;background:#073A4B;">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text" style="color:white !important;">Latest Updates</h3>
                    </div>
                </div>
            </div>

            <style>
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        filter: invert(100%);
    }

    .carousel-control-prev, .carousel-control-next {
        color: #000000; /* Black color */
        top: 50%; /* Adjust the top position */
        transform: translateY(-50%); /* Center vertically */
    }

    .carousel-control-prev {
        left: 0; /* Adjust the left position for the previous button */
    }

    .carousel-control-next {
        right: 0; /* Adjust the right position for the next button */
    }

</style>

<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2 class="text-center mb-4 mt-3">Announcements</h2>
            <div id="carouselExample" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <!-- Add your carousel items here -->
                    <div class="carousel-item active">
                        <div class="text-center top_logo">
                            <img class="mx-auto d-block" src="<?php echo base_url('/assets/images/party.jpg'); ?>" style="max-width: 350px; max-height: 250px;">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="text-center top_logo">
                            <img class="mx-auto d-block" src="<?php echo base_url('/assets/images/clean.png'); ?>" style="max-width: 350px; max-height: 250px;">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="text-center top_logo">
                            <img class="mx-auto d-block" src="<?php echo base_url('/assets/images/halloween.jpg'); ?>" style="max-width: 350px; max-height: 250px;">
                        </div>
                    </div>
                    <!-- Add more items as needed -->
                </div>
                <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>
</div>
<li style="list-style-type: none; text-align: center; margin-top: 20px;" class="<?= (current_url() === base_url('announcement')) ? 'active' : '' ?>">
    <a href="<?= base_url('announcement') ?>" style="color: black; text-decoration: underline;">View All Announcements</a>
</li>

<h2 class="text-center mb-4 mt-5">Current Officers</h2>

            <div class="m-portlet__body">
                <div class="m-form m-form--label-align-right m--margin-buttom-20" style="margin-bottom: 20px">
                    <div class="row align-items-center">
                        <!-- test here  -->
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 offset-md-3">
                                    <!-- <h2>Circular Graph</h2> -->
                                    <div id="chart-container"></div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="m-section" id="">
                    <div class="m_datatable" id=""></div>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="submit_committee" method="post">
    <div class="modal fade" id="committee_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div style="margin: 0 20px">
                        <div class="form-group m-form__group row">
                            <div style="margin: 20px auto;">
                                <h2 style="text-align: center!important;">Admin</h2>
                                <h5 style="text-align: center!important;" class="text-muted" id="subheader"></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Last Name <span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="committee_iD">
                                    <input type="hidden" id="action">
                                    <input type="text" class="form-control m-input m-input--solid" id="lname"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">First Name <span
                                            class="m--font-danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--solid" id="fname"
                                        autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Middle Name <span
                                            class="m--font-danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--solid" id="mname"
                                        autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Username <span
                                            class="m--font-danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--solid" id="username"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-4" style="margin: auto">
                                <button type="button"
                                    class="btn m-btn--pill m-btn--air btn-outline-info change_password_button"
                                    data-type="change" id="change_button">Change Password</button>
                                <div class="form-group" id="change_password_input" style="display: none">
                                    <label for="recipient-name" class="m--font-bolder">Password <span
                                            class="m--font-danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--solid" id="password"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-2" id="cancel_button" style="display: none; margin: auto">
                                <button type="button"
                                    class="btn m-btn--pill m-btn--air btn-outline-info change_password_button"
                                    data-type="cancel">Cancel</button>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Role <span
                                            class="m--font-danger">*</span></label>
                                    <select
                                        class="form-control m-bootstrap-select m-bootstrap-select--solid m_selectpicker"
                                        title="Role" tabindex="-98" id="role" required>
                                        <option value="admin">Admin</option>
                                        <option value="regular">Regular</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <!-- <label for="recipient-name" class="m--font-bolder">Authorization</label> -->
                                    <!-- <label class="m-checkbox">
                                        <input type="checkbox" id="authorize_change" name="authorize_change"> Authorized
                                        to confirm adding and updating of Participant Record.
                                        <span></span>
                                    </label> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-metal" data-dismiss="modal">Discard</button>&nbsp; <button
                        type="submit" class="btn btn-info">Submit</button>
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
    test();
});


</script>
