<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-subheader" style="padding-bottom: 10rem;background-color: #f0f1f7;">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title" style="color: #073A4B;"> Administrator </h3>
            </div>
            <br>
        </div>
    </div>
    <div class="m-content" style="margin-top: -11rem !important;">
        <div class="m-portlet content_portlet">
            <div class="m-portlet__head" style="padding: 1rem 1rem 1rem 1.5rem !important;background:#073A4B;">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text" style="color:white !important;">List of admins</h3>
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
                                            id="searchField">
                                        <span class="m-input-icon__icon m-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 order-1 order-xl-2 m--align-right">
                            <div class="form-group m-form__group">
                                <a href="#"
                                    class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill add_admin">
                                    <span>
                                        <i class="la la-plus"></i>
                                        <span>Add Admin</span>
                                    </span>
                                </a>
                                <div class="m-separator m-separator--dashed d-xl-none"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-section" id="refresh_here">
                    <div class="m_datatable" id="adminData"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="submit_admin" method="post">
    <div class="modal fade" id="admin_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                                    <label for="recipient-name" class="m--font-bolder">First Name <span
                                            class="m--font-danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--solid" id="fname"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Middle Name <span
                                            class="m--font-danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--solid" id="mname"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Last Name <span
                                            class="m--font-danger">*</span></label>
                                    <input type="hidden" id="admin_iD">
                                    <input type="hidden" id="action">
                                    <input type="text" class="form-control m-input m-input--solid" id="lname"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Contact Number<span
                                            class="m--font-danger">*</span></label>
                                    <input type="number" class="form-control m-input m-input--solid" id="contact_num"
                                        autocomplete="off" name="contact_num" required>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Address<span
                                            class="m--font-danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--solid" id="address"
                                        autocomplete="off" name="address">
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
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Email</label>
                                    <input type="email" class="form-control m-input m-input--solid" id="email_admin"
                                        autocomplete="off" name="email_admin" required>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder">Role <span
                                            class="m--font-danger">*</span></label>
                                    <select
                                        class="form-control m-bootstrap-select m-bootstrap-select--solid m_selectpicker"
                                        title="Role" tabindex="-98" id="role" required>
                                        <option value="admin">Admin</option>
                                        <option value="officer">Officer</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xl-4" style="">
                                <label for="recipient-name" class="m--font-bolder">Password <span
                                        class="m--font-danger">*</span></label>
                                <button type="button"
                                    class="btn m-btn--pill m-btn--air btn-outline-primary change_password_button"
                                    data-type="change" id="change_button">Change Password</button>
                                <div class="form-group" id="change_password_input" style="display: none">

                                    <input type="text" class="form-control m-input m-input--solid" id="password"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xl-2" id="cancel_button" style="display: none; margin: auto">
                                <button type="button"
                                    class="btn m-btn--pill m-btn--air btn-outline-primary    change_password_button"
                                    data-type="cancel">Cancel</button>
                            </div>
                            <!-- <div class="col-xl-6"></div> -->
                            <div class="col-xl-6" id="act_deact" style="display:none;">
                                <div class="form-group">
                                    <label for="recipient-name" class="m--font-bolder"> Activate / Deactivate Admin
                                        <span class="m--font-danger">*</span></label><br>
                                    <button type="button" class="btn m-btn--pill m-btn--air btn-outline-danger"
                                        id="deactivate" data-id="inactive" style="display:none;">Deactivate</button>
                                    <button type="button" class="btn m-btn--pill m-btn--air btn-outline-success"
                                        id="activate" data-id="active" style="display:none;">Activate</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <!-- <label for="recipient-name" class="m--font-bolder">Authorization</label> -->
                                    <!-- <label class="m-checkbox">
                                        <input type="checkbox" id="authorize_change" name="authorize_change"> Authorized
                                        to confirm adding and updating of homeowner Record.
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
<script type="text/javascript">
var displayData = function() {
    var offense = function(searchVal = "") {
        var options = {
            data: {
                type: 'remote',
                source: {
                    read: {
                        method: 'POST',
                        url: "<?= base_url(); ?>admin/admin_data",
                        params: {
                            query: {
                                searchField: searchVal,
                            },
                        },
                    }
                },
                saveState: {
                    cookie: false,
                    webstorage: false
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
            },
            layout: {
                theme: 'default',
                class: '',
                scroll: false,
                minHeight: 20,
                footer: false
            },
            sortable: true,
            pagination: true,
            toolbar: {
                placement: ['bottom'],
                items: {
                    pagination: {
                        pageSizeSelect: [10, 20, 30, 50, 100]
                    },
                }
            },
            search: {
                input: $('#searchField'),
            },
            columns: [{
                field: "lname",
                title: "Last Name",
                width: 120,
                selector: false,
                sortable: 'asc',
                textAlign: 'left',
            }, {
                field: "fname",
                title: "First Name",
                width: 120,
                selector: false,
                sortable: 'asc',
                textAlign: 'left',
            }, {
                field: "mname",
                title: "Middle Name",
                width: 120,
                selector: false,
                sortable: 'asc',
                textAlign: 'left',
            }, {
                field: "username",
                title: "Username",
                width: 120,
                selector: false,
                sortable: 'asc',
                textAlign: 'left',
            }, {
                field: "role",
                title: "Role",
                width: 120,
                selector: false,
                sortable: 'asc',
                textAlign: 'left',
                template: function(row, index, datatable) {
                    return row.role === 'admin' ? 'Admin' : row.role === 'officer' ? 'Officer' :
                        row
                        .role === 'regular' ? 'Regular' : '';
                }
            }, {
                field: "status",
                title: "Status",
                width: 120,
                selector: false,
                sortable: 'asc',
                textAlign: 'left',
                template: function(row, index, datatable) {
                    let stat = row.status;
                    let html = "";
                    if (stat == "active") {
                        html =
                            "<strong><span class='badge bg-success me-1'> </span><span class='text-success text- capitalize'> " +
                            stat + "</span></strong>";
                    } else {
                        html =
                            "<strong><span class='badge bg-danger me-1'> </span><span class='text-danger text- capitalize'> " +
                            stat + "</span></strong>";

                    }
                    return html;
                }
            }, {
                field: "Actions",
                width: 70,
                title: "Actions",
                sortable: false,
                overflow: 'visible',
                textAlign: 'center',
                template: function(row, index, datatable) {
                    var dropup = (datatable.getPageSize() - index) <= 4 ? 'dropup' : '';
                    let update =
                        `<a class="dropdown-item update_admin" href="#" data-id = "${row.id_admin}" data-lname = "${row.lname}" data-fname = "${row.fname}" data-mname = "${row.mname}" data-username = "${row.username}" data-role = "${row.role}" data-stat = "${row.status}" data-address = "${row.addr_admin}" data-contact= "${row.contact_admin}" data-email= "${row.email_admin}" ><i class="la la-edit"></i>Update Admin</a>`;
                    let trash = '';
                    // let trash = '<a class="dropdown-item stock_delete" href="#" data-id="'+row.committee_ID+'"><i class="la la-trash"></i>Delete Committee</a>';
                    return '\
                        <div class="dropdown ' + dropup + '">\
                        <a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">\
                        <i class="la la-ellipsis-h"></i>\
                        </a>\
                        <div class="dropdown-menu dropdown-menu-right">\
                        ' + update + '\
                        ' + trash + '\
                        </div>\
                        </div>\
                        ';
                }
            }],
        };
        var datatable = $('#adminData').mDatatable(options);
    };
    return {
        init: function(searchVal) {
            offense(searchVal);
        }
    };
}();

function initAdmin() {
    $('#adminData').mDatatable('destroy');
    displayData.init();
}
$(function() {
    initAdmin();
    $('.m_selectpicker').selectpicker();
})
$('#admin_modal').on('hidden.bs.modal', function(e) {
    $(this).find("input,textarea,select").val('').end()
})
$('#admin_modal').on('click', '.change_password_button', (e) => {
    let type = $(e.currentTarget).data('type');
    if (type === 'change') {
        $('#change_password_input').show();
        $('#change_button').hide();
        $('#cancel_button').show();
        $('#password').show();
    } else {
        $('#password').val('');
        $('#change_password_input').hide();
        $('#password').hide().prop('required', false)
        $('#change_button').show();
        $('#cancel_button').hide();
        $("#change_password_input :input").prop('required', null);
    }
})
$('.add_admin').click(() => {
    $('#change_password_input').show();
    $('#change_button').hide();
    $('#cancel_button').hide();
    $('#admin_modal').modal('show');
    $('#subheader').text('Create New Admin');
    $('#action').val('create');
    $('#password').show();
    $("#act_deact").hide();
})
$('#refresh_here').on('click', '.update_admin', (e) => {
    let admin_iD = $(e.currentTarget).data('id');
    let lname = $(e.currentTarget).data('lname');
    let fname = $(e.currentTarget).data('fname');
    let mname = $(e.currentTarget).data('mname');
    let username = $(e.currentTarget).data('username');
    let role = $(e.currentTarget).data('role');
    let address = $(e.currentTarget).data('address');
    let contact = $(e.currentTarget).data('contact');
    let email = $(e.currentTarget).data('email');
    let auth = $(e.currentTarget).data('auth') ? true : false;
    let stat = $(e.currentTarget).data('stat');
    $("#act_deact").show();
    status_buttons(stat, 1);
    // console.log(auth);
    // $('#authorize_change').prop('checked', auth);
    $('#password').val('');
    $('#change_password_input').hide();
    $('#change_button').show();
    $('#cancel_button').hide();
    $('#password').hide().prop('required', false)
    $("#change_password_input :input").prop('required', null);
    $('#admin_iD').val(admin_iD);
    $('#lname').val(lname);
    $('#fname').val(fname);
    $('#mname').val(mname);
    $('#username').val(username);
    $("#contact_num").val(contact);
    $("#address").val(address);
    $("#email_admin").val(email);
    $('#role').selectpicker('val', role);
    $('#action').val('update');
    $('#subheader').text('Update Admin');
    $('#admin_modal').modal('show');
})
$('#submit_admin').on('submit', (e) => {
    e.preventDefault()
    let admin_iD = $('#admin_iD').val();
    let action = $('#action').val();
    let lname = $('#lname').val();
    let fname = $('#fname').val();
    let mname = $('#mname').val();
    let username = $('#username').val();
    let password = $('#password').val();
    let role = $('#role').val();
    let contact = $("#contact_num").val();
    let address = $("#address").val();
    let email = $("#email_admin").val();
    // let authorization = $('#authorize_change').is(":checked");
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>admin/admin_action",
        dataType: "JSON",
        data: {
            admin_iD,
            action,
            lname,
            fname,
            mname,
            username,
            password,
            role,
            contact,
            address,
            email
            // authorization
        },
        success: function(data) {
            if (data === 1) {
                swal("Success!", `Admin successfully ${action}d!`, "success");
                $('#adminData').mDatatable('reload');
            } else if (data === 'Admin already exist!') {
                swal("Warning!", data, "warning");
            } else {
                swal("Error!", `Something went wrong!`, "error");
            }
            $('#admin_modal').modal('hide');
        }
    })
})
$("#deactivate,#activate").on("click", function() {
    let status = $(this).data("id");
    let admin_id = $('#admin_iD').val();
    swal({
        title: 'Are you sure you want to set admin to ' + status + " ?",
        text: "",
        type: "question",
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: `No`,
    }).then((result) => {
        if (result.value) {
            $('#admin_modal').modal('hide');
            $('#adminData').mDatatable('reload');
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>admin/update_status_admin",
                dataType: "JSON",
                data: {
                    admin_id,
                    status
                },
                success: function(data) {
                    //return
                }
            })
            // update_status_admin($(this).data("id"));
        } else if (result.dismiss === "cancel") {

        }
    });
});

function update_status_admin(status) {
    let admin_id = $('#admin_iD').val();
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>admin/update_status_admin",
        dataType: "JSON",
        data: {
            admin_id,
            status
        },
        success: function() {
            // $('#admin_modal').modal('hide');
            // $('#adminData').mDatatable('reload');
            console.log("success");
        }
    })
}

function status_buttons(stat, type) {
    if (type == 1) {
        if (stat == "active") {
            $("#activate").hide();
            $("#deactivate").show();
        } else {
            $("#deactivate").hide();
            $("#activate").show();
        }
    } else {
        if (stat == "ina") {
            $("#activate").hide();
            $("#deactivate").show();
        } else {
            $("#deactivate").hide();
            $("#activate").show();
        }
    }

}
</script>