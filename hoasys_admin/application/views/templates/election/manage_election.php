<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
.modal-open {
    overflow: hidden;
}

.modal {
    overflow-y: auto;
}
</style>
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-subheader" style="padding-bottom: 10rem;background-color: #f0f1f7">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title" style="color: #073A4B;"> Create Election </h3>
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
                            List of Election
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="tab-content">
                    <div class="tab-pane active show" id="MinorP" role="tabpanel">
                        <div class="m-form m-form--label-align-right m--margin-buttom-20" style="margin-bottom: 20px">
                            <div class="row">
                                <div class="col-xl-6 col-sm-12 text-right">
                                    <div class="m-input-icon m-input-icon--left">
                                        <input type="text" autocomplete="off"
                                            class="form-control m-input m-input--solid"
                                            placeholder="Search election title..." id="search_election">
                                        <span class="m-input-icon__icon m-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                </div>
                                <div class="col-xl-4" style="text-align:end">
                                    <a id="add_election_btn" href="#"
                                        class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill add_homeowner">
                                        <span>
                                            <i class="la la-plus"></i>
                                            <span>Add Election</span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="m-section" id="refresh_election">
                            <div class="m_datatable" id="datatable_election"></div>
                        </div>
                    </div>
                    <div class="tab-pane" id="MajorP" role="tabpanel">
                        <div class="m-form m-form--label-align-right m--margin-buttom-20" style="margin-bottom: 20px">
                            <div class="row">
                                <div class="col-xl-6 "></div>
                                <div class="col-xl-6 col-sm-12 text-right">
                                    <div class="m-input-icon m-input-icon--left">
                                        <input type="text" autocomplete="off"
                                            class="form-control m-input m-input--solid"
                                            placeholder="Search prize name ..." id="search_Field_prize_major">
                                        <span class="m-input-icon__icon m-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m-section" id="refresh_prize_major">
                            <div class="m_datatable" id="datatable_prizes_major"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="submit_election_details" method="post">
    <div class="modal fade" id="election_form" data-id="0" tabindex="-1" role="dialog"
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
                                <h3 style="text-align: center!important;">Create Election</h3>
                                <h5 style="text-align: center!important;" class="text-muted" id="subheader"></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Election Name<span
                                            class="m--font-danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--solid" id="election_name"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label class="m--font-bolder">Election Description</label>
                                    <textarea class="form-control m-input m-input--solid" style="height: 150px;"
                                        id="election_description" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-xl-12" id="sel_ho">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Select Positions <small
                                            class="text-primary"> (* Can Select Multiple Positions)</small></label>
                                    <select class="selectpicker m-input m-input--square" data-width="100%"
                                        name="select_positions" liveSearch=true id="select_positions"
                                        data-live-search="true" data-actions-box="true" multiple>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 mt-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Select Election <span
                                            class="text-success">Start</span><small class="text-warning"> (* Select time
                                            by 30 min interval, e.g. 12:00, 12:30)</small></label>
                                    <input class="form-control" id="date_Start" type="datetime-local"
                                        placeholder="Select Date and Time">
                                </div>
                            </div>
                            <div class="col-xl-6 mt-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Select Election <span
                                            class="text-danger">End</span><small class="text-warning"> (* Select time by
                                            30 min interval, e.g. 12:00, 12:30)</small></label>
                                    <input class="form-control" id="date_End" type="datetime-local"
                                        placeholder="Select Date and Time">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-metal" data-dismiss="modal">Close</button>&nbsp; <button
                        type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
<form id="submit_election_details_update" method="post">
    <div class="modal fade" id="election_form_update" data-id="0" tabindex="-1" role="dialog"
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
                                <h3 style="text-align: center!important;">Update Election</h3>
                                <h5 style="text-align: center!important;" class="text-muted" id="subheader"></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Election Name<span
                                            class="m--font-danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--solid"
                                        id="election_name_update" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label class="m--font-bolder">Election Description</label>
                                    <textarea class="form-control m-input m-input--solid" style="height: 150px;"
                                        id="election_description_update" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-xl-6 mt-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Select Election <span
                                            class="text-success">Start</span><small class="text-warning"> (* Select time
                                            by 30 min interval, e.g. 12:00, 12:30)</small></label>
                                    <input class="form-control" id="date_Start_update" type="datetime-local"
                                        placeholder="Select Date and Time">
                                </div>
                            </div>
                            <div class="col-xl-6 mt-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Select Election <span
                                            class="text-danger">End</span><small class="text-warning"> (* Select time by
                                            30 min interval, e.g. 12:00, 12:30)</small></label>
                                    <input class="form-control" id="date_End_update" type="datetime-local"
                                        placeholder="Select Date and Time">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-metal" data-dismiss="modal">Close</button>
                    <div id="buttons_statuses">
                    </div>
                    <button type="submit" class="btn btn-success" id="update_save_changes_voting_btn">Save
                        Changes</button>
                </div>
            </div>
        </div>
    </div>
