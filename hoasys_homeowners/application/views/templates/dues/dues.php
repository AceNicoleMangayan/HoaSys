<style>
.pay_label {
    font-weight: 500;
    color: slateblue;
}
</style>
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-subheader" style="padding-bottom: 10rem;background-color: #f0f1f7;">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title" style="color: #073A4B;"> Dues </h3>
            </div>
            <br>
        </div>
    </div>
    <div class="m-content" style="margin-top: -11rem !important;">
        <!-- test  -->
        <div class="m-portlet">
            <div class="m-portlet__head" style="padding: 1rem 1rem 1rem 1.5rem !important;background:#073A4B;">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text" style="color:white !important;">List of Dues</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <!-- <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active show" id="MinorP_btn" data-toggle="tab" href="#MinorP">MINOR
                            PRIZES</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="MajorP_btn" data-toggle="tab" href="#MajorP">MAJOR PRIZES</a>
                    </li>
                </ul> -->
                <div class="tab-content">
                    <div class="tab-pane active show" id="MinorP" role="tabpanel">
                        <div class="m-form m-form--label-align-right m--margin-buttom-20" style="margin-bottom: 20px">
                            <div class="row">
                                <div class="col-xl-3 text-left">
                                    <div class="form-group">
                                        <select class="form-control " name="status_pay" id="status_pay"
                                            style="width: 100%;">
                                            <option value="All" selected>All</option>
                                            <option value="paid">Paid</option>
                                            <option value="pending">Pending</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <select class="form-control m-select2" name="month_select_choose"
                                            id="month_select_choose" style="width: 100%;">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <select class="form-control m-select2" name="year_select_choose"
                                            id="year_select_choose" style="width: 100%;">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary" id="view_ledger">View Ledger</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m-section" id="refresh_payment">
                            <div class="m_datatable" id="datatable_payment"></div>
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
                                    <label for="recipient-name" class="m--font-bolder">Last Name<span
                                            class="m--font-danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--solid" id="last_name"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Block<span
                                            class="m--font-danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--solid" id="block"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Lot<span
                                            class="m--font-danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--solid" id="lot"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Contact Number<span
                                            class="m--font-danger">*</span></label>
                                    <input id="contact_number" type="number" min="0" value="1"
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
                                    <label for="recipient-name" class="m--font-bolder">Password<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="text" class="form-control m-input m-input--solid" id="pass"
                                        autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="border-top: 0.3px solid #c3c3c3;">
                            <div class="col-xl-6 pt-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Monthly Payment<span
                                            class="m--font-danger">*</span></label>
                                    <input step="0.1" type="number" class="form-control m-input m-input--solid"
                                        id="monthly_payment" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-6 pt-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Due Date is every (1st, 2nd, 3rd)
                                        of the month <span class="m--font-danger">*</span></label>
                                    <input placeholder="Type Either 1-31 day..." type="number"
                                        class="form-control m-input m-input--solid" id="due_date_payment"
                                        autocomplete="off" required>
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
                                    <label for="recipient-name" class="m--font-bolder">Last Name<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="text" class="form-control m-input m-input--solid" id="last_name_up"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Block<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="text" class="form-control m-input m-input--solid" id="block_up"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Lot<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="text" class="form-control m-input m-input--solid" id="lot_up"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Contact Number<span
                                            class="m--font-danger">*</span></label>
                                    <input id="contact_number_up" type="number" min="0" value="1"
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
                                    <input type="email" class="form-control m-input m-input--solid"
                                        id="email_address_up" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Password<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="text" class="form-control m-input m-input--solid" id="pass_up"
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
<form id="submit_created_billing" method="post">
    <div class="modal fade" id="create_billing_modal" data-type="0" data-id="0" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <input type="hidden" id="hidden_hid">
        <div class="modal-dialog" style="max-width: 800px" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div style="margin: 0 20px">
                        <div class="form-group m-form__group row">
                            <div style="margin: 20px auto;">
                                <h3 id="billing_title" style="text-align: center!important;">CREATE BILLING</h3>
                                <h5 style="text-align: center!important;" class="text-muted" id="subheader"></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Month<span
                                            class="m--font-danger">*</span></label>
                                    <select class="form-control m-select2" name="month_select" id="month_select"
                                        style="width: 100%;">
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Year<span
                                            class="m--font-danger">*</span></label>
                                    <select class="form-control m-select2" name="year_select" id="year_select"
                                        style="width: 100%;">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-metal" data-dismiss="modal">Close</button>&nbsp; <button
                        type="submit" class="btn btn-info">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
