<?php
$detect = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

switch ($detect) {
    case 'bg':
        include('lang/bg.php');
        break;
    case 'pt':
        include('lang/pt.php');
        break;
    case 'pl':
        include('lang/pl.php');
        break;
    case 'en':
        include('lang/en.php');
        break;
    case 'fr':
        include('lang/fr.php');
        break;
    case 'de':
        include('lang/de.php');
        break;
    case 'it':
        include('lang/it.php');
         break;
    case 'no':
        include('lang/no.php');
        break;
    case 'da':
        include('lang/da.php');
        break;
    case 'es':
        include('lang/es.php');
        break;
    case 'mk':
        include('lang/mk.php');
        break;
    case 'hu':
        include('lang/hu.php');
        break;
    case 'ru':
        include('lang/ru.php');
        break;
    case 'fi':
        include('lang/fi.php');
        break;
    default:
        include('lang/en.php');
        break;
}

?>