<link rel="stylesheet" type="text/css"
    href="<?php echo base_url(); ?>assets/src/demo/default/custom/components/custom/toastr/build/toastr.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/src/custom/css/partials/sidebar.css">
<style>
/* Change text color */
.m-menu__link-text {
    color: #073a4b !important;
    /* Change this to the desired text color */
}

/* Change hover color */
.m-menu__link:hover {
    background-color: yellow !important;
    /* Change this to the desired hover background color */
}

/* Change active item color */
.m-menu__item--active .m-menu__link-text {
    color: white !important;
}

/* Selected state (if needed) */
.m-menu__item--selected a {
    background: black !important;
    /* Change to your desired color */
}

.m-menu__item--active a {
    background: #073a4b !important;
    /* Change to your desired background color for active state */
    color: pink !important;
    /* Change to your desired text color for active state */
}

/* Hover state */
.m-menu__item:hover a {
    background: #f0f0f0 !important;
    /* Change to your desired background color for hover state */
    color: pink !important;
    /* Change to your desired text color for hover state */
}

.m-menu__link {
    transition: background-color 0.3s ease, color 0.3s ease;
}
</style>
<button class="m-aside-left-close  m-aside-left-close--skin-light " id="m_aside_left_close_btn"><i
        class="la la-close"></i></button>
<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-light sidebarDark"
    style="background: white;">
    <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-light m-aside-menu--submenu-skin-light "
        data-menu-vertical="true" data-menu-scrollable="true" data-menu-dropdown-timeout="500"
        style="position: fixed;width: inherit;">
        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow" id="side_menu">
            <li id="" style=""
                class="m-menu__item <?= (current_url() === base_url('dashboard')) ? 'm-menu__item--active' : '' ?>">
                <a href="<?= base_url('dashboard') ?>" class="m-menu__link">
                    <i class="m-menu__link-icon la la-dashcube "></i>
                    <span class="m-menu__link-text">Dashboard</span>
                </a>
            </li>
            <?php foreach ($menu as $key => $value) { ?>
            <li class="m-menu__item <?= (current_url() === base_url('admin')) ? 'm-menu__item--active' : '' ?>"
                aria-haspopup="true">
                <a href="<?= base_url('admin') ?>" class="m-menu__link "><i
                        class="m-menu__link-icon <?php echo $value->icon; ?>"></i>
                    <span class="m-menu__link-title">
                        <!-- <span class="m-menu__link-wrap"><span
                                class="m-menu__link-text"><?php echo ucwords($value->menu); ?></span></span> -->
                        <span class="m-menu__link-wrap"><span class="m-menu__link-text">Administrators</span></span>
                    </span>
                </a>
            </li>
            <?php } ?>
            <hr>
            <!-- if(strtolower($role) != 'regular') {  -->
            <li class="m-menu__item <?= (current_url() === base_url('dues')) ? 'm-menu__item--active' : '' ?>">
                <a href="<?= base_url('dues') ?>" class="m-menu__link">
                    <i class="m-menu__link-icon la la-money"></i>
                    <span class="m-menu__link-text">Dues</span>
                </a>
            </li>
            <li class="m-menu__item <?= (current_url() === base_url('announcement')) ? 'm-menu__item--active' : '' ?>">
                <a href="<?= base_url('announcement') ?>" class="m-menu__link">
                    <i class="m-menu__link-icon fa fa-bullhorn"></i>
                    <span class="m-menu__link-text">Announcement</span>
                </a>
            </li>
            <li class="m-menu__item <?= (current_url() === base_url('concerns')) ? 'm-menu__item--active' : '' ?>">
                <a href="<?= base_url('concerns') ?>" class="m-menu__link">
                    <i class="m-menu__link-icon fa fa-exclamation-circle"></i>
                    <span class="m-menu__link-text">Concerns</span>
                </a>
            </li>
            <li class="m-menu__item <?= (current_url() === base_url('forum')) ? 'm-menu__item--active' : '' ?>">
                <a href="<?= base_url('forum') ?>" class="m-menu__link">
                    <i class="m-menu__link-icon fa fa-comments"></i>
                    <span class="m-menu__link-text">Forum</span>
                </a>
            </li>
            <li class="m-menu__item <?= (current_url() === base_url('election')) ? 'm-menu__item--submenu m-menu__item--open m-menu__item--expanded' : '' ?>"
                aria-haspopup="true">
                <a href="#" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon fa fa-codepen"></i><span
                        class="m-menu__link-text">Election</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item <?= (current_url() === base_url('election')) ? 'm-menu__item--active' : '' ?>"
                            aria-haspopup="true">
                            <a href="<?= base_url('election') ?>" class="m-menu__link "><i
                                    class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                    class="m-menu__link-text">Election Results & Voting</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>
<script src="<?php echo base_url(); ?>assets/src/demo/default/custom/components/custom/toastr/build/toastr.min.js"
    type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/src/custom/js/role.js">
</script>