<form id="submit_created_billing_ho" method="post">
    <div class="modal fade" id="create_billing_modal_ho" data-exist="0" data-bid="0" data-save="0" data-type="0"
        data-id="0" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <input type="hidden" id="hidden_hid_ho">
        <div class="modal-dialog" style="max-width: 800px" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div style="margin: 0 20px">
                        <div class="form-group m-form__group row">
                            <div style="margin: 20px auto;">
                                <h3 id="billing_title_ho" style="text-align: center!important;">CREATE BILLING PER
                                    HOMEOWNER</h3>
                                <h5 style="text-align: center!important;" class="text-muted" id="subheader"></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Month<span
                                            class="m--font-danger">*</span></label>
                                    <select class="form-control m-select2 ho-select" name="month_select_ho"
                                        id="month_select_ho" style="width: 100%;">
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Year<span
                                            class="m--font-danger">*</span></label>
                                    <select class="form-control m-select2 ho-select" name="year_select_ho"
                                        id="year_select_ho" style="width: 100%;">
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <button type="button" class="btn btn-primary btn-block" id="get_ho_options_btn">Get
                                    Homeowner Names</button>
                            </div>
                            <div class="col-xl-12 mt-5" id="sel_ho" style="display:none">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Select Homeowners</label>
                                    <select class="selectpicker m-input m-input--square" data-width="100%"
                                        name="select_ho_name" liveSearch=true id="select_ho_name"
                                        data-live-search="true" data-actions-box="true" multiple disabled>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-metal" data-dismiss="modal">Close</button>&nbsp; <button
                        type="submit" class="btn btn-info">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="modal fade" id="create_billing_options" data-type="0" data-id="0" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <input type="hidden" id="">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div style="margin: 0 20px">
                    <div class="form-group m-form__group row">
                        <div style="margin: 20px auto;">
                            <h3 id="billing_title" style="text-align: center!important;">CHOOSE TYPE OF BILLING</h3>
                            <h5 style="text-align: center!important;" class="text-muted" id=""></h5>
                        </div>
                    </div>
                    <div class="row">
                        <!-- <div class="col-xl-3"></div> -->
                        <div class="col-xl-6 text-center">
                            <button id="per_year_billing_btn" type="button" class="btn"> <span><i class="fa fa-money"
                                        style="font-size:100px;"></i></span></button>
                            <br><span>Create Multiple Billing Per Month</span>
                        </div>
                        <div class="col-xl-6 text-center">
                            <button id="per_homeowner_billing_btn" type="button" class="btn"> <span><i
                                        class="fa fa-users" style="font-size:100px;"></i></span></button>
                            <br><span>Create Billing Per Homeowner</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-metal" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="view_payment_records_modal" data-total="0" data-rec="0" data-ho="0" data-id="0" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <input type="hidden" id="hidden_email">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div style="margin: 0 20px">
                    <div class="form-group m-form__group row">
                        <div style="margin: 20px auto;">
                            <h3 style="text-align: center!important;">Payment for <span id="month_year_display">February
                                    2023</span></h3>
                            <h5 style="text-align: center!important;" class="text-muted" id=""></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <label class="pay_label">Payment Status: </label>&nbsp;&nbsp;<span class="text-capitalize" style="font-weight:500" id="details_status"></span>
                        </div>
                        <div class="col-xl-12">
                            <label class="pay_label">Owner Name: </label>&nbsp;&nbsp;<span id="details_name"></span>
                        </div>
                        <div class="col-xl-12">
                            <label class="pay_label">Address: </label>&nbsp;&nbsp;<span id="details_address"> </span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xl-12">
                            <label class="pay_label" >Amount to Pay:</label>&nbsp;&nbsp;<span class="text-success" id="details_amount"></span>
                        </div>
                        <div class="col-xl-12">
                            <label class="pay_label">Penalty: </label>&nbsp;&nbsp;<span class="text-danger" id="details_penalty">
                            </span>
                        </div>
                        <div class="col-xl-12">
                            <label class="pay_label">Date Paid/Updated: </label>&nbsp;&nbsp;<span id="details_date_updated">
                            </span>
                        </div>
                        <div class="col-xl-12">
                            <label class="pay_label">Receipt Number: </label>&nbsp;&nbsp;<span class="text-info" id="receipt_label_updated">
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xl-12 pay_label" style="font-size:large !important">
                            <label class="text-success">TOTAL AMOUNT : Php </label>&nbsp;&nbsp;<span class="text-success" id="details_total"> 5679.00 </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-metal" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="penalty_modal" data-total="0" data-rec="0" data-ho="0" data-id="0" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <input type="hidden" id="">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div style="margin: 0 20px">
                    <div class="form-group m-form__group row">
                        <div style="margin: 20px auto;">
                            <h3 style="text-align: center!important;">Penalty</h3>
                            <h5 style="text-align: center!important;" class="text-muted" id=""></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="recipient-name" class="m--font-bolder">Monthly Payment<span
                                    class="m--font-danger">*</span></label>
                            <input type="text" pattern="\d+(\.\d{1,2})?" class="form-control m-input m-input--solid"
                                id="penalty_input" autocomplete="off" required>
                        </div>
                        <!-- <input type="text" pattern="\d+(\.\d{1,2})?" class="form-control m-input m-input--solid"
                            id="penalty_input" required> -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-metal" data-dismiss="modal">Close</button>&nbsp;
                <button type="button" class="btn btn-info" id="save_penalty">Save</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="view_ledger_modal" data-id="0" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <input type="hidden" id="">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 800px" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center">Ledger</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="form-group row">
                        <div class="col-12 text-center">
                            <h5 class="text-muted" id="homeownerName"></h5>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Bootstrap Table structure -->
                        <div class="col-12" style="margin-top: -35px;">
                             <!--<h5 id="name_ledger" class="font-weight-bold">. . .</h5>-->
                             <h5 id="grandTotal" class="text-danger font-weight-bold"></h5>
                        </div>
                        <div class="col-12 mt-3">
                            <label class="text-danger">List of Unpaid Bills</label>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="ledgerTable">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Month & Year</th>
                                            <th>Due Date</th>
                                            <th>Paid Amount</th>
                                            <th>Penalty</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ledgerBody">
                                        <!-- Data will be dynamically populated here -->
                                    </tbody>
                                    <tfoot>
                                        <!-- Grand total row will be added here -->
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url() ?>assets/src/custom/js/dues/dues.js?<?php echo $date_time; ?>">
</script>
