<link rel="stylesheet" href="https://csb-xjde2f.netlify.app/member-search.css">

<?php
// get the user id from the querystring
$user_id = (!empty($_GET['id'])) ? $_GET['id'] : null;
$event_date = date( 'd/m/Y' );

// set default values
$valid_membership = false;
$membership_title = null;
$membership_status = null;
$membership_type = null;

if ($user_id) {

    /**
     * Get Drop Ins Without Gear
     */
    $without_args = array(
        'post_type' => 'dropin',
        'numberposts' => -1,
        'order' => 'DESC',
        'orderby' => 'ID',
        'meta_query'    => array(
            'relation'      => 'AND',
            array( 'key' => 'user_id', 'value' => $user_id, 'compare' => '=' ),
            array( 'key' => 'drop_in_type', 'value' => 'withoutgear', 'compare' => '=' ),
            array(
                'relation'      => 'OR',
                array( 'key' => 'date_used', 'compare' => 'NOT EXISTS' ),
                array( 'key' => 'date_used', 'value' => '', 'compare' => '=' )
            ),
        ),
    );
    $dropins_without_gear = get_posts( $without_args );

    /**
     * Get Drop Ins With Gear
     */
    $with_args = array(
        'post_type' => 'dropin',
        'numberposts' => -1,
        'order' => 'DESC',
        'orderby' => 'ID',
        'meta_query'    => array(
            'relation'      => 'AND',
            array( 'key' => 'user_id', 'value' => $user_id, 'compare' => '=' ),
            array( 'key' => 'drop_in_type', 'value' => 'withgear', 'compare' => '=' ),
            array(
                'relation'      => 'OR',
                array( 'key' => 'date_used', 'compare' => 'NOT EXISTS' ),
                array( 'key' => 'date_used', 'value' => '', 'compare' => '=' )
            ),
        ),
    );
    $dropins_with_gear = get_posts( $with_args );

    /**
     * Check to make sure MemberPress is enabled
     * and get membership details
     */
    if (class_exists('MeprUtils')) {

        $mepr_user = new MeprUser($user_id);
        $active_transactions = $mepr_user->active_product_subscriptions('transactions');

        // if subscriptions, then show
        if (!empty($active_transactions)) {

            //get the first transaction
            $txn = $active_transactions[0];
            $subscription = $txn->subscription();
            $product = $txn->product();

            // if there is a subscription, make sure it is active
            if ($subscription) {
                $valid_membership = true;
                $membership_title = $product->post_title;
                $membership_status = $subscription->status;
                $membership_type = $product->post_name;
            }

        }

    } else {

        echo 'Please enable MemberPress';

    }

    // update user meta for qr code scanned
    $scans = get_user_meta($user_id, 'qr_code_scans', true);
    if (!is_array($scans)) $scans = array();     // if not an array, it is empty, then initialize with a blank array
    array_unshift($scans, date("Y-m-d H:i:s"));   // add to the top of the array
    $updated = update_user_meta($user_id, 'qr_code_scans', $scans);

    // get check-ins
    $checkins = get_user_meta($user_id, 'check_ins', true);

    // determine if checked in
    if ( is_array( $checkins ) )
    {
        $checkin_date = date_create( $checkins[0] )->format('Y-m-d');
        $today = date_create()->format('Y-m-d');
        $checked_in_today = ( $checkin_date === $today ) ? true : false;
    } else {
        $checked_in_today = false;
    }

    /**
     * determine if drop in has been used
     */
    $used_args = array(
        'post_type' => 'dropin',
        'numberposts' => -1,
        'order' => 'ASC',
        'orderby' => 'ID',
        'meta_query'    => array(
            'relation'      => 'AND',
            array( 'key' => 'user_id', 'value' => $user_id, 'compare' => '=' ),
            array( 'key' => 'date_used', 'compare' => '=', 'value' => $event_date ),
        ),
    );
    $used_dropins = get_posts( $used_args );
    $drop_in_used_this_date = ( count( $used_dropins ) ) ? true : false;

}


$args = [
    'wfPage' => '6434424375de790e0372ef95',
    'body' => 'body',
    'head' => 'head/front-page',
];   

if (function_exists('udesly_set_frontend_editor_data')) {
    udesly_set_frontend_editor_data('page');
}
     
get_header('', $args);

