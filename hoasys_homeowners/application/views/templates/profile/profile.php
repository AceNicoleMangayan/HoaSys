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
                <h3 class="m-subheader__title" style="color: #073A4B;"> My Profile </h3>
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
        <!-- test start  -->
        <div class="m-content">
            <div class="row">
                <div class="col-xl-4 col-lg-4">
                    <div class="m-portlet m-portlet--full-height  ">
                        <div class="m-portlet__body">
                            <div class="m-card-profile">
                                <div class="m-card-profile__title m--hide">
                                    Your Profile
                                </div>
                                <div class="m-card-profile__pic">
                                    <div class="m-card-profile__pic-wrapper" style="background: #f3e5f3;">
                                        <i class="fa fa-user" style="font-size: 7em;"></i>
                                        <img src="assets/app/media/img/users/user4.jpg" alt="">
                                    </div>
                                </div>
                                <div class="m-card-profile__details">
                                    <span class="m-card-profile__name" id="profile_fullname">Mark Andre</span>
                                    <a href="#" class="m-card-profile__email m-link" id="profile_email_display">mark.andre@gmail.com</a>
                                </div>
                            </div>
                            <!-- <ul class="m-nav m-nav--hover-bg m-portlet-fit--sides">
                                <li class="m-nav__separator m-nav__separator--fit"></li>
                                <li class="m-nav__section m--hide">
                                    <span class="m-nav__section-text">Section</span>
                                </li>
                                <li class="m-nav__item">
                                    <a href="indexa80c.html?page=header/profile&amp;demo=default" class="m-nav__link">
                                        <i class="m-nav__link-icon flaticon-profile-1"></i>
                                        <span class="m-nav__link-title">
                                            <span class="m-nav__link-wrap">
                                                <span class="m-nav__link-text">My Profile</span>
                                                <span class="m-nav__link-badge"><span class="m-badge m-badge--success">2</span></span>
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="m-nav__item">
                                    <a href="indexa80c.html?page=header/profile&amp;demo=default" class="m-nav__link">
                                        <i class="m-nav__link-icon flaticon-share"></i>
                                        <span class="m-nav__link-text">Activity</span>
                                    </a>
                                </li>
                                <li class="m-nav__item">
                                    <a href="indexa80c.html?page=header/profile&amp;demo=default" class="m-nav__link">
                                        <i class="m-nav__link-icon flaticon-chat-1"></i>
                                        <span class="m-nav__link-text">Messages</span>
                                    </a>
                                </li>
                                <li class="m-nav__item">
                                    <a href="indexa80c.html?page=header/profile&amp;demo=default" class="m-nav__link">
                                        <i class="m-nav__link-icon flaticon-graphic-2"></i>
                                        <span class="m-nav__link-text">Sales</span>
                                    </a>
                                </li>
                                <li class="m-nav__item">
                                    <a href="indexa80c.html?page=header/profile&amp;demo=default" class="m-nav__link">
                                        <i class="m-nav__link-icon flaticon-time-3"></i>
                                        <span class="m-nav__link-text">Events</span>
                                    </a>
                                </li>
                                <li class="m-nav__item">
                                    <a href="indexa80c.html?page=header/profile&amp;demo=default" class="m-nav__link">
                                        <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                        <span class="m-nav__link-text">Support</span>
                                    </a>
                                </li>
                            </ul> -->

                            <div class="m-portlet__body-separator"></div>

                            <div class="m-widget1 m-widget1--paddingless">
                                <div class="m-widget1__item">
                                    <div class="row m-row--no-padding align-items-center">
                                        <div class="col">
                                            <h3 class="m-widget1__title">Block</h3>
                                            <span class="m-widget1__desc">(Lot Block)</span>
                                        </div>
                                        <div class="col m--align-right">
                                            <span class="m-widget1__number m--font-brand" id="profile_block_display">8</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-widget1__item">
                                    <div class="row m-row--no-padding align-items-center">
                                        <div class="col">
                                            <h3 class="m-widget1__title">Lot</h3>
                                            <span class="m-widget1__desc">(Lot Location)</span>
                                        </div>
                                        <div class="col m--align-right">
                                            <span class="m-widget1__number m--font-danger" id="profile_lot_display">11</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-widget1__item">
                                    <div class="row m-row--no-padding align-items-center">
                                        <div class="col">
                                            <h3 class="m-widget1__title">Village</h3>
                                            <span class="m-widget1__desc">(Village Name)</span>
                                        </div>
                                        <div class="col m--align-right">
                                            <span class="m-widget1__number m--font-success" id="profile_village_display">Golden Glow North</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-8">
                    <div class="m-portlet m-portlet--full-height m-portlet--tabs  ">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-tools">
                                <ul class="nav nav-tabs m-tabs m-tabs-line   m-tabs-line--left m-tabs-line--primary" role="tablist">
                                    <li class="nav-item m-tabs__item">
                                        <a class="nav-link m-tabs__link active show" data-toggle="tab" href="#m_user_profile_tab_1" role="tab" aria-selected="true">
                                            <i class="flaticon-share m--hide"></i>
                                            Update Profile
                                        </a>
                                    </li>
                                    <li class="nav-item m-tabs__item">
                                        <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_user_profile_tab_2" role="tab" aria-selected="false">
                                            Billing Information
                                        </a>
                                    </li>
                                    <li class="nav-item m-tabs__item">
                                        <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_user_profile_tab_3" role="tab" aria-selected="false">
                                            Security Info
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="m-portlet__head-tools">
                                <ul class="m-portlet__nav">
                                    <li class="m-portlet__nav-item m-portlet__nav-item--last">
                                        <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
                                            <!-- <a href="#" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
                                                <i class="la la-gear"></i>
                                            </a> -->
                                            <div class="m-dropdown__wrapper">
                                                <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                                <div class="m-dropdown__inner">
                                                    <div class="m-dropdown__body">
                                                        <div class="m-dropdown__content">
                                                            <ul class="m-nav">
                                                                <li class="m-nav__section m-nav__section--first">
                                                                    <span class="m-nav__section-text">Quick Actions</span>
                                                                </li>
                                                                <li class="m-nav__item">
                                                                    <a href="#" class="m-nav__link">
                                                                        <i class="m-nav__link-icon flaticon-share"></i>
                                                                        <span class="m-nav__link-text">Create Post</span>
                                                                    </a>
                                                                </li>
                                                                <li class="m-nav__item">
                                                                    <a href="#" class="m-nav__link">
                                                                        <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                                        <span class="m-nav__link-text">Send Messages</span>
                                                                    </a>
                                                                </li>
                                                                <li class="m-nav__item">
                                                                    <a href="#" class="m-nav__link">
                                                                        <i class="m-nav__link-icon flaticon-multimedia-2"></i>
                                                                        <span class="m-nav__link-text">Upload File</span>
                                                                    </a>
                                                                </li>
                                                                <li class="m-nav__section">
                                                                    <span class="m-nav__section-text">Useful Links</span>
                                                                </li>
                                                                <li class="m-nav__item">
                                                                    <a href="#" class="m-nav__link">
                                                                        <i class="m-nav__link-icon flaticon-info"></i>
                                                                        <span class="m-nav__link-text">FAQ</span>
                                                                    </a>
                                                                </li>
                                                                <li class="m-nav__item">
                                                                    <a href="#" class="m-nav__link">
                                                                        <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                                        <span class="m-nav__link-text">Support</span>
                                                                    </a>
                                                                </li>
                                                                <li class="m-nav__separator m-nav__separator--fit m--hide">
                                                                </li>
                                                                <li class="m-nav__item m--hide">
                                                                    <a href="#" class="btn btn-outline-danger m-btn m-btn--pill m-btn--wide btn-sm">Submit</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active show" id="m_user_profile_tab_1">
                                <form class="m-form m-form--fit m-form--label-align-right">
                                    <div class="m-portlet__body">
                                        <div class="form-group m-form__group m--margin-top-10 m--hide">
                                            <div class="alert m-alert m-alert--default" role="alert">
                                                The example form below demonstrates common HTML form elements that receive updated styles from Bootstrap with additional classes.
                                            </div>
                                        </div>

                                        <div class="form-group m-form__group row">
                                            <div class="col-10 ml-auto">
                                                <h3 class="m-form__section">1. Personal Details</h3>
                                            </div>
                                        </div>

                                        <div class="form-group m-form__group row">
                                            <label for="example-text-input" class="col-xl-2 col-md-3 col-sm-12 col-form-label">First Name</label>
                                            <div class="col-xl-7 col-md-7 col-sm-12">
                                                <input class="form-control m-input" type="text" id="profile_fname" value="Mark Andre" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label for="example-text-input" class="col-xl-2 col-md-3 col-sm-12 col-form-label">Middle Name</label>
                                            <div class="col-xl-7 col-md-7 col-sm-12">
                                                <input class="form-control m-input" type="text" id="profile_mname" value="CTO" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label for="example-text-input" class="col-xl-2 col-md-3 col-sm-12 col-form-label">Last Name</label>
                                            <div class="col-xl-7 col-md-7 col-sm-12">
                                                <input class="form-control m-input" type="text" id="profile_lname" value="CTO" disabled>
                                            </div>
                                        </div>
                                        <div class="m-form__seperator m-form__seperator--dashed m-form__seperator--space-2x"></div>

                                        <div class="form-group m-form__group row">
                                            <div class="col-10 ml-auto">
                                                <h3 class="m-form__section">2. Address</h3>
                                            </div>
                                        </div>

                                        <div class="form-group m-form__group row">
                                            <label for="example-text-input" class="col-xl-2 col-md-3 col-sm-12 col-form-label">Block</label>
                                            <div class="col-xl-7 col-md-7 col-sm-12">
                                                <input class="form-control m-input" type="text" id="profile_block" value="L-12-20 Vertex, Cybersquare" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label for="example-text-input" class="col-xl-2 col-md-3 col-sm-12 col-form-label">Lot</label>
                                            <div class="col-xl-7 col-md-7 col-sm-12">
                                                <input class="form-control m-input" type="text" id="profile_lot" value="San Francisco" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label for="example-text-input" class="col-xl-2 col-md-3 col-sm-12 col-form-label">Village</label>
                                            <div class="col-xl-7 col-md-7 col-sm-12">
                                                <input class="form-control m-input" type="text" id="profile_village" value="California" disabled>
                                            </div>
                                        </div>
                                        <div class="m-form__seperator m-form__seperator--dashed m-form__seperator--space-2x"></div>

                                        <div class="form-group m-form__group row">
                                            <div class="col-10 ml-auto">
                                                <h3 class="m-form__section">3. Account Details (Updatable)</h3>
                                            </div>
                                        </div>

                                        <div class="form-group m-form__group row">
                                            <label for="example-text-input" class="col-xl-2 col-md-3 col-sm-12 col-form-label">Username</label>
                                            <div class="col-xl-7 col-md-7 col-sm-12">
                                                <input class="form-control m-input" type="text" id="profile_username" value="">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label for="example-text-input" class="col-xl-2 col-md-3 col-sm-12 col-form-label">Email</label>
                                            <div class="col-xl-7 col-md-7 col-sm-12">
                                                <input class="form-control m-input" type="email" value="" id="profile_email" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label for="example-text-input" class="col-xl-2 col-md-3 col-sm-12 col-form-label">Contact Number</label>
                                            <div class="col-xl-7 col-md-7 col-sm-12">
                                                <input class="form-control m-input" type="number" id="profile_contact_number" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-portlet__foot m-portlet__foot--fit">
                                        <div class="m-form__actions">
                                            <div class="row">
                                                <div class="col-2">
                                                </div>
                                                <div class="col-xl-7 col-md-7 col-sm-12">
                                                    <button type="button" id="save_account_details_btn" class="btn btn-primary m-btn m-btn--air m-btn--custom">Save changes</button>
                                                    <!-- <button type="reset" class="btn btn-secondary m-btn m-btn--air m-btn--custom">Cancel</button> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="m_user_profile_tab_2">
                                <form class="m-form m-form--fit m-form--label-align-right">
                                    <div class="m-portlet__body">
                                        <div class="form-group m-form__group row">
                                            <div class="col-10 ml-auto">
                                                <h3 class="m-form__section">1. Billing Details</h3>
                                            </div>
                                        </div>

                                        <div class="form-group m-form__group row">
                                            <label for="example-text-input" class="col-xl-2 col-md-3 col-sm-12 col-form-label">Monthly Payment</label>
                                            <div class="col-xl-7 col-md-7 col-sm-12">
                                                <input class="form-control m-input" type="text" id="profile_monthly" value="Mark Andre" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label for="example-text-input" class="col-xl-2 col-md-3 col-sm-12 col-form-label">Due Date</label>
                                            <div class="col-xl-7 col-md-7 col-sm-12">
                                                <input class="form-control m-input" type="text" id="profile_duedate" value="Mark Andre" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div class="tab-pane" id="m_user_profile_tab_3">
                                <form class="m-form m-form--fit m-form--label-align-right">
                                    <div class="m-portlet__body">
                                        <div class="form-group m-form__group row">
                                            <div class="col-10 ml-auto">
                                                <h3 class="m-form__section">1. Security Details </h3>
                                            </div>
                                        </div>

                                        <div class="form-group m-form__group row">
                                            <label for="example-text-input" class="col-xl-2 col-md-3 col-sm-12 col-form-label">Change Password</label>
                                            <div class="col-xl-5 col-md-7 col-sm-12">
                                                <input class="form-control m-input" type="text" id="profile_password" value="">
                                            </div>
                                        </div>
                                        <div class="m-portlet__foot m-portlet__foot--fit">
                                            <div class="m-form__actions">
                                                <div class="row">
                                                    <div class="col-2">
                                                    </div>
                                                    <div class="col-xl-7 col-md-7 col-sm-12">
                                                        <button type="button" id="change_password_btn" class="btn btn-primary m-btn m-btn--air m-btn--custom">Change Password</button>
                                                        <!-- <button type="reset" class="btn btn-secondary m-btn m-btn--air m-btn--custom">Cancel</button> -->
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
        <!-- end  -->
    </div>
