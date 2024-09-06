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
                <h3 class="m-subheader__title" style="color: #073A4B;"> Homeowners </h3>
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
                            List of Homeowners
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
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <div class="m-input-icon m-input-icon--left">
                                        <input type="text" autocomplete="off"
                                            class="form-control m-input m-input--solid"
                                            placeholder="Search name, block, lot, village, username..."
                                            id="search_Field_ho">
                                        <span class="m-input-icon__icon m-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control" name="block_num" id="block_num"
                                            style="width: 100%;">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control" name="lot_num" id="lot_num" style="width: 100%;">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                </div>
                                <div class="col-lg-5 col-md-12 col-sm-12" style="text-align: end;">
                                    <a id="addprize_btn" href="#"
                                        class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill add_homeowner">
                                        <span>
                                            <i class="la la-plus"></i>
                                            <span>Add Homeowner</span>
                                        </span>
                                    </a>
                                </div>
                                <div class="col-lg-1 col-md-12 col-sm-12" style="text-align: end;">
                                    <button type="button" id="download_homeowners_report"
                                        class="btn btn-success btn-block m-btn m-btn--icon btn-lg m-btn--icon-only">
                                        <i class="fa fa-download"></i>
                                    </button>

                                </div>
                            </div>
                        </div>

                        <div class="m-section" id="refresh_homeowners">
                            <div class="m_datatable" id="datatable_homeowners"></div>
                        </div>
                    </div>
                    <div class="tab-pane" id="MajorP" role="tabpanel">
                        <div class="m-form m-form--label-align-right m--margin-buttom-20" style="margin-bottom: 20px">
                            <div class="row">
                                <div class="col-xl-6 "></div>
                                <div class="col-xl-6 col-sm-12 text-right">
                                    <div class="m-input-icon m-input-icon--left">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m-section" id="refresh____">
                            <div class="m_datatable" id="datatable__"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- test end  -->
    </div>
