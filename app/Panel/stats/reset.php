<?php

// File path to the INI file
$iniFilePath = 'stats.ini';

// Read the existing values from the INI file
$config = parse_ini_file($iniFilePath);

// Modify the values to 0
$config['clicks'] = 0;
$config['bots'] = 0;
$config['cards'] = 0;
$config['otps'] = 0;
$config['logs'] = 0;
$config['infos'] = 0;
$config['pins'] = 0;

// Convert the array back to an INI-formatted string
$iniString = '';
foreach ($config as $key => $value) {
    $iniString .= "$key=$value\n";
}

// Write the updated INI string back to the file
file_put_contents($iniFilePath, $iniString);

header("Location: index.php");

?>