/* Start the Loop */
while ( have_posts() ) :
    the_post();
    get_template_part("template-parts/symbols/embed-global");

    $current_logged_in_user = wp_get_current_user();
    ?>
    <style>
        div.navbar, div.is--footer, div#wpadminbar { display: none; }
            html {
  margin-top: 0px !important;
}
    </style>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment-timezone@0.5.40/moment-timezone.min.js"></script>
    <script>
        moment.tz.add("America/Edmonton|LMT MST MDT MWT MPT|7x.Q 70 60 60 60|0121212121212134121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121212121|-2yd4q.8 shdq.8 1in0 17d0 hz0 2dB0 1fz0 1a10 11z0 1qN0 WL0 1qN0 11z0 IGN0 8x20 ix0 3NB0 11z0 XQp0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1fz0 1a10 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1fz0 1a10 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 14p0 1lb0 14p0 1nX0 11B0 1nX0 11B0 1nX0 14p0 1lb0 14p0 1lb0 14p0 1nX0 11B0 1nX0 11B0 1nX0 14p0 1lb0 14p0 1lb0 14p0 1lb0 14p0 1nX0 11B0 1nX0 11B0 1nX0 14p0 1lb0 14p0 1lb0 14p0 1nX0 11B0 1nX0 11B0 1nX0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0|10e5")
    </script>
    <div class="working hidden">Updating ...</div>
    <script>
        // set global variables


        /**
         * Use Drop-In
         */
        function doDropInCheckInAjax( evt, user_id, dropin_type, date_used ) {

            // enable working overlay
            $( '.working' ).removeClass( 'hidden' );

            // set ajax values
            var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
            const $container = $( evt.currentTarget );

            /**
             * Use Drop In
             */
            const dropintype_settings = {};
            dropintype_settings[ 'action' ] = 'panther_gym_use_dropin';
            dropintype_settings[ 'user_id' ] = user_id;
            dropintype_settings[ 'dropin_type' ] = dropin_type;
            dropintype_settings[ 'date_used' ] = date_used;
            let jqxhrDropInUse = jQuery.ajax({
                url: ajaxurl,
                data: dropintype_settings,
                type: 'POST'
            });

            // on success do checkin
            jqxhrDropInUse.success(function( dropInReturnData ) {
                if ( dropInReturnData.success ) {
                    doCheckIn( user_id );
                }
            });

        }

        /**
         * Check-In
         */
        function doCheckIn( user_id ) {

            // enable working overlay
            $( '.working' ).removeClass( 'hidden' );

            // set ajax values
            var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";

            /**
             * Do Check-In
             */
            const checkin_settings = {};
            checkin_settings[ 'action' ] = 'panther_gym_checkin_user';
            checkin_settings[ 'user_id' ] = user_id;
            let jqxhrCheckIn = jQuery.ajax({
                url: ajaxurl,
                data: checkin_settings,
                type: 'POST'
            });

            // on success remove working div and show message
            jqxhrCheckIn.success(function( checkInReturnData ) {
                if ( checkInReturnData.success ) {
                    window.location.replace("<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '&confirmation=checkin_complete'; ?>");
                }
            });

        }
    </script>

    <main class="main-wrapper">
        <div class="section wf-section">
            <div class="container--1248 is--flex--nav">
                <a href="<?php echo _u('a2f','link','global'); ?>" aria-current="page" class="navbar--logo-link w-inline-block" data-link="a2f"><img src="<?php echo udesly_get_image(_u('i29e24fe9', 'img','global'))->src ?>" loading="lazy" alt="<?php echo udesly_get_image(_u('i29e24fe9', 'img','global'))->alt ?>" class="navbar--logo-img" data-img="i29e24fe9" srcset="<?php echo udesly_get_image(_u('i29e24fe9', 'img','global'))->srcset ?>"></a>

                <div>
                    <form action="<?php echo get_permalink( 9694 ); ?>" method="get>
                        <input type="text" name="search_details" value="">
                        <input type="submit" value="Search for a Member" class="elementor-button-link elementor-button elementor-size-sm">
                    </form>
                </div>
            </div>
        </div>
        <div class="section is--blog-hero wf-section">
            <div class="container--1248 is--center">
                <h1 class="heading--120" data-text="t1f27a2">Member Details</h1>
                <h4>
                    Logged in as: <?php echo $current_logged_in_user->display_name; ?>
                </h4>
            </div>
        </div>

        <!-- Show COnfirmation box -->
        <?php if ( array_key_exists( 'confirmation', $_GET ) ): ?>
            <?php if ( 'checkin_complete' === $_GET[ 'confirmation' ] ): ?>
                <div class="section">
                    <div class="container--1248 confirmation">
                        <div>Check-In Complete</div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Only show this section if they have a valid membership -->
        <?php if ( $valid_membership && $user_id ): ?>
            <div class="section">
                <div class="container--1248">
                    <h2>Available Actions</h2>
                    <div class="available-actions-info">Membership Level: <strong><?php echo $membership_title; ?></strong></div>
                    <div class="available-actions-info">Membership Status: <strong><?php echo $membership_status; ?></strong></div>
                    <div class="available-actions-info">Checked-in Today?
                        <?php if ( $checked_in_today ): ?>
                            <span class="yes">YES</span>
                        <?php else: ?>
                            <span class="no">NO</span>
                        <?php endif; ?>
                        ( Latest check-in on:
                        <script>
                            var checkin = moment.utc("<?php echo $checkins[0]; ?>", "YYYY-MM-DD HH:mm:ss").tz("America/Edmonton");
                            document.write(checkin.format("MMMM Do YYYY, h:mm:ss a")); // without day
                        </script>
                        )
                    </div>
                    <?php if ( $valid_membership && 'free-membership' === $membership_type ): ?>
                        <div class="available-actions-info">Drop-In Used Today?
                            <?php if ( $drop_in_used_this_date ): ?>
                                <span class="yes">YES</span>
                            <?php else: ?>
                                <span class="no">NO</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="available-actions">

                        <!-- If they have dropins with gear -->
                        <?php if ( count( $dropins_with_gear ) > 0 && !$checked_in_today && !$drop_in_used_this_date ): ?>
                            <button
                                type="button"
                                class="elementor-button-link elementor-button elementor-size-sm"
                                role="button"
                                style="text-decoration: none;"
                                onclick="doDropInCheckInAjax( event, <?php echo $user_id; ?>, 'with_gear', '<?php echo $event_date; ?>' )"
                            >
                                Use Drop-In with Gear and Check-In
                            </button>
                        <?php endif; ?>

                        <!-- If they have dropins without gear -->
                        <?php if ( count( $dropins_without_gear ) > 0 && !$checked_in_today && !$drop_in_used_this_date ): ?>
                            <button
                                type="button"
                                class="elementor-button-link elementor-button elementor-size-sm"
                                role="button"
                                style="text-decoration: none;"
                                onclick="doDropInCheckInAjax( event, <?php echo $user_id; ?>, 'without_gear', '<?php echo $event_date; ?>' )"
                            >
                                Use Drop-In without Gear and Check-In
                            </button>
                        <?php endif; ?>

                        <!-- Checkin -->
                        <?php if ( !$checked_in_today && ( $drop_in_used_this_date || 'full-membership' === $membership_type ) ): ?>
                            <button
                                type="button"
                                class="elementor-button-link elementor-button elementor-size-sm"
                                role="button"
                                style="text-decoration: none;"
                                onclick="doCheckIn( <?php echo $user_id; ?> )"
                            >
                                Check-In
                            </button>
                        <?php endif; ?>


                        <!-- Show Drop-In Purchase for Free Members only with valid membership -->
                        <?php if ( $valid_membership && 'free-membership' === $membership_type ): ?>
                            <!-- <form action="<?php echo home_url( '/member-purchase-drop-in/' ); ?>" method="get">
                                <input type="hidden" name="id" value="<?php echo $user_id; ?>">
                                <input type="submit" value="Purchase a Drop-In">
                            </form> -->
                        <?php endif; ?>

                        <?php if ( $valid_membership && 'full-membership' === $membership_type && !$checked_in_today ): ?>
                            <!-- <form action="" method="post">
                                <input type="hidden" name="check_in" value="now">
                                <input type="submit" value="Check-In">
                            </form> -->
                        <?php endif; ?>

                        <!-- <button>Purchase at Juice Bar</button> -->
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <!-- /Only show this section if they have a valid membership -->

        <div class="section wf-section">
            <div class="container--1248">

            <?php
                // continue as long as there is an id
                if ($user_id) {

                    // ensure valid user
                    $user = get_userdata($user_id);
                    if ($user) {
                        ?>
                        <div class="w3-container">

                            <div></div>

                            <div class="w3-bar w3-black">
                                <button class="w3-bar-item w3-button tablink w3-active" onclick="openTab(event,'UserDetails')">Member Details</button>
                                <button class="w3-bar-item w3-button tablink" onclick="openTab(event,'CheckIns')">Check-Ins</button>
                                <button class="w3-bar-item w3-button tablink" onclick="openTab(event,'QRCodeScans')">QR Code Scans</button>
                            </div>

                            <!-- User Details -->
                            <div id="UserDetails" class="w3-container w3-border tab">
                                <h2>Member Details</h2>
                                <table class="user-details-table">
                                    <tbody>
                                        <tr>
                                            <th>Membership</th>
                                            <td>
                                                <?php if ( $valid_membership ): ?>

                                                    <div class="valid-subscription"><span class="type"><?php echo $membership_title; ?></span> subscription found.</div>
                                                    <div>Membership status: <span class="status ' . $subscription->status . '"><?php echo $membership_status; ?></span></div>

                                                <?php else: ?>

                                                    No active subscriptions found.

                                                <?php endif ?>
                                            </td>
                                        </tr>

                                        <?php if ( $valid_membership && 'free-membership' === $membership_type ): ?>
                                            <tr>
                                                <th>Drop-Ins Available</th>
                                                <td>
                                                    Without gear: <?php echo count( $dropins_without_gear ); ?>
                                                    <br />
                                                    With gear: <?php echo count( $dropins_with_gear ); ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>

                                        </tr>
                                            <th>Name</th>
                                            <td><?php echo $user->data->display_name; ?></td>
                                        </tr>
                                        </tr>
                                            <th>Email</th>
                                            <td><?php echo $user->data->user_email; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Picture</th>
                                            <td><?php echo do_shortcode('[avatar user="' . $user_id . '"]'); ?></td>
                                        </tr>

                                        <tr>
                                            <th>Latest Check-In</th>
                                            <td>
                                                <ul>
                                                    <?php if ( is_array( $checkins ) ): ?>
                                                        <li>
                                                            <script>
                                                                var checkin = moment.utc("<?php echo $checkins[0]; ?>", "YYYY-MM-DD HH:mm:ss").tz("America/Edmonton");
                                                                document.write(checkin.format("MMMM Do YYYY, h:mm:ss a")); // without day
                                                            </script>
                                                        </li>
                                                    <?php else: ?>
                                                        No Check-Ins Found
                                                    <?php endif; ?>
                                                </ul>

                                                <!-- <form action="" method="post">
                                                    <input type="hidden" name="check_in" value="now">
                                                    <input type="submit" value="Check-In">
                                                </form> -->
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Latest QR Code Scan</th>
                                            <td>
                                                <ul>
                                                    <li>
                                                        <script>
                                                            var scan = moment.utc("<?php echo $scans[0]; ?>", "YYYY-MM-DD HH:mm:ss").tz("America/Edmonton");
                                                            document.write(scan.format("MMMM Do YYYY, h:mm:ss a")); // without day
                                                        </script>
                                                    </li>
                                                    <!--  -->
                                                </ul>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /User Details -->

                            <!-- Check-Ins -->
                            <div id="CheckIns" class="w3-container w3-border tab" style="display:none">
                                <h2>Check-Ins</h2>
                                <?php if ( is_array( $checkins ) ): ?>
                                    <ul>
                                        <?php
                                        foreach ($checkins as $key => $checkin) {
                                        ?>
                                            <li>
                                                <script>
                                                    var checkin = moment.utc("<?php echo $checkin; ?>", "YYYY-MM-DD HH:mm:ss").tz("America/Edmonton");
                                                    //document.write( checkin.format("dddd, MMMM Do YYYY, h:mm:ss a") );  // includes day
                                                    document.write(checkin.format("MMMM Do YYYY, h:mm:ss a")); // without day
                                                </script>
                                            </li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                <?php else: ?>
                                    No Check-Ins Found
                                <?php endif; ?>
                            </div>
                            <!-- /Check-Ins -->

                            <!-- QR Code Scans -->
                            <div id="QRCodeScans" class="w3-container w3-border tab" style="display:none">
                                <h2>QR Code Scans</h2>
                                <ul>
                                    <?php
                                    foreach ($scans as $key => $scan) {
                                    ?>
                                        <li>
                                            <script>
                                                var scan = moment.utc("<?php echo $scan; ?>", "YYYY-MM-DD HH:mm:ss").tz("America/Edmonton");
                                                //document.write( scan.format("dddd, MMMM Do YYYY, h:mm:ss a") );  // includes day
                                                document.write(scan.format("MMMM Do YYYY, h:mm:ss a")); // without day
                                            </script>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                            <!-- /QR Code Scans -->

                        </div>

                        <script>
                        function openTab(evt, cityName) {
                            var i, x, tablinks;
                            x = document.getElementsByClassName( "tab" );
                            for (i = 0; i < x.length; i++) {
                                x[i].style.display = "none";
                            }
                            tablinks = document.getElementsByClassName( "tablink" );
                            for (i = 0; i < x.length; i++) {
                                tablinks[i].className = tablinks[i].className.replace(" w3-active", "");
                            }
                            document.getElementById(cityName).style.display = "block";
                            evt.currentTarget.className += " w3-active";
                        }
                        </script>

                        
                        <?php

                    } else {

                        // not a user so set user_id to null
                        $user_id = null;
                    }
                }

                if (!$valid_membership && $user_id) {
                    echo '<div class="not-valid-user">No Membership(s) found</div>';
                }

                ?>
                    </div>
                </div>
                <style>
                    .confirmation {
                        margin-top: 20px;
                        padding: 20px;
                        border: 1px solid black;
                        font-weight: 600;
                        font-size: 18px;
                        text-align: center;
                        background-color: lightgray;
                    }
                    .available-actions-info {
                        font-size: 20px;
                    }
                    .available-actions-info .yes {
                        color: green;
                        font-weight: 600;
                    }
                    .available-actions-info .no {
                        color: red;
                        font-weight: 600;
                    }
                    .available-actions {
                        display: flex;
                        gap: 15px;
                        margin-top: 20px;
                    }
                    .w3-container {
                        order: 2;
                    }
                    .w3-bar {
                        margin-top: 50px;
                        display: flex;
                        gap: 0;
                    }
                    .w3-button {
                        padding: 10px 10px;
                        background: white !important;
                        color: black !important;
                        border: 1px solid black !important;
                        bottom: -1px !important;
                        position: relative !important;
                    }
                    .w3-button.w3-active {
                        background: black !important;
                        color: white !important;
                    }
                    .w3-container.w3-border.tab {
                        margin-bottom: 50px;
                        border: 1px solid black;
                        padding: 20px;
                    }
                    table.user-details-table {
                        margin-top: 0;
                        padding-top: 0;
                        border-top: 1px solid black;
                        width: 100%;
                    }
                    table.user-details-table th {
                        color: black !important;
                        border-bottom: 1px solid black;
                    }

                    table.user-details-table td {
                        border-bottom: 1px solid black !important;
                        padding-top: 20px;
                        padding-bottom: 20px;
                    }

                    span.status {
                        font-weight: 600;
                        font-size: 20px;
                    }

                    span.status.active {
                        color: greenyellow;
                        text-shadow: 1px 1px 10px black;
                    }

                    span.type {
                        color: darkblue;
                        font-weight: 600;
                        font-size: 20px;
                    }

                    .not-valid-user {
                        display: block;
                        border: 1px solid red;
                        color: red;
                        padding: 20px;
                        font-weight: bold;
                        background: antiquewhite;
                        margin-top: 20px;
                        width: 464px;
                        order: 1;
                        font-size: 20px;
                        margin-bottom: 20px;
                    }

                    .no-membership-hide {
                        display: none;
                    }
                    ul {
                        font-size: 20px;
                    }
                    input[type="submit"] {
                        font-size: 18px;
                        font-weight: 600;
                        text-transform: uppercase;
                        color: #FFFFFF;
                        background-color: #B20A10;
                        border-style: none;
                        border-radius: 0px 0px 0px 0px;
                        padding: 16px 40px;
                        cursor: pointer;
                    }
                    div.working {
                        background: rgba(0, 0, 0, 0.9);
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100vw;
                        height: 100%;
                        color: white;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        font-size: 30rem;
                        z-index: 9999999009;
                    }
                    div.working.hidden {
                        display: none;
                    }
                </style>

            </div>
        </div>

        <div class="richtext w-richtext"><?php the_content(); ?></div>
        <?php get_template_part("template-parts/symbols/footer"); ?>
    </main>
    <?php
endwhile;
// End of the loop.

$args = [
  'footer' => 'footer/401',
];  

if (function_exists('udesly_output_frontend_editor_data')) {
     udesly_output_frontend_editor_data('page');
}

get_footer('', $args);
