<?php
/**
 * Created by PhpStorm.
 * User: luismi
 * Date: 21/07/16
 * Time: 8:50
 */
add_action( 'wp_authenticate' , 'wpimap_login_UserAuthentication' );

function wpimap_login_UserAuthentication () {

    if (isset($_POST['log'])) {
        $username = $_POST['log'];
    } else {
        $username = "";
    }

    if (isset($_POST['pwd'])) {
        $password = $_POST['pwd'];
    } else {
        $password = "";
    }

    //user do not exist on WPDB

    if ( ! username_exists( $username ) && $username != "" ) {

        // try to log into the external service or database with username and password

        $server = get_option('wpimap_login_server');
        $port = get_option('wpimap_login_port');

        $ext_auth = @imap_open( "{".gethostbyname($server).":".$port."/novalidate-cert/readonly}INBOX", $_POST['log'], $_POST['pwd'], OP_HALFOPEN );
        imap_errors();

        // if external authentication was successful
        if ($ext_auth) {

            imap_close($ext_auth);

            // find a way to get the user id
            $user_id = username_exists($_POST['log']);

            if ( !$user_id and email_exists($_POST['log']) == false ) {

                //If user do not exists, create it and login into site.

                $user_id = wp_create_user( $_POST['log'], $_POST['pwd'], $_POST['log'] );
                $user = wp_set_current_user($user_id,$_POST['log']);

                // this will actually make the user authenticated as soon as the cookie is in the browser
                wp_set_auth_cookie($user_id);

            } else {

                //If user exists, just login

                $userdata = get_userdata($user_id);
                $user = wp_set_current_user($user_id,$_POST['log']);

                // this will actually make the user authenticated as soon as the cookie is in the browser
                wp_set_auth_cookie($user_id);

            }

            do_action('wp_login',$userdata->ID);

        } else {

            return;

        }

    } else {

        //if the user exists, just login with data provided

        wp_authenticate($username,$password);

    }

}