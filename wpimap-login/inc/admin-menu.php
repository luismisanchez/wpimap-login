<?php
/**
 * Created by PhpStorm.
 * User: luismi
 * Date: 20/07/16
 * Time: 13:40
 */

//Add menu inside Settings
add_action( 'admin_menu', 'wpimap_login_menu' );
function wpimap_login_menu() {
    add_options_page( 'WP IMAP Login Options', 'WP IMAP Login', 'manage_options', 'wpimap_login', 'wpimap_login_options' );
    //call register settings function
    add_action( 'admin_init', 'wpimap_login_settings' );
}

//Registering Settings
function wpimap_login_settings() {
    register_setting( 'wpimap_login-settings-group', 'wpimap_login_server' );
    register_setting( 'wpimap_login-settings-group', 'wpimap_login_port' );
    register_setting( 'wpimap_login-test-settings-group', 'wpimap_login_testmail' );
    register_setting( 'wpimap_login-test-settings-group', 'wpimap_login_password' );
}

//Options page
function wpimap_login_options() {

    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

    $extensiones = get_loaded_extensions();

    if (!in_array('imap',$extensiones)) {

        wp_die("<h2>IMAP extension not loaded. This won't work!</h2>");

//    TODO: check and explain methods for loading php-imap

    }
    echo '<div class="wrap">';
    echo '<h1>WordPress IMAP Login options</h1>';

    ?>

    <form method="post" action="options.php">
        <?php settings_fields( 'wpimap_login-settings-group' ); ?>
        <?php do_settings_sections( 'wpimap_login-settings-group' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">IMAP Server</br><small>(IP Address or FQDN)</small></th>

                <td><input type="text" name="wpimap_login_server" value="<?php echo esc_attr( get_option('wpimap_login_server') ); ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row">IMAP Port</th>
                <td><input type="text" name="wpimap_login_port" value="<?php echo esc_attr( get_option('wpimap_login_port') ); ?>" /></td>
            </tr>

        </table>

        <?php submit_button(); ?>

    </form>

    <?php

    if (isset($_POST['wpimap_login_testmail'])) {

        $mailtest = esc_attr( $_POST['wpimap_login_testmail'] );

    } else {

        $mailtest = "";

    }

    if (isset($_POST['wpimap_login_password'])) {

        $password = esc_attr( $_POST['wpimap_login_password'] );

    } else {

        $password = "";

    }

    $server = get_option('wpimap_login_server');
    $port = get_option('wpimap_login_port');


    ?>

    <form method="post">
        <?php settings_fields( 'wpimap_login-test-settings-group' ); ?>
        <?php do_settings_sections( 'wpimap_login-test-settings-group' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Test Email</br><small>(Submit to check if connection is done)</small></th>

                <td><input type="text" name="wpimap_login_testmail" value="<?php echo $mailtest; ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row">Password</br><small>(To check IMAP mailbox connection)</small></th>

                <td><input type="password" name="wpimap_login_password" value="<?php echo $password; ?>" /></td>
            </tr>

        </table>

        <?php submit_button('Submit Test','secondary'); ?>

    </form>


<?php

    $ip = gethostbyname($server);

    test_imap($ip,$port,$mailtest,$password);

    echo '</div>';
}