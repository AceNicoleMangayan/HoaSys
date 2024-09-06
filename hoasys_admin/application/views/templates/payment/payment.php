<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-subheader" style="padding-bottom: 10rem;background-color: #272727db;">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title" style="color: #f0f0f0;"> Payment</h3>
                <input type="hidden" id="refresh_data_Update">
            </div>
            <br>
        </div>
    </div>
    <div class="m-content" style="margin-top: -11rem !important;">
        <!-- <div class="m-portlet content_portlet">
            <div class="m-portlet__head" style="padding: 1rem 1rem 1rem 1.5rem !important;">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Prizes</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="m-form m-form--label-align-right m--margin-buttom-20" style="margin-bottom: 20px">
                    <div class="row align-items-center">
                        <div class="col-xl-8 order-2 order-xl-1">
                            <div class="form-group m-form__group row align-items-center">
                                <div class="col-md-8">
                                    <div class="m-input-icon m-input-icon--left">
                                        <input type="text" autocomplete="off"
                                            class="form-control m-input m-input--solid" placeholder="Search..."
                                            id="search_Field_prize">
                                        <span class="m-input-icon__icon m-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 order-1 order-xl-2 m--align-right">
                            <div class="form-group m-form__group">
                                <a id="addprize_btn" href="#"
                                    class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill add_committee">
                                    <span>
                                        <i class="la la-plus"></i>
                                        <span>Add Prize</span>
                                    </span>
                                </a>
                                <div class="m-separator m-separator--dashed d-xl-none"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-section" id="refresh_prize">
                    <div class="m_datatable" id="datatable_prizes"></div>
                </div>

            </div>
        </div> -->
        <!-- test  -->
        <div class="m-portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                        List of Homeowner Payments
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
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
                                <div class="col-xl-6 col-sm-12 text-right">
                                    <div class="m-input-icon m-input-icon--left">
                                        <input type="text" autocomplete="off"
                                            class="form-control m-input m-input--solid"
                                            placeholder="Search name ..." id="search_Field_prize">
                                        <span class="m-input-icon__icon m-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xl-2">  
                                </div>
                                <div class="col-xl-4" style="text-align:end">                    
                        <a id="create_billing_btn" href="#"
                        class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill add_committee">
                        <span>
                            <i class="la la-plus"></i>
                            <span>Create Billing</span>
                        </span>
                    </a></div>
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
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="text" class="form-control m-input m-input--solid" id="last_name"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Block<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="text" class="form-control m-input m-input--solid" id="block"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Lot<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
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
                                        class="form-control m-input m-input--solid" autocomplete="off"
                                        required>
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
                                    <input step="0.1" type="number" class="form-control m-input m-input--solid" id="monthly_payment"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-6 pt-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Due Date is every (1st, 2nd, 3rd) of the month <span
                                            class="m--font-danger">*</span></label>
                                    <input placeholder="Type Either 1-31 day..." type="number" class="form-control m-input m-input--solid" id="due_date_payment"
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
                                        class="form-control m-input m-input--solid" autocomplete="off"
                                        required>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Email Address<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="">
                                    <input type="hidden" id="">
                                    <input type="email" class="form-control m-input m-input--solid" id="email_address_up"
                                        autocomplete="off" required>
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
    <div class="modal fade" id="create_billing_modal" data-type="0" data-id="0" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
                            <label for="recipient-name" class="m--font-bolder">Month<span class="m--font-danger">*</span></label>
                            <select class="form-control m-select2" name="month_select" id="month_select"
                                        style="width: 100%;">
                            </select>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                            <label for="recipient-name" class="m--font-bolder">Year<span class="m--font-danger">*</span></label>
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
    <div class="modal fade" id="view_payment_records_modal" data-id="0" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <input type="hidden" id="hidden_email">
        <div class="modal-dialog" style="max-width: 800px" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div style="margin: 0 20px">
                        <div class="form-group m-form__group row">
                            <div style="margin: 20px auto;">
                                <h3 style="text-align: center!important;">Payment Records</h3>
                                <h5 style="text-align: center!important;" class="text-muted" id=""></h5>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-xl-3">
                            <div class="form-group">
                            <label for="recipient-name" class="m--font-bolder">Status</label>
                            <select class="form-control " name="status_pay" id="status_pay"
                                        style="width: 100%;">
                            <option value="All" selected>All</option>
                            <option value="paid">PAID</option>
                            <option value="unpaid">UNPAID</option>
                            </select>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="form-group">
                            <label for="recipient-name" class="m--font-bolder">Month</label>
                            <select class="form-control m-select2" name="month_select_choose" id="month_select_choose"
                                        style="width: 100%;">
                            </select>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="form-group">
                            <label for="recipient-name" class="m--font-bolder">Year</label>
                            <select class="form-control m-select2" name="year_select_choose" id="year_select_choose"
                                        style="width: 100%;">
                            </select>
                            </div>
                        </div>
                        <div class="col-xl-3 " style="padding-top:1.8em">
                            <div class="form-group">
                            <a id="create_specific_billing_btn" href="#"
                        class="btn btn-success m-btn btn-sm m-btn--custom m-btn--icon m-btn--air m-btn--pill">
                        <span>
                            <i class="la la-plus"></i>
                            <span>Create Specific Billing</span>
                        </span>
                    </a>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                        <div class="m-section col-12" id="refresh_view_records">
                            <div class="m_datatable" id="datatable_view_records"></div>
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
<script src="<?php echo base_url() ?>assets/src/custom/js/payment/payment.js?<?php echo $date_time; ?>">
</script>