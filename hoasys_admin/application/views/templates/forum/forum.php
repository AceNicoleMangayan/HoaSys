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

.whats-on-your-mind {
    background-color: #fff;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.forum-card {
    margin-bottom: 20px;
    border-radius: 15px;
    /* Rounded edges */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    /* Shadow */
}

.forum-post {
    border: 1px solid #ddd;
    padding: 15px;
    background-color: #fff;
    border-radius: 12px !important;
}

.comments-section {
    margin-top: 20px;
}

.comment {
    /* border-top: 1px solid #ddd; */
    padding: 10px 0;
}

.see-more-comments {
    cursor: pointer;
    color: #007bff;
}

.reply-comments-design {
    cursor: pointer;
    color: #007bff;
}

.comment-icons {
    margin-top: 5px;
}

.load-more-btn {
    margin-top: 20px;
    text-align: center;
}

.comment_design {
    background: #f7f7f7;
    padding: 9px;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.sub_comments_design {
    background: #faf3ff;
    padding: 14px;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.see-forum-design {
    cursor: pointer;
    color: #007bff;
}
</style>

<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-subheader" style="padding-bottom: 10rem; background-color: #f0f1f7;">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title" style="color: #073A4B;"> Forum </h3>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control" id="search_Forum" placeholder="Search">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="m-content" id="content_all_forums" style="margin-top: -11rem !important;">

        <!-- What's on your mind? Section -->
        <div class="whats-on-your-mind">
            <h4 class="mb-3">What's on your mind now?</h4>
            <input type="text" class="form-control m-input" placeholder="Forum Title..." id="forum_title" required>
            <textarea id="forum_desc" class="form-control mt-3" rows="3" placeholder="Write your forum post..."
                required></textarea>
            <div class="row mt-3">
                <div class="col-6">
                    <button id="save_new_forum" class="btn btn-primary">Post Forum</button>
                </div>
                <div class="col-4 mt-3 text-right" style="font-size: smaller;"><i
                        class="fa fa-eye"></i>&nbsp;&nbsp;<span> Viewer</span></div>
                <div class="col-2 text-left">
                    <div class="form-group">
                        <select class="form-control text-left" id="visibilitySelect">
                            <option value="all" selected>All</option>
                            <option value="admin">Admin and Officers Only</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="list_forums">
        </div>

        <!-- Load More Button -->
        <div class="load-more-btn" id="loadMoreBtn">
            <button class="btn btn-primary" id="loadMore_actual_btn">Load More <i
                    class="fa fa-chevron-circle-down"></i></button>
        </div>

    </div>
</div>

<!-- modal  -->
<form id="submit_forum_reply" method="post">
    <div class="modal fade" id="submit_forum_reply_modal" data-type="1" data-id="0" data-reply="0" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div style="margin: 0 20px">
                        <div class="form-group m-form__group row">
                            <div style="margin: 20px auto;">
                                <h3 style="text-align: center!important;" id="form_reply_Label">Forum Comment</h3>
                                <h5 style="text-align: center!important;" class="text-muted" id="subheader"></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <!-- <label class="m--font-bolder">Your Forum Comment</label> -->
                                    <textarea class="form-control m-input m-input--solid"
                                        placeholder="Type your forum reply here ..." style="height: 150px;"
                                        id="forum_comment" rows="3" required></textarea>
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
<form id="submit_forum_update" method="post">
    <div class="modal fade" id="submit_forum_update_modal" data-type="1" data-id="0" data-reply="0" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div style="margin: 0 20px">
                        <div class="form-group m-form__group row">
                            <div style="margin: 20px auto;">
                                <h3 style="text-align: center!important;" id="form_reply_Label">Forum Update</h3>
                                <h5 style="text-align: center!important;" class="text-muted" id="subheader"></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <input type="text" class="form-control m-input m-input--solid"
                                        placeholder="Forum Title..." id="forum_title_update" required>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <!-- <label class="m--font-bolder">Your Forum Comment</label> -->
                                    <textarea class="form-control m-input m-input--solid"
                                        placeholder="Forum description here ..." style="height: 150px;"
                                        id="forum_desc_update" rows="3" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-metal" data-dismiss="modal">Close</button>&nbsp; <button
                        type="submit" class="btn btn-success">Save Update</button>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
<script src="<?php echo base_url() ?>assets/src/custom/js/forum/forum.js?<?php echo $date_time; ?>">
</script>
<script>
// Add your custom scripts here
function showMoreComments() {
    // Implement logic to load more comments or navigate to a new page with more comments
    console.log('Show more comments clicked');
}
let session_id = <?php echo $session_id; ?>;
</script>