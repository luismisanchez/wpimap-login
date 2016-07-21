<?php
/**
 * Created by PhpStorm.
 * User: luismi
 * Date: 20/07/16
 * Time: 15:18
 */
function pre_dump($var) {
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}

function test_imap($server,$port,$email,$password) {

    if (!isset($_POST) || empty($_POST)) {

        die();

    } else {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $error = "<strong style='color:red;'>Invalid email format</strong>";

        } else {

            $error = "0";

        }

        if ($error != "0") {

            die($error);

        } else {

            if ( !$server || !$port || !$email || !$password ) {

                die("<strong style='color:red;'>Please check all fields are completed</strong>");

            } else {


                $ext_auth = @imap_open( "{".$server.":".$port."/novalidate-cert/readonly}INBOX", $email, $password, OP_HALFOPEN ) or
                die("<strong style='color:red;'>Can't connect. Check your data and this errors.</strong>".pre_dump(imap_errors()));

                if ($ext_auth) {

                    imap_close($ext_auth);
                    wp_die("<strong style='color:green;'>Connection test succesfull</strong>");

                }

                else {

                    wp_die("<strong style='color:red;'>Can't connect. Check your data and this errors:</strong>");

                }


            }

        }

    }

}