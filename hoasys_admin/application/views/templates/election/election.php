<style>
#loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    z-index: 9999;
}

.spinner {
    border: 8px solid #f3f3f3;
    border-top: 8px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    margin: 20% auto;
    /* Adjust the margin to center the spinner */
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-subheader" style="padding-bottom: 10rem;background-color: #f0f1f7">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title" style="color: #073A4B;"> Election </h3>
                <input type="hidden" id="refresh_data_Update">
            </div>
            <br>
        </div>
    </div>
    <div class="m-content" style="margin-top: -11rem !important;">
        <!-- Add a loading overlay -->
        <div id="loading-overlay">
            <div class="spinner"></div>
        </div>
        <div class="m-portlet">
            <div class="m-portlet__head" style="background:#073A4B">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text" style="color:white !important">
                            Ongoing Election(s)
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <!-- <button type="button" id="open_test" class="btn btn-primary btn-icon"><i class="fa fa-remove"></i>
                        <span>Open </span></button> -->
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="m-portlet">
                    <div id="ongoing_elections_list" class="m-portlet__body  m-portlet__body--no-padding">

                    </div>
                </div>
            </div>
        </div>
        <div class="m-portlet">
            <div class="m-portlet__head" style="background:#073A4B">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text" style="color:white !important">
                            Published Election - (Viewing of Election Result)
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="m-portlet">
                    <div id="published_elections_list" class="m-portlet__body  m-portlet__body--no-padding">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<form id="submit_concern_email" method="post">
    <div class="modal fade" id="concern_send_details" data-id="0" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div style="margin: 0 20px">
                        <div class="form-group m-form__group row">
                            <div style="margin: 20px auto;">
                                <h3 style="text-align: center!important;">Send Email</h3>
                                <h5 style="text-align: center!important;" class="text-muted" id="subheader"></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Send To <span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="concern_ID_Update">
                                    <input type="text" class="form-control m-input m-input--solid" id="send_to"
                                        autocomplete="off" required disabled>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Subject <span
                                            class="m--font-danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--solid" id="subject_concern"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label class="m--font-bolder">Email Message to Sender</label>
                                    <textarea class="form-control m-input m-input--solid" style="height: 150px;"
                                        id="email_content" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-metal" data-dismiss="modal">Close</button>&nbsp; <button
                        type="submit" class="btn btn-success">Send</button>
                </div>
            </div>
        </div>
    </div>
</form>
<form id="submit_election_details_ongoing" method="post">
    <div class="modal fade" id="election_details_ongoing" data-id="0" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="form-group m-form__group row">
                        <div style="margin: 20px auto;">
                            <h3 style="text-align: center!important;" id="election_title_showing">Title</h3>
                            <h5 style="text-align: center!important;" class="text-success">Voters : <span id="voter_count_display">0</span></h5>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="m-portlet">
                            <div id="result-container" class="m-portlet__body  m-portlet__body--no-padding">

                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-metal" data-dismiss="modal">Close</button>&nbsp;
                    <button type="button" id="solve_btn" class="btn btn-success btn-icon" style="display:none;"><i
                            class="fa fa-check"></i> <span>Mark as solved </span></button>&nbsp;
                    <button type="button" id="unsolve_btn" class="btn btn-danger btn-icon" style="display:none;"><i
                            class="fa fa-remove"></i> <span>Mark as Unsolved </span></button>&nbsp;
                </div>
            </div>
        </div>
    </div>
</form>
<form id="submit_balot" method="post">
    <div class="modal fade" id="election_details_ongoing_balot" data-id="0" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="m-portlet__body">
                        <div class="text-center">
                            <h4>OFFICIAL BALLOT</h4>
                        </div>
                        <div class="m-portlet">
                            <div id="result-container-balot" class="m-portlet__body  m-portlet__body--no-padding">

                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-metal" data-dismiss="modal">Close</button>&nbsp;
                    <button type="button" id="submit_balot_btn" class="btn btn-success btn-icon"><i
                            class="fa fa-check"></i> <span>Submit Balot</span></button>&nbsp;
                </div>
            </div>
        </div>
    </div>
</form>
<script src="<?php echo base_url() ?>assets/src/custom/js/election/election.js?<?php echo $date_time; ?>">
</script>