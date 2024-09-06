<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/src/custom/css/partials/header_topbar.css">
<header class="m-grid__item m-header" data-minimize-offset="200" data-minimize-mobile-offset="200">
    <div class="m-container m-container--fluid m-container--full-height">
        <div class="m-stack m-stack--ver m-stack--desktop">
            <div class="m-stack__item m-brand  m-brand--skin-dark " style="background: white;">
                <div class="m-stack m-stack--ver m-stack--general">
                    <div class="m-stack__item m-stack__item--middle m-brand__logo">
                        <a href="#" class="m-brand__logo-wrapper">
                            <!-- <img alt=""
                                src="<?php echo base_url(); ?>/assets/images/img/logoAMSv2.png"
                            style="width: 140px;" /> -->
                            <!-- <img alt="" src="<?php echo base_url("/assets/images/login-creds-bg.jpg"); ?>"
                            style="width: 140px;"> --> <img src="<?php echo base_url("/assets/images/hoasys.png") ?>" style="width:9em" />
                        </a>
                    </div>
                    <div class="m-stack__item m-stack__item--middle m-brand__tools">
                        <a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block">
                            <span></span>
                        </a>
                        <a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
                            <span></span>
                        </a>
                        <!-- <a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
                            <span></span>
                        </a> -->
                        <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
                            <i class="flaticon-more"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav" style="background-color:white">
                <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-dark " id="m_aside_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
                <div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-dark m-aside-header-menu-mobile--submenu-skin-dark ">
                    <ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
                        <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel" id="header-time"></li>
                    </ul>
                </div>

                <div id="m_header_topbar" class="m-topbar m-stack m-stack--ver m-stack--general">
                    <div id="headerTopBar" class="m-stack__item m-topbar__nav-wrapper" style="background-color: white;">
                        <ul class="m-topbar__nav m-nav m-nav--inline">
                        <!-- Bell      -->
                        <!-- <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" data-dropdown-toggle="click" aria-expanded="true">
                                <a href="#" class="m-nav__link m-dropdown__toggle">
                                    <span class="m-topbar__userpic">
                                        <i class="fa fa-bell" style="font-size: x-large;"></i>
                                        <span class="m-badge m-badge--danger" style="margin-top: -3em;margin-left: -1em;">3</span>
                                    </span>
                                </a>
                                <div class="m-dropdown__wrapper">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" style="left: auto; right: 12.5px;"></span>
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__header m--align-center" style="background: url(assets/app/media/img/misc/user_profile_bg.jpg); background-size: cover;">
                                            <div class="m-card-user m-card-user--skin-dark">
                                                <div class="m-card-user__pic">
                                                </div>
                                                <div class="m-card-user__details">
                                                    <span class=" m--font-bolder text-capitalize">
                                                        <h4><?= $this->session->fullname ?>
                                                        </h4>
                                                    </span>
                                                    <span class="text-muted"><?= $this->session->role ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-dropdown__body">
                                            <div class="m-dropdown__content">
                                                <ul class="m-nav m-nav--skin-light">
                                                    <li class="m-nav__item m--align-right">
                                                        <a href="<?php echo base_url('login/logout'); ?>" class="m-nav__link">
                                                            <span class="m-nav__link-title">
                                                                <span class="m-nav__link-wrap">
                                                                    <span class="m-nav__link-text m--font-weight-300">Sign
                                                                        Out</span>
                                                                </span>
                                                            </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li> -->
                            <!-- Profile -->
                            <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" data-dropdown-toggle="click" aria-expanded="true">
                                <a href="#" class="m-nav__link m-dropdown__toggle">
                                    <span class="m-topbar__userpic">
                                        <img src="<?php echo base_url("/assets/images/profile2.png") ?>" class="m--img-rounded m--marginless m--img-centered" alt="">
                                    </span>
                                    <span class="m-topbar__username m--hide">Nick</span>
                                </a>
                                <!-- Rest of your dropdown code remains unchanged -->
                                <div class="m-dropdown__wrapper">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" style="left: auto; right: 12.5px;"></span>
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__header m--align-center" style="background: url(assets/app/media/img/misc/user_profile_bg.jpg); background-size: cover;">
                                            <div class="m-card-user m-card-user--skin-dark">
                                                <div class="m-card-user__pic">
                                                </div>
                                                <div class="m-card-user__details">
                                                    <span class=" m--font-bolder text-capitalize">
                                                        <h4><?= $this->session->fullname ?>
                                                        </h4>
                                                    </span>
                                                    <span class="text-muted"><?= $this->session->role_label ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-dropdown__body">
                                            <div class="m-dropdown__content">
                                                <ul class="m-nav m-nav--skin-light">
                                                    <!-- Profile Link -->
                                                    <!-- <li class="m-nav__item m--align-left">
                                                        <a href="<?php echo base_url('profile'); ?>"
                                                            class="m-nav__link">
                                                            <span class="m-nav__link-title">
                                                                <span class="m-nav__link-wrap">
                                                                    <span class="m-nav__link-text m--font-weight-300"><i
                                                                            class="fa fa-user-circle-o"></i>&nbsp;&nbsp;My
                                                                        Personal Profile</span>
                                                                </span>
                                                            </span>
                                                        </a>
                                                    </li> -->

                                                    <!-- Sign Out Link -->
                                                    <li class="m-nav__item m--align-left">
                                                        <a href="<?php echo base_url('login/logout'); ?>"
                                                            class="m-nav__link">
                                                            <span class="m-nav__link-title">
                                                                <span class="m-nav__link-wrap">
                                                                    <span class="m-nav__link-text m--font-weight-300"><i
                                                                            class="fa fa-sign-out"></i>&nbsp;&nbsp;Sign
                                                                        Out</span>
                                                                </span>
                                                            </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>