</div>
<form id="submit_concern_email_add" method="post">
    <div class="modal fade" id="concern_send_details_add" data-id="0" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div style="margin: 0 20px">
                        <div class="form-group m-form__group row">
                            <div style="margin: 20px auto;">
                                <h3 style="text-align: center!important;">Send Concern</h3>
                                <h5 style="text-align: center!important;" class="text-muted" id="subheader"></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Concern Title<span class="m--font-danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--solid" id="concern_title" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label class="m--font-bolder">Concern Description</label>
                                    <textarea class="form-control m-input m-input--solid" style="height: 150px;" id="concern_content" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-metal" data-dismiss="modal">Close</button>&nbsp; <button type="submit" class="btn btn-success">Send</button>
                </div>
            </div>
        </div>
    </div>
</form>
<form id="submit_otp_sending_confirm_password" method="post">
    <div class="modal fade" id="otp_sending_confirm_password" data-password="0" data-id="0" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div style="margin: 0 20px">
                        <div class="form-group m-form__group row">
                            <div style="margin: 20px auto;">
                                <h3 style="text-align: center!important;">Input OTP from email<span id=""></span></h3>
                                <h5 style="text-align: center!important;" class="text-muted" id="subheader">Please don't share your OTP to anyone.</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-2 col-sm-12">
                                <label for="example-text-input" class="col-xl-2 col-sm-12 col-form-label">Input OTP:</label>
                            </div>
                            <div class="col-xl-10 col-sm-12">
                                <input class="form-control m-input" type="text" id="input_otp" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-metal" data-dismiss="modal">Close</button>&nbsp;
                    <button type="submit" class="btn btn-info">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="<?php echo base_url() ?>assets/src/custom/js/profile/profile.js?<?php echo $date_time; ?>">
</script>