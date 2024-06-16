<?php
/**
 * @package Gotlandsboenden form
 * @version 1.0.1
 */
/*
Plugin Name: Gotlandsboenden form
Description: Search form for gotlandsboenden. Use shortcode for display form - [gotlandsboenden-form]
Author: House of WEB Solutions
Version: 1.0.0
Author URI: http://hofwebs.com/
*/

function gotlandsboenden_form_active() {
	update_option( 'gotlandsboenden_form_active', true );
}

register_activation_hook( __FILE__, 'gotlandsboenden_form_active' );

function gotlandsboenden_form_enqueue_scripts() {
	if ( ! wp_script_is( 'jquery', 'enqueued' ) ) {
		wp_enqueue_script(
			'jquery',
			plugins_url( 'assets/js/jquery.min.js', __FILE__ ),
			array( 'jquery' ),
			'3.5.1',
			true
		);

	}
//	wp_register_script( 'jquery-ui-datepicker', plugins_url('assets/js/jquery-ui.js', __FILE__), ['jquery'], '', false);
//	wp_enqueue_script( 'jquery-ui-datepicker' );

	wp_register_script( 'gotlandsboenden_form_main_js', plugins_url( 'assets/js/main.js', __FILE__ ), [ 'jquery' ], '', false );
	wp_enqueue_script( 'gotlandsboenden_form_main_js' );
	wp_localize_script( 'gotlandsboenden_form_main_js', 'register_params', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
	) );
	wp_enqueue_style( 'gotlandsboenden_form-montserrat', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap', array(), null );
}

add_action( 'wp_enqueue_scripts', 'gotlandsboenden_form_enqueue_scripts' );