</form>
<form id="submit_election_settings_display" method="post">
    <div class="modal fade" id="election_settings_display" data-id="0" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px" role="document">
            <div class="modal-content p-3">
                <div class="modal-body" style="max-height: 450px; overflow-x: auto;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <div class="form-group m-form__group row">
                        <div style="margin: 20px auto;">
                            <h3 style="text-align: center!important;" id="elect_title_display_pos"></h3>
                            <h5 style="text-align: center!important;" class="text-muted" id="subheader"></h5>
                        </div>
                    </div>
                    <h4>POSITIONS SETTINGS</h4>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5><span class="text-primary">1. </span>Add Position</h5>
                        </div>
                        <div class="col-xl-8">
                            <select class="form-control m-select2 m-input m-input--square"
                                name="select_positions_specific" id="select_positions_specific">
                            </select>
                        </div>
                        <div class="col-xl-4">
                            <button type="button" id="add_new_pos_btn" class="btn btn-block btn-success btn-icon"><i
                                    class="fa fa-check"></i> <span>Add Position</span></button>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5><span class="text-primary mt-2">2. </span>Position/s Added</h5>
                        </div>
                    </div>

                    <div id="election_positions_list" class="row">
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

<form id="submit_election_candidate" method="post">
    <div class="modal fade" id="election_candidate" data-id="0" data-elect="0" data-pos="0" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px" role="document">
            <div class="modal-content">
                <div class="form-group m-form__group row mt-4 mb-1">
                    <div style="margin: 20px auto;">
                        <h3 style="text-align: center!important;"><span class="text-primary"
                                id="election_name_cand">Name </span> Candidates <i class="fa fa-cog"></i></h3>
                        <h5 style="text-align: center!important;" class="text-muted"></h5>
                    </div>
                </div>
                <div class="p-1 mt-1" style="max-height: 470px; overflow-y: auto;margin-top: -42px;">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div style="margin: 0 20px">
                            <div class="row mt-2">
                                <div class="col-12">
                                    <h5><span class="text-primary">1. </span>Number of Winner/s</h5>
                                </div>
                                <div class="col-xl-10 col-sm-12"><input type="number" class="form-control" value="0"
                                        id="winner_candidate" min="0" step="1" required>
                                </div>
                                <div class="col-xl-2 col-sm-12">
                                    <!-- <button type="button" id="winner_save_btn" class="btn btn-success btn-sm edit-icon-btn mr-2 btn-block">
                                        <i class="fa fa-check"></i>
                                    </button> -->
                                    <button type="button" id="winner_save_btn" class="btn btn-success"><i
                                            class="fa fa-check"></i></button>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h5><span class="text-primary">2. </span>Add a Candidate</h5>
                                </div>
                                <br>
                                <div class="form-group m-form__group col-12">
                                    <!-- <label class="label_irequest" for="exampleSelect1">Select Homeowner</label> -->
                                    <select class="form-control m-select2 m-input m-input--square"
                                        name="election_candidate_options" id="election_candidate_options">
                                    </select>
                                </div>
                                <div class="col-12">
                                    <textarea class="form-control m-input m-input--solid"
                                        placeholder="Type candidate description ..." style="height: 100px;"
                                        id="candidate_desc" rows="2"></textarea>
                                </div>
                                <div class="col-5 mt-2">
                                </div>
                                <div class="col-7 text-right mt-2">
                                    <button type="button" id="add_new_candidate"
                                        class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
                                        Save
                                        Candidate</button>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-4" id="refresh_candidates">
                                <div class="col-6">
                                    <h5><span class="text-primary">3. </span>List of Candidate/s</h5>
                                    <span><i>(Kindly scroll down to view more records)</i></span>
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control" id="search_candidates"
                                        placeholder="Search Candidates ...">
                                </div>
                                <div class="m_datatable col-12" id="datatable_candidates"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="candidate_close_btn" class="btn btn-metal"
                        data-dismiss="modal">Close</button>&nbsp;
                </div>
            </div>
        </div>
    </div>
</form>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
flatpickr("input[type=datetime-local]", {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    minuteIncrement: 30,
});
</script>
<script src="<?php echo base_url() ?>assets/src/custom/js/election/election_management.js?<?php echo $date_time; ?>">
</script>