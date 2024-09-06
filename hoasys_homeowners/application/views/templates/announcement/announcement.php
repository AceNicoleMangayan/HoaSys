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
                <h3 class="m-subheader__title" style="color: #073A4B;"> Announcements </h3>
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
                            List of Announcements
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
                                            placeholder="Search title and description..." id="search_Field_prize">
                                        <span class="m-input-icon__icon m-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                </div>
                                <!-- <div class="col-xl-4" style="text-align:end">
                                    <a id="add_announcement_btn" href="#"
                                        class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill add_homeowner">
                                        <span>
                                            <i class="la la-plus"></i>
                                            <span>Create Announcement</span>
                                        </span>
                                    </a>
                                </div> -->
                            </div>
                        </div>
                        <div class="m-section" id="refresh_announcement">
                            <div class="m_datatable" id="datatable_announcement"></div>
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
<form id="submit_announcement" method="post">
    <div class="modal fade" id="announcement_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                                <h3 style="text-align: center!important;">New Announcement</h3>
                                <h5 style="text-align: center!important;" class="text-muted" id="subheader"></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Title<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="prize_ID_Update">
                                    <input type="hidden" id="action">
                                    <input type="text" class="form-control m-input m-input--solid" id="ann_title"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label class="m--font-bolder">Description</label>
                                    <textarea class="form-control m-input m-input--solid" style="height: 150px;"
                                        id="ann_description" rows="3"></textarea>
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
<form id="submit_update_announcement" method="post">
    <div class="modal fade" id="announcement_update_modal" tabindex="-1" role="dialog"
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
                                <h3 style="text-align: center!important;">Update Announcement</h3>
                                <h5 style="text-align: center!important;" class="text-muted" id="subheader"></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Title<span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="announcement_id_update">
                                    <input type="hidden" id="action">
                                    <input type="text" class="form-control m-input m-input--solid" id="ann_title_update"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label class="m--font-bolder">Description</label>
                                    <textarea class="form-control m-input m-input--solid" style="height: 150px;"
                                        id="ann_description_update" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-metal" data-dismiss="modal">Close</button>&nbsp;
                    <button type="button" id="publish_btn" class="btn btn-success btn-icon" style="display:none;"><i
                            class="fa fa-leanpub"></i> <span>Publish </span></button>&nbsp;
                    <button type="button" id="unpublish_btn" class="btn btn-danger btn-icon" style="display:none;"><i
                            class="fa fa-leanpub"></i> <span>Unpublish </span></button>&nbsp;
                    <button type="submit" class="btn btn-info">Save Update</button>
                </div>
            </div>
        </div>
    </div>
</form>
<form id="" method="post">
    <div class="modal fade" id="announcement_details_dashboard" data-id="0" tabindex="-1" role="dialog"
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
                                <h3 style="text-align: center!important;" id="announcement_title_dash">ore et </h3>
                                <h5 style="text-align: center!important;" class="text-muted"
                                    id="announcement_date_dash">sjdhsjgf</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <!-- <label class="m--font-bolder">Concern Description: </label> -->
                                <p id="announcement_desc_dash">Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                                    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
                                    veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
                                    dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                                    sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
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
<script src="<?php echo base_url() ?>assets/src/custom/js/announcement/announcement.js?<?php echo $date_time; ?>">
</script>