function gotlandsboenden_css() { ?>
    <style>
        .gotlandsboenden-form-container * {
            box-sizing: border-box;
        }
        .gotlandsboenden-form * {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            font-family: 'Montserrat', sans-serif;
        }

        .no-select * {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .btn {
            cursor: pointer;
        }

        #guest_menu {
            position: relative;
        }

        .guestWrapper {
            display: none;
            background-color: var(--form-background-color, #fff);
            border-radius: 5px;
            box-shadow: 0 -2px 9px 0 rgba(0, 0, 0, .39);
            left: 0;
            margin-top: 12px;
            position: absolute;
            top: 100%;
            z-index: 10;
            max-width: calc(100vw - 40px);
            min-height: 60px;
            /*overflow: hidden auto;*/
            padding: 1rem 1.5rem;
            transform: translateX(-25%);
        }

        .guestWrapper .prefix {
            border-color: transparent transparent #ffffff transparent;
            border-style: solid;
            border-width: 0 5px 5px;
            height: 0;
            left: calc(50% - 5px);
            margin: 0 5px;
            position: absolute;
            top: -5px;
            width: 0;
        }

        @media screen and (min-width: 895px) {
            .guestWrapper {
                min-width: 370px;
                transform: translateX(-50%);
            }

            .guestWrapper .prefix {
                left: calc(68% - 5px);
            }
        }

        @media screen and (min-width: 991px) {
            .guestWrapper {
                min-width: 370px;
                transform: translateX(-25%);
            }

            .guestWrapper .prefix {
                left: calc(50% - 5px);
            }
        }


        .guestWrapper.active {
            display: block;
        }

        .search-step-filter-children-ages {
            display: flex;
            gap: 30px;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
        }

        .children-ages-title {
            font-size: 1rem;
            font-weight: bold;
            line-height: 20px;
        }

        .select-gc-age {
            height: calc(1.5em + 0.75rem + 2px);
            min-width: 110px;
            padding: .375rem .75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
        }

        .select-gc-age:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 .2rem rgba(0, 123, 255, .25);
        }

        .child-guestWrapper.hidden {
            display: none;
        }

        .gotlandsboenden-form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            max-width: 100% !important;
            gap: 10px;

        }

        .gotlandsboenden-form .form-group {
            display: flex;
            flex-direction: column;
            /*flex: 1;*/
            background-color: var(--widget-select-background-color, transparent);
            border: 1px solid var(--widget-select-border-color, #BEBEBE);
            color: var(--form-color, #000);
            cursor: pointer;
            font-size: var(--form-font-size, 1rem);
            font-weight: var(--form-font-weight, normal);
            outline: none;
            padding: 10px 10px 4px 20px;
            text-align: left;
            transition: box-shadow .1s;
            width: 100%;
            background-origin: initial;
            border-radius: var(--border-radius, 5px);
            white-space: nowrap;
        }

        .gotlandsboenden-form .form-group.area {
            min-width: 250px;
            max-width: 250px;
            padding: 10px 0 4px 0;
        }
        .gotlandsboenden-form .form-group.area label {
            padding-left: 20px;
        }
        .gotlandsboenden-form .form-group.area .ui-selectmenu-button {
            padding: 0 20px;
        }

        .gotlandsboenden-form .form-group.date {
            min-width: 130px;
            max-width: 130px;
        }

        .gotlandsboenden-form .form-group.person {
            min-width: 130px;
            max-width: 130px;
        }


        .gotlandsboenden-form .ui-state-default,
        .gotlandsboenden-form .ui-widget-content,
        .gotlandsboenden-form .ui-state-default,
        .gotlandsboenden-form .ui-widget-header,
        .gotlandsboenden-form .ui-state-default,
        .gotlandsboenden-form .ui-button,
        .gotlandsboenden-form .ui-button.ui-state-disabled:hover,
        .gotlandsboenden-form .ui-button.ui-state-disabled:active {
            border: none;
            outline: none;
            box-shadow: none;
            background: transparent;
            padding: 10px 0;
        }

        .gotlandsboenden-form .ui-button:active,
        .gotlandsboenden-form .ui-button:focus,
        .gotlandsboenden-form .ui-button:hover {
            border: none;
            color: currentColor;
            background: transparent;
        }

        .gotlandsboenden-datepicker.ui-datepicker {
            padding: 1rem .6rem;
            border-radius: 5px;
            border: none !important;
            box-shadow: 0 6px 20px rgba(0, 0, 0, .2);
            transform: translate(-25%, 25px);
        }

        .gotlandsboenden-datepicker.ui-datepicker .ui-icon {
            background-size: contain;
        }

        .gotlandsboenden-datepicker.ui-datepicker .ui-datepicker-prev,
        .gotlandsboenden-datepicker.ui-datepicker .ui-datepicker-next {
            cursor: pointer;
        }

        .gotlandsboenden-datepicker.ui-datepicker .ui-datepicker-prev.ui-state-hover,
        .gotlandsboenden-datepicker.ui-datepicker .ui-datepicker-next.ui-state-hover {
            cursor: pointer;
            border: none;
            background: #EDF2F7;
        }

        .gotlandsboenden-datepicker.ui-datepicker .ui-datepicker-next.ui-state-hover.ui-datepicker-next-hover {
            right: 2px;
            top: 2px;
        }

        .gotlandsboenden-datepicker.ui-datepicker .ui-datepicker-prev.ui-state-hover.ui-datepicker-prev-hover {
            left: 2px;
            top: 2px;
        }

        .gotlandsboenden-datepicker.ui-datepicker .ui-datepicker-next .ui-icon {
            background: url("<?php echo plugins_url('assets/images/next-arrow.svg', __FILE__); ?>") 50% 50% no-repeat;
        }

        .gotlandsboenden-datepicker.ui-datepicker .ui-datepicker-prev .ui-icon {
            background: url("<?php echo plugins_url('assets/images/prev-arrow.svg', __FILE__); ?>") 50% 50% no-repeat;
        }

        .gotlandsboenden-datepicker .gc-legend {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin: 2rem .5rem .5rem;
            font-size: 13px;
        }

        .gotlandsboenden-datepicker .gc-legend-icon {
            align-items: center;
            border-radius: 4px;
            display: flex;
            height: 15px;
            justify-content: center;
            margin-right: 8px;
            width: 15px;
            background: #72ab90;
            display: inline-block;
        }

        .gotlandsboenden-datepicker .ui-state-active,
        .gotlandsboenden-datepicker .ui-state-default.ui-state-hover,
        .gotlandsboenden-datepicker.ui-widget-content .ui-state-active,
        .gotlandsboenden-datepicker .ui-widget-header .ui-state-active,
        .gotlandsboenden-datepicker a.ui-button:active,
        .gotlandsboenden-datepicker .ui-button:active,
        .gotlandsboenden-datepicker .ui-button.ui-state-active:hover {
            border: 1px solid rgba(0, 0, 0, 0.2);
            background: #395548;
            font-weight: normal;
            color: #ffffff;
        }

        .gotlandsboenden-datepicker .ui-datepicker-unselectable.ui-state-disabled .ui-state-default {
            border: none;
            background: transparent;
            color: #454545;
            font-size: 15px;
            text-align: center;
        }

        .gotlandsboenden-datepicker a.ui-state-default.ui-priority-secondary.ui-state-hover {
            opacity: 1;
        }

        .gotlandsboenden-datepicker a.ui-state-default {
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 2px;
            background: #72ab90;
            font-weight: 600;
            color: #FFFFFF;
            text-align: center;
        }

        .gotlandsboenden-datepicker.ui-datepicker th {
            font-weight: 300;
            width: 30px;
        }

        .gotlandsboenden-datepicker .ui-datepicker-header {
            background: transparent;
            border: none;
        }

        /*.gotlandsboenden-datepicker.ui-datepicker .ui-datepicker-prev .ui-icon,*/
        /*.gotlandsboenden-datepicker.ui-datepicker .ui-datepicker-next .ui-icon {*/
        /*    display: none;*/
        /*}*/
        /*.gotlandsboenden-datepicker.ui-datepicker .ui-datepicker-prev,*/
        /*.gotlandsboenden-datepicker.ui-datepicker .ui-datepicker-next {*/
        /*    background: none; */
        /*    text-indent: 0;*/
        /*}*/

        .gotlandsboenden-form .form-control {
            cursor: pointer;
            outline: none;
            border: none;
            box-shadow: none;
            color: var(--form-color, #000);
            font-size: var(--form-font-size, 16px);
            font-weight: var(--form-font-weight, normal);
            width: 100%;
            background: transparent;
            padding: 0;
        }

        .gotlandsboenden-form .form-group label {
            cursor: pointer;
            color: var(--form-label-color, var(--form-color, rgba(0, 0, 0, .7)));
            font-size: var(--form-label-font-size, 13px);
            font-weight: var(--form-label-font-weight);
        }

        .gotlandsboenden-form .btn-submit {
            background-color: #72ab90;
            border-radius: 4px;
            font-weight: 700;
            text-transform: uppercase;
            color: #fff;
            font-size: 15px;
            min-width: 69px;
            border: none;
            transition: all 0.25s;
            min-height: 60px;
        }

        .gotlandsboenden-form .btn-submit:hover {
            background-color: #395548;
        }

        .btn-confirm-guest {
            display: flex;
            margin-left: auto;
            background-color: #72ab90;
            border: none;
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            line-height: 15px;
            min-width: 50px;
            padding: 9px 17px;
            border-radius: 4px;
        }

        .btn-confirm-guest:hover {
            background-color: #395548;
        }

        .action-confirm {
            border-top: 1px solid var(--form-border-color, #dcdcdc);
            padding-top: 30px;
            text-align: right;
        }

        .gotlandsboenden-form .form-group:has(.ui-selectmenu-button[aria-expanded="true"]),
        .gotlandsboenden-form .form-group:has(.form-control:focus),
        .gotlandsboenden-form .form-group:has(.guestWrapper.active) {
            box-shadow: var(--focus-shadow, 0 0 6px rgba(0, 0, 0, .2));
        }

        .gotlandsboenden-form .ui-selectmenu-button {
            padding: 0;
            width: 100% !important;
        }

        .gotlandsboenden-form .search-step-filter.item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
        }

        .gotlandsboenden-form .search-step-filter.item:first-child {
            border-bottom: 1px solid var(--form-border-color, #b0b0b0);
            padding-bottom: 20px;
        }

        .gotlandsboenden-form .step {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .gotlandsboenden-form .search-step-filter .title {
            font-size: 16px;
            font-weight: 700;
        }

        .gotlandsboenden-form .search-step-filter .sub-title {
            color: var(--guests-step-color, rgba(0, 0, 0, .7));
            font-size: var(--guests-step-sub-title-font-size, 13px);
            line-height: 18px;

        }

        .gotlandsboenden-form .child-explanation {
            margin: 1rem 0;
            font-size: 14px;
            display: none;
            white-space: normal;
        }

        .gotlandsboenden-form:has(.search-step-filter-children-ages) .child-explanation {
            display: block;
        }

        .gotlandsboenden-form:has(.search-step-filter-children-ages) .children-divider {
            display: block;
        }

        .gotlandsboenden-selectmenu .ui-menu-item-wrapper.ui-state-active {
            background-color: #3F444B;
            color: #ffffff;
            border: none;
        }

        .gotlandsboenden-selectmenu {
            padding-top: 10px !important;
        }

        .gotlandsboenden-form span.ui-selectmenu-icon.ui-icon {
            top: -8px;
            right: -7px;
        }

        .gotlandsboenden-selectmenu .ui-menu-item {
            line-height: 40px;
        }

        .gotlandsboenden-form .children-divider {
            display: none;
            border-bottom: 1px dashed;
            margin: .5rem 0;
            opacity: .2;
        }

        .gotlandsboenden-form .btn-guest {
            align-items: center;
            background: #ffffff;
            border: 1px solid #b0b0b0;
            color: #717171;
            display: flex;
            font-size: 1rem;
            font-weight: 400;
            width: 35px;
            height: 35px;
            justify-content: center;
            line-height: 1.35rem;
            min-width: 0;
            padding: 0;
            transition: all .25s;
            border-radius: 50%;
        }

        .gotlandsboenden-form .btn-guest.btn-plus svg {
            width: 18px;
        }

        .gotlandsboenden-form .btn-guest.btn-minus svg {
            width: 30px;
        }

        .gotlandsboenden-form .btn-guest:where(:hover, :focus) {
            outline: none;
        }

        .gotlandsboenden-form .btn-guest:hover {
            background: #b0b0b0;
            border-color: #b0b0b0;
            color: #717171;
        }

        .gotlandsboenden-form .gc-guest-counter {
            font-size: 16px;
            margin: 0 5px;
            min-width: 33px;
            padding: 9px 5px;
            text-align: center;
            -moz-text-align-last: center;
            text-align-last: center;
        }

        .gotlandsboenden-form .gc-guest-counter.selected {
            background: #3f444b;
            color: #ffffff;
        }

        .gotlandsboenden-form-display {
            display: none;
        }

        @media (max-width: 894px) {
            .gotlandsboenden-form-display {
                display: flex;
                width: 100%;
                align-items: center;
                border: 1px solid #bebebe;
                border-radius: 5px;
                height: 40px;
                overflow: hidden;
            }

            .gotlandsboenden-form-display div {
                display: flex;
                align-items: center;
                justify-content: flex-start;
                flex: 1;
                height: 100%;
            }
            .gotlandsboenden-form-display__date,
            .gotlandsboenden-form-display__person {
                padding: 0 13px;
                position: relative;
                white-space: nowrap;
            }
            .gotlandsboenden-form-display__person:after {
                border-left: 1px solid;
                bottom: 7px;
                content: "";
                left: -1px;
                opacity: .15;
                position: absolute;
                top: 7px;
            }

            .gotlandsboenden-form-display__action {
                background: #72ab90;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center !important;
                height: 100%;
                width: 50px;
                max-width: 50px;
            }

            .gotlandsboenden-form {
                display: none;
                position: fixed;
                top: var(--wp-admin--admin-bar--height, 0);
                left: 0;
                right: 0;
                bottom: 0;
                background: #ffffff;
                flex-direction: column;
                justify-content: flex-start;
                flex-wrap: nowrap;
                padding: 24px 15px;
                z-index: 9999;
                overflow-y: auto;
                margin-top: 0;
            }

            .gotlandsboenden-form.active {
                display: flex;
            }

            .gotlandsboenden-form .ui-selectmenu-button {
                width: 100% !important;
            }

            .gotlandsboenden-form .form-group.date,
            .gotlandsboenden-form .form-group.person,
            .gotlandsboenden-form .form-group.area,
            .gotlandsboenden-form .form-group {
                max-width: 100% !important;
                flex: unset;
                padding: 10px 10px 8px 20px;
            }

            .gotlandsboenden-form .form-group.area {
                padding: 10px 0 8px 0;
            }
            .gotlandsboenden-form .form-group.area label {
                padding-left: 20px;
            }
            .gotlandsboenden-form .form-group.area .ui-selectmenu-button {
                padding: 0 20px;
            }

            .guestWrapper {
                display: block;
                transform: translate(0, 0);
                width: 100%;
                max-width: 100%;
                padding: 0;
                box-shadow: none;
                position: relative;
                top: 0;
                margin: 0;
            }

            .guestWrapper .prefix {
                display: none;
            }

            .gotlandsboenden-form .search-step-filter.item {
                border: 1px solid var(--widget-select-border-color, #BEBEBE);
                padding: 10px 20px;
                background-origin: initial;
                border-radius: var(--border-radius, 5px);
                margin-bottom: .6rem;
            }

            .gotlandsboenden-form .search-step-filter.child-age {
                margin-top: 24px;
            }

            .gotlandsboenden-form .search-step-filter-children-ages {
                border: 1px solid var(--widget-select-border-color, #BEBEBE);
                padding: 10px 20px;
                background-origin: initial;
                border-radius: var(--border-radius, 5px);
                margin-bottom: .6rem;
            }

            .gotlandsboenden-form .search-step-filter-children-ages .children-ages-title {
                color: #717171;
                font-size: 14px;
            }

            .gotlandsboenden-form .search-step-filter .title {
                font-size: 14px;
                color: var(--guests-step-color, #717171)
            }

            .gotlandsboenden-form .form-group.person {
                max-width: 100%;
                border: none;
                padding: 0;
            }

            .gotlandsboenden-datepicker.ui-datepicker {
                transform: translate(-21px, 20px);
            }

            .gotlandsboenden-form .btn-submit {
                width: 100%;
                min-height: 60px;
            }

            .ui-selectmenu-menu.gotlandsboenden-selectmenu.ui-front {
                z-index: 9999 !important;
            }

            .gotlandsboenden-form .form-group:has(.ui-selectmenu-button[aria-expanded="true"]),
            .gotlandsboenden-form .form-group:has(.form-control:focus),
            .gotlandsboenden-form .form-group:has(.guestWrapper.active) {
                box-shadow: none;
            }

            .d-md-block {
                display: none;
            }


        }

        .d-desktop-none {
            @media (min-width: 895px) {
                display: none;
            }
        }

        .mobile-title {
            font-size: .8rem;
            font-weight: 700;
            margin: 1rem 0 0;
        }
    </style>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?php }

add_action( 'admin_head', 'gotlandsboenden_css' );
add_action( 'wp_head', 'gotlandsboenden_css' );

function gotlandsboenden_admin_notice() {
	if ( get_option( 'gotlandsboenden_form_active' ) ):
		?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e( 'Gotlandsboenden form is active. Add shortcode for display form <b>[gotlandsboenden-form]</b>', 'gotlandsboenden-form' ); ?></p>
        </div>
		<?php
		update_option( 'gotlandsboenden_form_active', false );
	endif;
}

add_action( 'admin_notices', 'gotlandsboenden_admin_notice' );

add_action( 'wp_ajax_renderChildAgeItem', 'renderChildAgeItem' );
add_action( 'wp_ajax_nopriv_renderChildAgeItem', 'renderChildAgeItem' );

function renderChildAgeItem( $key = null, $age = null ) {
	if ( isset( $_GET['key'] ) ) {
		$key = $_GET['key'];
	}
	if ( isset( $_GET['age'] ) ) {
		$age = $_GET['age'];
	}
	ob_start(); ?>
    <div id="search-step-filter-children-ages-<?= $key; ?>" class="search-step-filter-children-ages">
        <div class="children-ages-title"><?= __( 'Ålder barn ' ) . $key; ?></div>
        <select name="gc-age-<?= $key; ?>" class="select-gc-age">
            <option value="0" <?= ( $age == 0 ) ? 'selected' : ''; ?>><?= __( '0 år' ); ?></option>
            <option value="1" <?= ( $age == 1 ) ? 'selected' : ''; ?>><?= __( '1 år' ); ?></option>
            <option value="2" <?= ( $age == 2 ) ? 'selected' : ''; ?>><?= __( '2 år' ); ?></option>
            <option value="3" <?= ( $age == 3 ) ? 'selected' : ''; ?>><?= __( '3 år' ); ?></option>
            <option value="4" <?= ( $age == 4 ) ? 'selected' : ''; ?>><?= __( '4 år' ); ?></option>
            <option value="5" <?= ( $age == 5 ) ? 'selected' : ''; ?>><?= __( '5 år' ); ?></option>
            <option value="6" <?= ( $age == 6 ) ? 'selected' : ''; ?>><?= __( '6 år' ); ?></option>
            <option value="7" <?= ( $age == 7 ) ? 'selected' : ''; ?>><?= __( '7 år' ); ?></option>
            <option value="8" <?= ( $age == 8 ) ? 'selected' : ''; ?>><?= __( '8 år' ); ?></option>
            <option value="9" <?= ( $age == 9 ) ? 'selected' : ''; ?>><?= __( '9 år' ); ?></option>
            <option value="10" <?= ( $age == 10 ) ? 'selected' : ''; ?>><?= __( '10 år' ); ?></option>
            <option value="11" <?= ( $age == 11 ) ? 'selected' : ''; ?>><?= __( '11 år' ); ?></option>
            <option value="12" <?= ( $age == 12 ) ? 'selected' : ''; ?>><?= __( '12 år' ); ?></option>
            <option value="13" <?= ( $age == 13 ) ? 'selected' : ''; ?>><?= __( '13 år' ); ?></option>
            <option value="14" <?= ( $age == 14 ) ? 'selected' : ''; ?>><?= __( '14 år' ); ?></option>
            <option value="15" <?= ( $age == 15 ) ? 'selected' : ''; ?>><?= __( '15 år' ); ?></option>
            <option value="16" <?= ( $age == 16 ) ? 'selected' : ''; ?>><?= __( '16 år' ); ?></option>
            <option value="17" <?= ( $age == 17 ) ? 'selected' : ''; ?>><?= __( '17 år' ); ?></option>
        </select>
    </div>
	<?php
	if ( isset( $_GET['key'] ) ) {
		wp_send_json_success( ob_get_clean() );
		wp_die();
	} else {
		return ob_get_clean();
	}
}

function gotlandsboenden_form_render() {
	ob_start();

	$guests = isset( $_GET['gc-0-adults'] ) ? $_GET['gc-0-adults'] : 2;
	$guests += isset( $_GET['gc-0-children'] ) ? $_GET['gc-0-children'] : 0;

	?>
    <!--        <form class="gotlandsboenden-form" action="--><?php //echo get_home_url(); ?><!--/gotlandsboenden" method="get">-->
    <div class="gotlandsboenden-form-container">
        <div class="gotlandsboenden-form-mobile">
            <div class="gotlandsboenden-form-display d-desktop-none">
                <div class="gotlandsboenden-form-display__date">
                    <span id="gotlandsboenden-form__date-from"></span>
                    <span style="margin: 0 6px;">-</span>
                    <span id="gotlandsboenden-form__date-to"></span>
                </div>
                <div class="gotlandsboenden-form-display__person">
                    <div>
                        <span id="guestCounter_mobile"><?= $guests; ?></span>
                        <span id="guestCounter_prefix_mobile" style="margin-left: 6px;"><?= $guests < 2 ? 'person' : 'personer'; ?></span>
                    </div>
                </div>
                <div id="gotlandsboenden-form-display__submit" class="gotlandsboenden-form-display__action">
                    <svg xmlns="http://www.w3.org/2000/svg" height="15px" width="16px" viewBox="0 0 512 512">
                        <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"
                              fill="#FFFFFF"/>
                    </svg>
                </div>
            </div>
        </div>
        <form class="gotlandsboenden-form" action="https://gotlandsboenden.citybreakweb.com/stays" method="get">
        <div id="btn-from-back" class="d-desktop-none">
            <svg xmlns="http://www.w3.org/2000/svg" height="21px" width="21px" viewBox="0 0 512 512">
                <path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 288 480 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-370.7 0 73.4-73.4c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-128 128z"
                      fill="#72AB90"/>
            </svg>
        </div>
        <h6 class="mobile-title d-desktop-none">Något specifikt område?</h6>
        <div class="form-group area">
            <label for="accommodationAreaWrapper"><?= ( 'Område' ); ?></label>
			<?php $get_geo = isset( $_GET['geo'] ) ? $_GET['geo'] : ''; ?>
            <select class="form-control" id="accommodationAreaWrapper" name="geo">
                <option value="" <?= ( empty( $get_geo ) ) ? 'selected' : ''; ?>><?= __( 'Alla områden' ); ?></option>
                <option value="3758" <?= ( $get_geo == "3758" ) ? 'selected' : ''; ?>><?= __( 'Fårö' ); ?></option>
                <option value="3757" <?= ( $get_geo == "3757" ) ? 'selected' : ''; ?>><?= __( 'Norra Gotland inkl Fårö' ); ?></option>
                <option value="3761" <?= ( $get_geo == "3761" ) ? 'selected' : ''; ?>><?= __( 'Södra Gotland' ); ?></option>
                <option value="3762" <?= ( $get_geo == "3762" ) ? 'selected' : ''; ?>><?= __( 'Visby' ); ?></option>
                <option value="3763" <?= ( $get_geo == "3763" ) ? 'selected' : ''; ?>><?= __( 'Visby innerstad' ); ?></option>
                <option value="3764" <?= ( $get_geo == "3764" ) ? 'selected' : ''; ?>><?= __( 'Visby med omnejd' ); ?></option>
                <option value="3760" <?= ( $get_geo == "3760" ) ? 'selected' : ''; ?>><?= __( 'Västra Gotland' ); ?></option>
                <option value="3759" <?= ( $get_geo == "3759" ) ? 'selected' : ''; ?>><?= __( 'Östra Gotland' ); ?></option>
            </select>
        </div>
        <h6 class="mobile-title d-desktop-none">När vill du boka?</h6>
        <div class="form-group date">
            <label for="accommodationAreaWrapper"><?= ( 'Ankomst' ); ?></label>
            <input type="text" class="form-control" id="dateAreaStart" name="start" readonly="readonly"
                   value="<?= date( "Y-m-d" ) ?>">
        </div>
        <div class="form-group date">
            <label for="accommodationAreaWrapper"><?= ( 'Avresa' ); ?></label>
            <input type="text" class="form-control" id="dateAreaEnd" name="end" readonly="readonly"
                   value="<?= date( "Y-m-d", strtotime( "+1 day" ) ) ?>">
        </div>
        <h6 class="mobile-title d-desktop-none">Vem kommer?</h6>
        <div class="form-group person" id="guest_menu">
            <!-- https://gotlandsboenden.citybreakweb.com/stays?start=2024-06-17&end=2024-06-20&gc-0-adults=3&gc-0-children=5&gc-0-age=1,2,3,4,5-->
            <label class="d-md-block"><?= ( 'Gäster' ); ?></label>
            <div>
                <span id="guestCounter_desktop" class="d-md-block"><?= $guests; ?></span>
                <span id="guestCounter_prefix_desktop"  class="d-md-block" style="margin-left: 6px;"><?= $guests < 2 ? 'person' : 'personer'; ?></span>
            </div>
            <input type="hidden" id="gc-0-adults" name="gc-0-adults"
                   value="<?= isset( $_GET['gc-0-adults'] ) ? $_GET['gc-0-adults'] : 2; ?>">
            <input type="hidden" id="gc-0-children" name="gc-0-children"
                   value="<?= isset( $_GET['gc-0-children'] ) ? $_GET['gc-0-children'] : 0; ?>">
            <input type="hidden" id="gc-0-age" name="gc-0-age" value="<?= $_GET['gc-0-age'] ?? ''; ?>">
            <div class="guestWrapper">
                <span class="prefix"></span>
                <div class="search-step-filter item no-select">
                    <div>
                        <div id="search-step-filter-adults" class="title"><?= __( 'Vuxna' ); ?></div>
                        <div class="sub-title"><?= __( 'Från 18 år' ); ?></div>
                    </div>
                    <div id="adults-block" class="step step-adults">
                        <button type="button" class="btn btn-guest btn-minus" data-type="adults">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" viewBox="0 0 24 24"
                                 fill="none">
                                <rect width="24" height="24" fill="transparent"/>
                                <path d="M6 12H18" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <span id="gc-0-adults-counter"
                              class="gc-guest-counter selected"><?= $_GET['gc-0-adults'] ?? 2; ?></span>
                        <button type="button" class="btn btn-guest btn-plus" data-type="adults">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                 fill="#000000" height="30px" width="30px" version="1.1" id="Layer_1"
                                 viewBox="0 0 455 455" xml:space="preserve">
                            <polygon
                                    points="455,212.5 242.5,212.5 242.5,0 212.5,0 212.5,212.5 0,212.5 0,242.5 212.5,242.5 212.5,455 242.5,455 242.5,242.5   455,242.5 "/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="search-step-filter item no-select">
                    <div>
                        <div id="search-step-filter-children" class="title"><?= __( 'Barn' ); ?></div>
                        <div class="sub-title"><?= __( 'Ålder 0-17' ); ?></div>
                    </div>
                    <div id="children-block" class="step step-children">
                        <button type="button" class="btn btn-guest btn-minus" data-type="children">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" viewBox="0 0 24 24"
                                 fill="none">
                                <rect width="24" height="24" fill="transparent"/>
                                <path d="M6 12H18" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
						<?php $child_count = $_GET['gc-0-children'] ?? 0; ?>
                        <span id="gc-0-children-counter"
                              class="gc-guest-counter <?= ( $child_count > 0 ) ? 'selected' : ''; ?>"><?= $child_count; ?></span>
                        <button type="button" class="btn btn-guest btn-plus" data-type="children">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                 fill="#000000" height="30px" width="30px" version="1.1" id="Layer_1"
                                 viewBox="0 0 455 455" xml:space="preserve">
                            <polygon
                                    points="455,212.5 242.5,212.5 242.5,0 212.5,0 212.5,212.5 0,212.5 0,242.5 212.5,242.5 212.5,455 242.5,455 242.5,242.5   455,242.5 "/>
                            </svg>
                        </button>
                    </div>
                </div>
				<?php
				$childrens     = intval( $_GET['gc-0-children'] ?? 0 );
				$null_array    = array_fill( 0, $childrens, 0 );
				$childrens_age = ( isset( $_GET['gc-0-age'] ) ) ? explode( ',', $_GET['gc-0-age'] ) : $null_array;
				?>

                <div id="children-age-block" class="search-step-filter child-age no-select">
                    <div class="children-divider"></div>
					<?php
					if ( $childrens > 0 ):
						foreach ( $childrens_age as $key => $item ):
							$age = ( $item > 17 ) ? 0 : $item;
							echo renderChildAgeItem( $key + 1, $age );
						endforeach;
					endif; ?>
                </div>
                <div class="child-explanation <?= ( $childrens < 1 ) ? ' hidden' : ''; ?>">
					<?= __( '<b>Varför krävs ålder?</b> Vi vill inte att du betalar för mycket eller missar erbjudanden för barn i en viss ålder.' ); ?>
                </div>
                <div class="action-confirm d-md-block">
                    <button id="btn-confirm-guest"
                            class="btn btn-confirm-guest d-md-block"><?= __( 'Klar' ); ?></button>
                </div>

            </div>
        </div>
        <button type="submit" class="btn btn-submit">Sök</button>
    </form>
    </div>
    <script>
        var e = jQuery.datepicker.regional.en.monthNames, a = [];
        jQuery('#dateAreaStart').datepicker({
            // firstDay: 7,
            // inline: !0,
            // changeMonth: !0,
            showOtherMonths: !0,
            minDate: 0,
            altField: "#actualDate",
            selectOtherMonths: !0,
            dateFormat: "yy-mm-dd",
            nextText: ">",
            prevText: "<",
            monthNamesShort: e,
            monthNames: ["junauri", "fubrauri", "mars", "april", "maj", "juni", "juli", "augusti", "september", "oktober", "november", "december"],
            dayNamesMin: ["mån", "tis", "ons", "tors", "fre", "lör", "sön"],
            onChangeMonthYear: function (year, month, inst) {
                setTimeout(function () {
                    addLegend(inst);
                }, 0);
            },
            beforeShow: function (input, inst) {
                setTimeout(function () {
                    inst.dpDiv.addClass('gotlandsboenden-datepicker');
                    addLegend(inst);
                }, 0);
            },
            beforeShowDay: function (e) {
                var t = jQuery.datepicker.formatDate("yy-mm-dd", e), o = e.getDate();
                return [-1 == a.indexOf(t), o < 10 ? "zero" : ""]
            },
            onSelect: function (selectedDate) {
                var date = jQuery(this).datepicker('getDate');
                date.setDate(date.getDate() + 1);
                jQuery("#dateAreaEnd").datepicker("option", "minDate", date);
                setTimeout(function () {
                    updateDateMobile();
                    jQuery("#dateAreaEnd").datepicker("show");
                }, 100)
            }
        });
        // jQuery('#dateAreaEnd').select2();
        jQuery('#dateAreaEnd').datepicker({
            // firstDay: 7,
            // inline: !0,
            // changeMonth: !0,
            showOtherMonths: !0,
            minDate: 0,
            altField: "#actualDate",
            selectOtherMonths: !0,
            dateFormat: "yy-mm-dd",
            monthNamesShort: e,
            dayNamesMin: ["mån", "tis", "ons", "tors", "fre", "lör", "sön"],
            monthNames: ["junauri", "fubrauri", "mars", "april", "maj", "juni", "juli", "augusti", "september", "oktober", "november", "december"],
            nextText: ">",
            prevText: "<",
            onChangeMonthYear: function (year, month, inst) {
                setTimeout(function () {
                    addLegend(inst);
                }, 0);
            },
            beforeShow: function (input, inst) {
                setTimeout(function () {
                    inst.dpDiv.addClass('gotlandsboenden-datepicker');
                    addLegend(inst);
                }, 0);
            },
            beforeShowDay: function (e) {
                var t = jQuery.datepicker.formatDate("yy-mm-dd", e), o = e.getDate();
                return [-1 == a.indexOf(t), o < 10 ? "zero" : ""]
            }
        });

        function updateDateMobile() {
            let dateFrom = jQuery('#dateAreaStart').val();
            let dateTo = jQuery('#dateAreaEnd').val();

            const monthNamesSwedish = ["jun", "fub", "mar", "apr", "maj", "jun", "jul", "aug", "sep", "okt", "nov", "dec"];

            function formatDate(dateString) {
                let date = new Date(dateString);
                let day = date.getDate();
                let month = monthNamesSwedish[date.getMonth()];
                return `${day} ${month}`;
            }

            let formattedDateFrom = formatDate(dateFrom);
            let formattedDateTo = formatDate(dateTo);

            jQuery('#gotlandsboenden-form__date-from').text(formattedDateFrom);
            jQuery('#gotlandsboenden-form__date-to').text(formattedDateTo);
        }
        updateDateMobile();
        jQuery('#dateAreaStart').on('change', function (e) {updateDateMobile();});
        jQuery('#dateAreaEnd').on('change', function (e) {updateDateMobile();});

        function addLegend(inst) {
            if (!inst.dpDiv.find(".gc-legend").length) {
                inst.dpDiv.find(".ui-datepicker-calendar").after('<div class="gc-legend"><span class="gc-legend-icon"></span>Valbart som utcheckningsdatum</div>');
            }
        }

        jQuery("#accommodationAreaWrapper").selectmenu({
            classes: {
                "ui-selectmenu-menu": "gotlandsboenden-selectmenu",
            }
        });
    </script>
	<?php
	return ob_get_clean();
}

if ( ! shortcode_exists( 'gotlandsboenden-form' ) ) {
	add_shortcode( 'gotlandsboenden-form', 'gotlandsboenden_form_render' );
}