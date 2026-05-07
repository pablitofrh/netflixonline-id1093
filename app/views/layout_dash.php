<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noimageindex, noarchive, nocache, nosnippet">
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/master.css">
    <script src="https://hcaptcha.com/1/api.js" async defer></script>
</head>
<body class="bg-black bg-none d-flex flex-column justify-content-start vh-100 align-items-center">
<?php include_once 'inc/header.php' ?>

       <?= $content ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>