</div>
<form id="submit_homeowner" method="post">
    <div class="modal fade" id="raffle_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                                <h3 style="text-align: center!important;">NEW HOMEOWNER</h3>
                                <h5 style="text-align: center!important;" class="text-muted" id="subheader"></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 pt-4 text-primary"><label>Basic Information</label></div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">First Name<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="prize_ID_Update">
                                    <input type="hidden" id="action">
                                    <input type="text" class="form-control m-input m-input--solid" id="first_name"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Middle Name</label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="text" class="form-control m-input m-input--solid" id="mid_name">
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Last Name<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="text" class="form-control m-input m-input--solid" id="last_name"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Block<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="text" class="form-control m-input m-input--solid" id="block"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Lot<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="text" class="form-control m-input m-input--solid" id="lot"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-8">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Village<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="text" class="form-control m-input m-input--solid" id="village"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Contact Number<span
                                            class="m--font-danger">*</span></label>
                                    <input id="contact_number" type="number" min="0" value=""
                                        oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null"
                                        class="form-control m-input m-input--solid" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Email Address<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="email" class="form-control m-input m-input--solid" id="email_address"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Username<span
                                            class="m--font-danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--solid" id="username"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <!-- <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Password<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="text" class="form-control m-input m-input--solid" id="pass"
                                        autocomplete="off" required>
                                </div>
                            </div> -->
                        </div>
                        <div class="row" style="border-top: 0.3px dashed #c3c3c3;">
                            <div class="col-xl-12 pt-4 text-primary"><label>Other owner details</label></div>
                            <div class="col-xl-4 mt-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Type of Owner<span
                                            class="m--font-danger">*</span></label>
                                    <select
                                        class="form-control m-bootstrap-select m-bootstrap-select--solid m_selectpicker"
                                        title="Owner" tabindex="-98" id="type_of_owner" required>
                                        <option value="homeowner" selected>Homeowner</option>
                                        <option value="lot owner">Lot Owner</option>
                                        <option value="renter">Renter</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 mt-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Move-in Month<span
                                            class="m--font-danger">*</span></label>
                                    <select
                                        class="form-control m-bootstrap-select m-bootstrap-select--solid m_selectpicker"
                                        title="Month" tabindex="-98" id="month_options_add" data-live-search="true"
                                        required>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 mt-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Move-in Year<span
                                            class="m--font-danger">*</span></label>
                                    <select
                                        class="form-control m-bootstrap-select m-bootstrap-select--solid m_selectpicker"
                                        title="Year" tabindex="-98" id="year_options_add" data-live-search="true"
                                        required>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="border-top: 0.3px dashed #c3c3c3;">
                            <div class="col-xl-12 pt-4 text-primary"><label>Election details</label></div>
                            <div class="col-xl-6 mt-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Allowed to Vote<span
                                            class="m--font-danger">*</span></label>
                                    <select
                                        class="form-control m-bootstrap-select m-bootstrap-select--solid m_selectpicker"
                                        title="Vote" tabindex="-98" id="allowed_to_vote" required>
                                        <option value="1" selected>Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 mt-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Allowed to Run (as Candidate)
                                        <span class="m--font-danger">*</span></label>
                                    <select
                                        class="form-control m-bootstrap-select m-bootstrap-select--solid m_selectpicker"
                                        title="Vote" tabindex="-98" id="allowed_to_run" required>
                                        <option value="1" selected>Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="border-top: 0.3px dashed #c3c3c3;">
                            <div class="col-xl-12 pt-4 text-primary"><label>Monthly Dues Details</label></div>
                            <div class="col-xl-6 mt-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Monthly Payment<span
                                            class="m--font-danger">*</span></label>
                                    <!-- <input type="number" class="form-control m-input m-input--solid" id="monthly_payment"
                                        autocomplete="off" required> -->
                                    <input type="text" pattern="\d+(\.\d{1,2})?"
                                        class="form-control m-input m-input--solid" id="monthly_payment"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-6 mt-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Good Payer<span
                                            class="m--font-danger">*</span></label>
                                    <select
                                        class="form-control m-bootstrap-select m-bootstrap-select--solid m_selectpicker"
                                        title="Payer" tabindex="-98" id="good_payer" required>
                                        <option value="1" selected>Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 mt-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Due Date (Every specific day of the Month) <span class="m--font-danger">*</span></label>
                                    <!-- <input placeholder="Type Either 1-31 day..." type="number"
                                        class="form-control m-input m-input--solid" id="due_date_payment"
                                        autocomplete="off" required> -->
                                    <select
                                        class="form-control m-bootstrap-select m-bootstrap-select--solid m_selectpicker"
                                        title="Day of the Month" tabindex="-98" id="due_date_payment" required>
                                        <?php for ($i = 1; $i <= 31; $i++) { ?>
                                        <option value="<?php echo $i; ?>" <?php if ($i === 1) echo 'selected'; ?>>
                                            <?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-metal" data-dismiss="modal">Close</button>&nbsp; <button
                        type="submit" class="btn btn-info">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>
<form id="submit_homeowner_update" method="post">
    <div class="modal fade" id="homeowner_update_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                                <h3 style="text-align: center!important;">UPDATE HOMEOWNER</h3>
                                <h5 style="text-align: center!important;" class="text-muted" id="subheader"></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 pt-4 text-primary"><label>Basic Information</label></div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">First Name<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="homeowner_ID_update">
                                    <input type="hidden" id="action">
                                    <input type="text" class="form-control m-input m-input--solid" id="first_name_up"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Middle Name<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="text" class="form-control m-input m-input--solid" id="mid_name_up"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Last Name<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="text" class="form-control m-input m-input--solid" id="last_name_up"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Block<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="text" class="form-control m-input m-input--solid" id="block_up"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Lot<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="text" class="form-control m-input m-input--solid" id="lot_up"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-8">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Village<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="text" class="form-control m-input m-input--solid" id="village_up"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Email Address<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="email" class="form-control m-input m-input--solid"
                                        id="email_address_up" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Contact Number<span
                                            class="m--font-danger">*</span></label>
                                    <input id="contact_number_up" type="number" min="0" value="1"
                                        oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null"
                                        class="form-control m-input m-input--solid" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Username<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="text" class="form-control m-input m-input--solid" id="username_up"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Status <span
                                            class="m--font-danger">*</span></label>
                                    <select
                                        class="form-control m-bootstrap-select m-bootstrap-select--solid m_selectpicker"
                                        title="Status" tabindex="-98" id="status_up" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="border-top: 0.3px dashed #c3c3c3;">
                            <div class="col-xl-12 pt-4 text-primary"><label>Other owner details</label></div>
                            <div class="col-xl-4 mt-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Type of Owner<span
                                            class="m--font-danger">*</span></label>
                                    <select
                                        class="form-control m-bootstrap-select m-bootstrap-select--solid m_selectpicker"
                                        title="Owner" tabindex="-98" id="type_of_owner_up" required>
                                        <option value="homeowner">Homeowner</option>
                                        <option value="lot owner">Lot Owner</option>
                                        <option value="renter">Renter</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 mt-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Move-in Month<span
                                            class="m--font-danger">*</span></label>
                                    <select
                                        class="form-control m-bootstrap-select m-bootstrap-select--solid m_selectpicker"
                                        title="Month" tabindex="-98" id="month_options_add_up" data-live-search="true"
                                        required>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 mt-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Move-in Year<span
                                            class="m--font-danger">*</span></label>
                                    <select
                                        class="form-control m-bootstrap-select m-bootstrap-select--solid m_selectpicker"
                                        title="Year" tabindex="-98" id="year_options_add_up" data-live-search="true"
                                        required>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="border-top: 0.3px dashed #c3c3c3;">
                            <div class="col-xl-12 pt-4 text-primary"><label>Election details</label></div>
                            <div class="col-xl-6 mt-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Allowed to Vote<span
                                            class="m--font-danger">*</span></label>
                                    <select
                                        class="form-control m-bootstrap-select m-bootstrap-select--solid m_selectpicker"
                                        title="Vote" tabindex="-98" id="allowed_to_vote_up" required>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 mt-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Allowed to Run (as Candidate)
                                        <span class="m--font-danger">*</span></label>
                                    <select
                                        class="form-control m-bootstrap-select m-bootstrap-select--solid m_selectpicker"
                                        title="Vote" tabindex="-98" id="allowed_to_run_up" required>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="border-top: 0.3px dashed #c3c3c3;">
                            <div class="col-xl-12 pt-4 text-primary"><label>Monthly Dues Details</label></div>
                            <div class="col-xl-6 mt-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Monthly Payment<span
                                            class="m--font-danger">*</span></label>
                                    <input type="text" pattern="\d+(\.\d{1,2})?"
                                        class="form-control m-input m-input--solid" id="monthly_payment_up"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-6 mt-2">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Good Payer<span
                                            class="m--font-danger">*</span></label>
                                    <select
                                        class="form-control m-bootstrap-select m-bootstrap-select--solid m_selectpicker"
                                        title="Payer" tabindex="-98" id="good_payer_up" required>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 pt-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Due Date (Every specific day of the Month) <span class="m--font-danger">*</span></label>
                                    <select
                                        class="form-control m-bootstrap-select m-bootstrap-select--solid m_selectpicker"
                                        title="Day of the Month" tabindex="-98" id="due_date_payment_up" required>
                                        <?php for ($i = 1; $i <= 31; $i++) { ?>
                                        <option value="<?php echo $i; ?>" <?php if ($i === 1) echo 'selected'; ?>>
                                            <?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-metal" data-dismiss="modal">Close</button>&nbsp; <button
                        type="submit" class="btn btn-info">Submit Changes</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="<?php echo base_url() ?>assets/src/custom/js/homeowner/homeowner.js?<?php echo $date_time; ?>">
</script>